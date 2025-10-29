# 📱 WhatsApp Integration Guide - SICANTIK Companion

**Tanggal:** 29 Oktober 2025  
**Module:** sicantik_whatsapp  
**Timeline:** Week 6

---

## 🎯 OVERVIEW

WhatsApp notification system untuk SICANTIK Companion App yang mengirimkan notifikasi otomatis ke:
- **Pemohon izin** - Status perizinan & download link
- **Staff DPMPTSP** - Dokumen baru & reminder
- **Pejabat** - Approval request

---

## 📋 NOTIFICATION SCENARIOS

### 1. **Izin Selesai Diproses** 🎉
**Trigger:** Status izin = 'done' & PDF tersedia  
**Recipient:** Pemohon  
**Template Code:** `permit_ready`

```
Yth. Bapak/Ibu {nama_pemohon},

Perizinan Anda telah selesai diproses:
📋 Jenis: {jenis_izin}
📄 No. Surat: {no_surat}
📅 Tanggal: {tanggal_terbit}

Dokumen dapat diambil di kantor atau download:
🔗 {link_download}

Verifikasi dokumen: Scan QR code pada dokumen

Terima kasih,
DPMPTSP Kabupaten Karo
```

**Variables:**
- `{nama_pemohon}` - Nama lengkap pemohon
- `{jenis_izin}` - Jenis perizinan
- `{no_surat}` - Nomor surat keputusan
- `{tanggal_terbit}` - Tanggal terbit izin
- `{link_download}` - URL download PDF

---

### 2. **Dokumen Baru untuk Ditandatangani** 📄
**Trigger:** PDF imported & status = 'pending_signature'  
**Recipient:** Staff DPMPTSP  
**Template Code:** `document_pending`

```
🔔 Notifikasi Dokumen Baru

Ada {jumlah} dokumen menunggu tanda tangan digital:

1. {jenis_izin} - {nama_pemohon}
   No: {pendaftaran_id}

Silakan proses melalui dashboard:
🔗 {link_dashboard}

SICANTIK Companion
```

**Variables:**
- `{jumlah}` - Jumlah dokumen pending
- `{jenis_izin}` - Jenis perizinan
- `{nama_pemohon}` - Nama pemohon
- `{pendaftaran_id}` - ID pendaftaran
- `{link_dashboard}` - URL dashboard

---

### 3. **Approval Required** 🔐
**Trigger:** Dokumen perlu approval pejabat  
**Recipient:** Kepala Dinas / Pejabat berwenang  
**Template Code:** `approval_required`

```
🔐 Approval Required

Yth. {nama_pejabat},

Dokumen berikut memerlukan persetujuan Anda:
📋 {jenis_izin}
👤 Pemohon: {nama_pemohon}
📄 No: {no_surat}

Approve via:
🔗 {link_approval}

SICANTIK Companion
```

**Variables:**
- `{nama_pejabat}` - Nama pejabat
- `{jenis_izin}` - Jenis perizinan
- `{nama_pemohon}` - Nama pemohon
- `{no_surat}` - Nomor surat
- `{link_approval}` - URL approval page

---

### 4. **Status Update** 📢
**Trigger:** Status dokumen berubah  
**Recipient:** Pemohon & Staff terkait  
**Template Code:** `status_update`

```
📢 Update Status Perizinan

No: {pendaftaran_id}
Status: {status_lama} → {status_baru}
Waktu: {timestamp}

Detail: {link_detail}

DPMPTSP Kab. Karo
```

**Variables:**
- `{pendaftaran_id}` - ID pendaftaran
- `{status_lama}` - Status sebelumnya
- `{status_baru}` - Status baru
- `{timestamp}` - Waktu update
- `{link_detail}` - URL detail

---

### 5. **Reminder Dokumen Pending** ⏰
**Trigger:** Cron job (daily) - Dokumen pending > 24 jam  
**Recipient:** Staff yang bertanggung jawab  
**Template Code:** `reminder`

```
⏰ Reminder: Dokumen Pending

{jumlah} dokumen belum diproses:

- {jenis_izin} ({nama_pemohon})
  Pending sejak: {waktu_pending}

Segera proses: {link_dashboard}

SICANTIK Companion
```

**Variables:**
- `{jumlah}` - Jumlah dokumen pending
- `{jenis_izin}` - Jenis perizinan
- `{nama_pemohon}` - Nama pemohon
- `{waktu_pending}` - Durasi pending
- `{link_dashboard}` - URL dashboard

---

### 6. **Notifikasi Izin Mendekati Masa Berlaku** ⚠️
**Trigger:** Cron job (daily) - Izin akan habis dalam 90/60/30 hari  
**Recipient:** Pemohon izin  
**Template Code:** `permit_expiry_warning`

```
⚠️ Pengingat Masa Berlaku Izin

Yth. Bapak/Ibu {nama_pemohon},

Izin Anda akan segera berakhir:
📋 Jenis: {jenis_izin}
📄 No. Surat: {no_surat}
📅 Berlaku s/d: {tanggal_berakhir}
⏰ Sisa waktu: {sisa_hari} hari

Segera ajukan perpanjangan untuk menghindari:
❌ Izin tidak berlaku
❌ Sanksi administrasi
❌ Proses ulang dari awal

Ajukan perpanjangan:
🔗 {link_perpanjangan}

Hubungi kami:
📞 {kontak_dpmptsp}

DPMPTSP Kabupaten Karo
```

**Variables:**
- `{nama_pemohon}` - Nama lengkap pemohon
- `{jenis_izin}` - Jenis perizinan
- `{no_surat}` - Nomor surat keputusan
- `{tanggal_berakhir}` - Tanggal berakhir izin
- `{sisa_hari}` - Jumlah hari tersisa
- `{link_perpanjangan}` - URL form perpanjangan
- `{kontak_dpmptsp}` - Nomor telepon DPMPTSP

**Notification Schedule:**
- **90 hari sebelum:** Notifikasi pertama (early warning)
- **60 hari sebelum:** Notifikasi kedua (reminder)
- **30 hari sebelum:** Notifikasi ketiga (urgent)
- **7 hari sebelum:** Notifikasi final (critical)

---

### 7. **Notifikasi Perpanjangan Izin Disetujui** ✅
**Trigger:** Perpanjangan izin selesai diproses  
**Recipient:** Pemohon izin  
**Template Code:** `permit_renewal_approved`

```
✅ Perpanjangan Izin Disetujui

Yth. Bapak/Ibu {nama_pemohon},

Perpanjangan izin Anda telah disetujui:
📋 Jenis: {jenis_izin}
📄 No. Surat Baru: {no_surat_baru}
📅 Berlaku: {tanggal_mulai} s/d {tanggal_berakhir}
🔄 Masa berlaku: {masa_berlaku} tahun

Dokumen perpanjangan dapat diambil di kantor atau download:
🔗 {link_download}

Verifikasi dokumen: Scan QR code pada dokumen

Terima kasih atas kepatuhan Anda.

DPMPTSP Kabupaten Karo
```

**Variables:**
- `{nama_pemohon}` - Nama lengkap pemohon
- `{jenis_izin}` - Jenis perizinan
- `{no_surat_baru}` - Nomor surat perpanjangan
- `{tanggal_mulai}` - Tanggal mulai berlaku
- `{tanggal_berakhir}` - Tanggal berakhir
- `{masa_berlaku}` - Durasi masa berlaku (tahun)
- `{link_download}` - URL download PDF perpanjangan

---

## 🔧 TECHNICAL IMPLEMENTATION

### Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Odoo Event System                         │
│  - create() override                                        │
│  - write() override                                         │
│  - Custom actions                                           │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│              WhatsApp Message Queue                          │
│  Model: whatsapp.message                                    │
│  Status: draft → queued → sent → delivered/failed           │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│              Cron Job: Process Queue                         │
│  Frequency: Every 1 minute                                  │
│  Batch size: 50 messages                                    │
│  Rate limit: 60 msg/min                                     │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│            WhatsApp API Connector                            │
│  - Template rendering                                       │
│  - API authentication                                       │
│  - Send message                                             │
│  - Handle response                                          │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│              WhatsApp Business API                          │
│  Provider: Meta / Twilio / MessageBird / Vonage             │
└─────────────────────────────────────────────────────────────┘
```

---

## 📦 MODULE STRUCTURE

```
addons_odoo/sicantik_whatsapp/
├── __init__.py
├── __manifest__.py
├── models/
│   ├── __init__.py
│   ├── whatsapp_config.py       # API configuration
│   ├── whatsapp_template.py     # Message templates
│   ├── whatsapp_message.py      # Message queue & log
│   ├── whatsapp_provider.py     # Provider abstraction
│   └── res_partner.py           # Extend partner with WA number
├── views/
│   ├── whatsapp_config_views.xml
│   ├── whatsapp_template_views.xml
│   ├── whatsapp_message_views.xml
│   └── whatsapp_menus.xml
├── data/
│   ├── whatsapp_templates.xml   # Default templates
│   └── cron_data.xml            # Cron jobs
├── security/
│   └── ir.model.access.csv
└── README.md
```

---

## 💻 CODE EXAMPLES

### 1. WhatsApp Config Model

```python
# models/whatsapp_config.py
from odoo import models, fields, api
from odoo.exceptions import ValidationError
import requests

class WhatsAppConfig(models.Model):
    _name = 'whatsapp.config'
    _description = 'WhatsApp API Configuration'
    
    name = fields.Char('Configuration Name', required=True)
    active = fields.Boolean(default=True)
    
    # Provider selection
    provider = fields.Selection([
        ('meta', 'Meta Cloud API'),
        ('twilio', 'Twilio'),
        ('messagebird', 'MessageBird'),
        ('vonage', 'Vonage')
    ], string='Provider', required=True, default='meta')
    
    # API Credentials
    api_key = fields.Char('API Key', required=True)
    api_secret = fields.Char('API Secret')
    phone_number_id = fields.Char('Phone Number ID', required=True)
    business_account_id = fields.Char('Business Account ID')
    
    # Webhook
    webhook_url = fields.Char('Webhook URL')
    webhook_verify_token = fields.Char('Webhook Verify Token')
    
    # Rate Limiting
    max_messages_per_minute = fields.Integer(
        'Max Messages/Minute', 
        default=60,
        help='Maximum messages per minute to avoid rate limiting'
    )
    max_messages_per_day = fields.Integer(
        'Max Messages/Day', 
        default=1000
    )
    
    # Retry Settings
    max_retry_attempts = fields.Integer('Max Retry Attempts', default=3)
    retry_delay = fields.Integer('Retry Delay (seconds)', default=300)
    
    # Statistics
    total_sent = fields.Integer('Total Sent', readonly=True)
    total_delivered = fields.Integer('Total Delivered', readonly=True)
    total_failed = fields.Integer('Total Failed', readonly=True)
    
    @api.constrains('api_key', 'phone_number_id')
    def _check_credentials(self):
        for record in self:
            if not record.api_key or not record.phone_number_id:
                raise ValidationError('API Key and Phone Number ID are required')
    
    def action_test_connection(self):
        """Test WhatsApp API connection"""
        self.ensure_one()
        try:
            # Test based on provider
            if self.provider == 'meta':
                self._test_meta_connection()
            elif self.provider == 'twilio':
                self._test_twilio_connection()
            # Add other providers...
            
            return {
                'type': 'ir.actions.client',
                'tag': 'display_notification',
                'params': {
                    'title': 'Success',
                    'message': 'WhatsApp API connection successful!',
                    'type': 'success',
                    'sticky': False,
                }
            }
        except Exception as e:
            raise ValidationError(f'Connection failed: {str(e)}')
    
    def _test_meta_connection(self):
        """Test Meta Cloud API connection"""
        url = f'https://graph.facebook.com/v18.0/{self.phone_number_id}'
        headers = {'Authorization': f'Bearer {self.api_key}'}
        response = requests.get(url, headers=headers)
        response.raise_for_status()
```

---

### 2. WhatsApp Template Model

```python
# models/whatsapp_template.py
from odoo import models, fields, api
from odoo.exceptions import ValidationError
import re

class WhatsAppTemplate(models.Model):
    _name = 'whatsapp.template'
    _description = 'WhatsApp Message Template'
    _order = 'sequence, name'
    
    name = fields.Char('Template Name', required=True)
    code = fields.Char('Template Code', required=True, index=True)
    sequence = fields.Integer('Sequence', default=10)
    active = fields.Boolean(default=True)
    
    # Template Configuration
    trigger = fields.Selection([
        ('permit_ready', 'Izin Selesai'),
        ('document_pending', 'Dokumen Pending'),
        ('approval_required', 'Perlu Approval'),
        ('status_update', 'Update Status'),
        ('reminder', 'Reminder'),
        ('permit_expiry_warning', 'Peringatan Masa Berlaku'),
        ('permit_renewal_approved', 'Perpanjangan Disetujui')
    ], string='Trigger Event', required=True)
    
    recipient_type = fields.Selection([
        ('applicant', 'Pemohon'),
        ('staff', 'Staff'),
        ('official', 'Pejabat')
    ], string='Recipient Type', required=True)
    
    # Message Content
    message_template = fields.Text(
        'Message Template', 
        required=True,
        help='Use {variable_name} for dynamic content'
    )
    
    # Variables
    variables = fields.Text(
        'Available Variables',
        help='Comma-separated list of variables, e.g., nama_pemohon, jenis_izin'
    )
    
    # Statistics
    usage_count = fields.Integer('Usage Count', readonly=True)
    last_used = fields.Datetime('Last Used', readonly=True)
    
    @api.constrains('code')
    def _check_unique_code(self):
        for record in self:
            if self.search_count([('code', '=', record.code), ('id', '!=', record.id)]) > 0:
                raise ValidationError(f'Template code "{record.code}" already exists')
    
    def render_template(self, values):
        """Render template with provided values"""
        self.ensure_one()
        message = self.message_template
        
        # Extract variables from template
        template_vars = re.findall(r'\{(\w+)\}', message)
        
        # Replace variables
        for var in template_vars:
            if var in values:
                message = message.replace(f'{{{var}}}', str(values[var]))
            else:
                raise ValidationError(f'Missing variable: {var}')
        
        return message
    
    def action_preview(self):
        """Preview template with sample data"""
        self.ensure_one()
        return {
            'type': 'ir.actions.act_window',
            'name': 'Preview Template',
            'res_model': 'whatsapp.template.preview.wizard',
            'view_mode': 'form',
            'target': 'new',
            'context': {'default_template_id': self.id}
        }
```

---

### 3. WhatsApp Message Model

```python
# models/whatsapp_message.py
from odoo import models, fields, api
from datetime import datetime, timedelta
import logging

_logger = logging.getLogger(__name__)

class WhatsAppMessage(models.Model):
    _name = 'whatsapp.message'
    _description = 'WhatsApp Message Queue'
    _order = 'create_date desc'
    
    # Message Info
    name = fields.Char('Subject', compute='_compute_name', store=True)
    template_id = fields.Many2one('whatsapp.template', 'Template', required=True)
    
    # Recipient
    partner_id = fields.Many2one('res.partner', 'Recipient')
    phone_number = fields.Char('Phone Number', required=True)
    
    # Message Content
    message = fields.Text('Message Content', required=True)
    
    # Status
    state = fields.Selection([
        ('draft', 'Draft'),
        ('queued', 'Queued'),
        ('sending', 'Sending'),
        ('sent', 'Sent'),
        ('delivered', 'Delivered'),
        ('read', 'Read'),
        ('failed', 'Failed')
    ], default='draft', required=True, tracking=True)
    
    # Delivery Info
    sent_date = fields.Datetime('Sent Date', readonly=True)
    delivered_date = fields.Datetime('Delivered Date', readonly=True)
    read_date = fields.Datetime('Read Date', readonly=True)
    
    # Error Handling
    retry_count = fields.Integer('Retry Count', default=0)
    error_message = fields.Text('Error Message')
    
    # External Reference
    external_id = fields.Char('External Message ID')
    
    # Related Document
    res_model = fields.Char('Related Model')
    res_id = fields.Integer('Related ID')
    
    @api.depends('template_id', 'partner_id')
    def _compute_name(self):
        for record in self:
            if record.template_id and record.partner_id:
                record.name = f'{record.template_id.name} - {record.partner_id.name}'
            else:
                record.name = 'WhatsApp Message'
    
    def action_send(self):
        """Send WhatsApp message"""
        for record in self:
            try:
                # Get active config
                config = self.env['whatsapp.config'].search([('active', '=', True)], limit=1)
                if not config:
                    raise Exception('No active WhatsApp configuration found')
                
                # Get provider
                provider = self.env['whatsapp.provider'].get_provider(config.provider)
                
                # Send message
                result = provider.send_message(
                    phone_number=record.phone_number,
                    message=record.message,
                    config=config
                )
                
                # Update record
                record.write({
                    'state': 'sent',
                    'sent_date': fields.Datetime.now(),
                    'external_id': result.get('message_id')
                })
                
                # Update template stats
                record.template_id.write({
                    'usage_count': record.template_id.usage_count + 1,
                    'last_used': fields.Datetime.now()
                })
                
                _logger.info(f'WhatsApp message sent: {record.id}')
                
            except Exception as e:
                _logger.error(f'Failed to send WhatsApp message {record.id}: {str(e)}')
                record.write({
                    'state': 'failed',
                    'error_message': str(e),
                    'retry_count': record.retry_count + 1
                })
    
    @api.model
    def cron_process_queue(self):
        """Cron job to process message queue"""
        # Get config
        config = self.env['whatsapp.config'].search([('active', '=', True)], limit=1)
        if not config:
            _logger.warning('No active WhatsApp configuration found')
            return
        
        # Get queued messages
        messages = self.search([
            ('state', '=', 'queued')
        ], limit=config.max_messages_per_minute)
        
        _logger.info(f'Processing {len(messages)} WhatsApp messages')
        
        for message in messages:
            message.action_send()
    
    @api.model
    def cron_retry_failed(self):
        """Cron job to retry failed messages"""
        config = self.env['whatsapp.config'].search([('active', '=', True)], limit=1)
        if not config:
            return
        
        # Get failed messages that haven't exceeded retry limit
        retry_time = datetime.now() - timedelta(seconds=config.retry_delay)
        messages = self.search([
            ('state', '=', 'failed'),
            ('retry_count', '<', config.max_retry_attempts),
            ('write_date', '<=', retry_time)
        ])
        
        _logger.info(f'Retrying {len(messages)} failed WhatsApp messages')
        
        for message in messages:
            message.write({'state': 'queued'})
```

---

### 4. Integration with Permit Model

```python
# In sicantik_connector/models/sicantik_permit.py
# Add WhatsApp notification triggers

from datetime import datetime, timedelta

class SicantikPermit(models.Model):
    _inherit = 'sicantik.permit'
    
    # Add fields for tracking expiry notifications
    expiry_notified_90 = fields.Boolean('Notified 90 Days', default=False)
    expiry_notified_60 = fields.Boolean('Notified 60 Days', default=False)
    expiry_notified_30 = fields.Boolean('Notified 30 Days', default=False)
    expiry_notified_7 = fields.Boolean('Notified 7 Days', default=False)
    
    def write(self, vals):
        """Override to trigger WhatsApp notifications"""
        result = super().write(vals)
        
        # Check if status changed to 'done'
        if vals.get('status') == 'done':
            self._send_permit_ready_notification()
        
        # Check if status changed
        if 'status' in vals:
            self._send_status_update_notification(vals.get('status'))
        
        # Check if renewal approved
        if vals.get('is_renewal') and vals.get('status') == 'done':
            self._send_renewal_approved_notification()
        
        return result
    
    def _send_permit_ready_notification(self):
        """Send notification when permit is ready"""
        for record in self:
            # Get template
            template = self.env['whatsapp.template'].search([
                ('code', '=', 'permit_ready'),
                ('active', '=', True)
            ], limit=1)
            
            if not template:
                continue
            
            # Prepare variables
            values = {
                'nama_pemohon': record.applicant_name,
                'jenis_izin': record.permit_type_id.name,
                'no_surat': record.permit_number,
                'tanggal_terbit': record.issue_date.strftime('%d-%m-%Y'),
                'link_download': f'https://perizinan.karokab.go.id/download/{record.registration_id}'
            }
            
            # Render message
            message = template.render_template(values)
            
            # Create message record
            self.env['whatsapp.message'].create({
                'template_id': template.id,
                'partner_id': record.partner_id.id,
                'phone_number': record.partner_id.mobile or record.partner_id.phone,
                'message': message,
                'state': 'queued',
                'res_model': self._name,
                'res_id': record.id
            })
    
    def _send_renewal_approved_notification(self):
        """Send notification when renewal is approved"""
        for record in self:
            template = self.env['whatsapp.template'].search([
                ('code', '=', 'permit_renewal_approved'),
                ('active', '=', True)
            ], limit=1)
            
            if not template:
                continue
            
            # Calculate masa berlaku
            if record.expiry_date and record.issue_date:
                masa_berlaku = (record.expiry_date - record.issue_date).days / 365
            else:
                masa_berlaku = 0
            
            values = {
                'nama_pemohon': record.applicant_name,
                'jenis_izin': record.permit_type_id.name,
                'no_surat_baru': record.permit_number,
                'tanggal_mulai': record.issue_date.strftime('%d-%m-%Y'),
                'tanggal_berakhir': record.expiry_date.strftime('%d-%m-%Y'),
                'masa_berlaku': int(masa_berlaku),
                'link_download': f'https://perizinan.karokab.go.id/download/{record.registration_id}'
            }
            
            message = template.render_template(values)
            
            self.env['whatsapp.message'].create({
                'template_id': template.id,
                'partner_id': record.partner_id.id,
                'phone_number': record.partner_id.mobile or record.partner_id.phone,
                'message': message,
                'state': 'queued',
                'res_model': self._name,
                'res_id': record.id
            })
    
    @api.model
    def cron_check_expiring_permits(self):
        """
        Cron job to check permits approaching expiry
        Run daily at 09:00 AM
        
        NOTE: This uses WORKAROUND solution (two-step API process)
        TODO: Migrate to optimized solution after API update
        """
        today = fields.Date.today()
        
        # Define notification thresholds
        thresholds = [
            (90, 'expiry_notified_90'),
            (60, 'expiry_notified_60'),
            (30, 'expiry_notified_30'),
            (7, 'expiry_notified_7')
        ]
        
        template = self.env['whatsapp.template'].search([
            ('code', '=', 'permit_expiry_warning'),
            ('active', '=', True)
        ], limit=1)
        
        if not template:
            _logger.warning('Expiry warning template not found')
            return
        
        # WORKAROUND: Sync expiry dates first
        # This will fetch expiry dates from API using two-step process
        connector = self.env['sicantik.connector'].search([], limit=1)
        if connector:
            _logger.info('Starting expiry date sync (workaround)...')
            connector.sync_expiry_dates_workaround()
            _logger.info('Expiry date sync completed')
        
        for days, field_name in thresholds:
            target_date = today + timedelta(days=days)
            
            # Find permits expiring on target date that haven't been notified
            permits = self.search([
                ('expiry_date', '=', target_date),
                ('status', '=', 'active'),
                (field_name, '=', False)
            ])
            
            _logger.info(f'Found {len(permits)} permits expiring in {days} days')
            
            for permit in permits:
                # Calculate remaining days
                sisa_hari = (permit.expiry_date - today).days
                
                # Prepare variables
                values = {
                    'nama_pemohon': permit.applicant_name,
                    'jenis_izin': permit.permit_type_id.name,
                    'no_surat': permit.permit_number,
                    'tanggal_berakhir': permit.expiry_date.strftime('%d-%m-%Y'),
                    'sisa_hari': sisa_hari,
                    'link_perpanjangan': f'https://perizinan.karokab.go.id/perpanjangan/{permit.registration_id}',
                    'kontak_dpmptsp': '0628-20XXX'  # Replace with actual number
                }
                
                # Render message
                message = template.render_template(values)
                
                # Create WhatsApp message
                self.env['whatsapp.message'].create({
                    'template_id': template.id,
                    'partner_id': permit.partner_id.id,
                    'phone_number': permit.partner_id.mobile or permit.partner_id.phone,
                    'message': message,
                    'state': 'queued',
                    'res_model': self._name,
                    'res_id': permit.id
                })
                
                # Mark as notified
                permit.write({field_name: True})
                
                _logger.info(f'Expiry warning sent for permit {permit.permit_number} ({days} days)')
```

---

## 🔐 SECURITY & PERMISSIONS

### Access Rights (ir.model.access.csv)

```csv
id,name,model_id:id,group_id:id,perm_read,perm_write,perm_create,perm_unlink
access_whatsapp_config_manager,whatsapp.config.manager,model_whatsapp_config,base.group_system,1,1,1,1
access_whatsapp_template_user,whatsapp.template.user,model_whatsapp_template,base.group_user,1,0,0,0
access_whatsapp_template_manager,whatsapp.template.manager,model_whatsapp_template,base.group_system,1,1,1,1
access_whatsapp_message_user,whatsapp.message.user,model_whatsapp_message,base.group_user,1,0,0,0
access_whatsapp_message_manager,whatsapp.message.manager,model_whatsapp_message,base.group_system,1,1,1,1
```

---

## 📊 MONITORING & ANALYTICS

### Dashboard Metrics

```python
# Add to sicantik dashboard
def _compute_whatsapp_stats(self):
    """Compute WhatsApp statistics"""
    Message = self.env['whatsapp.message']
    
    # Today's stats
    today = fields.Date.today()
    today_start = datetime.combine(today, datetime.min.time())
    
    self.whatsapp_sent_today = Message.search_count([
        ('sent_date', '>=', today_start),
        ('state', 'in', ['sent', 'delivered', 'read'])
    ])
    
    self.whatsapp_failed_today = Message.search_count([
        ('create_date', '>=', today_start),
        ('state', '=', 'failed')
    ])
    
    self.whatsapp_queued = Message.search_count([
        ('state', '=', 'queued')
    ])
    
    # Delivery rate
    total_sent = Message.search_count([
        ('state', 'in', ['sent', 'delivered', 'read', 'failed'])
    ])
    delivered = Message.search_count([
        ('state', 'in', ['delivered', 'read'])
    ])
    
    self.whatsapp_delivery_rate = (delivered / total_sent * 100) if total_sent > 0 else 0
```

---

## 🚀 DEPLOYMENT CHECKLIST

### Pre-Deployment

- [ ] **WhatsApp Business Account**
  - [ ] Create Facebook Business Manager account
  - [ ] Verify business
  - [ ] Add phone number
  - [ ] Complete business verification

- [ ] **API Setup**
  - [ ] Choose provider (Meta/Twilio/etc)
  - [ ] Get API credentials
  - [ ] Setup webhook URL
  - [ ] Configure webhook verification

- [ ] **Template Approval**
  - [ ] Submit message templates to Meta
  - [ ] Wait for approval (24-48 hours)
  - [ ] Test approved templates

### Configuration

- [ ] **Odoo Setup**
  - [ ] Install sicantik_whatsapp module
  - [ ] Configure WhatsApp API credentials
  - [ ] Test API connection
  - [ ] Import message templates

- [ ] **Partner Data**
  - [ ] Add mobile numbers to partners
  - [ ] Validate phone number format
  - [ ] Set opt-in preferences

- [ ] **Cron Jobs**
  - [ ] Enable message queue processor (every 1 minute)
  - [ ] Enable retry failed messages (every 5 minutes)
  - [ ] Enable daily reminders (daily at 09:00)
  - [ ] **Enable expiry check (daily at 09:00)** ⚠️
  - [ ] Enable renewal reminders (weekly)

### Testing

- [ ] **Unit Tests**
  - [ ] Test template rendering
  - [ ] Test message queueing
  - [ ] Test API connector

- [ ] **Integration Tests**
  - [ ] Send test message
  - [ ] Verify delivery
  - [ ] Test webhook callbacks

- [ ] **User Acceptance**
  - [ ] Test all notification scenarios
  - [ ] Verify message content
  - [ ] Check delivery timing

### Go-Live

- [ ] **Production Setup**
  - [ ] Switch to production API
  - [ ] Update webhook URL
  - [ ] Configure rate limits

- [ ] **Monitoring**
  - [ ] Setup error alerts
  - [ ] Monitor delivery rates
  - [ ] Track usage statistics

---

## 💰 COST ESTIMATION

### Meta Cloud API (Recommended)

| Region | Cost per Message | Free Tier |
|--------|------------------|-----------|
| Indonesia | $0.0088 | 1,000 msg/month |

**Monthly Estimate (Detailed):**

| Notification Type | Frequency | Messages/Month | Cost |
|-------------------|-----------|----------------|------|
| Izin selesai | 100 permits | 100 | $0.88 |
| Dokumen pending | 50 reminders | 50 | $0.44 |
| Approval required | 80 requests | 80 | $0.70 |
| Status update | 150 updates | 150 | $1.32 |
| Staff reminder | 20 reminders | 20 | $0.18 |
| **Expiry warning (90d)** | 80 permits | 80 | $0.70 |
| **Expiry warning (60d)** | 80 permits | 80 | $0.70 |
| **Expiry warning (30d)** | 80 permits | 80 | $0.70 |
| **Expiry warning (7d)** | 80 permits | 80 | $0.70 |
| **Renewal approved** | 60 renewals | 60 | $0.53 |
| **TOTAL** | | **780** | **$6.85** |

**With Free Tier:**
- First 1,000 messages: FREE
- **Effective Cost: $0/month** (under free tier)

**If exceeding free tier:**
- Messages over 1,000: $0.0088 each
- Example: 1,500 messages = $4.40/month

### Twilio WhatsApp API

| Region | Cost per Message | No Free Tier |
|--------|------------------|--------------|
| Indonesia | $0.0085 | - |

**Monthly Estimate:**
- 780 messages × $0.0085 = **$6.63/month**

### Cost Optimization Tips:
1. **Batch notifications** - Combine multiple updates in one message
2. **Smart scheduling** - Send expiry warnings at optimal intervals
3. **Opt-in system** - Only send to users who want notifications
4. **Priority filtering** - Focus on high-value notifications
5. **Template reuse** - Maximize approved template usage

---

## 📚 RESOURCES

### Official Documentation
- [Meta Cloud API](https://developers.facebook.com/docs/whatsapp/cloud-api)
- [Twilio WhatsApp API](https://www.twilio.com/docs/whatsapp)
- [MessageBird WhatsApp](https://developers.messagebird.com/api/whatsapp/)

### Odoo Resources
- [Odoo 18 Documentation](https://www.odoo.com/documentation/18.0/)
- [Odoo WhatsApp Module](https://apps.odoo.com/apps/modules/18.0/whatsapp/)

### Testing Tools
- [WhatsApp Business API Sandbox](https://developers.facebook.com/docs/whatsapp/cloud-api/get-started)
- [Webhook Testing](https://webhook.site/)

---

**Generated:** 29 Oktober 2025  
**Status:** 📋 READY FOR IMPLEMENTATION  
**Timeline:** Week 6 (5 days)  
**Priority:** HIGH (User-facing feature)

