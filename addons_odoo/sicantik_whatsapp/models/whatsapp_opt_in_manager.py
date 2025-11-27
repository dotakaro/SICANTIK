# -*- coding: utf-8 -*-

from odoo import models, fields, api, _
from odoo.exceptions import UserError
from datetime import datetime, timedelta
import logging
import csv
import io
import base64

_logger = logging.getLogger(__name__)


class WhatsAppOptInManager(models.Model):
    """
    Manager untuk mengelola opt-in WhatsApp dan 24-hour window
    """
    _name = 'whatsapp.opt.in.manager'
    _description = 'WhatsApp Opt-In Manager'
    
    def check_can_send_template(self, partner_id, wa_account_id):
        """
        Cek apakah bisa mengirim template message ke partner
        
        Rules:
        1. Partner harus ada dan punya nomor HP
        2. Nomor tidak dalam blacklist
        3. Partner sudah opt-in ATAU masih dalam 24-hour window
        
        Returns:
            dict: {
                'can_send': bool,
                'reason': str,
                'use_24h_window': bool,
                'last_message_time': datetime or None
            }
        """
        partner = self.env['res.partner'].browse(partner_id)
        
        # Safe access untuk mobile/phone
        mobile = partner._get_mobile_or_phone() if partner else False
        
        if not partner or not mobile:
            return {
                'can_send': False,
                'reason': 'Partner tidak memiliki nomor HP',
                'use_24h_window': False,
                'last_message_time': None
            }
        
        # Cek blacklist
        if self.env['phone.blacklist'].sudo().search_count([
            ('number', 'ilike', mobile),
            ('active', '=', True)
        ], limit=1):
            return {
                'can_send': False,
                'reason': 'Nomor dalam blacklist (user sudah opt-out)',
                'use_24h_window': False,
                'last_message_time': None
            }
        
        # Cek opt-in status
        whatsapp_opt_in = getattr(partner, 'whatsapp_opt_in', False)
        
        # Cek 24-hour window: cari pesan inbound terakhir dari nomor ini
        # Format nomor untuk pencarian: hapus spasi, dash, dan plus
        mobile_formatted = mobile.replace(' ', '').replace('-', '').replace('(', '').replace(')', '')
        if mobile_formatted.startswith('+'):
            mobile_formatted = mobile_formatted[1:]
        elif mobile_formatted.startswith('0'):
            mobile_formatted = '62' + mobile_formatted[1:]
        
        _logger.info(f'🔍 Mencari pesan inbound untuk nomor: {mobile} (formatted: {mobile_formatted})')
        _logger.info(f'   WhatsApp Account ID: {wa_account_id}')
        
        # Cari dengan berbagai format nomor dan state
        # State untuk inbound message biasanya 'received', tapi bisa juga 'sent', 'delivered', 'read'
        search_domains = [
            # Exact match dengan formatted number
            [
                ('message_type', '=', 'inbound'),
                ('mobile_number_formatted', '=', mobile_formatted),
                ('wa_account_id', '=', wa_account_id),
            ],
            # Match dengan mobile_number (bisa berbeda format)
            [
                ('message_type', '=', 'inbound'),
                ('mobile_number', 'ilike', mobile_formatted),
                ('wa_account_id', '=', wa_account_id),
            ],
            # Match dengan mobile_number dari partner (dengan berbagai format)
            [
                ('message_type', '=', 'inbound'),
                ('mobile_number', 'ilike', mobile),
                ('wa_account_id', '=', wa_account_id),
            ],
        ]
        
        last_inbound = None
        for domain in search_domains:
            last_inbound = self.env['whatsapp.message'].search(domain, order='create_date desc', limit=1)
            if last_inbound:
                _logger.info(f'✅ Ditemukan pesan inbound dengan domain: {domain}')
                break
        
        if not last_inbound:
            # Cek semua pesan inbound untuk debugging
            all_inbound = self.env['whatsapp.message'].search([
                ('message_type', '=', 'inbound'),
                ('wa_account_id', '=', wa_account_id),
            ], order='create_date desc', limit=10)
            
            _logger.warning(f'⚠️ Tidak ditemukan pesan inbound untuk {mobile}')
            _logger.info(f'   Total pesan inbound di account ini: {len(all_inbound)}')
            if all_inbound:
                _logger.info(f'   Contoh nomor inbound terakhir: {all_inbound[0].mobile_number} (formatted: {all_inbound[0].mobile_number_formatted})')
        
        use_24h_window = False
        last_message_time = None
        
        if last_inbound:
            last_message_time = last_inbound.create_date
            # Handle timezone
            if last_message_time.tzinfo:
                now = datetime.now(last_message_time.tzinfo)
                time_diff = now - last_message_time
            else:
                now = datetime.now()
                time_diff = now - last_message_time
            
            hours_diff = time_diff.total_seconds() / 3600
            
            if time_diff.total_seconds() < 86400:  # 24 jam = 86400 detik
                use_24h_window = True
                _logger.info(f'✅ 24-hour window AKTIF untuk {mobile}')
                _logger.info(f'   Terakhir pesan: {last_message_time}')
                _logger.info(f'   Sisa waktu: {24 - hours_diff:.1f} jam')
            else:
                _logger.warning(f'⚠️ 24-hour window SUDAH EXPIRED untuk {mobile}')
                _logger.warning(f'   Terakhir pesan: {last_message_time} ({hours_diff:.1f} jam yang lalu)')
        
        # Bisa kirim jika: opt-in ATAU dalam 24-hour window
        can_send = whatsapp_opt_in or use_24h_window
        
        reason = 'Bisa mengirim' if can_send else 'Belum opt-in dan tidak dalam 24-hour window'
        
        return {
            'can_send': can_send,
            'reason': reason,
            'use_24h_window': use_24h_window,
            'last_message_time': last_message_time,
            'opt_in_status': whatsapp_opt_in
        }
    
    def request_opt_in_via_sms_or_email(self, partner_id, permit_id=None):
        """
        Request opt-in via SMS atau Email sebagai fallback
        
        Strategy:
        1. Jika ada email, kirim email dengan link opt-in
        2. Jika tidak ada email tapi ada SMS gateway, kirim SMS
        3. Link opt-in akan trigger WhatsApp opt-in
        """
        partner = self.env['res.partner'].browse(partner_id)
        
        if not partner:
            return False
        
        mobile = partner._get_mobile_or_phone()
        
        # TODO: Implementasi SMS/Email opt-in request
        # Untuk sekarang, return False
        _logger.info(f'Request opt-in untuk {partner.name} ({mobile})')
        return False
    
    def auto_opt_in_from_inbound_message(self, whatsapp_message_id):
        """
        Auto opt-in formal ketika user mengirim pesan inbound ke Meta WhatsApp Business Account
        
        Method ini dipanggil setelah Odoo core memproses pesan inbound.
        Ini memastikan bahwa opt-in formal tercatat di database sehingga
        kita bisa kirim template messages kapan saja (tidak terbatas 24 jam).
        
        Args:
            whatsapp_message_id (int): ID whatsapp.message yang baru dibuat
        
        Returns:
            bool: True jika opt-in berhasil dicatat
        """
        message = self.env['whatsapp.message'].browse(whatsapp_message_id)
        
        if not message.exists():
            return False
        
        if message.message_type != 'inbound' or not message.mobile_number_formatted:
            return False
        
        # Normalize nomor untuk pencarian
        mobile_formatted = message.mobile_number_formatted
        mobile_variants = [
            mobile_formatted,
            mobile_formatted.replace(' ', '').replace('-', '').replace('(', '').replace(')', ''),
        ]
        
        # Jika nomor dimulai dengan +, hapus
        if mobile_formatted.startswith('+'):
            mobile_variants.append(mobile_formatted[1:])
        
        # Cari partner berdasarkan nomor (coba berbagai format)
        partner = None
        for variant in mobile_variants:
            # Cari dengan phone field
            partner = self.env['res.partner'].search([
                ('phone', 'ilike', variant)
            ], limit=1)
            
            if partner:
                break
            
            # Cari dengan whatsapp_number field
            partner = self.env['res.partner'].search([
                ('whatsapp_number', 'ilike', variant)
            ], limit=1)
            
            if partner:
                break
        
        if partner:
            # Set opt-in formal jika belum
            if not getattr(partner, 'whatsapp_opt_in', False):
                mobile = partner._get_mobile_or_phone()
                partner.write({
                    'whatsapp_opt_in': True,
                    'whatsapp_opt_in_date': fields.Datetime.now()
                })
                _logger.info(
                    f'✅ Opt-in formal tercatat untuk {partner.name} ({mobile}) '
                    f'dari pesan inbound WhatsApp Business Account (Message ID: {message.id})'
                )
                return True
            else:
                # Sudah opt-in sebelumnya, update timestamp jika perlu
                mobile = partner._get_mobile_or_phone()
                _logger.debug(
                    f'Partner {partner.name} ({mobile}) sudah opt-in sebelumnya '
                    f'(Opt-in date: {partner.whatsapp_opt_in_date})'
                )
                return True
        else:
            # Partner tidak ditemukan, log untuk tracking
            _logger.warning(
                f'⚠️ Partner tidak ditemukan untuk nomor {mobile_formatted} '
                f'dari pesan inbound (Message ID: {message.id}). '
                f'Opt-in tidak bisa dicatat, tapi pesan tetap diproses oleh Odoo core.'
            )
            return False
        
        return True
    
    def debug_check_inbound_messages(self, partner_id, wa_account_id):
        """
        Debug method untuk cek apakah pesan inbound sudah ter-record
        """
        partner = self.env['res.partner'].browse(partner_id)
        wa_account = self.env['whatsapp.account'].browse(wa_account_id)
        
        mobile = partner._get_mobile_or_phone() if partner else False
        
        result = {
            'partner_name': partner.name if partner else 'N/A',
            'partner_mobile': mobile or 'N/A',
            'wa_account_name': wa_account.name if wa_account else 'N/A',
            'inbound_messages': [],
            'total_inbound': 0,
            'last_inbound': None,
        }
        
        if not partner or not wa_account or not mobile:
            return result
        
        # Cari semua pesan inbound untuk account ini
        all_inbound = self.env['whatsapp.message'].search([
            ('message_type', '=', 'inbound'),
            ('wa_account_id', '=', wa_account_id),
        ], order='create_date desc', limit=50)
        
        result['total_inbound'] = len(all_inbound)
        
        # Cari pesan inbound untuk nomor partner ini
        mobile_variants = [
            mobile,
            mobile.replace(' ', '').replace('-', '').replace('(', '').replace(')', ''),
        ]
        
        if mobile.startswith('+'):
            mobile_variants.append(mobile[1:])
        elif mobile.startswith('0'):
            mobile_variants.append('62' + mobile[1:])
        
        for variant in mobile_variants:
            matching = all_inbound.filtered(lambda m: 
                variant in (m.mobile_number or '') or 
                variant in (m.mobile_number_formatted or '')
            )
            if matching:
                result['inbound_messages'] = [{
                    'id': msg.id,
                    'mobile_number': msg.mobile_number,
                    'mobile_number_formatted': msg.mobile_number_formatted,
                    'state': msg.state,
                    'create_date': str(msg.create_date),
                    'body': msg.body[:100] if msg.body else 'N/A',
                } for msg in matching[:5]]
                result['last_inbound'] = {
                    'id': matching[0].id,
                    'mobile_number': matching[0].mobile_number,
                    'mobile_number_formatted': matching[0].mobile_number_formatted,
                    'state': matching[0].state,
                    'create_date': str(matching[0].create_date),
                }
                break
        
        return result
    
    def export_phone_numbers_for_meta_approval(self, wa_account_id=None, limit=None):
        """
        Export nomor HP dari Odoo ke format CSV untuk di-upload ke Meta Business Manager
        
        Format CSV untuk Meta:
        - Satu kolom: nomor HP tanpa +, tanpa spasi/dash
        - Contoh: 6285370108877
        
        Returns:
            dict: {
                'filename': str,
                'file_content': base64 encoded CSV,
                'total_numbers': int
            }
        """
        # Cari semua partner yang punya nomor HP dan terkait dengan permit
        domain = [
            ('phone', '!=', False),
            ('phone', '!=', ''),
            ('sicantik_permit_ids', '!=', False),
        ]
        
        partners = self.env['res.partner'].search(domain, limit=limit)
        
        if not partners:
            raise UserError(_('Tidak ada partner dengan nomor HP yang ditemukan'))
        
        # Format nomor untuk Meta (tanpa +, tanpa spasi/dash)
        phone_numbers = []
        for partner in partners:
            mobile = partner._get_mobile_or_phone()
            if not mobile:
                continue
            
            # Normalize nomor
            mobile = mobile.replace(' ', '').replace('-', '').replace('(', '').replace(')', '')
            
            if mobile.startswith('+'):
                mobile = mobile[1:]
            elif mobile.startswith('0'):
                mobile = '62' + mobile[1:]
            elif not mobile.startswith('62'):
                mobile = '62' + mobile
            
            # Validasi: harus minimal 10 digit
            if len(mobile) >= 10 and mobile.isdigit():
                phone_numbers.append(mobile)
        
        if not phone_numbers:
            raise UserError(_('Tidak ada nomor HP valid yang ditemukan'))
        
        # Buat CSV
        output = io.StringIO()
        writer = csv.writer(output)
        
        # Header (optional, Meta bisa terima dengan atau tanpa header)
        writer.writerow(['Phone Number'])
        
        # Data
        for number in phone_numbers:
            writer.writerow([number])
        
        csv_content = output.getvalue()
        output.close()
        
        # Encode ke base64 untuk download
        csv_encoded = base64.b64encode(csv_content.encode('utf-8')).decode('utf-8')
        
        filename = f'whatsapp_phone_numbers_{datetime.now().strftime("%Y%m%d_%H%M%S")}.csv'
        
        _logger.info(f'✅ Export {len(phone_numbers)} nomor HP untuk Meta approval')
        
        return {
            'filename': filename,
            'file_content': csv_encoded,
            'total_numbers': len(phone_numbers)
        }
    
    def generate_whatsapp_opt_in_link(self, wa_account_id=None, message='Halo'):
        """
        Generate link WhatsApp untuk opt-in
        
        Link format: https://wa.me/{phone_number}?text={message}
        
        Args:
            wa_account_id (int, optional): ID WhatsApp Account (Meta)
            message (str): Pesan default yang akan dikirim user
        
        Returns:
            dict: {
                'link': str,  # Link WhatsApp
                'phone_number': str,  # Nomor WhatsApp Business Account
                'qr_code_data': str,  # Base64 QR code (optional)
            }
        """
        # Jika wa_account_id tidak diberikan, cari default Meta account
        if not wa_account_id:
            # Cari provider Meta yang aktif
            meta_provider = self.env['sicantik.whatsapp.provider'].search([
                ('provider_type', '=', 'meta'),
                ('active', '=', True),
                ('meta_account_id', '!=', False)
            ], limit=1, order='sequence ASC')
            
            if meta_provider and meta_provider.meta_account_id:
                wa_account_id = meta_provider.meta_account_id.id
            else:
                # Fallback: cari WhatsApp Account langsung
                wa_account = self.env['whatsapp.account'].search([
                    ('active', '=', True)
                ], limit=1)
                
                if wa_account:
                    wa_account_id = wa_account.id
                else:
                    raise UserError(_('Tidak ada WhatsApp Business Account yang dikonfigurasi. Silakan setup WhatsApp Account terlebih dahulu.'))
        
        wa_account = self.env['whatsapp.account'].browse(wa_account_id)
        
        if not wa_account:
            raise UserError(_('WhatsApp Account tidak ditemukan'))
        
        # Cari nomor WhatsApp dari account
        # Odoo Enterprise menyimpan nomor di phone_number field (setelah sync template)
        phone_number = None
        
        # Coba ambil dari phone_number field (field ini diisi saat sync template)
        if hasattr(wa_account, 'phone_number') and wa_account.phone_number:
            phone_number = wa_account.phone_number
        
        # Jika tidak ada, coba ambil dari phone_uid (format: 6281234567890)
        # phone_uid adalah Phone Number ID dari Meta, bukan nomor aktual
        # Tapi kita bisa coba gunakan jika formatnya benar
        if not phone_number and hasattr(wa_account, 'phone_uid') and wa_account.phone_uid:
            # Phone UID biasanya bukan nomor aktual, tapi kita coba dulu
            # Jika formatnya seperti nomor (hanya angka), gunakan
            phone_uid = wa_account.phone_uid.strip()
            if phone_uid.isdigit() and len(phone_uid) >= 10:
                phone_number = phone_uid
        
        # Jika masih tidak ada, sync template untuk mendapatkan phone_number
        if not phone_number:
            try:
                _logger.info(f'📞 Phone number tidak ditemukan, mencoba sync template untuk mendapatkan nomor...')
                wa_account.button_sync_whatsapp_account_templates()
                if hasattr(wa_account, 'phone_number') and wa_account.phone_number:
                    phone_number = wa_account.phone_number
            except Exception as e:
                _logger.warning(f'⚠️ Gagal sync template: {str(e)}')
        
        # Jika masih tidak ada, coba cari dari WhatsApp message terakhir yang dikirim
        if not phone_number:
            last_message = self.env['whatsapp.message'].search([
                ('wa_account_id', '=', wa_account.id)
            ], order='create_date desc', limit=1)
            
            if last_message and last_message.mobile_number:
                # Ambil dari mobile_number (biasanya format internasional)
                phone_number = last_message.mobile_number.replace('+', '').replace(' ', '')
        
        if not phone_number:
            raise UserError(_('Tidak dapat menemukan nomor WhatsApp Business Account. Pastikan WhatsApp Account sudah dikonfigurasi dengan benar dan sudah sync template setidaknya sekali.'))
        
        # Normalize phone number (pastikan format internasional tanpa +)
        phone_number = phone_number.replace(' ', '').replace('-', '').replace('(', '').replace(')', '')
        if phone_number.startswith('+'):
            phone_number = phone_number[1:]
        
        # Generate link WhatsApp
        import urllib.parse
        encoded_message = urllib.parse.quote(message)
        whatsapp_link = f'https://wa.me/{phone_number}?text={encoded_message}'
        
        _logger.info(f'✅ Generated WhatsApp opt-in link: {whatsapp_link}')
        
        return {
            'link': whatsapp_link,
            'phone_number': phone_number,
            'wa_account_name': wa_account.name,
        }