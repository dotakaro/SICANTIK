# -*- coding: utf-8 -*-

from odoo import models, api, fields, _
from odoo.tools import html2plaintext
import logging

_logger = logging.getLogger(__name__)


class WhatsappMessage(models.Model):
    """
    Override WhatsApp Message untuk memastikan opt-in formal tercatat
    ketika pesan inbound diterima dari Meta WhatsApp Business Account.
    Juga mendeteksi pesan persetujuan khusus untuk logging yang lebih detail.
    Dan mengirim balasan otomatis atas persetujuan tersebut (session message).
    """
    _inherit = 'whatsapp.message'
    
    @api.model_create_multi
    def create(self, vals_list):
        """
        Override create untuk memastikan opt-in formal tercatat
        setelah pesan inbound dibuat oleh Odoo core.
        Juga mendeteksi pesan persetujuan khusus untuk logging yang lebih detail.
        Dan mengirim balasan otomatis atas persetujuan tersebut.
        """
        # Log semua pesan yang akan dibuat untuk debugging
        _logger.info(
            f'🔍 [DEBUG] whatsapp.message.create() dipanggil dengan {len(vals_list)} pesan'
        )
        for idx, vals in enumerate(vals_list):
            _logger.info(
                f'   [{idx}] message_type={vals.get("message_type")}, '
                f'mobile={vals.get("mobile_number")}, '
                f'body={str(vals.get("body", ""))[:50]}...'
            )
        
        # Panggil method parent untuk membuat pesan (Odoo core logic)
        messages = super().create(vals_list)
        
        _logger.info(
            f'🔍 [DEBUG] whatsapp.message.create() selesai, {len(messages)} pesan dibuat'
        )
        
        # Setelah pesan dibuat, cek apakah ada pesan inbound baru
        for message in messages:
            _logger.info(
                f'🔍 [DEBUG] Memproses pesan ID={message.id}, '
                f'type={message.message_type}, '
                f'mobile={message.mobile_number_formatted}, '
                f'body={str(message.body)[:50] if message.body else "None"}...'
            )
            
            if message.message_type == 'inbound' and message.mobile_number_formatted:
                try:
                    # Cek apakah pesan mengandung persetujuan
                    # Body ada di mail_message_id, bukan langsung di whatsapp.message
                    # whatsapp.message.body adalah related field ke mail_message_id.body
                    body_text = ''
                    
                    # Baca body dari mail_message_id (prioritas utama)
                    if message.mail_message_id:
                        if message.mail_message_id.body:
                            body_text = html2plaintext(message.mail_message_id.body).lower()
                            _logger.info(
                                f'🔍 [DEBUG] Body dari mail_message_id untuk Message ID={message.id}: {body_text[:100]}...'
                            )
                        else:
                            _logger.warning(
                                f'⚠️ mail_message_id.body kosong untuk Message ID={message.id}, '
                                f'mail_message_id={message.mail_message_id.id}'
                            )
                    else:
                        _logger.warning(
                            f'⚠️ mail_message_id tidak ditemukan untuk Message ID={message.id}'
                        )
                    
                    # Fallback: baca dari whatsapp.message.body (related field)
                    if not body_text and message.body:
                        body_text = html2plaintext(message.body).lower()
                        _logger.info(
                            f'🔍 [DEBUG] Body dari whatsapp.message.body (fallback) untuk Message ID={message.id}: {body_text[:100]}...'
                        )
                    
                    _logger.info(
                        f'🔍 [DEBUG] Body text final untuk Message ID={message.id}: {body_text[:100] if body_text else "(kosong)"}...'
                    )
                    
                    consent_keywords = [
                        'ya saya setuju',
                        'setuju',
                        'saya setuju',
                        'ya setuju',
                        'setuju menerima',
                        'setuju menerima pesan notifikasi',
                        'setuju menerima pesan notifikasi dari dpmptsp',
                    ]
                    
                    is_consent_message = any(keyword in body_text for keyword in consent_keywords)
                    
                    _logger.info(
                        f'🔍 [DEBUG] Is consent message: {is_consent_message} untuk Message ID={message.id}'
                    )
                    
                    # Panggil opt-in manager untuk set opt-in formal
                    opt_in_manager = self.env['whatsapp.opt.in.manager']
                    opt_in_manager.auto_opt_in_from_inbound_message(message.id)
                    
                    # Jika body kosong atau mail_message_id belum terisi, coba lagi dengan delay
                    if not body_text and not message.mail_message_id:
                        _logger.info(
                            f'⚠️ Body kosong atau mail_message_id belum terisi untuk Message ID={message.id}, '
                            f'mencoba delayed check...'
                        )
                        # Schedule delayed check (gunakan cron atau delay)
                        # Untuk sekarang, kita coba langsung setelah create selesai
                        self.env['ir.cron'].sudo().create({
                            'name': f'Check consent reply for message {message.id}',
                            'model_id': self.env['ir.model']._get_id('whatsapp.message'),
                            'state': 'code',
                            'code': f'model._check_and_send_consent_reply_delayed({message.id})',
                            'interval_number': 1,
                            'interval_type': 'minutes',
                            'numbercall': 1,
                            'active': True,
                        })
                    elif is_consent_message:
                        _logger.info(
                            f'✅ Pesan persetujuan terdeteksi untuk nomor {message.mobile_number_formatted} '
                            f'(Message ID: {message.id})'
                        )
                        _logger.info(
                            f'   Isi pesan: {body_text[:100]}...'
                        )
                        
                        # Kirim balasan otomatis atas persetujuan (session message, tidak perlu template)
                        # Karena masih dalam 24-hour window setelah user mengirim pesan inbound
                        self._send_consent_reply(message)
                    else:
                        _logger.info(
                            f'✅ Opt-in formal diproses untuk pesan inbound '
                            f'(Message ID: {message.id}, Nomor: {message.mobile_number_formatted})'
                        )
                except Exception as e:
                    # Jangan gagal jika ada error dalam opt-in processing
                    # Log error tapi tetap lanjutkan proses normal
                    _logger.warning(
                        f'⚠️ Error saat memproses opt-in formal untuk pesan inbound '
                        f'(Message ID: {message.id}): {str(e)}',
                        exc_info=True
                    )
        
        return messages
    
    def _send_consent_reply(self, inbound_message):
        """
        Kirim balasan otomatis atas pesan persetujuan.
        Menggunakan session message (text message tanpa template) karena masih dalam 24-hour window.
        
        Args:
            inbound_message: whatsapp.message record (inbound message dari user)
        """
        try:
            # Pastikan ini adalah pesan inbound dari Meta WhatsApp Business Account
            if not inbound_message.wa_account_id:
                _logger.warning(
                    f'⚠️ Tidak bisa kirim balasan: WhatsApp Account tidak ditemukan '
                    f'(Message ID: {inbound_message.id})'
                )
                return
            
            # Cari partner berdasarkan nomor WhatsApp
            # Odoo 18.4 hanya punya field 'phone', tidak ada 'mobile'
            partner = self.env['res.partner'].search([
                ('phone', '=', inbound_message.mobile_number_formatted)
            ], limit=1)
            
            if not partner:
                # Coba cari dengan format lain (tanpa +, tanpa spasi)
                mobile_clean = inbound_message.mobile_number_formatted.replace('+', '').replace(' ', '').replace('-', '').replace('(', '').replace(')', '')
                partner = self.env['res.partner'].search([
                    ('phone', 'ilike', mobile_clean),
                ], limit=1)
            
            # Jika masih tidak ditemukan, coba cari dengan berbagai variasi format
            if not partner:
                # Coba dengan format internasional (dengan +)
                if not inbound_message.mobile_number_formatted.startswith('+'):
                    partner = self.env['res.partner'].search([
                        ('phone', 'ilike', f'+{inbound_message.mobile_number_formatted}'),
                    ], limit=1)
            
            if not partner:
                # Coba cari dengan format lokal (tanpa kode negara)
                # Jika nomor dimulai dengan 62 (Indonesia), coba tanpa 62
                if inbound_message.mobile_number_formatted.startswith('62'):
                    local_number = inbound_message.mobile_number_formatted[2:]
                    partner = self.env['res.partner'].search([
                        ('phone', 'ilike', local_number),
                    ], limit=1)
            
            # Siapkan pesan balasan
            partner_name = partner.name if partner else 'Bapak/Ibu'
            reply_message = f"""Terima kasih {partner_name} atas persetujuan Anda.

Notifikasi WhatsApp untuk perizinan Anda telah diaktifkan. Mulai sekarang, Anda akan menerima:

✅ Notifikasi real-time saat izin selesai diproses
✅ Update status perizinan otomatis
✅ Peringatan masa berlaku izin
✅ Link download dokumen langsung

Jika ada pertanyaan, jangan ragu untuk menghubungi kami.

Terima kasih.

DPMPTSP Kabupaten Karo
Kabupaten Karo"""
            
            # Buat whatsapp.message untuk session message (text tanpa template)
            # Karena masih dalam 24-hour window, kita bisa kirim session message langsung
            # Catatan: whatsapp.message memerlukan mail.message untuk body
            # Kita perlu buat mail.message terlebih dahulu
            mail_message_vals = {
                'model': 'res.partner' if partner else False,
                'res_id': partner.id if partner else False,
                'body': reply_message,
                'message_type': 'notification',
                'subtype_id': self.env.ref('mail.mt_note').id,
            }
            mail_message = self.env['mail.message'].create(mail_message_vals)
            
            message_vals = {
                'wa_account_id': inbound_message.wa_account_id.id,
                'mobile_number': inbound_message.mobile_number,
                'mobile_number_formatted': inbound_message.mobile_number_formatted,
                'mail_message_id': mail_message.id,
                'message_type': 'outbound',  # Outbound message
                'state': 'outgoing',
            }
            
            # Buat dan kirim pesan
            reply_msg = self.env['whatsapp.message'].create(message_vals)
            
            # Kirim pesan sebagai reply ke pesan inbound (menggunakan context)
            try:
                # Gunakan WhatsAppApi untuk mengirim session message
                from odoo.addons.whatsapp.tools.whatsapp_api import WhatsAppApi
                wa_api = WhatsAppApi(inbound_message.wa_account_id)
                
                # Kirim sebagai reply ke pesan inbound
                # Format send_vals untuk text message: {'body': 'text content', 'preview_url': True}
                send_vals = {
                    'body': reply_message,
                    'preview_url': True,  # Enable link preview
                }
                
                # Cari msg_uid dari pesan inbound untuk reply
                # msg_uid adalah WhatsApp Message ID dari Meta (format: wamid.xxx)
                parent_message_id = False
                if hasattr(inbound_message, 'msg_uid') and inbound_message.msg_uid:
                    parent_message_id = inbound_message.msg_uid
                
                # Kirim sebagai reply (dengan context message_id jika ada)
                # Ini adalah session message (text tanpa template) karena masih dalam 24-hour window
                msg_uid = wa_api._send_whatsapp(
                    number=inbound_message.mobile_number_formatted,
                    message_type='text',
                    send_vals=send_vals,
                    parent_message_id=parent_message_id  # Reply ke pesan inbound (jika ada)
                )
                
                # Update message dengan msg_uid dari Meta
                if msg_uid:
                    reply_msg.write({
                        'msg_uid': msg_uid,
                        'state': 'sent'
                    })
                    _logger.info(
                        f'✅ Balasan persetujuan terkirim ke {inbound_message.mobile_number_formatted} '
                        f'(Message UID: {msg_uid})'
                    )
                else:
                    # Jika tidak ada msg_uid, coba kirim via normal method
                    reply_msg._send_message(with_commit=True)
                    _logger.info(
                        f'✅ Balasan persetujuan terkirim ke {inbound_message.mobile_number_formatted} '
                        f'(via normal method)'
                    )
                    
            except Exception as send_error:
                _logger.error(
                    f'❌ Error saat mengirim balasan persetujuan: {str(send_error)}',
                    exc_info=True
                )
                # Coba kirim via normal method sebagai fallback
                try:
                    reply_msg._send_message(with_commit=True)
                except Exception as fallback_error:
                    _logger.error(
                        f'❌ Error saat fallback send: {str(fallback_error)}',
                        exc_info=True
                    )
                    
        except Exception as e:
            # Jangan gagal jika ada error dalam pengiriman balasan
            # Log error tapi tetap lanjutkan proses normal
            _logger.warning(
                f'⚠️ Error saat membuat balasan persetujuan '
                f'(Message ID: {inbound_message.id}): {str(e)}',
                exc_info=True
            )

