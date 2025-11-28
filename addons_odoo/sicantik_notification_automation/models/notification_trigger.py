# -*- coding: utf-8 -*-

from odoo import models, fields, api, _
from odoo.exceptions import UserError, ValidationError
import logging

_logger = logging.getLogger(__name__)


class NotificationTrigger(models.Model):
    """
    Model untuk mendefinisikan event/pemicu yang dapat memicu notifikasi
    """
    _name = 'sicantik.notification.trigger'
    _description = 'Pemicu Notifikasi'
    _order = 'sequence, name'
    
    # Informasi Dasar
    name = fields.Char(
        string='Nama Pemicu',
        required=True,
        translate=True,
        help='Nama pemicu yang mudah dipahami'
    )
    code = fields.Char(
        string='Kode Pemicu',
        required=True,
        copy=False,
        help='Identifier unik untuk pemicu (digunakan dalam kode)'
    )
    active = fields.Boolean(
        string='Aktif',
        default=True,
        help='Nonaktifkan untuk menonaktifkan pemicu tanpa menghapus'
    )
    sequence = fields.Integer(
        string='Urutan',
        default=10,
        help='Urutan tampilan (semakin kecil semakin atas)'
    )
    description = fields.Text(
        string='Deskripsi',
        help='Penjelasan tentang pemicu ini'
    )
    
    # Konfigurasi Model
    model_id = fields.Many2one(
        'ir.model',
        string='Model',
        required=True,
        ondelete='restrict',
        help='Model Odoo yang akan di-monitor'
    )
    model_name = fields.Char(
        related='model_id.model',
        string='Nama Model',
        store=True,
        readonly=True
    )
    
    # Tipe Pemicu
    trigger_type = fields.Selection([
        ('on_create', 'Saat Record Dibuat'),
        ('on_write', 'Saat Record Diupdate'),
        ('on_state_change', 'Saat Status Berubah'),
        ('on_field_change', 'Saat Field Tertentu Berubah'),
        ('cron', 'Jadwal Cron'),
        ('manual', 'Manual'),
        ('api', 'API Call'),
    ], string='Tipe Pemicu', required=True, default='on_state_change')
    
    # Konfigurasi Field (untuk on_field_change)
    field_to_watch = fields.Many2one(
        'ir.model.fields',
        string='Field yang Diperhatikan',
        domain="[('model_id', '=', model_id)]",
        help='Field yang akan di-monitor untuk perubahan'
    )
    field_to_watch_name = fields.Char(
        related='field_to_watch.name',
        string='Nama Field',
        store=True,
        readonly=True
    )
    
    # Konfigurasi Status (untuk on_state_change)
    state_field = fields.Char(
        string='Field Status',
        default='state',
        help='Nama field status (default: state)'
    )
    state_from = fields.Char(
        string='Status Dari',
        help='Status awal (kosong = semua status)'
    )
    state_to = fields.Char(
        string='Status Ke',
        help='Status target yang memicu notifikasi'
    )
    
    # Konfigurasi Cron (untuk cron trigger)
    cron_expression = fields.Char(
        string='Cron Expression',
        help='Format: interval_number,interval_type (contoh: 1,days)'
    )
    cron_interval_number = fields.Integer(
        string='Interval Number',
        default=1,
        help='Nomor interval (contoh: 1 untuk setiap 1 hari)'
    )
    cron_interval_type = fields.Selection([
        ('minutes', 'Menit'),
        ('hours', 'Jam'),
        ('days', 'Hari'),
        ('weeks', 'Minggu'),
        ('months', 'Bulan'),
    ], string='Tipe Interval', default='days')
    
    # Kondisi Domain
    condition_domain = fields.Char(
        string='Kondisi Domain',
        help='Domain Odoo untuk filter record (contoh: [("active", "=", True)])'
    )
    
    # Aturan Terkait
    rule_ids = fields.One2many(
        'sicantik.notification.rule',
        'trigger_id',
        string='Aturan Notifikasi'
    )
    rule_count = fields.Integer(
        string='Jumlah Aturan',
        compute='_compute_rule_count',
        store=True
    )
    
    # Statistik
    execution_count = fields.Integer(
        string='Jumlah Eksekusi',
        default=0,
        readonly=True
    )
    last_execution_date = fields.Datetime(
        string='Eksekusi Terakhir',
        readonly=True
    )
    
    _sql_constraints = [
        ('code_unique', 'UNIQUE(code)', 'Kode pemicu harus unik!')
    ]
    
    @api.depends('rule_ids')
    def _compute_rule_count(self):
        """Hitung jumlah aturan terkait"""
        for record in self:
            record.rule_count = len(record.rule_ids)
    
    @api.constrains('code')
    def _check_code(self):
        """Validasi kode pemicu"""
        for record in self:
            if not record.code:
                raise ValidationError(_('Kode pemicu harus diisi'))
            if not record.code.replace('_', '').isalnum():
                raise ValidationError(_('Kode pemicu hanya boleh mengandung huruf, angka, dan underscore'))
    
    def name_get(self):
        """Format nama untuk ditampilkan"""
        result = []
        for record in self:
            name = record.name
            if record.code:
                name = f"{name} ({record.code})"
            result.append((record.id, name))
        return result
    
    def action_view_rules(self):
        """Buka view aturan terkait"""
        self.ensure_one()
        return {
            'name': _('Aturan Notifikasi'),
            'type': 'ir.actions.act_window',
            'res_model': 'sicantik.notification.rule',
            'view_mode': 'tree,form',
            'domain': [('trigger_id', '=', self.id)],
            'context': {'default_trigger_id': self.id},
        }
    
    def _process_trigger(self, code, records, **kwargs):
        """
        Proses pemicu dan evaluasi aturan terkait
        
        Args:
            code (str): Kode pemicu
            records: Recordset yang memicu event
            **kwargs: Parameter tambahan (old_state, new_state, dll)
        
        Returns:
            dict: Hasil pemrosesan
        """
        trigger = self.search([('code', '=', code), ('active', '=', True)], limit=1)
        
        if not trigger:
            _logger.debug(f"Pemicu dengan kode '{code}' tidak ditemukan atau tidak aktif")
            return {'processed': False, 'reason': 'trigger_not_found'}
        
        # Evaluasi kondisi pemicu
        if not trigger._evaluate_condition(records, **kwargs):
            _logger.debug(f"Kondisi pemicu '{code}' tidak terpenuhi")
            return {'processed': False, 'reason': 'condition_not_met'}
        
        # Proses aturan terkait
        processed_rules = []
        for rule in trigger.rule_ids.filtered(lambda r: r.active):
            try:
                result = rule._evaluate_and_execute(records, **kwargs)
                if result.get('executed'):
                    processed_rules.append(rule.id)
            except Exception as e:
                _logger.error(f"Error processing rule {rule.id} for trigger {code}: {e}")
        
        # Update statistik
        trigger.write({
            'execution_count': trigger.execution_count + 1,
            'last_execution_date': fields.Datetime.now()
        })
        
        return {
            'processed': True,
            'trigger_id': trigger.id,
            'rules_processed': processed_rules,
            'count': len(processed_rules)
        }
    
    def _evaluate_condition(self, records, **kwargs):
        """
        Evaluasi kondisi pemicu
        
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
                _logger.error(f"Error evaluating domain for trigger {self.code}: {e}")
                return False
        
        # Evaluasi kondisi khusus berdasarkan trigger_type
        if self.trigger_type == 'on_state_change':
            if 'old_state' in kwargs and 'new_state' in kwargs:
                if self.state_from and kwargs['old_state'] != self.state_from:
                    return False
                if self.state_to and kwargs['new_state'] != self.state_to:
                    return False
        
        return True

