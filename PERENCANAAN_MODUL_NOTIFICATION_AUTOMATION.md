# PERENCANAAN MODUL SICANTIK NOTIFICATION AUTOMATION

**Versi:** 1.0.0  
**Tanggal:** 28 November 2025  
**Status:** Planning Phase  
**Modul:** `sicantik_notification_automation`

---

## DAFTAR ISI

1. [Ringkasan Eksekutif](#1-ringkasan-eksekutif)
2. [Tujuan dan Ruang Lingkup](#2-tujuan-dan-ruang-lingkup)
3. [Arsitektur Sistem](#3-arsitektur-sistem)
4. [Struktur Modul](#4-struktur-modul)
5. [Model dan Skema Database](#5-model-dan-skema-database)
6. [Alur Kerja](#6-alur-kerja-workflow)
7. [Integrasi dengan Modul Lain](#7-integrasi-dengan-modul-lain)
8. [Use Cases Detail](#8-use-cases-detail)
9. [Spesifikasi Teknis](#9-spesifikasi-teknis)
10. [Fase Implementasi](#10-fase-implementasi)
11. [Strategi Pengujian](#11-strategi-pengujian)
12. [Persyaratan Dokumentasi](#12-persyaratan-dokumentasi)
13. [Penilaian Risiko](#13-penilaian-risiko)
14. [Timeline dan Sumber Daya](#14-timeline-dan-sumber-daya)
15. [Kriteria Keberhasilan](#15-kriteria-keberhasilan)
16. [Pengelolaan Cron Jobs di Odoo UI](#16-pengelolaan-cron-jobs-di-odoo-ui)

---

## 1. RINGKASAN EKSEKUTIF

### 1.1 Gambaran Umum

Modul `sicantik_notification_automation` adalah modul tambahan yang memberikan kemampuan **notifikasi massal** dan **otomatis** berdasarkan pemicu/event tertentu. Modul ini dirancang dengan prinsip **modular** sehingga dapat di-install/uninstall tanpa merusak fungsi dasar sistem.

Modul ini akan mengintegrasikan sistem notifikasi berbasis aturan (rule-based) yang fleksibel, memungkinkan administrator untuk mengkonfigurasi berbagai skenario notifikasi tanpa perlu modifikasi kode. Sistem ini menggunakan event-driven architecture yang memungkinkan notifikasi otomatis saat kondisi tertentu terpenuhi.

### 1.2 Fitur Utama

- ✅ **Notifikasi Berbasis Aturan**: Sistem aturan notifikasi yang dapat dikonfigurasi melalui UI
- ✅ **Pemicu Berbasis Event**: Pemicu berdasarkan event Odoo (create, write, state change)
- ✅ **Kampanye Massal**: Kampanye notifikasi massal dengan pemrosesan batch
- ✅ **Manajemen Antrian**: Antrian pengiriman dengan mekanisme ulang coba
- ✅ **Penjadwalan**: Penjadwalan pengiriman (langsung, terjadwal, batch)
- ✅ **Analitik**: Pencatatan dan pelacakan lengkap untuk audit
- ✅ **Pembatasan Laju**: Pembatasan pengiriman untuk menghindari spam
- ✅ **Daftar Hitam**: Manajemen nomor yang tidak ingin menerima notifikasi

### 1.3 Nilai Bisnis

- **Efisiensi**: Otomatisasi notifikasi mengurangi pekerjaan manual hingga 80%
- **Konsistensi**: Notifikasi terkirim tepat waktu dan konsisten
- **Skalabilitas**: Dapat menangani pengiriman massal dengan efisien (1000+ notifikasi per jam)
- **Kepatuhan**: Pencatatan lengkap untuk audit dan compliance
- **Fleksibilitas**: Mudah menambah aturan dan pemicu baru tanpa modifikasi kode

---

## 2. TUJUAN DAN RUANG LINGKUP

### 2.1 Tujuan Utama

1. **Mengotomatisasi** pengiriman notifikasi berdasarkan kondisi tertentu
2. **Mengelola** kampanye notifikasi massal dengan efisien
3. **Menyediakan** sistem yang fleksibel dan dapat dikonfigurasi
4. **Memastikan** tidak merusak fungsi dasar saat uninstall

### 2.2 Ruang Lingkup (In Scope)

- ✅ Sistem notifikasi berbasis aturan
- ✅ Pemicu berbasis event (Odoo signals)
- ✅ Kampanye notifikasi massal
- ✅ Manajemen antrian dengan mekanisme ulang coba
- ✅ Penjadwalan (langsung, terjadwal, batch)
- ✅ Integrasi dengan `sicantik_whatsapp`
- ✅ Pencatatan dan analitik
- ✅ Pembatasan laju pengiriman
- ✅ Manajemen daftar hitam

### 2.3 Di Luar Ruang Lingkup (Out of Scope)

- ❌ Notifikasi email (fokus WhatsApp dulu)
- ❌ Notifikasi SMS
- ❌ Push notifications untuk aplikasi mobile
- ❌ Template builder UI (menggunakan template yang sudah ada)
- ❌ A/B testing untuk kampanye
- ❌ Machine learning untuk optimasi timing

---

## 3. ARSITEKTUR SISTEM

### 3.1 Arsitektur Tingkat Tinggi

```
┌─────────────────────────────────────────────────────────────┐
│       SICANTIK NOTIFICATION AUTOMATION (Modul Baru)        │
└───────────────────────────┬─────────────────────────────────┘
                            │
        ┌───────────────────┼───────────────────┐
        │                   │                   │
        ▼                   ▼                   ▼
┌──────────────┐   ┌──────────────┐   ┌──────────────┐
│   Pemicu     │   │    Aturan     │   │  Kampanye    │
│   Manager    │──▶│   Engine      │──▶│  Manager     │
└──────────────┘   └──────────────┘   └──────────────┘
        │                   │                   │
        │                   │                   │
        └───────────────────┼───────────────────┘
                            │
                            ▼
                   ┌──────────────┐
                   │    Antrian   │
                   │   Manager    │
                   └──────┬───────┘
                          │
                          ▼
        ┌─────────────────────────────────────┐
        │   SICANTIK WHATSAPP (Modul)         │
        │       WhatsApp Dispatcher            │
        └─────────────────┬───────────────────┘
                          │
                          ▼
        ┌─────────────────────────────────────┐
        │      Provider WhatsApp               │
        │   (Meta / Fonnte / Watzap)          │
        └─────────────────────────────────────┘
```

### 3.2 Komponen Utama

#### A. Pemicu Manager (Trigger Manager)
- Mendeteksi event dari Odoo (signals)
- Memproses pemicu berdasarkan konfigurasi
- Memicu evaluasi aturan yang sesuai

#### B. Mesin Aturan (Rule Engine)
- Mengevaluasi kondisi aturan
- Menentukan target penerima
- Menyiapkan data untuk template
- Membuat entri antrian

#### C. Manajer Kampanye (Campaign Manager)
- Mengelola kampanye massal
- Pemrosesan batch
- Pelacakan progres
- Penanganan error

#### D. Manajer Antrian (Queue Manager)
- Mengantri pengiriman notifikasi
- Mekanisme ulang coba
- Pembatasan laju
- Pelacakan status

---

## 4. STRUKTUR MODUL

### 4.1 Struktur File

```
addons_odoo/sicantik_notification_automation/
├── __init__.py
├── __manifest__.py
├── README.md
│
├── models/
│   ├── __init__.py
│   ├── notification_trigger.py          # Model untuk pemicu/event
│   ├── notification_rule.py            # Model untuk aturan notifikasi
│   ├── notification_campaign.py         # Model untuk kampanye massal
│   ├── notification_queue.py            # Model untuk antrian pengiriman
│   ├── notification_log.py              # Model untuk log pengiriman
│   └── notification_blacklist.py       # Model untuk daftar hitam nomor
│
├── views/
│   ├── notification_trigger_views.xml
│   ├── notification_rule_views.xml
│   ├── notification_campaign_views.xml
│   ├── notification_queue_views.xml
│   ├── notification_log_views.xml
│   ├── notification_analytics_views.xml  # Dashboard analitik
│   └── notification_menus.xml
│
├── controllers/
│   ├── __init__.py
│   └── notification_api.py              # REST API untuk eksternal (optional)
│
├── wizard/
│   ├── __init__.py
│   ├── notification_test_wizard.py      # Wizard untuk test send
│   ├── notification_preview_wizard.py   # Wizard untuk preview pesan
│   └── notification_campaign_wizard.py  # Wizard untuk create campaign
│
├── data/
│   ├── notification_trigger_data.xml    # Default pemicu
│   ├── notification_rule_data.xml       # Default aturan
│   └── cron_data.xml                    # Scheduled jobs
│
├── security/
│   ├── ir.model.access.csv
│   └── notification_security.xml        # Record rules (optional)
│
└── static/
    └── description/
        ├── index.html
        └── main.png
```

### 4.2 Dependencies

```python
"depends": [
    "base",
    "mail",                    # Untuk mail.thread dan mail.activity.mixin
    "sicantik_connector",      # Untuk akses data izin
    "sicantik_whatsapp",       # Untuk pengiriman pesan WhatsApp
    # "sicantik_tte",          # Optional: untuk pemicu dokumen
]
```

---

## 5. MODEL DAN SKEMA DATABASE

### 5.1 Pemicu Notifikasi (`sicantik.notification.trigger`)

**Tujuan**: Mendefinisikan event/pemicu yang dapat memicu notifikasi

**Field Utama:**
- `name` (Char): Nama pemicu
- `code` (Char): Kode unik pemicu (required, unique)
- `active` (Boolean): Status aktif/nonaktif
- `model_id` (Many2one): Model Odoo yang akan di-monitor
- `trigger_type` (Selection): Tipe pemicu (on_create, on_write, on_state_change, on_field_change, cron, manual)
- `field_to_watch` (Many2one): Field yang diperhatikan (untuk on_field_change)
- `state_field` (Char): Nama field status (default: state)
- `state_from` (Char): Status awal
- `state_to` (Char): Status target yang memicu notifikasi
- `condition_domain` (Char): Domain Odoo untuk filter record
- `rule_ids` (One2many): Aturan notifikasi terkait

**Default Pemicu:**
1. `permit_state_change` - Saat status izin berubah
2. `permit_expiring` - Saat izin mendekati masa berlaku
3. `document_uploaded` - Saat dokumen baru diupload
4. `document_pending_signature` - Saat dokumen perlu ditandatangani
5. `document_signed` - Saat dokumen sudah ditandatangani
6. `document_approved` - Saat dokumen disetujui

---

### 5.2 Aturan Notifikasi (`sicantik.notification.rule`)

**Tujuan**: Mendefinisikan aturan notifikasi yang akan dieksekusi saat pemicu terjadi

**Field Utama:**
- `name` (Char): Nama aturan
- `active` (Boolean): Status aktif/nonaktif
- `trigger_id` (Many2one): Pemicu terkait
- `condition_domain` (Char): Domain tambahan untuk filter
- `template_key` (Char): Key template dari sicantik_whatsapp
- `recipient_type` (Selection): Tipe penerima (partner, staff, official, custom, field)
- `recipient_domain` (Char): Domain untuk mencari penerima (custom)
- `schedule_type` (Selection): Tipe penjadwalan (immediate, scheduled, batch)
- `batch_size` (Integer): Ukuran batch (default: 100)
- `rate_limit_enabled` (Boolean): Aktifkan pembatasan laju
- `rate_limit_count` (Integer): Max pengiriman per periode
- `max_retries` (Integer): Max ulang coba (default: 3)
- `respect_blacklist` (Boolean): Hormati daftar hitam (default: True)

---

### 5.3 Kampanye Notifikasi (`sicantik.notification.campaign`)

**Tujuan**: Mengelola kampanye notifikasi massal

**Field Utama:**
- `name` (Char): Nama kampanye
- `description` (Text): Deskripsi
- `rule_id` (Many2one): Aturan terkait
- `target_domain` (Char): Domain untuk filter target
- `target_model` (Char): Model Odoo target
- `template_key` (Char): Override template dari aturan
- `scheduled_date` (Datetime): Jadwal pengiriman
- `state` (Selection): Status (draft, scheduled, running, paused, completed, cancelled)
- `total_targets` (Integer): Total target
- `total_sent` (Integer): Total terkirim
- `total_failed` (Integer): Total gagal
- `progress_percentage` (Float): Persentase progres

---

### 5.4 Antrian Notifikasi (`sicantik.notification.queue`)

**Tujuan**: Mengantri dan mengelola pengiriman notifikasi

**Field Utama:**
- `rule_id` (Many2one): Aturan terkait
- `campaign_id` (Many2one): Kampanye terkait
- `partner_id` (Many2one): Penerima (required)
- `permit_id` (Many2one): Izin terkait
- `document_id` (Many2one): Dokumen terkait
- `template_key` (Char): Key template
- `context_values` (Json): Data untuk template
- `scheduled_date` (Datetime): Jadwal pengiriman
- `priority` (Integer): Prioritas (default: 5)
- `state` (Selection): Status (pending, processing, sent, failed, cancelled, skipped)
- `sent_date` (Datetime): Tanggal terkirim
- `error_message` (Text): Pesan error
- `retry_count` (Integer): Jumlah ulang coba
- `provider_id` (Many2one): Provider yang digunakan
- `external_message_id` (Char): ID pesan eksternal

---

### 5.5 Log Notifikasi (`sicantik.notification.log`)

**Tujuan**: Mencatat log lengkap pengiriman untuk audit

**Field Utama:**
- `rule_id` (Many2one): Aturan terkait
- `campaign_id` (Many2one): Kampanye terkait
- `queue_id` (Many2one): Entri antrian terkait
- `partner_id` (Many2one): Penerima
- `template_key` (Char): Key template
- `context_values` (Json): Data context
- `state` (Selection): Status (sent, failed, skipped)
- `sent_date` (Datetime): Tanggal terkirim
- `error_message` (Text): Pesan error
- `provider_id` (Many2one): Provider yang digunakan
- `processing_time_ms` (Integer): Waktu proses (ms)

---

### 5.6 Daftar Hitam Notifikasi (`sicantik.notification.blacklist`)

**Tujuan**: Mengelola daftar nomor yang tidak ingin menerima notifikasi

**Field Utama:**
- `phone_number` (Char): Nomor telepon (required, unique)
- `partner_id` (Many2one): Partner terkait (optional)
- `reason` (Text): Alasan
- `active` (Boolean): Status aktif (default: True)
- `blocked_count` (Integer): Jumlah diblokir
- `last_blocked_date` (Datetime): Terakhir diblokir

---

## 6. ALUR KERJA (WORKFLOW)

### 6.1 Alur Kerja: Notifikasi Berbasis Event

```
1. Event Terjadi (Odoo Signal)
   │
   ▼
2. Pemicu Manager Mendeteksi Event
   │
   ▼
3. Cari Pemicu yang Sesuai
   │
   ▼
4. Evaluasi Kondisi Pemicu
   │
   ├─▶ Tidak Sesuai → Stop
   │
   └─▶ Sesuai → Lanjut
       │
       ▼
5. Cari Aturan yang Terkait dengan Pemicu
   │
   ▼
6. Untuk Setiap Aturan:
   │
   ├─▶ Evaluasi Kondisi Aturan
   │   │
   │   ├─▶ Tidak Sesuai → Skip Aturan
   │   │
   │   └─▶ Sesuai → Lanjut
   │       │
   │       ▼
   │   7. Tentukan Penerima
   │       │
   │       ├─▶ Partner (dari record)
   │       ├─▶ Staff (dari grup)
   │       ├─▶ Official (dari grup)
   │       └─▶ Custom (dari domain)
   │       │
   │       ▼
   │   8. Siapkan Context Values
   │       │
   │       ▼
   │   9. Buat Entri Antrian
   │       │
   │       ├─▶ Langsung → Langsung ke Antrian
   │       ├─▶ Terjadwal → Antrian dengan scheduled_date
   │       └─▶ Batch → Antrian dengan batch flag
```

### 6.2 Alur Kerja: Pemrosesan Antrian

```
1. Cron Job: Proses Antrian (setiap 1 menit)
   │
   ▼
2. Ambil Entri Antrian (state='pending', scheduled_date <= now)
   │
   ▼
3. Untuk Setiap Entri Antrian:
   │
   ├─▶ Cek Daftar Hitam
   │   │
   │   ├─▶ Di Daftar Hitam → Skip, Update State='skipped'
   │   │
   │   └─▶ Tidak → Lanjut
   │       │
   │       ▼
   │   4. Cek Pembatasan Laju
   │       │
   │       ├─▶ Melebihi Limit → Tunda, Update scheduled_date
   │       │
   │       └─▶ Masih dalam Limit → Lanjut
   │           │
   │           ▼
   │       5. Update State='processing'
   │           │
   │           ▼
   │       6. Panggil WhatsApp Dispatcher
   │           │
   │           ├─▶ Berhasil → Update State='sent', Log Success
   │           │
   │           └─▶ Gagal → Cek Ulang Coba
   │               │
   │               ├─▶ Ulang Coba < Max → Jadwalkan Ulang Coba
   │               │
   │               └─▶ Ulang Coba >= Max → Update State='failed', Log Error
```

### 6.3 Alur Kerja: Eksekusi Kampanye

```
1. Pengguna Membuat Kampanye
   │
   ▼
2. Set State='draft'
   │
   ▼
3. Pengguna Klik "Mulai Kampanye"
   │
   ▼
4. Evaluasi Target Domain
   │
   ▼
5. Buat Entri Antrian untuk Semua Target
   │
   ▼
6. Update State='running'
   │
   ▼
7. Pemrosesan Antrian (sama seperti alur kerja di atas)
   │
   ▼
8. Monitor Progres
   │
   ├─▶ Masih Ada Pending → Lanjut
   │
   └─▶ Semua Selesai → Update State='completed'
```

---

## 7. INTEGRASI DENGAN MODUL LAIN

### 7.1 Integrasi dengan `sicantik_whatsapp`

**Metode Integrasi:**
- Menggunakan `sicantik.whatsapp.dispatcher` yang sudah ada
- Memanggil `send_template_message()` dengan parameter yang sesuai
- Tidak perlu modifikasi modul `sicantik_whatsapp`

**Contoh Kode:**
```python
def _send_notification(self, queue_entry):
    """Kirim notifikasi menggunakan WhatsApp Dispatcher"""
    dispatcher = self.env['sicantik.whatsapp.dispatcher']
    
    result = dispatcher.send_template_message(
        template_key=queue_entry.template_key,
        partner_id=queue_entry.partner_id.id,
        context_values=queue_entry.context_values,
        permit_id=queue_entry.permit_id.id if queue_entry.permit_id else None
    )
    
    return result
```

### 7.2 Integrasi dengan `sicantik_connector`

**Titik Pemicu:**
- `on_state_change` untuk `sicantik.permit`
- `on_field_change` untuk `expiry_date`
- `cron` untuk batch checking

**Contoh Signal Handler:**
```python
from odoo import api, models

class SicantikPermit(models.Model):
    _inherit = 'sicantik.permit'
    
    def write(self, vals):
        # Deteksi state change
        if 'state' in vals:
            old_states = {r.id: r.state for r in self}
            result = super().write(vals)
            # Pemicu notifikasi
            for record in self:
                if old_states[record.id] != record.state:
                    self.env['sicantik.notification.trigger']._process_trigger(
                        'permit_state_change', record,
                        old_state=old_states[record.id],
                        new_state=record.state
                    )
            return result
        return super().write(vals)
```

### 7.3 Integrasi dengan `sicantik_tte`

**Titik Pemicu:**
- `on_create` untuk `sicantik.document`
- `on_state_change` untuk workflow dokumen
- `cron` untuk reminder dokumen pending

**Pemicu Khusus:**
- `document_pending_signature`: Saat `sicantik.document.state` berubah ke 'pending_signature'
- `document_signed`: Saat `sicantik.document.state` berubah ke 'signed'
- `signature_workflow_pending`: Saat `signature.workflow.state` berubah ke 'pending' (untuk approval)

---

## 8. USE CASES DETAIL

### Use Case 1: Notifikasi Otomatis Saat Status Izin Berubah

**Pemicu**: `permit_state_change`  
**Aturan**: `notify_permit_approved`  
**Kondisi**: `state` dari `draft` ke `approved`  
**Penerima**: Partner (pemohon)  
**Template**: `permit_ready`  
**Penjadwalan**: Langsung

**Alur:**
1. Pengguna mengubah status izin dari `draft` ke `approved`
2. Signal `on_state_change` terdeteksi
3. Pemicu `permit_state_change` dievaluasi
4. Aturan `notify_permit_approved` ditemukan dan dievaluasi
5. Kondisi sesuai → Tentukan penerima (partner dari permit)
6. Siapkan context values (permit_number, permit_type, dll)
7. Buat entri antrian dengan state='pending', scheduled_date=now
8. Proses antrian mengambil entri dan mengirim via WhatsApp Dispatcher
9. Update antrian state='sent' dan buat log entry

---

### Use Case 2: Kampanye Massal Peringatan Masa Berlaku

**Kampanye**: `peringatan_masa_berlaku_30_hari`  
**Target**: Semua izin yang akan berakhir dalam 30 hari  
**Template**: `permit_reminder`  
**Penjadwalan**: Batch, 100 per batch, interval 5 menit

**Alur:**
1. Administrator membuat kampanye baru
2. Set target domain: `[('expiry_date', '<=', 30_hari_dari_sekarang)]`
3. Set template: `permit_reminder`
4. Set penjadwalan: Batch, size=100, interval=5
5. Klik "Mulai Kampanye"
6. Sistem mengevaluasi domain dan menemukan 500 izin
7. Sistem membuat 500 entri antrian dengan batch flag
8. Proses antrian mengambil 100 entri pertama
9. Kirim 100 notifikasi
10. Tunggu 5 menit
11. Ambil 100 entri berikutnya
12. Ulangi sampai semua selesai
13. Update kampanye state='completed'

---

### Use Case 3: Reminder Dokumen Pending (Cron-Based)

**Pemicu**: `cron_document_pending_reminder`  
**Aturan**: `remind_pending_documents`  
**Cron**: Setiap hari jam 10:00  
**Kondisi**: Dokumen dengan state='pending_signature' lebih dari 24 jam  
**Penerima**: Staff DPMPTSP  
**Template**: `reminder`

**Alur:**
1. Cron job berjalan setiap hari jam 10:00
2. Pemicu `cron_document_pending_reminder` dieksekusi
3. Evaluasi kondisi: `[('state', '=', 'pending_signature'), ('create_date', '<', 24_jam_yang_lalu)]`
4. Ditemukan 15 dokumen yang sesuai
5. Aturan `remind_pending_documents` dievaluasi
6. Tentukan penerima: Staff dari grup "DPMPTSP Staff"
7. Untuk setiap dokumen, buat entri antrian untuk setiap staff
8. Proses antrian mengirim notifikasi
9. Log semua pengiriman

---

### Use Case 4: Notifikasi Saat Dokumen Perlu Ditandatangani

**Pemicu**: `document_pending_signature`  
**Aturan**: `notify_document_needs_signature`  
**Kondisi**: `sicantik.document.state` berubah ke 'pending_signature'  
**Penerima**: Staff DPMPTSP atau Pejabat Berwenang  
**Template**: `document_pending`  
**Penjadwalan**: Langsung

**Alur:**
1. Dokumen diupload dan `action_request_signature()` dipanggil
2. State dokumen berubah ke 'pending_signature'
3. Signal `on_state_change` terdeteksi
4. Pemicu `document_pending_signature` dievaluasi
5. Aturan `notify_document_needs_signature` ditemukan
6. Tentukan penerima: Staff dari grup "DPMPTSP Staff" atau pejabat berwenang
7. Siapkan context values (document_number, permit_number, applicant_name, dll)
8. Buat entri antrian untuk setiap penerima
9. Proses antrian mengirim notifikasi
10. Log semua pengiriman

---

### Use Case 5: Notifikasi Saat Dokumen Sudah Ditandatangani

**Pemicu**: `document_signed`  
**Aturan**: `notify_document_signed`  
**Kondisi**: `sicantik.document.state` berubah ke 'signed'  
**Penerima**: Partner (pemohon)  
**Template**: `document_signed`  
**Penjadwalan**: Langsung

**Alur:**
1. Dokumen berhasil ditandatangani via BSRE
2. State dokumen berubah ke 'signed'
3. Signal `on_state_change` terdeteksi
4. Pemicu `document_signed` dievaluasi
5. Aturan `notify_document_signed` ditemukan
6. Tentukan penerima: Partner dari permit terkait
7. Siapkan context values (document_number, permit_number, signature_date, dll)
8. Buat entri antrian
9. Proses antrian mengirim notifikasi
10. Log pengiriman

---

## 9. SPESIFIKASI TEKNIS

### 9.1 Implementasi Odoo Signals

```python
from odoo import api, models
from odoo.addons.base.models.ir_model import MODULE_UNINSTALL_FLAG

class NotificationTrigger(models.Model):
    _name = 'sicantik.notification.trigger'
    
    def _register_signals(self):
        """Daftarkan Odoo signals untuk pemicu"""
        # Daftarkan untuk setiap pemicu yang aktif
        for trigger in self.search([('active', '=', True)]):
            if trigger.trigger_type == 'on_create':
                models.signals.post_create.connect(
                    self._on_create_handler,
                    sender=self.env[trigger.model_name]
                )
            elif trigger.trigger_type == 'on_write':
                models.signals.post_write.connect(
                    self._on_write_handler,
                    sender=self.env[trigger.model_name]
                )
            # ... dll
```

### 9.2 Pemrosesan Antrian Cron Job

```python
@api.model
def cron_process_notification_queue(self):
    """Cron job untuk memproses antrian notifikasi"""
    # Ambil entri antrian yang siap dikirim
    queue_entries = self.env['sicantik.notification.queue'].search([
        ('state', '=', 'pending'),
        ('scheduled_date', '<=', fields.Datetime.now()),
        ('is_blacklisted', '=', False),
    ], limit=100, order='priority desc, scheduled_date asc')
    
    for entry in queue_entries:
        try:
            entry.action_process()
        except Exception as e:
            _logger.error(f"Error processing queue {entry.id}: {e}")
            entry.write({
                'state': 'failed',
                'error_message': str(e)
            })
```

### 9.3 Implementasi Pembatasan Laju

```python
def _check_rate_limit(self, rule_id):
    """Cek apakah masih dalam pembatasan laju"""
    rule = self.env['sicantik.notification.rule'].browse(rule_id)
    
    if not rule.rate_limit_enabled:
        return True
    
    # Hitung pengiriman dalam periode terakhir
    period_start = self._get_period_start(rule.rate_limit_period)
    
    count = self.env['sicantik.notification.log'].search_count([
        ('rule_id', '=', rule_id),
        ('sent_date', '>=', period_start),
        ('state', '=', 'sent'),
    ])
    
    return count < rule.rate_limit_count
```

### 9.4 Persiapan Context Values

```python
def _prepare_context_values(self, rule, record):
    """Siapkan context values untuk template"""
    context = {}
    
    # Jika ada custom code
    if rule.context_preparation_code:
        # Eksekusi custom code dengan context yang aman
        safe_dict = {
            'record': record,
            'env': self.env,
            'fields': fields,
            'datetime': datetime,
            'timedelta': timedelta,
        }
        exec(rule.context_preparation_code, safe_dict)
        context = safe_dict.get('context', {})
    else:
        # Default context preparation
        if hasattr(record, 'permit_id'):
            permit = record.permit_id
            context = {
                'partner_name': permit.applicant_name or 'Bapak/Ibu',
                'permit_number': permit.permit_number or '',
                'permit_type': permit.permit_type_name or '',
                'status': permit.state or '',
            }
    
    return context
```

---

## 10. FASE IMPLEMENTASI

### Phase 1: Foundation (Minggu 1-2)

**Tujuan**: Setup struktur dasar modul

**Tugas:**
- ✅ Buat struktur modul dasar
- ✅ Implementasi model `notification_trigger`
- ✅ Implementasi model `notification_rule`
- ✅ Setup dependencies dan manifest
- ✅ Buat security rules dasar
- ✅ Setup default pemicu (data XML)

**Deliverables:**
- Modul dapat di-install
- Model pemicu dan aturan dapat dibuat via UI
- Default pemicu tersedia

---

### Phase 2: Core Engine (Minggu 3-4)

**Tujuan**: Implementasi engine untuk pemicu dan evaluasi aturan

**Tugas:**
- ✅ Implementasi signal handlers
- ✅ Implementasi logika evaluasi pemicu
- ✅ Implementasi logika evaluasi aturan
- ✅ Implementasi penentuan penerima
- ✅ Implementasi persiapan context values
- ✅ Testing evaluasi pemicu dan aturan

**Deliverables:**
- Pemicu dapat mendeteksi event
- Aturan dapat dievaluasi dengan benar
- Penerima dapat ditentukan dengan benar

---

### Phase 3: Queue System (Minggu 5-6)

**Tujuan**: Implementasi sistem antrian pengiriman

**Tugas:**
- ✅ Implementasi model `notification_queue`
- ✅ Implementasi logika pemrosesan antrian
- ✅ Implementasi mekanisme ulang coba
- ✅ Implementasi pembatasan laju
- ✅ Implementasi pengecekan daftar hitam
- ✅ Implementasi cron job untuk pemrosesan antrian (setiap 1 menit, priority 20)
- ✅ Testing pemrosesan antrian

**Deliverables:**
- Entri antrian dapat dibuat
- Antrian dapat diproses dengan benar
- Mekanisme ulang coba berfungsi
- Pembatasan laju berfungsi

---

### Phase 4: Campaign System (Minggu 7-8)

**Tujuan**: Implementasi sistem kampanye massal

**Tugas:**
- ✅ Implementasi model `notification_campaign`
- ✅ Implementasi logika eksekusi kampanye
- ✅ Implementasi pemrosesan batch
- ✅ Implementasi pelacakan progres
- ✅ Implementasi manajemen kampanye (start, pause, resume, cancel)
- ✅ Implementasi cron job untuk pemrosesan batch kampanye (setiap 5 menit, priority 15)
- ✅ Testing eksekusi kampanye

**Deliverables:**
- Kampanye dapat dibuat dan dijalankan
- Pemrosesan batch berfungsi
- Pelacakan progres akurat

---

### Phase 5: Integration (Minggu 9)

**Tujuan**: Integrasi dengan modul yang ada

**Tugas:**
- ✅ Integrasi dengan `sicantik_whatsapp` dispatcher
- ✅ Integrasi dengan `sicantik_connector` (signal handlers)
- ✅ Integrasi dengan `sicantik_tte` (signal handlers untuk dokumen)
- ✅ Testing integrasi end-to-end

**Deliverables:**
- Notifikasi dapat dikirim via WhatsApp
- Pemicu dari modul lain berfungsi
- Testing end-to-end berhasil

---

### Phase 6: Logging & Analytics (Minggu 10)

**Tujuan**: Implementasi logging dan analitik

**Tugas:**
- ✅ Implementasi model `notification_log`
- ✅ Implementasi logging untuk semua pengiriman
- ✅ Implementasi dashboard analitik
- ✅ Implementasi reporting
- ✅ Implementasi cron job untuk cleanup log lama (setiap hari, priority 5)
- ✅ Testing logging dan analitik

**Deliverables:**
- Semua pengiriman tercatat di log
- Dashboard analitik tersedia
- Reporting berfungsi

---

### Phase 7: UI & UX (Minggu 11)

**Tujuan**: Implementasi user interface

**Tugas:**
- ✅ Buat views untuk semua model
- ✅ Buat wizards (test, preview)
- ✅ Implementasi dashboard analitik
- ✅ Implementasi menu structure
- ✅ Tambahkan link ke Scheduled Actions untuk monitoring cron jobs
- ✅ UI/UX improvements
- ✅ Testing UI

**Deliverables:**
- Semua fitur dapat diakses via UI
- Wizards berfungsi dengan baik
- Dashboard analitik informatif

---

### Phase 8: Testing & Documentation (Minggu 12)

**Tujuan**: Testing menyeluruh dan dokumentasi

**Tugas:**
- ✅ Unit testing
- ✅ Integration testing
- ✅ User acceptance testing
- ✅ Performance testing
- ✅ Testing cron jobs via UI (enable/disable, run manually, view logs)
- ✅ Dokumentasi user guide (termasuk cara mengelola cron jobs)
- ✅ Dokumentasi developer guide

**Deliverables:**
- Semua test cases passed
- Dokumentasi lengkap
- Modul siap untuk production

---

## 11. STRATEGI PENGUJIAN

### 11.1 Unit Testing

**Ruang Lingkup**: Testing method dan fungsi individual

**Test Cases:**
1. Evaluasi logika pemicu
2. Evaluasi logika aturan
3. Penentuan penerima
4. Persiapan context values
5. Logika pemrosesan antrian
6. Mekanisme ulang coba
7. Pembatasan laju
8. Pengecekan daftar hitam

**Tools**: Odoo test framework (`odoo.tests.common`)

---

### 11.2 Integration Testing

**Ruang Lingkup**: Testing integrasi antar komponen

**Test Cases:**
1. Alur Pemicu → Aturan → Antrian
2. Alur Antrian → WhatsApp Dispatcher
3. Alur Kampanye → Antrian → Dispatcher
4. Signal handlers dari modul lain
5. Eksekusi cron jobs

---

### 11.3 End-to-End Testing

**Ruang Lingkup**: Testing skenario pengguna lengkap

**Test Cases:**
1. Use Case 1: Notifikasi otomatis status izin
2. Use Case 2: Kampanye massal peringatan masa berlaku
3. Use Case 3: Reminder dokumen pending
4. Use Case 4: Notifikasi dokumen perlu ditandatangani
5. Use Case 5: Notifikasi dokumen sudah ditandatangani
6. Skenario error (provider down, template tidak valid, dll)
7. Kinerja dengan volume besar (1000+ entri antrian)

---

### 11.4 Performance Testing

**Ruang Lingkup**: Testing kinerja dan skalabilitas

**Test Cases:**
1. Pemrosesan antrian dengan 1000 entri
2. Kampanye dengan 5000 target
3. Pembatasan laju dengan volume tinggi
4. Pemrosesan antrian bersamaan
5. Optimasi query database

**Metrik:**
- Waktu pemrosesan per entri antrian
- Throughput (entri per menit)
- Jumlah query database
- Penggunaan memori

---

### 11.5 Security Testing

**Ruang Lingkup**: Testing keamanan dan kontrol akses

**Test Cases:**
1. Kontrol akses (ir.model.access)
2. Record rules (jika ada)
3. Pencegahan SQL injection
4. Pencegahan XSS (jika ada UI input)
5. Perlindungan CSRF (jika ada API)

---

## 12. PERSYARATAN DOKUMENTASI

### 12.1 Dokumentasi Pengguna

**Konten:**
1. Gambaran umum modul
2. Panduan instalasi
3. Panduan konfigurasi
4. Cara membuat pemicu
5. Cara membuat aturan
6. Cara membuat kampanye
7. Cara memantau antrian
8. Cara melihat log
9. **Cara mengelola cron jobs via UI Odoo**
10. Panduan troubleshooting
11. FAQ

**Format**: File Markdown di folder `docs/`

---

### 12.2 Dokumentasi Developer

**Konten:**
1. Gambaran umum arsitektur
2. Referensi model
3. Referensi API (jika ada)
4. Titik ekstensi
5. Panduan kustomisasi
6. Contoh kode

**Format**: Komentar inline code + file markdown terpisah

---

### 12.3 Dokumentasi API (Jika Ada)

**Konten:**
1. Daftar endpoint
2. Format request/response
3. Autentikasi
4. Kode error
5. Pembatasan laju

**Format**: Spesifikasi OpenAPI/Swagger

---

## 13. PENILAIAN RISIKO

### 13.1 Risiko Teknis

| Risiko | Probabilitas | Dampak | Mitigasi |
|--------|-------------|--------|----------|
| Masalah kinerja dengan volume besar | Sedang | Tinggi | Implementasi pemrosesan batch, optimasi query |
| Signal handlers menyebabkan slowdown | Sedang | Sedang | Pemrosesan async, sistem antrian |
| Pembatasan laju tidak efektif | Rendah | Sedang | Testing menyeluruh, monitoring |
| Masalah integrasi dengan modul lain | Sedang | Tinggi | Testing integrasi, mekanisme fallback |

### 13.2 Risiko Bisnis

| Risiko | Probabilitas | Dampak | Mitigasi |
|--------|-------------|--------|----------|
| Spam notifikasi | Rendah | Tinggi | Pembatasan laju, daftar hitam, kontrol pengguna |
| Notifikasi tidak terkirim | Sedang | Tinggi | Mekanisme ulang coba, logging, monitoring |
| Kebingungan pengguna dengan kompleksitas | Sedang | Sedang | UI yang intuitif, dokumentasi lengkap |

### 13.3 Risiko Operasional

| Risiko | Probabilitas | Dampak | Mitigasi |
|--------|-------------|--------|----------|
| Modul tidak dapat di-uninstall dengan bersih | Rendah | Sedang | Cleanup proper di uninstall hook |
| Korupsi data saat uninstall | Rendah | Tinggi | Backup sebelum uninstall, cascade proper |

---

## 14. TIMELINE DAN SUMBER DAYA

### 14.1 Timeline

**Total Durasi**: 12 minggu (3 bulan)

**Breakdown:**
- Phase 1-2: Foundation & Core Engine (4 minggu)
- Phase 3-4: Queue & Campaign (4 minggu)
- Phase 5-6: Integration & Logging (2 minggu)
- Phase 7-8: UI & Testing (2 minggu)

### 14.2 Kebutuhan Sumber Daya

**Pengembangan:**
- 1 Senior Odoo Developer (full-time)
- 1 Junior Odoo Developer (part-time, untuk testing)

**Pengujian:**
- 1 QA Engineer (part-time, di phase 7-8)

**Dokumentasi:**
- Technical Writer (part-time, di phase 8)

---

## 15. KRITERIA KEBERHASILAN

### 15.1 Kriteria Fungsional

- ✅ Semua use cases dapat dijalankan dengan sukses
- ✅ Notifikasi terkirim dengan akurat dan tepat waktu
- ✅ Pemrosesan antrian stabil dengan volume besar
- ✅ Kampanye dapat dijalankan tanpa error
- ✅ Logging lengkap dan akurat

### 15.2 Kriteria Kinerja

- ✅ Pemrosesan antrian: minimal 100 entri per menit
- ✅ Kampanye dengan 1000 target: selesai dalam 1 jam
- ✅ Response time UI: < 2 detik
- ✅ Query database: < 100ms per query

### 15.3 Kriteria Kualitas

- ✅ Code coverage: minimal 80%
- ✅ Zero critical bugs
- ✅ Dokumentasi lengkap
- ✅ User acceptance: minimal 90% satisfaction

---

## 16. PENGELOLAAN CRON JOBS DI ODOO UI

### 16.1 Cara Mengakses Scheduled Actions (Cron Jobs)

**Langkah-langkah:**
1. Login ke Odoo sebagai Administrator
2. Aktifkan Developer Mode:
   - Klik avatar di kanan atas → Preferences → Aktifkan "Developer Mode"
3. Buka menu Scheduled Actions:
   - Settings → Technical → Automation → Scheduled Actions
   - Atau: Apps → Technical → Automation → Scheduled Actions

### 16.2 Fitur yang Tersedia di UI

**Informasi Cron Job:**
- **Name**: Nama cron job (contoh: "SICANTIK: Process Notification Queue")
- **Model**: Model yang dieksekusi (contoh: "sicantik.notification.queue")
- **Method**: Method yang dipanggil (contoh: "cron_process_queue")
- **Interval**: Frekuensi eksekusi (contoh: "1 minutes", "1 days")
- **Next Run**: Waktu eksekusi berikutnya
- **Last Run**: Waktu eksekusi terakhir
- **Active**: Status aktif/nonaktif (toggle)
- **Priority**: Prioritas eksekusi (0-100)

**Aksi yang Dapat Dilakukan:**
- **Enable/Disable**: Toggle kolom "Active" untuk mengaktifkan/menonaktifkan cron job
- **Run Manually**: Buka form cron job → klik "Run Manually" atau "Execute Now" untuk test tanpa menunggu jadwal
- **Edit**: Buka form cron job → ubah konfigurasi (interval, priority, code, active status)
- **View Logs**: Buka form cron job → tab "Logs" atau "View Logs" untuk melihat history eksekusi dan error

### 16.3 Cara Memantau Eksekusi Cron Jobs

**1. Via UI - Scheduled Actions:**
- Lihat kolom "Last Run" untuk mengetahui kapan terakhir dieksekusi
- Lihat kolom "Next Run" untuk mengetahui kapan akan dieksekusi berikutnya
- Klik "View Logs" untuk melihat detail eksekusi

**2. Via Logs Odoo:**
- Settings → Technical → Logging
- Filter berdasarkan model atau method
- Search: nama method cron (contoh: "cron_process_queue")

**3. Via Database (Advanced):**
```sql
SELECT name, active, interval_number, interval_type, 
       lastcall, nextcall, priority 
FROM ir_cron 
WHERE name LIKE 'SICANTIK%'
ORDER BY priority DESC, name;
```

### 16.4 Best Practices untuk Cron Jobs

**Naming Convention:**
- Gunakan prefix: "SICANTIK: [Deskripsi]"
- Contoh: "SICANTIK: Process Notification Queue"

**Priority:**
- Rendah (5-10): Background tasks, cleanup
- Sedang (15-20): Regular sync, notifications
- Tinggi (25-30): Critical tasks, urgent notifications

**Interval:**
- Minutes: Pemrosesan antrian (sering dieksekusi)
- Hours: Sync data berkala
- Days: Tugas harian (reporting, cleanup)

**Error Handling:**
- Selalu gunakan try-except dalam method cron
- Log error dengan detail yang jelas
- Jangan biarkan error menghentikan eksekusi cron berikutnya

### 16.5 Cron Jobs yang Akan Dibuat untuk Modul Notification Automation

**1. Process Notification Queue (Setiap 1 menit):**
```xml
<record id="cron_process_notification_queue" model="ir.cron">
    <field name="name">SICANTIK: Process Notification Queue</field>
    <field name="model_id" ref="model_sicantik_notification_queue"/>
    <field name="state">code</field>
    <field name="code">model.cron_process_queue()</field>
    <field name="interval_number">1</field>
    <field name="interval_type">minutes</field>
    <field name="active" eval="True"/>
    <field name="priority">20</field>
</record>
```
- **Priority**: 20 (sedang-tinggi)
- **Interval**: 1 minutes
- **Tujuan**: Memproses antrian notifikasi yang pending
- **Method**: `model.cron_process_queue()`

**2. Process Campaign Batch (Setiap 5 menit):**
```xml
<record id="cron_process_campaign_batch" model="ir.cron">
    <field name="name">SICANTIK: Process Campaign Batch</field>
    <field name="model_id" ref="model_sicantik_notification_campaign"/>
    <field name="state">code</field>
    <field name="code">model.cron_process_campaign_batch()</field>
    <field name="interval_number">5</field>
    <field name="interval_type">minutes</field>
    <field name="active" eval="True"/>
    <field name="priority">15</field>
</record>
```
- **Priority**: 15 (sedang)
- **Interval**: 5 minutes
- **Tujuan**: Memproses batch kampanye massal
- **Method**: `model.cron_process_campaign_batch()`

**3. Cleanup Old Logs (Setiap hari jam 02:00):**
```xml
<record id="cron_cleanup_notification_logs" model="ir.cron">
    <field name="name">SICANTIK: Cleanup Old Notification Logs</field>
    <field name="model_id" ref="model_sicantik_notification_log"/>
    <field name="state">code</field>
    <field name="code">model.cron_cleanup_old_logs()</field>
    <field name="interval_number">1</field>
    <field name="interval_type">days</field>
    <field name="active" eval="True"/>
    <field name="priority">5</field>
</record>
```
- **Priority**: 5 (rendah)
- **Interval**: 1 days
- **Tujuan**: Membersihkan log lama (> 90 hari)
- **Method**: `model.cron_cleanup_old_logs()`

### 16.6 Monitoring dan Troubleshooting Cron Jobs

**Monitoring via UI:**

Setelah modul terinstall, semua cron jobs dapat dimonitor melalui:
- Settings → Technical → Automation → Scheduled Actions
- Filter berdasarkan nama: "SICANTIK: Process Notification Queue", dll
- Lihat status "Last Run" dan "Next Run"
- Klik "Run Manually" untuk test
- Lihat "View Logs" untuk detail eksekusi

**Troubleshooting:**

**Cron tidak berjalan:**
- Cek status "Active" = True di UI
- Cek "Next Run" sudah terlewati
- Cek log untuk error
- Pastikan Odoo worker berjalan

**Cron error:**
- Buka form cron → tab "Logs"
- Lihat error message
- Perbaiki method yang dipanggil
- Test manual dengan "Run Manually"

**Cron terlalu lambat:**
- Cek priority (tingkatkan jika perlu)
- Optimasi method yang dipanggil
- Pertimbangkan pemrosesan batch

---

## 17. LANGKAH SELANJUTNYA

1. **Review Dokumen Perencanaan** dengan stakeholder
2. **Approve Timeline** dan alokasi sumber daya
3. **Setup Development Environment**
4. **Mulai Phase 1**: Foundation
5. **Weekly Progress Review**

---

**Dokumen ini adalah living document dan akan di-update sesuai perkembangan implementasi.**

**Versi**: 1.0.0  
**Terakhir Diupdate**: 28 November 2025  
**Status**: Ready for Implementation

