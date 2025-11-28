# -*- coding: utf-8 -*-

from odoo import models, fields, api, _
from odoo.exceptions import UserError, ValidationError
import logging
from datetime import datetime, timedelta

_logger = logging.getLogger(__name__)


class NotificationRule(models.Model):
    """
    Model untuk mendefinisikan aturan notifikasi yang akan dieksekusi saat pemicu terjadi
    """
    _name = 'sicantik.notification.rule'
    _description = 'Aturan Notifikasi'
    _order = 'sequence, name'
    
    # Informasi Dasar
    name = fields.Char(
        string='Nama Aturan',
        required=True,
        translate=True,
        help='Nama aturan yang mudah dipahami'
    )
    active = fields.Boolean(
        string='Aktif',
        default=True,
        help='Nonaktifkan untuk menonaktifkan aturan tanpa menghapus'
    )
    sequence = fields.Integer(
        string='Urutan',
        default=10,
        help='Urutan evaluasi (semakin kecil dievaluasi lebih dulu)'
    )
    description = fields.Text(
        string='Deskripsi',
        help='Penjelasan tentang aturan ini'
    )
    
    # Asosiasi Pemicu
    trigger_id = fields.Many2one(
        'sicantik.notification.trigger',
        string='Pemicu',
        required=True,
        ondelete='cascade',
        help='Pemicu yang terkait dengan aturan ini'
    )
    trigger_code = fields.Char(
        related='trigger_id.code',
        string='Kode Pemicu',
        store=True,
        readonly=True
    )
    
    # Kondisi
    condition_domain = fields.Char(
        string='Kondisi Domain',
        help='Domain tambahan untuk filter record (contoh: [("state", "=", "approved")])'
    )
    condition_python = fields.Text(
        string='Kondisi Python',
        help='Kode Python untuk kondisi kompleks (opsional)'
    )
    
    # Konfigurasi Template
    template_key = fields.Char(
        string='Template Key',
        required=True,
        help='Key template dari sicantik_whatsapp (contoh: permit_ready)'
    )
    template_master_id = fields.Many2one(
        'sicantik.whatsapp.template.master',
        string='Master Template',
        help='Link ke master template (optional)'
    )
    
    # Konfigurasi Penerima
    recipient_type = fields.Selection([
        ('partner', 'Partner (Pemohon)'),
        ('staff', 'Staff DPMPTSP'),
        ('official', 'Pejabat Berwenang'),
        ('custom', 'Custom (Domain)'),
        ('field', 'Dari Field Record'),
    ], string='Tipe Penerima', required=True, default='partner')
    
    recipient_domain = fields.Char(
        string='Domain Penerima',
        help='Domain untuk mencari penerima (untuk tipe custom)'
    )
    recipient_field = fields.Char(
        string='Field Penerima',
        help='Nama field yang berisi partner_id (untuk tipe field)'
    )
    
    # Konfigurasi Staff/Official
    staff_group_id = fields.Many2one(
        'res.groups',
        string='Grup Staff',
        help='Grup staff yang akan menerima notifikasi'
    )
    official_group_id = fields.Many2one(
        'res.groups',
        string='Grup Pejabat',
        help='Grup pejabat yang akan menerima notifikasi'
    )
    
    # Penjadwalan
    schedule_type = fields.Selection([
        ('immediate', 'Langsung'),
        ('scheduled', 'Terjadwal'),
        ('batch', 'Batch (Massal)'),
    ], string='Tipe Penjadwalan', required=True, default='immediate')
    
    scheduled_delay = fields.Integer(
        string='Delay (menit)',
        default=0,
        help='Delay sebelum pengiriman (untuk tipe scheduled)'
    )
    batch_size = fields.Integer(
        string='Ukuran Batch',
        default=100,
        help='Jumlah pengiriman per batch'
    )
    batch_interval = fields.Integer(
        string='Interval Batch (menit)',
        default=5,
        help='Interval antar batch'
    )
    
    # Pembatasan Laju
    rate_limit_enabled = fields.Boolean(
        string='Aktifkan Pembatasan Laju',
        default=True,
        help='Aktifkan pembatasan jumlah pengiriman per periode'
    )
    rate_limit_count = fields.Integer(
        string='Max Pengiriman',
        default=100,
        help='Maksimal pengiriman per periode'
    )
    rate_limit_period = fields.Selection([
        ('minute', 'Per Menit'),
        ('hour', 'Per Jam'),
        ('day', 'Per Hari'),
    ], string='Periode Pembatasan Laju', default='hour')
    
    # Konfigurasi Ulang Coba
    max_retries = fields.Integer(
        string='Max Ulang Coba',
        default=3,
        help='Maksimal jumlah ulang coba jika gagal'
    )
    retry_delay = fields.Integer(
        string='Delay Ulang Coba (menit)',
        default=5,
        help='Delay sebelum ulang coba'
    )
    
    # Daftar Hitam
    respect_blacklist = fields.Boolean(
        string='Hormati Daftar Hitam',
        default=True,
        help='Skip pengiriman ke nomor yang ada di daftar hitam'
    )
    
    # Persiapan Context Values
    context_preparation_code = fields.Text(
        string='Kode Persiapan Context',
        help='Kode Python untuk menyiapkan context_values (opsional)'
    )
    
    # Statistik
    execution_count = fields.Integer(
        string='Jumlah Eksekusi',
        default=0,
        readonly=True
    )
    success_count = fields.Integer(
        string='Berhasil',
        default=0,
        readonly=True
    )
    failed_count = fields.Integer(
        string='Gagal',
        default=0,
        readonly=True
    )
    last_execution_date = fields.Datetime(
        string='Eksekusi Terakhir',
        readonly=True
    )
    
    # Record Terkait
    queue_ids = fields.One2many(
        'sicantik.notification.queue',
        'rule_id',
        string='Antrian Pengiriman'
    )
    log_ids = fields.One2many(
        'sicantik.notification.log',
        'rule_id',
        string='Log Pengiriman'
    )
    
    def _evaluate_and_execute(self, records, **kwargs):
        """
        Evaluasi aturan dan eksekusi jika kondisi terpenuhi
        
        Args:
            records: Recordset yang memicu event
            **kwargs: Parameter tambahan
        
        Returns:
            dict: Hasil evaluasi dan eksekusi
        """
        self.ensure_one()
        
        if not self.active:
            return {'executed': False, 'reason': 'rule_inactive'}
        
        # Evaluasi kondisi
        if not self._evaluate_condition(records, **kwargs):
            return {'executed': False, 'reason': 'condition_not_met'}
        
        # Tentukan penerima
        recipients = self._determine_recipients(records, **kwargs)
        
        if not recipients:
            _logger.warning(f"Tidak ada penerima ditemukan untuk aturan {self.name}")
            return {'executed': False, 'reason': 'no_recipients'}
        
        # Buat entri antrian
        import json
        queue_entries = []
        for record in records:
            for recipient in recipients:
                context_values = self._prepare_context_values(record, recipient, **kwargs)
                
                scheduled_date = fields.Datetime.now()
                if self.schedule_type == 'scheduled':
                    scheduled_date = scheduled_date + timedelta(minutes=self.scheduled_delay)
                
                queue_entry = self.env['sicantik.notification.queue'].create({
                    'rule_id': self.id,
                    'partner_id': recipient.id,
                    'template_key': self.template_key,
                    'context_values': json.dumps(context_values),  # Convert to JSON string
                    'scheduled_date': scheduled_date,
                    'priority': 5,
                    'state': 'pending',
                    'max_retries': self.max_retries,
                })
                
                # Link ke record terkait jika ada
                if hasattr(record, '_name'):
                    if record._name == 'sicantik.permit':
                        queue_entry.permit_id = record.id
                    elif record._name == 'sicantik.document':
                        queue_entry.document_id = record.id
                
                queue_entries.append(queue_entry.id)
        
        # Update statistik
        self.write({
            'execution_count': self.execution_count + 1,
            'last_execution_date': fields.Datetime.now()
        })
        
        return {
            'executed': True,
            'queue_entries': queue_entries,
            'recipients_count': len(recipients),
            'records_count': len(records)
        }
    
    def _evaluate_condition(self, records, **kwargs):
        """
        Evaluasi kondisi aturan
        
        Args:
            records: Recordset yang akan dievaluasi
            **kwargs: Parameter tambahan
        
        Returns:
            bool: True jika kondisi terpenuhi
        """
        self.ensure_one()
        
        if not records:
            return False
        
        # Evaluasi domain jika ada
        if self.condition_domain:
            try:
                domain = eval(self.condition_domain)
                filtered_records = records.filtered_domain(domain)
                if not filtered_records:
                    return False
                records = filtered_records
            except Exception as e:
                _logger.error(f"Error evaluating domain for rule {self.name}: {e}")
                return False
        
        # Evaluasi kondisi Python jika ada
        if self.condition_python:
            try:
                safe_dict = {
                    'records': records,
                    'record': records[0] if len(records) == 1 else None,
                    'env': self.env,
                    'fields': fields,
                    'datetime': datetime,
                    'timedelta': timedelta,
                    'kwargs': kwargs,
                }
                result = eval(self.condition_python, safe_dict)
                if not result:
                    return False
            except Exception as e:
                _logger.error(f"Error evaluating Python condition for rule {self.name}: {e}")
                return False
        
        return True
    
    def _determine_recipients(self, records, **kwargs):
        """
        Tentukan penerima berdasarkan recipient_type
        
        Args:
            records: Recordset yang memicu event
            **kwargs: Parameter tambahan
        
        Returns:
            recordset: res.partner yang akan menerima notifikasi
        """
        self.ensure_one()
        
        recipients = self.env['res.partner']
        
        if self.recipient_type == 'partner':
            # Ambil partner dari record
            for record in records:
                if hasattr(record, 'partner_id') and record.partner_id:
                    recipients |= record.partner_id
                elif hasattr(record, 'permit_id') and record.permit_id and record.permit_id.partner_id:
                    recipients |= record.permit_id.partner_id
        
        elif self.recipient_type == 'staff':
            # Ambil user dari grup staff
            if self.staff_group_id:
                users = self.env['res.users'].search([
                    ('groups_id', 'in', [self.staff_group_id.id])
                ])
                recipients = users.mapped('partner_id')
        
        elif self.recipient_type == 'official':
            # Ambil user dari grup pejabat
            if self.official_group_id:
                users = self.env['res.users'].search([
                    ('groups_id', 'in', [self.official_group_id.id])
                ])
                recipients = users.mapped('partner_id')
        
        elif self.recipient_type == 'custom':
            # Gunakan domain custom
            if self.recipient_domain:
                try:
                    domain = eval(self.recipient_domain)
                    recipients = self.env['res.partner'].search(domain)
                except Exception as e:
                    _logger.error(f"Error evaluating recipient domain for rule {self.name}: {e}")
        
        elif self.recipient_type == 'field':
            # Ambil partner dari field record
            if self.recipient_field:
                for record in records:
                    partner = getattr(record, self.recipient_field, None)
                    if partner:
                        recipients |= partner
        
        # Filter partner yang memiliki nomor telepon
        recipients = recipients.filtered(lambda p: p.phone or p.mobile or getattr(p, 'whatsapp_number', False))
        
        return recipients
    
    def _prepare_context_values(self, record, recipient, **kwargs):
        """
        Siapkan context values untuk template
        
        Args:
            record: Record yang memicu event
            recipient: Partner penerima
            **kwargs: Parameter tambahan
        
        Returns:
            dict: Context values untuk template
        """
        self.ensure_one()
        
        context = {}
        
        # Jika ada custom code
        if self.context_preparation_code:
            try:
                safe_dict = {
                    'record': record,
                    'recipient': recipient,
                    'env': self.env,
                    'fields': fields,
                    'datetime': datetime,
                    'timedelta': timedelta,
                    'kwargs': kwargs,
                }
                exec(self.context_preparation_code, safe_dict)
                context = safe_dict.get('context', {})
            except Exception as e:
                _logger.error(f"Error executing context preparation code for rule {self.name}: {e}")
                context = {}
        else:
            # Default context preparation
            context = {
                'partner_name': recipient.name or 'Bapak/Ibu',
            }
            
            # Tambahkan data dari record
            if hasattr(record, 'permit_id') and record.permit_id:
                permit = record.permit_id
                context.update({
                    'permit_number': permit.permit_number or '',
                    'permit_type': permit.permit_type_name or '',
                    'status': permit.state or '',
                    'applicant_name': permit.applicant_name or '',
                })
            
            if hasattr(record, 'document_number'):
                context.update({
                    'document_number': record.document_number or '',
                    'document_name': record.name or '',
                })
        
        return context
    
    def _check_rate_limit(self):
        """
        Cek apakah masih dalam pembatasan laju
        
        Returns:
            bool: True jika masih dalam limit
        """
        self.ensure_one()
        
        if not self.rate_limit_enabled:
            return True
        
        # Hitung periode awal
        now = fields.Datetime.now()
        if self.rate_limit_period == 'minute':
            period_start = now - timedelta(minutes=1)
        elif self.rate_limit_period == 'hour':
            period_start = now - timedelta(hours=1)
        else:  # day
            period_start = now - timedelta(days=1)
        
        # Hitung pengiriman dalam periode terakhir
        count = self.env['sicantik.notification.log'].search_count([
            ('rule_id', '=', self.id),
            ('sent_date', '>=', period_start),
            ('state', '=', 'sent'),
        ])
        
        return count < self.rate_limit_count

