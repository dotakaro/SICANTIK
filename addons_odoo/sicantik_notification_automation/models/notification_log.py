# -*- coding: utf-8 -*-

from odoo import models, fields, api, _
import logging

_logger = logging.getLogger(__name__)


class NotificationLog(models.Model):
    """
    Model untuk mencatat log lengkap pengiriman untuk audit
    """
    _name = 'sicantik.notification.log'
    _description = 'Log Notifikasi'
    _order = 'sent_date desc, id desc'
    _rec_name = 'display_name'
    
    # Informasi Dasar
    display_name = fields.Char(
        string='Nama',
        compute='_compute_display_name',
        store=True,
        readonly=True
    )
    
    # Asosiasi
    rule_id = fields.Many2one(
        'sicantik.notification.rule',
        string='Aturan',
        ondelete='set null',
        help='Aturan notifikasi terkait'
    )
    campaign_id = fields.Many2one(
        'sicantik.notification.campaign',
        string='Kampanye',
        ondelete='set null',
        help='Kampanye terkait (jika ada)'
    )
    queue_id = fields.Many2one(
        'sicantik.notification.queue',
        string='Antrian',
        ondelete='set null',
        help='Entri antrian terkait'
    )
    
    # Penerima
    partner_id = fields.Many2one(
        'res.partner',
        string='Penerima',
        required=True,
        ondelete='cascade',
        help='Partner yang menerima notifikasi'
    )
    partner_name = fields.Char(
        related='partner_id.name',
        string='Nama Penerima',
        store=True,
        readonly=True
    )
    partner_phone = fields.Char(
        related='partner_id.phone',
        string='Nomor Telepon',
        store=True,
        readonly=True
    )
    
    # Konfigurasi Template
    template_key = fields.Char(
        string='Template Key',
        required=True,
        help='Key template yang digunakan'
    )
    context_values = fields.Text(
        string='Context Values',
        help='Data context yang digunakan (JSON format)'
    )
    
    # Status
    state = fields.Selection([
        ('sent', 'Terkirim'),
        ('failed', 'Gagal'),
        ('skipped', 'Dilewati'),
    ], string='Status', required=True, index=True)
    
    # Pengiriman
    sent_date = fields.Datetime(
        string='Tanggal Terkirim',
        required=True,
        index=True,
        help='Waktu pengiriman'
    )
    provider_id = fields.Many2one(
        'sicantik.whatsapp.provider',
        string='Provider',
        help='Provider yang digunakan'
    )
    provider_type = fields.Selection([
        ('meta', 'Meta WhatsApp'),
        ('fonnte', 'Fonnte'),
        ('watzap', 'Watzap'),
    ], string='Tipe Provider')
    external_message_id = fields.Char(
        string='ID Pesan Eksternal',
        help='ID pesan dari provider eksternal'
    )
    
    # Error Handling
    error_message = fields.Text(
        string='Pesan Error',
        help='Pesan error jika gagal'
    )
    
    # Statistik
    processing_time_ms = fields.Integer(
        string='Waktu Proses (ms)',
        help='Waktu yang dibutuhkan untuk memproses'
    )
    
    @api.depends('partner_id', 'template_key', 'sent_date')
    def _compute_display_name(self):
        """Generate nama untuk log"""
        for record in self:
            name_parts = []
            if record.partner_id:
                name_parts.append(record.partner_id.name or 'Unknown')
            if record.template_key:
                name_parts.append(record.template_key)
            if record.sent_date:
                name_parts.append(record.sent_date.strftime('%Y-%m-%d %H:%M'))
            record.display_name = ' - '.join(name_parts) if name_parts else f'Log #{record.id}'
    
    @api.model
    def cron_cleanup_old_logs(self):
        """
        Cron job untuk membersihkan log lama
        
        Dipanggil setiap hari dengan priority 5
        Menghapus log yang lebih dari 90 hari
        """
        from datetime import timedelta
        
        cutoff_date = fields.Datetime.now() - timedelta(days=90)
        
        old_logs = self.search([
            ('sent_date', '<', cutoff_date)
        ])
        
        count = len(old_logs)
        old_logs.unlink()
        
        _logger.info(f"Cleaned up {count} old notification logs (older than 90 days)")
        
        return {'deleted': count}

