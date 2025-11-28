# -*- coding: utf-8 -*-

from odoo import models, fields, api, _
from odoo.exceptions import UserError, ValidationError
import logging
from datetime import datetime, timedelta
import json

_logger = logging.getLogger(__name__)


class NotificationQueue(models.Model):
    """
    Model untuk mengantri dan mengelola pengiriman notifikasi
    """
    _name = 'sicantik.notification.queue'
    _description = 'Antrian Notifikasi'
    _order = 'priority desc, scheduled_date asc, id asc'
    
    # Informasi Dasar
    name = fields.Char(
        string='Nama',
        compute='_compute_name',
        store=True,
        readonly=True
    )
    
    # Asosiasi
    rule_id = fields.Many2one(
        'sicantik.notification.rule',
        string='Aturan',
        required=True,
        ondelete='cascade',
        help='Aturan notifikasi terkait'
    )
    campaign_id = fields.Many2one(
        'sicantik.notification.campaign',
        string='Kampanye',
        ondelete='set null',
        help='Kampanye terkait (jika ada)'
    )
    
    # Penerima
    partner_id = fields.Many2one(
        'res.partner',
        string='Penerima',
        required=True,
        ondelete='cascade',
        help='Partner yang akan menerima notifikasi'
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
    
    # Record Terkait
    permit_id = fields.Many2one(
        'sicantik.permit',
        string='Izin',
        ondelete='set null',
        help='Izin terkait (jika ada)'
    )
    document_id = fields.Many2one(
        'sicantik.document',
        string='Dokumen',
        ondelete='set null',
        help='Dokumen terkait (jika ada)'
    )
    
    # Konfigurasi Template
    template_key = fields.Char(
        string='Template Key',
        required=True,
        help='Key template dari sicantik_whatsapp'
    )
    context_values = fields.Text(
        string='Context Values',
        help='Data untuk template (JSON format)'
    )
    
    # Penjadwalan
    scheduled_date = fields.Datetime(
        string='Jadwal Pengiriman',
        required=True,
        default=fields.Datetime.now,
        index=True,
        help='Waktu pengiriman yang dijadwalkan'
    )
    priority = fields.Integer(
        string='Prioritas',
        default=5,
        help='Prioritas pengiriman (semakin tinggi semakin diprioritaskan)'
    )
    
    # Status
    state = fields.Selection([
        ('pending', 'Pending'),
        ('processing', 'Sedang Diproses'),
        ('sent', 'Terkirim'),
        ('failed', 'Gagal'),
        ('cancelled', 'Dibatalkan'),
        ('skipped', 'Dilewati'),
    ], string='Status', default='pending', required=True, index=True)
    
    # Pengiriman
    sent_date = fields.Datetime(
        string='Tanggal Terkirim',
        readonly=True,
        help='Waktu pengiriman berhasil'
    )
    provider_id = fields.Many2one(
        'sicantik.whatsapp.provider',
        string='Provider',
        readonly=True,
        help='Provider yang digunakan untuk pengiriman'
    )
    provider_type = fields.Selection([
        ('meta', 'Meta WhatsApp'),
        ('fonnte', 'Fonnte'),
        ('watzap', 'Watzap'),
    ], string='Tipe Provider', readonly=True)
    external_message_id = fields.Char(
        string='ID Pesan Eksternal',
        readonly=True,
        help='ID pesan dari provider eksternal'
    )
    
    # Error Handling
    error_message = fields.Text(
        string='Pesan Error',
        readonly=True,
        help='Pesan error jika pengiriman gagal'
    )
    retry_count = fields.Integer(
        string='Jumlah Ulang Coba',
        default=0,
        readonly=True,
        help='Jumlah kali sudah diulang coba'
    )
    max_retries = fields.Integer(
        string='Max Ulang Coba',
        default=3,
        help='Maksimal jumlah ulang coba'
    )
    next_retry_date = fields.Datetime(
        string='Jadwal Ulang Coba',
        readonly=True,
        help='Waktu ulang coba berikutnya'
    )
    
    # Daftar Hitam
    is_blacklisted = fields.Boolean(
        string='Di Daftar Hitam',
        compute='_compute_is_blacklisted',
        store=True,
        help='True jika nomor penerima ada di daftar hitam'
    )
    
    # Statistik
    processing_time_ms = fields.Integer(
        string='Waktu Proses (ms)',
        readonly=True,
        help='Waktu yang dibutuhkan untuk memproses'
    )
    
    @api.depends('partner_id', 'template_key', 'scheduled_date')
    def _compute_name(self):
        """Generate nama untuk antrian"""
        for record in self:
            name_parts = []
            if record.partner_id:
                name_parts.append(record.partner_id.name or 'Unknown')
            if record.template_key:
                name_parts.append(record.template_key)
            if record.scheduled_date:
                name_parts.append(record.scheduled_date.strftime('%Y-%m-%d %H:%M'))
            record.name = ' - '.join(name_parts) if name_parts else f'Queue #{record.id}'
    
    @api.depends('partner_id')
    def _compute_is_blacklisted(self):
        """Cek apakah nomor penerima ada di daftar hitam"""
        for record in self:
            if not record.partner_id:
                record.is_blacklisted = False
                continue
            
            # Cek daftar hitam berdasarkan nomor telepon
            phone_numbers = []
            if record.partner_id.phone:
                phone_numbers.append(record.partner_id.phone)
            if record.partner_id.mobile:
                phone_numbers.append(record.partner_id.mobile)
            if hasattr(record.partner_id, 'whatsapp_number') and record.partner_id.whatsapp_number:
                phone_numbers.append(record.partner_id.whatsapp_number)
            
            if phone_numbers:
                blacklist = self.env['sicantik.notification.blacklist'].search([
                    ('phone_number', 'in', phone_numbers),
                    ('active', '=', True)
                ], limit=1)
                record.is_blacklisted = bool(blacklist)
            else:
                record.is_blacklisted = False
    
    def action_process(self):
        """
        Proses pengiriman notifikasi dari antrian
        
        Returns:
            dict: Hasil pemrosesan
        """
        self.ensure_one()
        
        if self.state != 'pending':
            return {'success': False, 'reason': 'not_pending'}
        
        if self.is_blacklisted:
            self.write({
                'state': 'skipped',
                'error_message': 'Nomor penerima ada di daftar hitam'
            })
            return {'success': False, 'reason': 'blacklisted'}
        
        # Cek jadwal
        if self.scheduled_date > fields.Datetime.now():
            return {'success': False, 'reason': 'not_scheduled_yet'}
        
        # Cek pembatasan laju
        if self.rule_id and not self.rule_id._check_rate_limit():
            # Tunda pengiriman
            delay = timedelta(minutes=5)
            self.write({
                'scheduled_date': fields.Datetime.now() + delay
            })
            return {'success': False, 'reason': 'rate_limit_exceeded'}
        
        # Update status ke processing
        self.write({'state': 'processing'})
        
        start_time = datetime.now()
        
        try:
            # Parse context values
            context_values = {}
            if self.context_values:
                try:
                    context_values = json.loads(self.context_values)
                except json.JSONDecodeError:
                    _logger.warning(f"Invalid JSON in context_values for queue {self.id}")
                    context_values = {}
            
            # Kirim via WhatsApp Dispatcher
            dispatcher = self.env['sicantik.whatsapp.dispatcher']
            result = dispatcher.send_template_message(
                template_key=self.template_key,
                partner_id=self.partner_id.id,
                context_values=context_values,
                permit_id=self.permit_id.id if self.permit_id else None,
            )
            
            # Hitung waktu proses
            processing_time = (datetime.now() - start_time).total_seconds() * 1000
            
            if result.get('success'):
                # Berhasil
                provider_id = result.get('provider_id')
                if isinstance(provider_id, int):
                    provider_record = self.env['sicantik.whatsapp.provider'].browse(provider_id)
                else:
                    provider_record = provider_id
                
                self.write({
                    'state': 'sent',
                    'sent_date': fields.Datetime.now(),
                    'provider_id': provider_record.id if provider_record else False,
                    'provider_type': result.get('provider_type'),
                    'external_message_id': result.get('message_id'),
                    'processing_time_ms': int(processing_time),
                    'retry_count': 0,
                })
                
                # Buat log entry
                self.env['sicantik.notification.log'].create({
                    'rule_id': self.rule_id.id if self.rule_id else False,
                    'campaign_id': self.campaign_id.id if self.campaign_id else False,
                    'queue_id': self.id,
                    'partner_id': self.partner_id.id,
                    'template_key': self.template_key,
                    'context_values': self.context_values,
                    'state': 'sent',
                    'sent_date': fields.Datetime.now(),
                    'provider_id': self.provider_id.id if self.provider_id else False,
                    'provider_type': self.provider_type,
                    'external_message_id': self.external_message_id,
                    'processing_time_ms': int(processing_time),
                })
                
                # Update statistik aturan
                if self.rule_id:
                    self.rule_id.write({
                        'success_count': self.rule_id.success_count + 1
                    })
                
                return {'success': True, 'message_id': result.get('message_id')}
            else:
                # Gagal
                error_msg = result.get('error', 'Unknown error')
                self._handle_failure(error_msg)
                return {'success': False, 'error': error_msg}
        
        except Exception as e:
            # Error exception
            error_msg = str(e)
            _logger.error(f"Error processing queue {self.id}: {error_msg}")
            self._handle_failure(error_msg)
            return {'success': False, 'error': error_msg}
    
    def _handle_failure(self, error_message):
        """
        Handle kegagalan pengiriman dengan mekanisme ulang coba
        
        Args:
            error_message (str): Pesan error
        """
        self.ensure_one()
        
        self.retry_count += 1
        
        if self.retry_count < self.max_retries:
            # Jadwalkan ulang coba
            delay = timedelta(minutes=self.rule_id.retry_delay if self.rule_id else 5)
            self.write({
                'state': 'pending',
                'error_message': f"Ulang coba {self.retry_count}/{self.max_retries}: {error_message}",
                'next_retry_date': fields.Datetime.now() + delay,
                'scheduled_date': fields.Datetime.now() + delay,
            })
        else:
            # Maksimal ulang coba tercapai
            self.write({
                'state': 'failed',
                'error_message': f"Gagal setelah {self.max_retries} ulang coba: {error_message}",
            })
            
            # Buat log entry untuk kegagalan
            self.env['sicantik.notification.log'].create({
                'rule_id': self.rule_id.id if self.rule_id else False,
                'campaign_id': self.campaign_id.id if self.campaign_id else False,
                'queue_id': self.id,
                'partner_id': self.partner_id.id,
                'template_key': self.template_key,
                'context_values': self.context_values,
                'state': 'failed',
                'error_message': error_message,
            })
            
            # Update statistik aturan
            if self.rule_id:
                self.rule_id.write({
                    'failed_count': self.rule_id.failed_count + 1
                })
    
    @api.model
    def cron_process_queue(self):
        """
        Cron job untuk memproses antrian notifikasi
        
        Dipanggil setiap 1 menit dengan priority 20
        """
        # Ambil entri antrian yang siap dikirim
        queue_entries = self.search([
            ('state', '=', 'pending'),
            ('scheduled_date', '<=', fields.Datetime.now()),
            ('is_blacklisted', '=', False),
        ], limit=100, order='priority desc, scheduled_date asc')
        
        processed_count = 0
        success_count = 0
        failed_count = 0
        
        for entry in queue_entries:
            try:
                result = entry.action_process()
                processed_count += 1
                if result.get('success'):
                    success_count += 1
                else:
                    failed_count += 1
            except Exception as e:
                _logger.error(f"Error processing queue entry {entry.id}: {e}")
                entry.write({
                    'state': 'failed',
                    'error_message': str(e)
                })
                failed_count += 1
        
        _logger.info(
            f"Processed {processed_count} queue entries: "
            f"{success_count} success, {failed_count} failed"
        )
        
        return {
            'processed': processed_count,
            'success': success_count,
            'failed': failed_count
        }
    
    def action_cancel(self):
        """Batalkan pengiriman"""
        self.write({'state': 'cancelled'})
    
    def action_retry(self):
        """Ulang coba pengiriman"""
        self.write({
            'state': 'pending',
            'scheduled_date': fields.Datetime.now(),
            'retry_count': 0,
            'error_message': False,
        })

