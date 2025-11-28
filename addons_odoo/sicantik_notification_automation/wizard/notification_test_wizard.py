# -*- coding: utf-8 -*-

from odoo import models, fields, api, _
from odoo.exceptions import UserError
import logging

_logger = logging.getLogger(__name__)


class NotificationTestWizard(models.TransientModel):
    """
    Wizard untuk test send notifikasi ke nomor tertentu
    """
    _name = 'sicantik.notification.test.wizard'
    _description = 'Test Send Notifikasi'
    
    partner_id = fields.Many2one(
        'res.partner',
        string='Penerima',
        required=True,
        help='Partner yang akan menerima notifikasi test'
    )
    template_key = fields.Char(
        string='Template Key',
        required=True,
        help='Key template yang akan digunakan'
    )
    context_values = fields.Text(
        string='Context Values (JSON)',
        help='Data context untuk template (format JSON)'
    )
    
    def action_send_test(self):
        """Kirim notifikasi test"""
        self.ensure_one()
        
        if not self.partner_id.phone and not self.partner_id.mobile:
            raise UserError(_('Partner harus memiliki nomor telepon'))
        
        # Parse context values
        import json
        context_values = {}
        if self.context_values:
            try:
                context_values = json.loads(self.context_values)
            except json.JSONDecodeError:
                raise UserError(_('Format JSON tidak valid'))
        
        # Kirim via WhatsApp Dispatcher
        dispatcher = self.env['sicantik.whatsapp.dispatcher']
        result = dispatcher.send_template_message(
            template_key=self.template_key,
            partner_id=self.partner_id.id,
            context_values=context_values,
        )
        
        if result.get('success'):
            return {
                'type': 'ir.actions.client',
                'tag': 'display_notification',
                'params': {
                    'title': _('Test Berhasil'),
                    'message': _('Notifikasi test berhasil dikirim ke %s') % self.partner_id.name,
                    'type': 'success',
                }
            }
        else:
            raise UserError(_('Test gagal: %s') % result.get('error', 'Unknown error'))

