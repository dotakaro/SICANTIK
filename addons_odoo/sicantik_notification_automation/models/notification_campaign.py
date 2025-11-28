# -*- coding: utf-8 -*-

from odoo import models, fields, api, _
from odoo.exceptions import UserError, ValidationError
import logging

_logger = logging.getLogger(__name__)


class NotificationCampaign(models.Model):
    """
    Model untuk mengelola kampanye notifikasi massal
    """
    _name = 'sicantik.notification.campaign'
    _description = 'Kampanye Notifikasi'
    _order = 'create_date desc'
    
    # Informasi Dasar
    name = fields.Char(
        string='Nama Kampanye',
        required=True,
        help='Nama kampanye yang mudah dipahami'
    )
    description = fields.Text(
        string='Deskripsi',
        help='Penjelasan tentang kampanye ini'
    )
    
    # Asosiasi
    rule_id = fields.Many2one(
        'sicantik.notification.rule',
        string='Aturan',
        required=True,
        ondelete='restrict',
        help='Aturan notifikasi yang akan digunakan'
    )
    
    # Target
    target_model = fields.Char(
        string='Model Target',
        required=True,
        help='Nama model Odoo target (contoh: sicantik.permit)'
    )
    target_domain = fields.Char(
        string='Domain Target',
        required=True,
        help='Domain untuk filter target (contoh: [("state", "=", "approved")])'
    )
    
    # Override Template
    template_key = fields.Char(
        string='Template Key Override',
        help='Override template dari aturan (kosongkan untuk menggunakan template dari aturan)'
    )
    
    # Penjadwalan
    scheduled_date = fields.Datetime(
        string='Jadwal Pengiriman',
        help='Jadwal mulai kampanye (kosongkan untuk langsung)'
    )
    
    # Status
    state = fields.Selection([
        ('draft', 'Draft'),
        ('scheduled', 'Terjadwal'),
        ('running', 'Berjalan'),
        ('paused', 'Dijeda'),
        ('completed', 'Selesai'),
        ('cancelled', 'Dibatalkan'),
    ], string='Status', default='draft', required=True, index=True)
    
    # Statistik
    total_targets = fields.Integer(
        string='Total Target',
        readonly=True,
        help='Total target yang ditemukan'
    )
    total_sent = fields.Integer(
        string='Total Terkirim',
        compute='_compute_statistics',
        store=True,
        help='Total yang berhasil dikirim'
    )
    total_failed = fields.Integer(
        string='Total Gagal',
        compute='_compute_statistics',
        store=True,
        help='Total yang gagal dikirim'
    )
    total_pending = fields.Integer(
        string='Total Pending',
        compute='_compute_statistics',
        store=True,
        help='Total yang masih pending'
    )
    progress_percentage = fields.Float(
        string='Progres (%)',
        compute='_compute_statistics',
        store=True,
        help='Persentase progres kampanye'
    )
    
    # Record Terkait
    queue_ids = fields.One2many(
        'sicantik.notification.queue',
        'campaign_id',
        string='Antrian Pengiriman'
    )
    log_ids = fields.One2many(
        'sicantik.notification.log',
        'campaign_id',
        string='Log Pengiriman'
    )
    
    @api.depends('queue_ids.state')
    def _compute_statistics(self):
        """Hitung statistik kampanye"""
        for record in self:
            queues = record.queue_ids
            record.total_sent = len(queues.filtered(lambda q: q.state == 'sent'))
            record.total_failed = len(queues.filtered(lambda q: q.state == 'failed'))
            record.total_pending = len(queues.filtered(lambda q: q.state == 'pending'))
            
            if record.total_targets > 0:
                completed = record.total_sent + record.total_failed
                record.progress_percentage = (completed / record.total_targets) * 100
            else:
                record.progress_percentage = 0.0
    
    def action_start(self):
        """Mulai kampanye"""
        self.ensure_one()
        
        if self.state not in ('draft', 'scheduled', 'paused'):
            raise UserError(_('Kampanye tidak dapat dimulai dari status ini'))
        
        # Evaluasi target domain
        try:
            domain = eval(self.target_domain)
            target_model = self.env[self.target_model]
            targets = target_model.search(domain)
        except Exception as e:
            raise UserError(_(f'Error evaluating target domain: {e}'))
        
        if not targets:
            raise UserError(_('Tidak ada target ditemukan'))
        
        # Tentukan template
        template_key = self.template_key or self.rule_id.template_key
        
        # Buat entri antrian untuk setiap target
        queue_entries = []
        for target in targets:
            # Tentukan penerima berdasarkan aturan
            recipients = self.rule_id._determine_recipients(target)
            
            for recipient in recipients:
                context_values = self.rule_id._prepare_context_values(target, recipient)
                
                queue_entry = self.env['sicantik.notification.queue'].create({
                    'rule_id': self.rule_id.id,
                    'campaign_id': self.id,
                    'partner_id': recipient.id,
                    'template_key': template_key,
                    'context_values': str(context_values),  # Convert to string for storage
                    'scheduled_date': self.scheduled_date or fields.Datetime.now(),
                    'priority': 5,
                    'state': 'pending',
                    'max_retries': self.rule_id.max_retries,
                })
                
                # Link ke record terkait jika ada
                if target._name == 'sicantik.permit':
                    queue_entry.permit_id = target.id
                elif target._name == 'sicantik.document':
                    queue_entry.document_id = target.id
                
                queue_entries.append(queue_entry.id)
        
        # Update kampanye
        self.write({
            'state': 'running',
            'total_targets': len(queue_entries),
        })
        
        return {
            'type': 'ir.actions.client',
            'tag': 'display_notification',
            'params': {
                'title': _('Kampanye Dimulai'),
                'message': _('Kampanye "%s" telah dimulai dengan %d entri antrian') % (self.name, len(queue_entries)),
                'type': 'success',
            }
        }
    
    def action_pause(self):
        """Jeda kampanye"""
        self.write({'state': 'paused'})
    
    def action_resume(self):
        """Lanjutkan kampanye"""
        if self.state != 'paused':
            raise UserError(_('Hanya kampanye yang dijeda yang dapat dilanjutkan'))
        self.write({'state': 'running'})
    
    def action_cancel(self):
        """Batalkan kampanye"""
        if self.state == 'completed':
            raise UserError(_('Kampanye yang sudah selesai tidak dapat dibatalkan'))
        
        # Batalkan semua antrian yang masih pending
        self.queue_ids.filtered(lambda q: q.state == 'pending').write({'state': 'cancelled'})
        
        self.write({'state': 'cancelled'})
    
    @api.model
    def cron_process_campaign_batch(self):
        """
        Cron job untuk memproses batch kampanye
        
        Dipanggil setiap 5 menit dengan priority 15
        """
        # Ambil kampanye yang sedang berjalan
        campaigns = self.search([
            ('state', '=', 'running')
        ])
        
        for campaign in campaigns:
            # Cek apakah ada antrian yang masih pending
            pending_queues = campaign.queue_ids.filtered(lambda q: q.state == 'pending')
            
            if not pending_queues:
                # Semua selesai
                campaign.write({'state': 'completed'})
                continue
            
            # Proses batch sesuai dengan konfigurasi aturan
            batch_size = campaign.rule_id.batch_size or 100
            queues_to_process = pending_queues[:batch_size]
            
            for queue_entry in queues_to_process:
                try:
                    queue_entry.action_process()
                except Exception as e:
                    _logger.error(f"Error processing campaign queue {queue_entry.id}: {e}")
        
        return True

