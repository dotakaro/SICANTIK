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
        # Panggil method parent untuk membuat pesan (Odoo core logic)
        messages = super().create(vals_list)
        
        # Setelah pesan dibuat, cek apakah ada pesan inbound baru
        for message in messages:
            if message.message_type == 'inbound' and message.mobile_number_formatted:
                try:
                    # Cek apakah pesan mengandung persetujuan
                    body_text = html2plaintext(message.body).lower() if message.body else ''
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
                    
                    # Panggil opt-in manager untuk set opt-in formal
                    opt_in_manager = self.env['whatsapp.opt.in.manager']
                    opt_in_manager.auto_opt_in_from_inbound_message(message.id)
                    
                    if is_consent_message:
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
            partner = self.env['res.partner'].search([
                ('phone', '=', inbound_message.mobile_number_formatted)
            ], limit=1)
            
            if not partner:
                # Coba cari dengan format lain
                mobile_clean = inbound_message.mobile_number_formatted.replace('+', '').replace(' ', '')
                partner = self.env['res.partner'].search([
                    '|',
                    ('phone', 'ilike', mobile_clean),
                    ('mobile', 'ilike', mobile_clean),
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
            message_vals = {
                'wa_account_id': inbound_message.wa_account_id.id,
                'mobile_number': inbound_message.mobile_number,
                'mobile_number_formatted': inbound_message.mobile_number_formatted,
                'body': reply_message,
                'message_type': 'text',  # Session message (text tanpa template)
                'state': 'outgoing',
            }
            
            # Jika ada partner, link ke partner
            if partner:
                message_vals['res_model'] = 'res.partner'
                message_vals['res_id'] = partner.id
            
            # Buat dan kirim pesan
            reply_msg = self.env['whatsapp.message'].create(message_vals)
            
            # Kirim pesan sebagai reply ke pesan inbound (menggunakan context)
            try:
                # Gunakan WhatsAppApi untuk mengirim session message
                from odoo.addons.whatsapp.tools.whatsapp_api import WhatsAppApi
                wa_api = WhatsAppApi(inbound_message.wa_account_id)
                
                # Kirim sebagai reply ke pesan inbound
                # Format send_vals untuk text message: {'body': 'text content'}
                send_vals = {
                    'body': reply_message
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
                
                # Update message dengan message_uid dari Meta
                if msg_uid:
                    reply_msg.write({
                        'message_uid': msg_uid,
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

