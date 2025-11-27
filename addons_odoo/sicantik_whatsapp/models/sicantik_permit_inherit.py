# -*- coding: utf-8 -*-

from odoo import models, fields, api, _
from odoo.exceptions import UserError
from datetime import datetime, timedelta
import logging

_logger = logging.getLogger(__name__)


class SicantikPermit(models.Model):
    """
    Inherit model sicantik.permit untuk menambahkan trigger notifikasi WhatsApp
    """
    _inherit = 'sicantik.permit'
    
    def write(self, vals):
        """Override untuk trigger notifikasi WhatsApp saat status berubah"""
        result = super().write(vals)
        
        # Cek jika status berubah menjadi 'active' dan ada permit_number
        if vals.get('status') == 'active' and vals.get('permit_number'):
            self._kirim_notifikasi_izin_selesai()
        
        # Cek jika status berubah
        if 'status' in vals:
            status_lama = self.status if hasattr(self, 'status') else 'draft'
            self._kirim_notifikasi_update_status(status_lama, vals.get('status'))
        
        # Cek jika perpanjangan disetujui
        if vals.get('is_renewal') and vals.get('status') == 'active':
            self._kirim_notifikasi_perpanjangan_disetujui()
        
        return result
    
    def _kirim_notifikasi_izin_selesai(self):
        """
        Kirim notifikasi WhatsApp saat izin selesai diproses
        Template: permit_ready
        
        Menggunakan dispatcher multi-provider untuk routing otomatis.
        """
        for record in self:
            if not record.partner_id:
                _logger.warning(
                    f'⏭️  Skip notifikasi izin selesai untuk {record.registration_id}: '
                    f'Tidak ada partner'
                )
                continue
            
            # Safe access untuk mobile/phone
            mobile_number = record.partner_id._get_mobile_or_phone()
            if not mobile_number:
                _logger.warning(
                    f'⏭️  Skip notifikasi izin selesai untuk {record.registration_id}: '
                    f'Tidak ada nomor WhatsApp'
                )
                continue
            
            try:
                # Gunakan dispatcher untuk routing otomatis
                dispatcher = self.env['sicantik.whatsapp.dispatcher']
                
                # Prepare context values sesuai parameter template master
                context_values = {
                    'partner_name': record.applicant_name or record.partner_id.name or '',
                    'permit_number': record.permit_number or '',
                    'permit_type': record.permit_type_id.name if record.permit_type_id else '',
                    'status': dict(record._fields['status'].selection).get(record.status, record.status) if hasattr(record, 'status') else '',
                }
                
                # Kirim via dispatcher
                result = dispatcher.send_template_message(
                    template_key='permit_ready',
                    partner_id=record.partner_id.id,
                    context_values=context_values,
                    permit_id=record.id
                )
                
                if result.get('success'):
                    _logger.info(
                        f'✅ Notifikasi izin selesai dikirim ke {record.partner_id.name} '
                        f'via {result.get("provider", "unknown")}'
                    )
                else:
                    _logger.error(
                        f'❌ Gagal kirim notifikasi izin selesai: {result.get("error", "Unknown error")}'
                    )
                    
            except Exception as e:
                _logger.error(f'❌ Error mengirim notifikasi izin selesai: {str(e)}', exc_info=True)
    
    def _kirim_notifikasi_update_status(self, status_lama, status_baru):
        """
        Kirim notifikasi WhatsApp saat status izin berubah
        Template: status_update
        
        Menggunakan dispatcher multi-provider untuk routing otomatis.
        """
        for record in self:
            if not record.partner_id:
                continue
            
            # Safe access untuk mobile/phone
            mobile_number = record.partner_id._get_mobile_or_phone()
            if not mobile_number:
                continue
            
            # Skip jika status tidak berubah secara signifikan
            if status_lama == status_baru:
                continue
            
            try:
                # Gunakan dispatcher untuk routing otomatis
                dispatcher = self.env['sicantik.whatsapp.dispatcher']
                
                # Prepare context values
                status_field = record._fields.get('status')
                status_lama_label = dict(status_field.selection).get(status_lama, status_lama) if status_field else status_lama
                status_baru_label = dict(status_field.selection).get(status_baru, status_baru) if status_field else status_baru
                
                context_values = {
                    'partner_name': record.applicant_name or record.partner_id.name or '',
                    'permit_number': record.permit_number or record.registration_id or '',
                    'permit_type': record.permit_type_id.name if record.permit_type_id else '',
                    'new_status': status_baru_label,
                    'update_date': fields.Datetime.now().strftime('%d-%m-%Y %H:%M'),
                }
                
                # Kirim via dispatcher
                result = dispatcher.send_template_message(
                    template_key='status_update',
                    partner_id=record.partner_id.id,
                    context_values=context_values,
                    permit_id=record.id
                )
                
                if result.get('success'):
                    _logger.info(
                        f'✅ Notifikasi update status dikirim: {status_lama} → {status_baru} '
                        f'via {result.get("provider", "unknown")}'
                    )
                else:
                    _logger.error(
                        f'❌ Gagal kirim notifikasi update status: {result.get("error", "Unknown error")}'
                    )
                    
            except Exception as e:
                _logger.error(f'❌ Error mengirim notifikasi update status: {str(e)}', exc_info=True)
    
    def _kirim_notifikasi_perpanjangan_disetujui(self):
        """
        Kirim notifikasi WhatsApp saat perpanjangan izin disetujui
        Template: renewal_approved
        
        Menggunakan dispatcher multi-provider untuk routing otomatis.
        """
        for record in self:
            if not record.partner_id:
                continue
            
            # Safe access untuk mobile/phone
            mobile_number = record.partner_id._get_mobile_or_phone()
            if not mobile_number:
                continue
            
            try:
                # Gunakan dispatcher untuk routing otomatis
                dispatcher = self.env['sicantik.whatsapp.dispatcher']
                
                # Prepare context values
                new_expiry_date = ''
                if hasattr(record, 'expiry_date') and record.expiry_date:
                    new_expiry_date = record.expiry_date.strftime('%d-%m-%Y')
                
                context_values = {
                    'partner_name': record.applicant_name or record.partner_id.name or '',
                    'permit_number': record.permit_number or '',
                    'permit_type': record.permit_type_id.name if record.permit_type_id else '',
                    'new_expiry_date': new_expiry_date,
                }
                
                # Kirim via dispatcher
                result = dispatcher.send_template_message(
                    template_key='renewal_approved',
                    partner_id=record.partner_id.id,
                    context_values=context_values,
                    permit_id=record.id
                )
                
                if result.get('success'):
                    _logger.info(
                        f'✅ Notifikasi perpanjangan disetujui dikirim ke {record.partner_id.name} '
                        f'via {result.get("provider", "unknown")}'
                    )
                else:
                    _logger.error(
                        f'❌ Gagal kirim notifikasi perpanjangan: {result.get("error", "Unknown error")}'
                    )
                    
            except Exception as e:
                _logger.error(f'❌ Error mengirim notifikasi perpanjangan: {str(e)}', exc_info=True)
    
    @api.model
    def cron_check_expiring_permits(self):
        """
        Override cron job untuk mengintegrasikan dengan WhatsApp notifications
        Dijalankan setiap hari jam 09:00
        
        Mengirim notifikasi pada:
        - 90 hari sebelum expired
        - 60 hari sebelum expired
        - 30 hari sebelum expired
        - 7 hari sebelum expired
        """
        today = fields.Date.today()
        
        _logger.info('='*80)
        _logger.info('🔔 CRON: Cek izin mendekati masa berlaku (dengan WhatsApp)')
        _logger.info('='*80)
        
        # WORKAROUND: Sync expiry dates terlebih dahulu
        connector = self.env['sicantik.connector'].search([
            ('active', '=', True)
        ], limit=1)
        
        if connector:
            _logger.info('🔄 Sync expiry dates (workaround)...')
            try:
                connector.sync_expiry_dates_workaround(max_permits=100)
            except Exception as e:
                _logger.error(f'Error sync expiry dates: {str(e)}')
        
        # Ambil template WhatsApp
        template = self.env['whatsapp.template'].search([
            ('template_name', '=', 'peringatan_masa_berlaku_izin'),
            ('status', '=', 'approved'),
            ('active', '=', True)
        ], limit=1)
        
        if not template:
            _logger.warning('⚠️  Template WhatsApp "peringatan_masa_berlaku_izin" tidak ditemukan atau belum approved')
            # Fallback ke method parent jika template belum tersedia
            return super().cron_check_expiring_permits()
        
        # Threshold notifikasi
        thresholds = [
            (90, 'expiry_notified_90'),
            (60, 'expiry_notified_60'),
            (30, 'expiry_notified_30'),
            (7, 'expiry_notified_7')
        ]
        
        total_notifikasi = 0
        
        for hari, field_name in thresholds:
            target_date = today + timedelta(days=hari)
            
            # Cari izin yang akan expired pada target_date dan belum dikirim notifikasi
            izin_expiring = self.search([
                ('expiry_date', '=', target_date),
                ('status', '=', 'active'),
                (field_name, '=', False),
                ('partner_id', '!=', False)
            ])
            # Filter permits with mobile/phone manually (cannot use related field in domain)
            permits_with_mobile = self.env['sicantik.permit']
            for permit in izin_expiring:
                if permit.partner_id and permit.partner_id._get_mobile_or_phone():
                    permits_with_mobile |= permit
            izin_expiring = permits_with_mobile
            
            if not izin_expiring:
                _logger.info(f'  {hari} hari: Tidak ada izin yang perlu notifikasi')
                continue
            
            _logger.info(f'  {hari} hari: Ditemukan {len(izin_expiring)} izin')
            
            for izin in izin_expiring:
                try:
                    # Hitung sisa hari
                    sisa_hari = (izin.expiry_date - today).days
                    
                    # Gunakan dispatcher untuk routing otomatis
                    dispatcher = self.env['sicantik.whatsapp.dispatcher']
                    
                    # Prepare context values sesuai parameter template master
                    expiry_date_str = izin.expiry_date.strftime('%d-%m-%Y') if izin.expiry_date else ''
                    renewal_link = f'https://sicantik.dotakaro.com/perpanjangan/{izin.registration_id}'
                    contact_info = '0628-20XXX'  # TODO: Ambil dari config
                    
                    context_values = {
                        'partner_name': izin.applicant_name or izin.partner_id.name or '',
                        'permit_number': izin.permit_number or '',
                        'permit_type': izin.permit_type_id.name if izin.permit_type_id else '',
                        'expiry_date': expiry_date_str,
                        'days_remaining': str(sisa_hari),
                        'renewal_link': renewal_link,
                        'contact_info': contact_info,
                    }
                    
                    # Kirim via dispatcher
                    result = dispatcher.send_template_message(
                        template_key='permit_reminder',
                        partner_id=izin.partner_id.id,
                        context_values=context_values,
                        permit_id=izin.id
                    )
                    
                    if result.get('success'):
                        # Tandai sudah dikirim notifikasi
                        izin.write({field_name: True})
                        
                        total_notifikasi += 1
                        _logger.info(
                            f'    ✅ Notifikasi {hari} hari dikirim: '
                            f'{izin.permit_number} - {izin.applicant_name} '
                            f'(Sisa: {sisa_hari} hari) via {result.get("provider", "unknown")}'
                        )
                    else:
                        _logger.error(
                            f'    ❌ Gagal kirim notifikasi untuk {izin.registration_id}: '
                            f'{result.get("error", "Unknown error")}'
                        )
                    
                except Exception as e:
                    _logger.error(
                        f'    ❌ Error mengirim notifikasi untuk {izin.registration_id}: {str(e)}',
                        exc_info=True
                    )
        
        _logger.info('='*80)
        _logger.info(f'✅ Total notifikasi dikirim: {total_notifikasi}')
        _logger.info('='*80)
    
    def action_send_test_notification(self):
        """
        Kirim test notification WhatsApp untuk permit ini
        Override dari sicantik_connector untuk implementasi WhatsApp
        """
        self.ensure_one()
        
        # Auto-link partner jika belum ada tapi ada applicant_name
        if not self.partner_id and self.applicant_name:
            # Coba cari partner berdasarkan nama pemohon
            partner = self.env['res.partner'].search([
                ('name', 'ilike', self.applicant_name.strip())
            ], limit=1)
            
            if partner:
                # Link partner ke permit
                self.write({'partner_id': partner.id})
                _logger.info(f'✅ Auto-linked partner {partner.name} ke permit {self.registration_id}')
            else:
                # Jika tidak ditemukan, tampilkan pesan dengan instruksi
                return {
                    'type': 'ir.actions.client',
                    'tag': 'display_notification',
                    'params': {
                        'title': 'Test Notification',
                        'message': f'Tidak dapat mengirim test notification: Partner dengan nama "{self.applicant_name}" tidak ditemukan di sistem. Silakan buat partner terlebih dahulu atau link partner manual ke permit ini.',
                        'type': 'warning',
                        'sticky': True,
                    }
                }
        
        # Validasi: Pastikan ada partner setelah auto-link
        if not self.partner_id:
            return {
                'type': 'ir.actions.client',
                'tag': 'display_notification',
                'params': {
                    'title': 'Test Notification',
                    'message': 'Tidak dapat mengirim test notification: Permit ini belum memiliki partner/anggota yang terkait. Silakan link partner manual ke permit ini.',
                    'type': 'warning',
                    'sticky': False,
                }
            }
        
        # Safe access untuk mobile/phone
        mobile_number = self.partner_id._get_mobile_or_phone()
        if not mobile_number:
            return {
                'type': 'ir.actions.client',
                'tag': 'display_notification',
                'params': {
                    'title': 'Test Notification',
                    'message': f'Tidak dapat mengirim test notification: Partner {self.partner_id.name} tidak memiliki nomor WhatsApp.',
                    'type': 'warning',
                    'sticky': False,
                }
            }
        
        # Cari template WhatsApp untuk test notification
        # Gunakan template "peringatan_masa_berlaku_izin" sebagai test template
        template = self.env['whatsapp.template'].search([
            ('template_name', '=', 'peringatan_masa_berlaku_izin'),
            ('status', '=', 'approved'),
            ('active', '=', True)
        ], limit=1)
        
        if not template:
            # Fallback: coba template lain
            template = self.env['whatsapp.template'].search([
                ('template_name', '=', 'izin_selesai_diproses'),
                ('status', '=', 'approved'),
                ('active', '=', True)
            ], limit=1)
        
        if not template:
            return {
                'type': 'ir.actions.client',
                'tag': 'display_notification',
                'params': {
                    'title': 'Test Notification',
                    'message': 'Tidak dapat mengirim test notification: Template WhatsApp belum tersedia atau belum approved. Silakan sync template dari WhatsApp Account terlebih dahulu.',
                    'type': 'warning',
                    'sticky': False,
                }
            }
        
        try:
            _logger.info(f'📱 Starting test notification untuk {self.partner_id.name} ({mobile_number})')
            _logger.info(f'   Template: {template.name} (ID: {template.id}, Status: {template.status})')
            _logger.info(f'   WhatsApp Account: {template.wa_account_id.name if template.wa_account_id else "N/A"}')
            
            # Cek opt-in status dan 24-hour window SEBELUM kirim
            opt_in_manager = self.env['whatsapp.opt.in.manager']
            can_send_check = opt_in_manager.check_can_send_template(
                self.partner_id.id,
                template.wa_account_id.id if template.wa_account_id else False
            )
            
            _logger.info(f'   Opt-in check: can_send={can_send_check["can_send"]}, reason={can_send_check["reason"]}')
            _logger.info(f'   24h window: {can_send_check["use_24h_window"]}, opt-in: {can_send_check.get("opt_in_status", False)}')
            
            if not can_send_check['can_send']:
                # Debug: cek apakah pesan inbound sudah ter-record
                debug_info = opt_in_manager.debug_check_inbound_messages(
                    self.partner_id.id,
                    template.wa_account_id.id if template.wa_account_id else False
                )
                
                _logger.warning(f'⚠️ Debug info: {debug_info}')
                
                # Buat pesan error yang lebih informatif
                error_msg = f'⚠️ Tidak dapat mengirim test notification: {can_send_check["reason"]}\n\n'
                error_msg += f'📋 Status:\n'
                error_msg += f'• Total pesan inbound di account: {debug_info["total_inbound"]}\n'
                if debug_info['last_inbound']:
                    error_msg += f'• Pesan inbound terakhir ditemukan: {debug_info["last_inbound"]["mobile_number"]}\n'
                else:
                    error_msg += f'• ⚠️ TIDAK ditemukan pesan inbound untuk nomor {mobile_number}\n'
                
                error_msg += f'\n📋 Solusi:\n'
                error_msg += f'1. Pastikan Webhook sudah terkonfigurasi di Meta Business Manager\n'
                error_msg += f'2. Pastikan nomor {mobile_number} sudah mengirim pesan ke WhatsApp Business Account\n'
                error_msg += f'3. Cek di menu WhatsApp → Messages apakah pesan inbound sudah ter-record\n'
                error_msg += f'4. Jika belum ter-record, berarti webhook tidak bekerja - cek konfigurasi webhook di Meta\n'
                error_msg += f'5. Atau tambahkan nomor ke daftar kontak di Meta Business Manager untuk pre-approval'
                
                return {
                    'type': 'ir.actions.client',
                    'tag': 'display_notification',
                    'params': {
                        'title': 'Test Notification - Opt-In Required',
                        'message': error_msg,
                        'type': 'warning',
                        'sticky': True,
                    }
                }
            
            # Buat composer untuk mengirim pesan test
            composer = self.env['whatsapp.composer'].create({
                'res_model': self._name,
                'res_ids': self.ids,
                'wa_template_id': template.id,
            })
            
            _logger.info(f'   Composer created: {composer.id}')
            
            # Jika template menggunakan free text untuk link perpanjangan
            if template.template_name == 'peringatan_masa_berlaku_izin':
                if self.expiry_date:
                    sisa_hari = (self.expiry_date - fields.Date.today()).days
                    composer.free_text_6 = f'https://perizinan.karokab.go.id/perpanjangan/{self.registration_id}'
                    composer.free_text_7 = '0628-20XXX'  # TODO: Ambil dari config
                    _logger.info(f'   Free text set: link={composer.free_text_6}, contact={composer.free_text_7}')
            
            # Untuk test notification, kirim langsung tanpa cron
            # force_create=True akan membuat message meskipun ada validasi
            messages = composer._create_whatsapp_messages(force_create=True)
            
            _logger.info(f'   Messages created: {len(messages)} message(s)')
            
            if not messages:
                _logger.warning('   ⚠️ Tidak ada message yang dibuat')
                return {
                    'type': 'ir.actions.client',
                    'tag': 'display_notification',
                    'params': {
                        'title': 'Test Notification',
                        'message': 'Tidak dapat membuat WhatsApp message. Pastikan nomor HP valid dan template sudah approved.',
                        'type': 'warning',
                        'sticky': True,
                    }
                }
            
            # Log detail message sebelum dikirim
            message = messages[0] if messages else None
            for msg in messages:
                _logger.info(f'   Message {msg.id}: state={msg.state}, mobile={msg.mobile_number}, formatted={msg.mobile_number_formatted}')
            
            if not message:
                return {
                    'type': 'ir.actions.client',
                    'tag': 'display_notification',
                    'params': {
                        'title': 'Test Notification',
                        'message': 'Tidak dapat membuat WhatsApp message.',
                        'type': 'warning',
                        'sticky': True,
                    }
                }
            
            # Kirim message langsung (tanpa cron)
            _logger.info('   📤 Sending message...')
            _logger.info(f'   Request details: to={message.mobile_number_formatted}, template={template.template_name}')
            
            try:
                messages._send_message(with_commit=True)
                _logger.info('   ✅ Send message completed')
            except Exception as send_error:
                _logger.error(f'   ❌ Error during send_message: {str(send_error)}', exc_info=True)
                raise
            
            # Refresh message untuk mendapatkan state terbaru dari database
            if message:
                # Invalidate cache dan baca ulang dari database
                message.invalidate_recordset(['state', 'msg_uid', 'failure_reason', 'failure_type'])
                # Baca ulang dari database dengan ID yang sama
                message = self.env['whatsapp.message'].browse(message.id)
                
                _logger.info(f'   Message state setelah send: {message.state}')
                _logger.info(f'   Message UID: {message.msg_uid or "N/A"}')
                _logger.info(f'   Failure type: {message.failure_type or "N/A"}')
                _logger.info(f'   Failure reason: {message.failure_reason or "N/A"}')
                
                if message.state == 'sent' and message.msg_uid:
                    # Cari nomor WhatsApp Business Account untuk ditampilkan
                    wa_account = template.wa_account_id
                    wa_phone_number = 'N/A'
                    if wa_account:
                        # Coba cari nomor WhatsApp dari account
                        # Phone number biasanya ada di discuss.channel atau bisa diambil dari phone_uid
                        try:
                            channel = self.env['discuss.channel'].search([
                                ('wa_account_id', '=', wa_account.id),
                                ('channel_type', '=', 'whatsapp')
                            ], limit=1)
                            if channel and channel.whatsapp_number:
                                wa_phone_number = channel.whatsapp_number
                        except:
                            pass
                    
                    _logger.info(f'✅ Test notification berhasil dikirim ke {self.partner_id.name} ({mobile_number}) dengan UID: {message.msg_uid}')
                    return {
                        'type': 'ir.actions.client',
                        'tag': 'display_notification',
                        'params': {
                            'title': 'Test Notification',
                            'message': f'✅ Test notification berhasil dikirim ke {self.partner_id.name} ({mobile_number}).\n\nMessage ID: {message.msg_uid}\n\n⚠️ PENTING: Jika pesan tidak masuk ke WhatsApp, kemungkinan nomor belum opt-in.\n\n📋 Aturan Meta WhatsApp Business API:\nNomor penerima HARUS sudah opt-in sebelum bisa menerima template messages.\n\n🔧 Cara Opt-In:\n1. Dari nomor {mobile_number}, kirim pesan ke nomor WhatsApp Business Account ({wa_phone_number})\n2. Setelah itu, template message bisa dikirim ke nomor tersebut\n3. Atau tambahkan nomor ke daftar kontak di Meta Business Manager\n\n💡 Untuk Development:\nGunakan nomor test di Meta Business Manager yang bisa langsung menerima template messages tanpa opt-in.',
                            'type': 'success',
                            'sticky': True,
                        }
                    }
                elif message.state == 'sent' and not message.msg_uid:
                    # State sent tapi tidak ada msg_uid - kemungkinan API tidak mengembalikan message ID
                    _logger.warning(f'⚠️ Message state=sent tapi tidak ada msg_uid. Kemungkinan API tidak mengembalikan message ID.')
                    return {
                        'type': 'ir.actions.client',
                        'tag': 'display_notification',
                        'params': {
                            'title': 'Test Notification',
                            'message': f'Message dikirim tapi tidak ada Message ID dari API. State: {message.state}. Silakan cek WhatsApp Anda atau hubungi admin untuk cek log API.',
                            'type': 'warning',
                            'sticky': True,
                        }
                    }
                elif message.state == 'error':
                    error_msg = message.failure_reason or message.failure_type or 'Unknown error'
                    _logger.error(f'❌ Test notification gagal: {error_msg} (type: {message.failure_type})')
                    return {
                        'type': 'ir.actions.client',
                        'tag': 'display_notification',
                        'params': {
                            'title': 'Test Notification',
                            'message': f'Gagal mengirim test notification: {error_msg}',
                            'type': 'danger',
                            'sticky': True,
                        }
                    }
                elif message.state == 'bounced':
                    error_msg = message.failure_reason or 'Nomor HP tidak valid atau tidak terdaftar di WhatsApp'
                    _logger.error(f'❌ Test notification bounced: {error_msg}')
                    return {
                        'type': 'ir.actions.client',
                        'tag': 'display_notification',
                        'params': {
                            'title': 'Test Notification',
                            'message': f'Pesan tidak dapat dikirim: {error_msg}. Pastikan nomor HP valid dan terdaftar di WhatsApp.',
                            'type': 'danger',
                            'sticky': True,
                        }
                    }
                else:
                    # Message masih dalam state 'outgoing', mungkin perlu waktu
                    _logger.warning(f'⚠️ Test notification dalam state: {message.state}')
                    return {
                        'type': 'ir.actions.client',
                        'tag': 'display_notification',
                        'params': {
                            'title': 'Test Notification',
                            'message': f'Test notification sedang diproses (state: {message.state}). Silakan cek WhatsApp Anda dalam beberapa saat atau hubungi admin untuk cek log.',
                            'type': 'info',
                            'sticky': True,
                        }
                    }
            else:
                return {
                    'type': 'ir.actions.client',
                    'tag': 'display_notification',
                    'params': {
                        'title': 'Test Notification',
                        'message': 'Tidak dapat membuat WhatsApp message.',
                        'type': 'warning',
                        'sticky': True,
                    }
                }
            
        except Exception as e:
            _logger.error(f'❌ Error mengirim test notification: {str(e)}', exc_info=True)
            return {
                'type': 'ir.actions.client',
                'tag': 'display_notification',
                'params': {
                    'title': 'Test Notification',
                    'message': f'Error mengirim test notification: {str(e)}',
                    'type': 'danger',
                    'sticky': True,
                }
            }
    
    def action_send_opt_in_message(self):
        """
        Kirim pesan opt-in ke pemilik izin untuk notifikasi WhatsApp
        
        Menggunakan text message langsung via Fonnte/Watzap (bukan template).
        """
        self.ensure_one()
        
        if not self.partner_id:
            return {
                'type': 'ir.actions.client',
                'tag': 'display_notification',
                'params': {
                    'title': 'Pesan Opt-In',
                    'message': 'Permit ini belum memiliki partner. Silakan link partner terlebih dahulu.',
                    'type': 'warning',
                    'sticky': False,
                }
            }
        
        mobile = self.partner_id._get_mobile_or_phone()
        if not mobile:
            return {
                'type': 'ir.actions.client',
                'tag': 'display_notification',
                'params': {
                    'title': 'Pesan Opt-In',
                    'message': f'Partner {self.partner_id.name} tidak memiliki nomor WhatsApp.',
                    'type': 'warning',
                    'sticky': False,
                }
            }
        
        # Prepare pesan opt-in
        message = f"""Yth. {self.applicant_name or self.partner_id.name},

DPMPTSP Kabupaten Karo memberikan layanan notifikasi WhatsApp untuk memudahkan komunikasi terkait perizinan Anda.

Dengan layanan ini, Anda akan menerima:
✅ Notifikasi real-time saat izin selesai diproses
✅ Update status perizinan otomatis
✅ Peringatan masa berlaku izin
✅ Link download dokumen langsung

Untuk mengaktifkan layanan ini, silakan balas pesan ini dengan kata "YA" atau "SETUJU".

Terima kasih atas perhatiannya.

DPMPTSP Kabupaten Karo
Kabupaten Karo"""
        
        try:
            dispatcher = self.env['sicantik.whatsapp.dispatcher']
            
            # Kirim via Fonnte (atau provider default yang mendukung text message)
            result = dispatcher.send_text_message(
                partner_id=self.partner_id.id,
                message=message,
                provider_type='fonnte'  # Force Fonnte karena mendukung text message
            )
            
            if result.get('success'):
                _logger.info(
                    f'✅ Pesan opt-in dikirim ke {self.partner_id.name} ({mobile}) '
                    f'via {result.get("provider", "unknown")}'
                )
                return {
                    'type': 'ir.actions.client',
                    'tag': 'display_notification',
                    'params': {
                        'title': 'Pesan Opt-In',
                        'message': f'✅ Pesan opt-in berhasil dikirim ke {self.partner_id.name} ({mobile}) via {result.get("provider", "unknown")}.\n\nPenerima dapat membalas dengan "YA" atau "SETUJU" untuk mengaktifkan notifikasi WhatsApp.',
                        'type': 'success',
                        'sticky': False,
                    }
                }
            else:
                error_msg = result.get('error', 'Unknown error')
                _logger.error(f'❌ Gagal kirim pesan opt-in: {error_msg}')
                return {
                    'type': 'ir.actions.client',
                    'tag': 'display_notification',
                    'params': {
                        'title': 'Pesan Opt-In',
                        'message': f'Gagal mengirim pesan opt-in: {error_msg}',
                        'type': 'danger',
                        'sticky': True,
                    }
                }
                
        except Exception as e:
            _logger.error(f'❌ Error mengirim pesan opt-in: {str(e)}', exc_info=True)
            return {
                'type': 'ir.actions.client',
                'tag': 'display_notification',
                'params': {
                    'title': 'Pesan Opt-In',
                    'message': f'Error mengirim pesan opt-in: {str(e)}',
                    'type': 'danger',
                    'sticky': True,
                }
            }
    
    def action_send_notification_info(self):
        """
        Kirim pesan informasi layanan notifikasi WhatsApp ke pemilik izin
        
        Menggunakan text message langsung via Fonnte/Watzap.
        """
        self.ensure_one()
        
        if not self.partner_id:
            return {
                'type': 'ir.actions.client',
                'tag': 'display_notification',
                'params': {
                    'title': 'Informasi Notifikasi',
                    'message': 'Permit ini belum memiliki partner. Silakan link partner terlebih dahulu.',
                    'type': 'warning',
                    'sticky': False,
                }
            }
        
        mobile = self.partner_id._get_mobile_or_phone()
        if not mobile:
            return {
                'type': 'ir.actions.client',
                'tag': 'display_notification',
                'params': {
                    'title': 'Informasi Notifikasi',
                    'message': f'Partner {self.partner_id.name} tidak memiliki nomor WhatsApp.',
                    'type': 'warning',
                    'sticky': False,
                }
            }
        
        # Prepare pesan informasi
        message = f"""Yth. {self.applicant_name or self.partner_id.name},

📱 Layanan Notifikasi WhatsApp DPMPTSP Kabupaten Karo

Kami memberikan layanan notifikasi WhatsApp untuk memudahkan komunikasi terkait perizinan Anda.

Layanan yang tersedia:
✅ Notifikasi saat izin selesai diproses
✅ Update status perizinan otomatis
✅ Peringatan masa berlaku izin (90, 60, 30, 7 hari sebelum expired)
✅ Link download dokumen langsung
✅ Notifikasi perpanjangan izin

Layanan ini GRATIS dan dapat membantu Anda:
• Tetap update dengan status perizinan Anda
• Tidak ketinggalan informasi penting
• Akses dokumen dengan mudah via WhatsApp

Untuk pertanyaan atau bantuan, silakan hubungi:
📞 DPMPTSP Kabupaten Karo
🌐 https://sicantik.dotakaro.com

Terima kasih atas perhatiannya.

DPMPTSP Kabupaten Karo
Kabupaten Karo"""
        
        try:
            dispatcher = self.env['sicantik.whatsapp.dispatcher']
            
            # Kirim via Fonnte (atau provider default yang mendukung text message)
            result = dispatcher.send_text_message(
                partner_id=self.partner_id.id,
                message=message,
                provider_type='fonnte'  # Force Fonnte karena mendukung text message
            )
            
            if result.get('success'):
                _logger.info(
                    f'✅ Pesan informasi notifikasi dikirim ke {self.partner_id.name} ({mobile}) '
                    f'via {result.get("provider", "unknown")}'
                )
                return {
                    'type': 'ir.actions.client',
                    'tag': 'display_notification',
                    'params': {
                        'title': 'Informasi Notifikasi',
                        'message': f'✅ Pesan informasi berhasil dikirim ke {self.partner_id.name} ({mobile}) via {result.get("provider", "unknown")}.',
                        'type': 'success',
                        'sticky': False,
                    }
                }
            else:
                error_msg = result.get('error', 'Unknown error')
                _logger.error(f'❌ Gagal kirim pesan informasi: {error_msg}')
                return {
                    'type': 'ir.actions.client',
                    'tag': 'display_notification',
                    'params': {
                        'title': 'Informasi Notifikasi',
                        'message': f'Gagal mengirim pesan informasi: {error_msg}',
                        'type': 'danger',
                        'sticky': True,
                    }
                }
                
        except Exception as e:
            _logger.error(f'❌ Error mengirim pesan informasi: {str(e)}', exc_info=True)
            return {
                'type': 'ir.actions.client',
                'tag': 'display_notification',
                'params': {
                    'title': 'Informasi Notifikasi',
                    'message': f'Error mengirim pesan informasi: {str(e)}',
                    'type': 'danger',
                    'sticky': True,
                }
            }

