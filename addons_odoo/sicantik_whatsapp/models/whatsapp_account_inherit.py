# -*- coding: utf-8 -*-

from odoo import api, models
import logging

_logger = logging.getLogger(__name__)


class WhatsappAccount(models.Model):
    """
    Override WhatsApp Account untuk fix webhook URL menggunakan domain yang benar
    dan memastikan opt-in formal tercatat ketika pesan inbound diterima.
    """
    _inherit = 'whatsapp.account'

    @api.depends('account_uid')
    def _compute_callback_url(self):
        """
        Override untuk menggunakan domain yang benar (sicantik.dotakaro.com)
        bukan localhost atau internal URL.
        """
        for account in self:
            # Ambil webhook base URL dari system parameter atau gunakan default
            webhook_base_url = self.env['ir.config_parameter'].sudo().get_param(
                'sicantik_whatsapp.webhook_base_url',
                default='https://sicantik.dotakaro.com'
            )
            # Pastikan tidak ada trailing slash
            webhook_base_url = webhook_base_url.rstrip('/')
            account.callback_url = f"{webhook_base_url}/whatsapp/webhook"
    
    @api.model
    def create(self, vals):
        """Override create untuk memastikan callback_url ter-set dengan benar."""
        account = super().create(vals)
        account._compute_callback_url()
        return account
    
    def write(self, vals):
        """Override write untuk memastikan callback_url ter-update jika account_uid berubah."""
        result = super().write(vals)
        if 'account_uid' in vals:
            self._compute_callback_url()
        return result
    
    def _process_messages(self, value):
        """
        Override _process_messages untuk memastikan opt-in formal tercatat
        ketika pesan inbound diterima dari Meta WhatsApp Business Account.
        
        Setelah Odoo core memproses pesan inbound, kita akan:
        1. Cari partner berdasarkan nomor WhatsApp
        2. Set whatsapp_opt_in = True jika belum
        3. Catat timestamp opt-in
        """
        # Panggil method parent untuk memproses pesan (Odoo core logic)
        result = super()._process_messages(value)
        
        # Setelah pesan diproses, cek apakah ada pesan inbound baru
        # Odoo core akan membuat whatsapp.message record untuk setiap pesan inbound
        try:
            # Cari pesan inbound yang baru saja dibuat untuk account ini
            # Format value dari webhook Meta
            if 'messages' not in value and value.get('whatsapp_business_api_data', {}).get('messages'):
                value = value['whatsapp_business_api_data']
            
            for message_data in value.get('messages', []):
                sender_mobile = message_data.get('from')
                if not sender_mobile:
                    continue
                
                # Cari whatsapp.message yang baru dibuat untuk nomor ini
                whatsapp_message = self.env['whatsapp.message'].sudo().search([
                    ('wa_account_id', '=', self.id),
                    ('mobile_number_formatted', '=', sender_mobile),
                    ('message_type', '=', 'inbound')
                ], order='create_date desc', limit=1)
                
                if whatsapp_message:
                    # Panggil opt-in manager untuk set opt-in formal
                    opt_in_manager = self.env['whatsapp.opt.in.manager']
                    opt_in_manager.auto_opt_in_from_inbound_message(whatsapp_message.id)
                    _logger.info(
                        f'✅ Opt-in formal tercatat untuk nomor {sender_mobile} '
                        f'dari pesan inbound WhatsApp Business Account'
                    )
        except Exception as e:
            # Jangan gagal jika ada error dalam opt-in processing
            # Log error tapi tetap lanjutkan proses normal
            _logger.warning(
                f'⚠️ Error saat memproses opt-in formal untuk pesan inbound: {str(e)}',
                exc_info=True
            )
        
        return result

