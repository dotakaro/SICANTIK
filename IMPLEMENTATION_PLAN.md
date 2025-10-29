# 🚀 SICANTIK Companion - Implementation Plan

**Tanggal:** 29 Oktober 2025  
**Strategi:** Production API Integration  
**Timeline:** 5 Minggu

---

## 🎯 STRATEGI UTAMA

Menggunakan **Production API** dari `perizinan.karokab.go.id` untuk development Odoo Companion App.

### Keputusan Arsitektur:
✅ **Consume Production API** - Tidak maintain legacy app local  
✅ **Odoo 18 Enterprise** - Platform utama companion app  
✅ **MinIO** - Document repository untuk PDF  
✅ **BSRE API** - Digital signature dengan TTE  
✅ **QR Code** - Verification system  

---

## 📦 Module Structure

```
addons_odoo/
├── sicantik_connector/          # Core API Integration
│   ├── __init__.py
│   ├── __manifest__.py
│   ├── models/
│   │   ├── __init__.py
│   │   ├── sicantik_config.py       # API configuration
│   │   ├── sicantik_connector.py    # API service
│   │   ├── sicantik_permit.py       # Permit records
│   │   └── sicantik_permit_type.py  # Permit types
│   ├── views/
│   │   ├── sicantik_config_views.xml
│   │   ├── sicantik_permit_views.xml
│   │   └── sicantik_menus.xml
│   ├── data/
│   │   ├── cron_data.xml            # Scheduled jobs
│   │   └── default_config.xml       # Default configuration
│   ├── security/
│   │   └── ir.model.access.csv
│   └── README.md
│
├── sicantik_tte/                # Digital Signature Module
│   ├── __init__.py
│   ├── __manifest__.py
│   ├── models/
│   │   ├── __init__.py
│   │   ├── bsre_connector.py        # BSRE API integration
│   │   ├── signature_workflow.py    # Signing workflow
│   │   └── signature_log.py         # Audit trail
│   ├── views/
│   │   ├── signature_views.xml
│   │   └── signature_menus.xml
│   ├── data/
│   │   └── signature_config.xml
│   ├── security/
│   │   └── ir.model.access.csv
│   └── README.md
│
├── sicantik_verification/       # Public Verification Portal
│   ├── __init__.py
│   ├── __manifest__.py
│   ├── models/
│   │   ├── __init__.py
│   │   └── verification_log.py      # Verification tracking
│   ├── controllers/
│   │   ├── __init__.py
│   │   └── portal.py                # Public portal
│   ├── views/
│   │   ├── portal_templates.xml     # Web templates
│   │   └── verification_views.xml
│   ├── static/
│   │   ├── src/
│   │   │   ├── js/
│   │   │   │   └── qr_scanner.js    # QR code scanner
│   │   │   └── css/
│   │   │       └── portal.css
│   │   └── description/
│   │       └── icon.png
│   ├── security/
│   │   └── ir.model.access.csv
│   └── README.md
│
└── sicantik_whatsapp/           # WhatsApp Notification Module
    ├── __init__.py
    ├── __manifest__.py
    ├── models/
    │   ├── __init__.py
    │   ├── whatsapp_config.py       # WhatsApp API configuration
    │   ├── whatsapp_template.py     # Message templates
    │   ├── whatsapp_message.py      # Message queue & log
    │   └── res_partner.py           # Extend partner with WA number
    ├── views/
    │   ├── whatsapp_config_views.xml
    │   ├── whatsapp_template_views.xml
    │   ├── whatsapp_message_views.xml
    │   └── whatsapp_menus.xml
    ├── data/
    │   ├── whatsapp_templates.xml   # Default message templates
    │   └── cron_data.xml            # Message queue processor
    ├── security/
    │   └── ir.model.access.csv
    └── README.md
```

---

## 📅 Implementation Timeline

### **Week 1: Core Connector Module**
**Goal:** Setup API integration dengan production

#### Day 1-2: Module Setup
- [x] Create module structure
- [ ] Create `__manifest__.py`
- [ ] Setup dependencies
- [ ] Create basic models

#### Day 3-4: API Integration
- [ ] Implement `sicantik_config` model
- [ ] Implement `sicantik_connector` service
- [ ] Test connection to production API
- [ ] Implement error handling

#### Day 5: Data Models
- [ ] Create `sicantik_permit` model
- [ ] Create `sicantik_permit_type` model
- [ ] Create views
- [ ] Setup security

**Deliverables:**
- ✅ Working API connection
- ✅ Configuration interface
- ✅ Basic data models

---

### **Week 2: Data Synchronization**
**Goal:** Automated polling dan sync data

#### Day 1-2: Polling Mechanism
- [ ] Implement polling logic
- [ ] Create cron jobs
- [ ] Test data synchronization
- [ ] Handle duplicates

#### Day 3-4: Data Management
- [ ] Implement pagination
- [ ] Add filters and search
- [ ] Create dashboard
- [ ] Add statistics

#### Day 5: Testing & Optimization
- [ ] Test with large datasets
- [ ] Optimize queries
- [ ] Add logging
- [ ] Performance tuning

**Deliverables:**
- ✅ Automated data sync
- ✅ Dashboard with statistics
- ✅ Logging system

---

### **Week 3: PDF Management & Expiry Sync**
**Goal:** PDF detection, import, storage, dan expiry date sync

#### Day 1-2: MinIO Integration
- [ ] Setup MinIO connection
- [ ] Create bucket structure
- [ ] Implement upload logic
- [ ] Test file storage

#### Day 3-4: PDF Detection & Expiry Sync
- [ ] Monitor for new PDFs
- [ ] Implement import workflow
- [ ] Add metadata extraction
- [ ] **Implement expiry date sync (WORKAROUND)** ⚠️
- [ ] Test two-step API process
- [ ] Add progress tracking

#### Day 5: File Management & Testing
- [ ] Create file browser
- [ ] Implement download
- [ ] Add version control
- [ ] Test expiry sync with sample data
- [ ] Performance testing

**Deliverables:**
- ✅ MinIO integration
- ✅ PDF import workflow
- ✅ File management UI
- ✅ **Expiry sync workaround** ⚠️

---

### **Week 4: Digital Signature (TTE BSRE)**
**Goal:** Implement digital signature workflow

#### Day 1-2: BSRE Integration
- [ ] Setup BSRE connector
- [ ] Implement authentication
- [ ] Test signing API
- [ ] Handle certificates

#### Day 3-4: Signature Workflow
- [ ] Create signing interface
- [ ] Implement batch signing
- [ ] Add approval workflow
- [ ] Generate signed PDFs

#### Day 5: QR Code Generation
- [ ] Generate QR codes
- [ ] Embed in PDFs
- [ ] Store verification data
- [ ] Test QR codes

**Deliverables:**
- ✅ BSRE integration
- ✅ Signing workflow
- ✅ QR code system

---

### **Week 5: Verification Portal**
**Goal:** Public verification portal

#### Day 1-2: Portal Development
- [ ] Create public controller
- [ ] Design verification page
- [ ] Implement QR scanner
- [ ] Add certificate display

#### Day 3-4: Verification Logic
- [ ] Implement verification
- [ ] Add audit logging
- [ ] Create statistics
- [ ] Test verification

#### Day 5: Polish & Testing
- [ ] UI/UX improvements
- [ ] Mobile responsiveness
- [ ] Integration testing
- [ ] Bug fixes

**Deliverables:**
- ✅ Public verification portal
- ✅ QR code scanner
- ✅ Audit system
- ✅ Mobile-responsive UI

---

### **Week 6: WhatsApp Notification System**
**Goal:** Automated WhatsApp notifications for stakeholders

#### Day 1-2: WhatsApp Integration
- [ ] Setup WhatsApp Business API
- [ ] Create whatsapp_config model
- [ ] Implement API connector
- [ ] Test message sending

#### Day 3-4: Notification Templates
- [ ] Create message templates
- [ ] Implement template engine
- [ ] Add multilingual support
- [ ] Test template rendering

#### Day 5: Notification Workflows
- [ ] Implement notification triggers
- [ ] Create message queue
- [ ] Add retry mechanism
- [ ] Setup monitoring

**Deliverables:**
- ✅ WhatsApp API integration
- ✅ Message templates
- ✅ Automated notifications
- ✅ Message queue system

---

## 🛠️ Technical Stack

### Backend
- **Odoo 18 Enterprise** - Main platform
- **Python 3.10+** - Programming language
- **PostgreSQL 15** - Metadata database
- **MinIO** - Object storage for PDFs

### External Services
- **SICANTIK API** - perizinan.karokab.go.id
- **BSRE API** - api.bsre.id (TTE)
- **WhatsApp Business API** - Official WhatsApp API
- **Redis** - Caching layer & message queue

### Frontend
- **OWL Framework** - Odoo's JavaScript framework
- **Bootstrap 5** - UI framework
- **QR Code Scanner** - jsQR library

---

## 📱 WhatsApp Notification System

### Use Cases

#### 1. **Notifikasi ke Pemohon**
```
Trigger: Izin selesai diproses & PDF tersedia
Recipient: Pemohon izin
Message:
  "Yth. Bapak/Ibu {nama_pemohon},
   
   Perizinan Anda telah selesai diproses:
   📋 Jenis: {jenis_izin}
   📄 No. Surat: {no_surat}
   📅 Tanggal: {tanggal_terbit}
   
   Dokumen dapat diambil di kantor atau download:
   🔗 {link_download}
   
   Verifikasi dokumen: Scan QR code pada dokumen
   
   Terima kasih,
   DPMPTSP Kabupaten Karo"
```

#### 2. **Notifikasi ke Staff Internal**
```
Trigger: Dokumen baru masuk untuk ditandatangani
Recipient: Staff DPMPTSP
Message:
  "🔔 Notifikasi Dokumen Baru
   
   Ada {jumlah} dokumen menunggu tanda tangan digital:
   
   1. {jenis_izin} - {nama_pemohon}
      No: {pendaftaran_id}
   
   Silakan proses melalui dashboard:
   🔗 {link_dashboard}
   
   SICANTIK Companion"
```

#### 3. **Notifikasi ke Pejabat**
```
Trigger: Dokumen perlu approval untuk signature
Recipient: Kepala Dinas / Pejabat berwenang
Message:
  "🔐 Approval Required
   
   Yth. {nama_pejabat},
   
   Dokumen berikut memerlukan persetujuan Anda:
   📋 {jenis_izin}
   👤 Pemohon: {nama_pemohon}
   📄 No: {no_surat}
   
   Approve via:
   🔗 {link_approval}
   
   SICANTIK Companion"
```

#### 4. **Notifikasi Status Update**
```
Trigger: Status dokumen berubah (signed, verified, etc)
Recipient: Pemohon & Staff terkait
Message:
  "📢 Update Status Perizinan
   
   No: {pendaftaran_id}
   Status: {status_lama} → {status_baru}
   Waktu: {timestamp}
   
   Detail: {link_detail}
   
   DPMPTSP Kab. Karo"
```

#### 5. **Reminder Notification**
```
Trigger: Dokumen pending > 24 jam
Recipient: Staff yang bertanggung jawab
Message:
  "⏰ Reminder: Dokumen Pending
   
   {jumlah} dokumen belum diproses:
   
   - {jenis_izin} ({nama_pemohon})
     Pending sejak: {waktu_pending}
   
   Segera proses: {link_dashboard}
   
   SICANTIK Companion"
```

#### 6. **Notifikasi Izin Mendekati Masa Berlaku** ⚠️
```
Trigger: Cron job (daily) - Izin akan habis dalam 90/60/30 hari
Recipient: Pemohon izin
Message:
  "⚠️ Pengingat Masa Berlaku Izin
   
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
   
   DPMPTSP Kabupaten Karo"
```

#### 7. **Notifikasi Perpanjangan Izin Disetujui** ✅
```
Trigger: Perpanjangan izin selesai diproses
Recipient: Pemohon izin
Message:
  "✅ Perpanjangan Izin Disetujui
   
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
   
   DPMPTSP Kabupaten Karo"
```

### WhatsApp API Options

#### **Option 1: WhatsApp Business API (Official)** ⭐ RECOMMENDED
```python
# Official API from Meta
# Requires: Business verification, Phone number
# Cost: Pay per message
# Features: Templates, media, buttons, webhooks

Provider Options:
1. Meta Cloud API (Direct)
2. Twilio WhatsApp API
3. MessageBird WhatsApp API
4. Vonage WhatsApp API
```

**Keuntungan:**
- ✅ Official & reliable
- ✅ Rich features (buttons, media)
- ✅ Template approval system
- ✅ Delivery reports
- ✅ Webhook support

**Kelemahan:**
- ❌ Requires business verification
- ❌ Template approval process
- ❌ Cost per message
- ❌ Setup complexity

#### **Option 2: WhatsApp Web API (Unofficial)** ⚠️ NOT RECOMMENDED
```python
# Using libraries like whatsapp-web.js
# Free but violates WhatsApp ToS
# Risk of account ban
```

**Kelemahan:**
- ❌ Against WhatsApp ToS
- ❌ Account ban risk
- ❌ Unstable
- ❌ No official support

### Implementation Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Odoo Event Triggers                       │
│  - Permit status change                                     │
│  - Document signed                                          │
│  - Approval required                                        │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│              WhatsApp Message Queue (Redis)                  │
│  - Queue management                                         │
│  - Retry logic                                              │
│  - Rate limiting                                            │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│            WhatsApp API Connector (Python)                   │
│  - Template rendering                                       │
│  - API authentication                                       │
│  - Message sending                                          │
│  - Delivery tracking                                        │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│              WhatsApp Business API                          │
│  - Message delivery                                         │
│  - Delivery receipts                                        │
│  - Webhook callbacks                                        │
└─────────────────────────────────────────────────────────────┘
```

### Message Template System

```python
# Template Model
class WhatsAppTemplate(models.Model):
    _name = 'whatsapp.template'
    
    name = fields.Char('Template Name', required=True)
    code = fields.Char('Template Code', required=True)
    trigger = fields.Selection([
        ('permit_ready', 'Izin Selesai'),
        ('document_pending', 'Dokumen Pending'),
        ('approval_required', 'Perlu Approval'),
        ('status_update', 'Update Status'),
        ('reminder', 'Reminder')
    ])
    recipient_type = fields.Selection([
        ('applicant', 'Pemohon'),
        ('staff', 'Staff'),
        ('official', 'Pejabat')
    ])
    message_template = fields.Text('Message Template')
    variables = fields.Text('Available Variables')
    active = fields.Boolean(default=True)
```

### Configuration Requirements

```python
# WhatsApp Config Model
class WhatsAppConfig(models.Model):
    _name = 'whatsapp.config'
    
    provider = fields.Selection([
        ('meta', 'Meta Cloud API'),
        ('twilio', 'Twilio'),
        ('messagebird', 'MessageBird'),
        ('vonage', 'Vonage')
    ])
    api_key = fields.Char('API Key')
    api_secret = fields.Char('API Secret')
    phone_number_id = fields.Char('Phone Number ID')
    business_account_id = fields.Char('Business Account ID')
    webhook_url = fields.Char('Webhook URL')
    webhook_verify_token = fields.Char('Webhook Verify Token')
    
    # Rate limiting
    max_messages_per_minute = fields.Integer(default=60)
    max_messages_per_day = fields.Integer(default=1000)
    
    # Retry settings
    max_retry_attempts = fields.Integer(default=3)
    retry_delay = fields.Integer(default=300)  # seconds
```

---

## 📊 Data Flow

```
┌─────────────────────────────────────────────────────────────┐
│                    SICANTIK Production                       │
│              perizinan.karokab.go.id                        │
└────────────────────────┬────────────────────────────────────┘
                         │ REST API
                         │ (Polling every 15 min)
                         ▼
┌─────────────────────────────────────────────────────────────┐
│              Odoo Companion - Connector Module               │
│  - Fetch new permits                                        │
│  - Detect PDF generation                                    │
│  - Store metadata                                           │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│                    MinIO Storage                            │
│  - Import PDF files                                         │
│  - Version control                                          │
│  - Metadata indexing                                        │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│              Odoo Companion - TTE Module                    │
│  - Digital signature workflow                               │
│  - BSRE API integration                                     │
│  - QR code generation                                       │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│         Odoo Companion - Verification Module                │
│  - Public verification portal                               │
│  - QR code scanner                                          │
│  - Certificate validation                                   │
└─────────────────────────────────────────────────────────────┘
```

---

## 🔒 Security Requirements

### 1. API Security
- ✅ HTTPS only for production
- ✅ API key authentication (optional)
- ✅ Rate limiting
- ✅ Request timeout
- ✅ Retry with exponential backoff

### 2. Data Security
- ✅ Encrypted storage (MinIO)
- ✅ Access control (Odoo groups)
- ✅ Audit logging
- ✅ Data retention policy

### 3. Digital Signature
- ✅ BSRE certified
- ✅ Certificate validation
- ✅ Timestamp authority
- ✅ Non-repudiation

---

## 📈 Monitoring & Maintenance

### Metrics to Track
1. **API Performance**
   - Response time
   - Success rate
   - Error rate

2. **Data Sync**
   - New permits detected
   - PDFs imported
   - Sync failures

3. **Signature Operations**
   - Documents signed
   - Signature failures
   - Certificate status

4. **Verification**
   - Verification requests
   - Valid vs invalid
   - Geographic distribution

### Maintenance Tasks
- **Daily:** Check sync status
- **Weekly:** Review error logs
- **Monthly:** Performance optimization
- **Quarterly:** Security audit

---

## 🧪 Testing Strategy

### Unit Tests
- API connector methods
- Data models
- Business logic

### Integration Tests
- API connection
- MinIO operations
- BSRE signing

### End-to-End Tests
- Complete workflow
- User scenarios
- Error handling

### Performance Tests
- Large dataset handling
- Concurrent operations
- API rate limits

---

## 📚 Documentation

### User Documentation
- [ ] User manual (Bahasa Indonesia)
- [ ] Video tutorials
- [ ] FAQ
- [ ] Troubleshooting guide

### Technical Documentation
- [ ] API documentation
- [ ] Architecture diagram
- [ ] Database schema
- [ ] Deployment guide

### Developer Documentation
- [ ] Code comments
- [ ] Module README
- [ ] Contributing guidelines
- [ ] Changelog

---

## 🎯 Success Criteria

### Phase 1: Core (Week 1-2)
- ✅ API connection working
- ✅ Data sync automated
- ✅ Dashboard functional

### Phase 2: PDF (Week 3)
- ✅ PDF import working
- ✅ MinIO integration stable
- ✅ File management complete

### Phase 3: Signature (Week 4)
- ✅ BSRE integration working
- ✅ Signing workflow complete
- ✅ QR codes generated

### Phase 4: Verification (Week 5)
- ✅ Public portal live
- ✅ QR scanner working
- ✅ Verification accurate

### Phase 5: WhatsApp (Week 6)
- ✅ WhatsApp API integrated
- ✅ Message templates working
- ✅ Automated notifications
- ✅ Delivery tracking functional

---

## 🚀 Deployment Plan

### Development Environment
- Docker Compose
- Local Odoo instance
- Test data

### Staging Environment
- Production-like setup
- Real API connection
- Limited users

### Production Environment
- High availability
- Backup strategy
- Monitoring system
- Disaster recovery

---

## 📝 Summary

### Total Timeline: **6 Weeks**

| Week | Module | Focus | Status |
|------|--------|-------|--------|
| 1 | sicantik_connector | Core API Integration | ⏳ Pending |
| 2 | sicantik_connector | Data Synchronization | ⏳ Pending |
| 3 | sicantik_connector | PDF Management | ⏳ Pending |
| 4 | sicantik_tte | Digital Signature | ⏳ Pending |
| 5 | sicantik_verification | Verification Portal | ⏳ Pending |
| 6 | sicantik_whatsapp | WhatsApp Notifications | ⏳ Pending |

### Key Features Covered:
- ✅ Production API Integration (perizinan.karokab.go.id)
- ✅ PDF Document Management (MinIO)
- ✅ Digital Signature (TTE BSRE)
- ✅ QR Code Verification
- ✅ Public Verification Portal
- ✅ **WhatsApp Notifications** (NEW)

### WhatsApp Notification Highlights:
- 📱 **7 notification scenarios covered**
  1. Izin selesai diproses
  2. Dokumen baru untuk ditandatangani
  3. Approval required
  4. Status update
  5. Reminder dokumen pending
  6. **Izin mendekati masa berlaku (90/60/30 hari)** ⚠️
  7. **Perpanjangan izin disetujui** ✅
- 🔔 Automated triggers for all stakeholders
- 📝 Template-based messaging system
- 🔄 Message queue with retry logic
- 📊 Delivery tracking & monitoring
- 🌐 Multi-provider support (Meta, Twilio, etc)
- ⏰ Daily cron for expiry reminders

---

**Generated:** 29 Oktober 2025  
**Updated:** 29 Oktober 2025 (Added WhatsApp Module)  
**Status:** ✅ READY TO START  
**Next Step:** Create sicantik_connector module

**Command to Start:**
```bash
cd /Users/rimba/odoo-dev/SICANTIK
mkdir -p addons_odoo/sicantik_connector
cd addons_odoo/sicantik_connector
```

**Note:** WhatsApp Business API requires:
- Business verification with Meta
- Approved phone number
- Message template approval
- API credentials from provider

