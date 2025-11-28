# -*- coding: utf-8 -*-

from odoo import models, fields, api, _
from odoo.exceptions import UserError, ValidationError
import logging
import re

_logger = logging.getLogger(__name__)


class NotificationBlacklist(models.Model):
    """
    Model untuk mengelola daftar nomor yang tidak ingin menerima notifikasi
    """
    _name = 'sicantik.notification.blacklist'
    _description = 'Daftar Hitam Notifikasi'
    _order = 'create_date desc'
    
    # Informasi Dasar
    phone_number = fields.Char(
        string='Nomor Telepon',
        required=True,
        index=True,
        help='Nomor telepon yang akan diblokir'
    )
    partner_id = fields.Many2one(
        'res.partner',
        string='Partner',
        ondelete='set null',
        help='Partner terkait (optional)'
    )
    reason = fields.Text(
        string='Alasan',
        help='Alasan nomor ini diblokir'
    )
    active = fields.Boolean(
        string='Aktif',
        default=True,
        help='Nonaktifkan untuk mengaktifkan kembali nomor ini'
    )
    
    # Statistik
    blocked_count = fields.Integer(
        string='Jumlah Diblokir',
        default=0,
        readonly=True,
        help='Jumlah kali nomor ini diblokir'
    )
    last_blocked_date = fields.Datetime(
        string='Terakhir Diblokir',
        readonly=True,
        help='Waktu terakhir nomor ini diblokir'
    )
    
    _sql_constraints = [
        ('phone_number_unique', 'UNIQUE(phone_number)', 'Nomor telepon sudah ada di daftar hitam!')
    ]
    
    @api.constrains('phone_number')
    def _check_phone_number(self):
        """Validasi format nomor telepon"""
        for record in self:
            if not record.phone_number:
                raise ValidationError(_('Nomor telepon harus diisi'))
            
            # Normalisasi nomor telepon (hapus spasi, dash, dll)
            normalized = re.sub(r'[\s\-\(\)]', '', record.phone_number)
            
            # Validasi hanya angka dan + (untuk kode negara)
            if not re.match(r'^\+?\d+$', normalized):
                raise ValidationError(_('Format nomor telepon tidak valid'))
    
    def action_block(self):
        """Blokir nomor dan update statistik"""
        self.ensure_one()
        self.write({
            'active': True,
            'blocked_count': self.blocked_count + 1,
            'last_blocked_date': fields.Datetime.now()
        })
    
    def action_unblock(self):
        """Aktifkan kembali nomor"""
        self.write({'active': False})
    
    @api.model
    def is_blacklisted(self, phone_number):
        """
        Cek apakah nomor ada di daftar hitam
        
        Args:
            phone_number (str): Nomor telepon yang akan dicek
        
        Returns:
            bool: True jika nomor ada di daftar hitam
        """
        if not phone_number:
            return False
        
        # Normalisasi nomor telepon
        normalized = re.sub(r'[\s\-\(\)]', '', phone_number)
        
        # Cek di daftar hitam
        blacklist = self.search([
            ('phone_number', '=', normalized),
            ('active', '=', True)
        ], limit=1)
        
        if blacklist:
            # Update statistik
            blacklist.write({
                'blocked_count': blacklist.blocked_count + 1,
                'last_blocked_date': fields.Datetime.now()
            })
            return True
        
        return False

