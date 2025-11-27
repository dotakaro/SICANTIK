# -*- coding: utf-8 -*-

from odoo import api, fields, models


class ResConfigSettings(models.TransientModel):
    _inherit = 'res.config.settings'

    whatsapp_provider_id = fields.Many2one(
        'sicantik.whatsapp.provider',
        string='Default WhatsApp Provider',
        domain="[('active', '=', True)]",
        help='Provider default untuk mengirim notifikasi WhatsApp. '
             'Dapat di override per workflow jika dibutuhkan.',
    )
    
    whatsapp_webhook_base_url = fields.Char(
        string='WhatsApp Webhook Base URL',
        help='Base URL untuk webhook WhatsApp Meta. '
             'Contoh: https://sicantik.dotakaro.com',
        config_parameter='sicantik_whatsapp.webhook_base_url',
    )

    def set_values(self):
        super().set_values()
        icp = self.env['ir.config_parameter'].sudo()
        icp.set_param(
            'sicantik_whatsapp.default_provider_id',
            self.whatsapp_provider_id.id or False,
        )
        # Trigger recompute callback_url untuk semua WhatsApp account
        if self.whatsapp_webhook_base_url:
            self.env['whatsapp.account'].search([])._compute_callback_url()

    @api.model
    def get_values(self):
        res = super().get_values()
        icp = self.env['ir.config_parameter'].sudo()
        provider_id = icp.get_param('sicantik_whatsapp.default_provider_id', default=False)
        res['whatsapp_provider_id'] = int(provider_id) if provider_id else False
        return res

