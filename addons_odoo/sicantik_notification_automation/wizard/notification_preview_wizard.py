# -*- coding: utf-8 -*-

from odoo import models, fields, api, _
from odoo.exceptions import UserError
import logging

_logger = logging.getLogger(__name__)


class NotificationPreviewWizard(models.TransientModel):
    """
    Wizard untuk preview pesan sebelum dikirim
    """
    _name = 'sicantik.notification.preview.wizard'
    _description = 'Preview Notifikasi'
    
    rule_id = fields.Many2one(
        'sicantik.notification.rule',
        string='Aturan',
        required=True,
        help='Aturan yang akan di-preview'
    )
    partner_id = fields.Many2one(
        'res.partner',
        string='Penerima',
        required=True,
        help='Partner untuk preview'
    )
    preview_text = fields.Html(
        string='Preview Pesan',
        readonly=True,
        help='Preview pesan yang akan dikirim'
    )
    
    @api.onchange('rule_id', 'partner_id')
    def _onchange_preview(self):
        """Generate preview pesan"""
        if not self.rule_id or not self.partner_id:
            return
        
        # Ambil template master jika ada
        template_master = self.rule_id.template_master_id
        if not template_master:
            # Cari template master berdasarkan template_key
            template_master = self.env['sicantik.whatsapp.template.master'].search([
                ('template_key', '=', self.rule_id.template_key)
            ], limit=1)
        
        if template_master:
            # Generate preview dari template master
            body_preview = template_master.body_preview or ''
            
            # Replace placeholder dengan contoh data
            context_values = {
                'partner_name': self.partner_id.name or 'Bapak/Ibu',
                'permit_number': 'CONTOH/2025/00001',
                'permit_type': 'Contoh Jenis Izin',
                'status': 'Contoh Status',
            }
            
            # Replace placeholder
            for key, value in context_values.items():
                body_preview = body_preview.replace(f'{{{{{key}}}}}', str(value))
            
            self.preview_text = body_preview
        else:
            self.preview_text = _('Template tidak ditemukan')

