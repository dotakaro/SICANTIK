# -*- coding: utf-8 -*-

from odoo import models, api
from odoo.tools import html2plaintext
import logging

_logger = logging.getLogger(__name__)


class WhatsappMessage(models.Model):
    """
    Override WhatsApp Message untuk memastikan opt-in formal tercatat
    ketika pesan inbound diterima dari Meta WhatsApp Business Account.
    Juga mendeteksi pesan persetujuan khusus untuk logging yang lebih detail.
    """
    _inherit = 'whatsapp.message'
    
    @api.model_create_multi
    def create(self, vals_list):
        """
        Override create untuk memastikan opt-in formal tercatat
        setelah pesan inbound dibuat oleh Odoo core.
        Juga mendeteksi pesan persetujuan khusus untuk logging yang lebih detail.
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

