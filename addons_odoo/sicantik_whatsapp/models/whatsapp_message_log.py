# -*- coding: utf-8 -*-

from odoo import models, fields, api, _
from odoo.exceptions import UserError
import logging

_logger = logging.getLogger(__name__)


class WhatsAppMessageLog(models.Model):
    """
    Model untuk menyimpan history/log pesan WhatsApp yang dikirim via Fonnte/Watzap
    (bukan Meta API yang sudah punya whatsapp.message)
    """
    _name = 'sicantik.whatsapp.message.log'
    _description = 'WhatsApp Message Log (Fonnte/Watzap)'
    _order = 'create_date desc'
    _rec_name = 'mobile_number'

    # Message Info
    mobile_number = fields.Char(
        string='Nomor Tujuan',
        required=True,
        index=True,
        help='Nomor WhatsApp penerima pesan'
    )
    
    partner_id = fields.Many2one(
        'res.partner',
        string='Partner',
        index=True,
        help='Partner yang menerima pesan'
    )
    
    # Provider Info
    provider_type = fields.Selection(
        selection=[
            ('fonnte', 'Fonnte'),
            ('watzap', 'Watzap.id'),
            ('meta', 'Meta (Official)'),
        ],
        string='Provider',
        required=True,
        index=True,
        help='Provider yang digunakan untuk mengirim pesan'
    )
    
    provider_id = fields.Many2one(
        'sicantik.whatsapp.provider',
        string='Provider Profile',
        help='Profil provider yang digunakan'
    )
    
    # Message Content
    message_type = fields.Selection(
        selection=[
            ('text', 'Text Message'),
            ('template', 'Template Message'),
        ],
        string='Tipe Pesan',
        default='text',
        required=True,
        help='Tipe pesan yang dikirim'
    )
    
    message_content = fields.Text(
        string='Isi Pesan',
        help='Isi pesan yang dikirim (untuk text message)'
    )
    
    template_key = fields.Char(
        string='Template Key',
        help='Key template yang digunakan (untuk template message)'
    )
    
    template_id = fields.Many2one(
        'sicantik.whatsapp.template.master',
        string='Master Template',
        help='Master template yang digunakan'
    )
    
    # Status
    state = fields.Selection(
        selection=[
            ('pending', 'Pending'),
            ('sent', 'Sent'),
            ('delivered', 'Delivered'),
            ('read', 'Read'),
            ('failed', 'Failed'),
        ],
        string='Status',
        default='pending',
        required=True,
        index=True,
        help='Status pengiriman pesan'
    )
    
    # External Reference
    external_message_id = fields.Char(
        string='External Message ID',
        help='ID pesan dari provider (Fonnte/Watzap)'
    )
    
    # Error Handling
    error_message = fields.Text(
        string='Error Message',
        help='Pesan error jika pengiriman gagal'
    )
    
    # Related Document
    res_model = fields.Char(
        string='Related Model',
        index=True,
        help='Model terkait (misal: sicantik.permit, sicantik.document)'
    )
    
    res_id = fields.Integer(
        string='Related ID',
        index=True,
        help='ID record terkait'
    )
    
    # Timestamps
    sent_date = fields.Datetime(
        string='Sent Date',
        readonly=True,
        help='Waktu pesan dikirim'
    )
    
    delivered_date = fields.Datetime(
        string='Delivered Date',
        readonly=True,
        help='Waktu pesan terkirim (delivered)'
    )
    
    read_date = fields.Datetime(
        string='Read Date',
        readonly=True,
        help='Waktu pesan dibaca'
    )
    
    # Additional Info
    response_data = fields.Text(
        string='Response Data',
        help='Data response dari provider (JSON format)'
    )
    
    @api.model
    def create_log(self, partner_id=None, mobile_number=None, provider_type=None, 
                   provider_id=None, message_type='text', message_content=None,
                   template_key=None, template_id=None, external_message_id=None,
                   state='sent', error_message=None, res_model=None, res_id=None,
                   response_data=None):
        """
        Helper method untuk membuat log pesan
        
        Args:
            partner_id: ID partner penerima
            mobile_number: Nomor WhatsApp penerima
            provider_type: Tipe provider ('fonnte', 'watzap', 'meta')
            provider_id: ID provider profile
            message_type: 'text' atau 'template'
            message_content: Isi pesan (untuk text message)
            template_key: Key template (untuk template message)
            template_id: ID master template (untuk template message)
            external_message_id: ID pesan dari provider
            state: Status pengiriman
            error_message: Pesan error jika gagal
            res_model: Model terkait
            res_id: ID record terkait
            response_data: Data response dari provider (JSON string)
        
        Returns:
            Recordset: Record log yang baru dibuat
        """
        vals = {
            'mobile_number': mobile_number,
            'partner_id': partner_id,
            'provider_type': provider_type,
            'provider_id': provider_id,
            'message_type': message_type,
            'message_content': message_content,
            'template_key': template_key,
            'template_id': template_id,
            'external_message_id': external_message_id,
            'state': state,
            'error_message': error_message,
            'res_model': res_model,
            'res_id': res_id,
            'response_data': response_data,
        }
        
        if state == 'sent':
            vals['sent_date'] = fields.Datetime.now()
        
        return self.create(vals)
    
    def action_update_status(self, new_state, external_message_id=None, response_data=None):
        """
        Update status log pesan
        
        Args:
            new_state: Status baru ('sent', 'delivered', 'read', 'failed')
            external_message_id: ID pesan dari provider (optional)
            response_data: Data response dari provider (optional)
        """
        self.ensure_one()
        
        vals = {
            'state': new_state,
        }
        
        if external_message_id:
            vals['external_message_id'] = external_message_id
        
        if response_data:
            vals['response_data'] = response_data
        
        if new_state == 'sent' and not self.sent_date:
            vals['sent_date'] = fields.Datetime.now()
        elif new_state == 'delivered' and not self.delivered_date:
            vals['delivered_date'] = fields.Datetime.now()
        elif new_state == 'read' and not self.read_date:
            vals['read_date'] = fields.Datetime.now()
        
        self.write(vals)
    
    def action_mark_failed(self, error_message, response_data=None):
        """
        Tandai log sebagai failed
        
        Args:
            error_message: Pesan error
            response_data: Data response dari provider (optional)
        """
        self.ensure_one()
        self.write({
            'state': 'failed',
            'error_message': error_message,
            'response_data': response_data,
        })
    
    @api.depends('partner_id', 'mobile_number', 'message_type', 'template_key')
    def _compute_display_name(self):
        """Compute display name untuk record"""
        for record in self:
            if record.partner_id:
                name = f"{record.partner_id.name} ({record.mobile_number})"
            else:
                name = record.mobile_number or 'Unknown'
            
            if record.message_type == 'template' and record.template_key:
                name += f" - {record.template_key}"
            
            record.display_name = name
    
    display_name = fields.Char(
        string='Display Name',
        compute='_compute_display_name',
        store=True,
        index=True
    )
    
    def action_view_partner(self):
        """
        Action untuk membuka form partner dari log pesan
        """
        self.ensure_one()
        if not self.partner_id:
            return False
        
        return {
            'type': 'ir.actions.act_window',
            'name': 'Partner',
            'res_model': 'res.partner',
            'res_id': self.partner_id.id,
            'view_mode': 'form',
            'target': 'current',
        }

