# -*- coding: utf-8 -*-

from odoo import api, fields, models


class ResConfigSettings(models.TransientModel):
    _inherit = 'res.config.settings'

    tte_verification_base_url = fields.Char(
        string='TTE Verification Base URL',
        help='Base URL untuk QR code verifikasi dokumen TTE. '
             'Contoh: https://sicantik.dotakaro.com',
        config_parameter='sicantik_tte.verification_base_url',
    )

    def set_values(self):
        super().set_values()
        # Trigger recompute verification_url untuk semua dokumen yang sudah signed
        if self.tte_verification_base_url:
            self.env['sicantik.document'].search([
                ('state', 'in', ['signed', 'verified'])
            ])._compute_verification_url()

