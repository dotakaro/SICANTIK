# -*- coding: utf-8 -*-

from odoo import api, models


class WhatsappAccount(models.Model):
    """
    Override WhatsApp Account untuk fix webhook URL menggunakan domain yang benar.
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

