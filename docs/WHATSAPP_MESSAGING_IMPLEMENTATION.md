# 📱 Implementasi WhatsApp Messaging di SICANTIK

**Tanggal:** 28 November 2025  
**Status:** ✅ Ready for Implementation  
**Provider:** Meta, Watzap.id, Fonnte

---

## 🎯 Overview

Sistem messaging WhatsApp di SICANTIK menggunakan arsitektur **multi-provider** dengan routing otomatis berdasarkan:
- Status opt-in partner (untuk Meta)
- Ketersediaan provider
- Fallback otomatis jika provider utama gagal

---

## 🏗️ Arsitektur Messaging

```
┌─────────────────────────────────────────────────────────────┐
│              Event Triggers (Odoo Models)                   │
│  - sicantik.permit.write() → status change                 │
│  - sicantik.document.write() → state change                 │
│  - Cron jobs → scheduled notifications                     │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│         WhatsApp Dispatcher (sicantik.whatsapp.dispatcher)  │
│  1. determine_provider() → pilih provider                  │
│  2. send_template_message() → kirim pesan                   │
│  3. Parameter conversion → format provider                 │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│              Provider Implementation                        │
│  ├─ Meta (Odoo Enterprise)                                 │
│  ├─ Watzap.id (API Gateway)                                │
│  └─ Fonnte (API Gateway)                                   │
└─────────────────────────────────────────────────────────────┘
```

---

## 📋 7 Skenario Notifikasi

### 1. **Izin Selesai Diproses** (`permit_ready`)
- **Trigger:** `sicantik.permit.write()` → `status == 'active'` dan ada `permit_number`
- **Recipient:** Pemohon izin (`permit.partner_id`)
- **Template Key:** `permit_ready`
- **Status:** ✅ Sudah diimplementasikan (menggunakan Odoo Enterprise)

### 2. **Dokumen Baru untuk Tandatangan** (`document_pending`)
- **Trigger:** `sicantik.document.write()` → `state == 'pending_signature'`
- **Recipient:** Staff DPMPTSP (semua user internal dengan nomor WhatsApp)
- **Template Key:** `document_pending`
- **Status:** ✅ Sudah diimplementasikan (menggunakan Odoo Enterprise)

### 3. **Dokumen Perlu Approval** (`approval_required`)
- **Trigger:** `sicantik.document.write()` → `state == 'pending_signature'` dan `requires_approval == True`
- **Recipient:** Pejabat berwenang (dari workflow)
- **Template Key:** `approval_required`
- **Status:** ✅ Sudah diimplementasikan (menggunakan Odoo Enterprise)

### 4. **Update Status Perizinan** (`status_update`)
- **Trigger:** `sicantik.permit.write()` → `status` berubah
- **Recipient:** Pemohon & Staff terkait
- **Template Key:** `status_update`
- **Status:** ✅ Sudah diimplementasikan (menggunakan Odoo Enterprise)

### 5. **Reminder Dokumen Pending** (`reminder`)
- **Trigger:** Cron job (daily 10:00) → Dokumen pending > 24 jam
- **Recipient:** Staff yang bertanggung jawab
- **Template Key:** `reminder`
- **Status:** ✅ Sudah diimplementasikan (menggunakan Odoo Enterprise)

### 6. **Peringatan Masa Berlaku Izin** (`permit_expiry_warning`)
- **Trigger:** Cron job (daily 09:00) → Izin akan habis dalam 90/60/30/7 hari
- **Recipient:** Pemohon izin
- **Template Key:** `permit_expiry_warning`
- **Status:** ✅ Sudah diimplementasikan (menggunakan Odoo Enterprise)

### 7. **Perpanjangan Izin Disetujui** (`permit_renewal_approved`)
- **Trigger:** `sicantik.permit.write()` → `is_renewal == True` dan `status == 'active'`
- **Recipient:** Pemohon izin
- **Template Key:** `permit_renewal_approved`
- **Status:** ✅ Sudah diimplementasikan (menggunakan Odoo Enterprise)

---

## 🔄 Migrasi ke Multi-Provider Dispatcher

### Status Saat Ini

**Implementasi Lama (Odoo Enterprise Only):**
- Menggunakan `whatsapp.composer` langsung
- Hanya mendukung Meta WhatsApp Business API
- Memerlukan opt-in untuk setiap partner
- Tidak ada fallback jika Meta tidak tersedia

**Implementasi Baru (Multi-Provider):**
- Menggunakan `sicantik.whatsapp.dispatcher`
- Mendukung Meta, Watzap.id, dan Fonnte
- Routing otomatis berdasarkan opt-in status
- Fallback otomatis ke provider gateway

### Langkah Migrasi

#### Step 1: Update Permit Notifications

**File:** `addons_odoo/sicantik_whatsapp/models/sicantik_permit_inherit.py`

**Sebelumnya:**
```python
# Menggunakan whatsapp.composer langsung
composer = self.env['whatsapp.composer'].create({
    'res_model': self._name,
    'res_ids': record.ids,
    'wa_template_id': template.id,
})
composer._send_whatsapp_template(force_send_by_cron=True)
```

**Sekarang:**
```python
# Menggunakan dispatcher untuk routing otomatis
dispatcher = self.env['sicantik.whatsapp.dispatcher']

# Prepare context values
context_values = {
    'partner_name': record.applicant_name,
    'permit_number': record.permit_number,
    'permit_type': record.permit_type_id.name if record.permit_type_id else '',
    'issue_date': record.issue_date.strftime('%d-%m-%Y') if record.issue_date else '',
    'download_link': f'https://sicantik.dotakaro.com/download/{record.registration_id}',
}

# Kirim via dispatcher
result = dispatcher.send_template_message(
    template_key='permit_ready',
    partner_id=record.partner_id.id,
    context_values=context_values,
    permit_id=record.id
)

if result['success']:
    _logger.info(f'✅ Notifikasi dikirim via {result["provider"]}')
else:
    _logger.error(f'❌ Gagal kirim: {result.get("error")}')
```

#### Step 2: Update Document Notifications

**File:** `addons_odoo/sicantik_whatsapp/models/sicantik_document_inherit.py`

**Sama seperti Step 1**, ganti penggunaan `whatsapp.composer` dengan `dispatcher.send_template_message()`.

#### Step 3: Konfigurasi Template Master

**File:** `addons_odoo/sicantik_whatsapp/data/master_templates_data.xml`

Pastikan semua template master sudah dikonfigurasi dengan:
- Template key yang benar
- Parameter list yang sesuai
- Mapping ke template ID masing-masing provider

**Contoh:**
```xml
<record id="master_template_permit_ready" model="sicantik.whatsapp.template.master">
    <field name="template_key">permit_ready</field>
    <field name="name">Izin Selesai Diproses</field>
    <field name="body_preview">Yth. {{partner_name}}, izin {{permit_type}} Anda dengan nomor {{permit_number}} telah selesai diproses.</field>
    <field name="parameter_list">["partner_name", "permit_type", "permit_number", "issue_date", "download_link"]</field>
    
    <!-- Meta -->
    <field name="meta_template_name">izin_selesai_diproses</field>
    
    <!-- Watzap -->
    <field name="watzap_template_id">template_watzap_001</field>
    <field name="watzap_status">configured</field>
    
    <!-- Fonnte -->
    <field name="fonnte_template_id">template_fonnte_001</field>
    <field name="fonnte_status">configured</field>
</record>
```

#### Step 4: Setup Provider di Settings

1. Buka **Settings → General Settings → WhatsApp Notifications**
2. Pilih **Default Provider** (biasanya Fonnte atau Watzap untuk fallback)
3. Pastikan provider sudah dikonfigurasi dengan lengkap:
   - Token API sudah diisi
   - Test connection berhasil
   - Status = "Configured"

---

## 🔧 Cara Menggunakan Dispatcher

### Contoh 1: Kirim Notifikasi Izin Selesai

```python
# Di method sicantik.permit
dispatcher = self.env['sicantik.whatsapp.dispatcher']

context_values = {
    'partner_name': self.applicant_name,
    'permit_type': self.permit_type_id.name,
    'permit_number': self.permit_number,
    'issue_date': self.issue_date.strftime('%d-%m-%Y'),
    'download_link': f'https://sicantik.dotakaro.com/download/{self.registration_id}',
}

result = dispatcher.send_template_message(
    template_key='permit_ready',
    partner_id=self.partner_id.id,
    context_values=context_values,
    permit_id=self.id
)
```

### Contoh 2: Kirim Notifikasi Dokumen Baru

```python
# Di method sicantik.document
dispatcher = self.env['sicantik.whatsapp.dispatcher']

# Untuk setiap staff
for staff_user in staff_users:
    context_values = {
        'jumlah': str(jumlah_pending),
        'jenis_izin': self.permit_type_id.name if self.permit_type_id else '',
        'nama_pemohon': self.permit_id.applicant_name if self.permit_id else '',
        'pendaftaran_id': self.permit_id.registration_id if self.permit_id else '',
        'link_dashboard': 'https://sicantik.dotakaro.com/dashboard',
    }
    
    result = dispatcher.send_template_message(
        template_key='document_pending',
        partner_id=staff_user.partner_id.id,
        context_values=context_values
    )
```

### Contoh 3: Kirim Notifikasi Expiry Warning

```python
# Di cron job
dispatcher = self.env['sicantik.whatsapp.dispatcher']

for permit in expiring_permits:
    context_values = {
        'partner_name': permit.applicant_name,
        'permit_type': permit.permit_type_id.name,
        'permit_number': permit.permit_number,
        'expiry_date': permit.expiry_date.strftime('%d-%m-%Y'),
        'remaining_days': str(sisa_hari),
        'renewal_link': f'https://sicantik.dotakaro.com/renewal/{permit.registration_id}',
        'contact_dpmptsp': '0628-20XXX',
    }
    
    result = dispatcher.send_template_message(
        template_key='permit_expiry_warning',
        partner_id=permit.partner_id.id,
        context_values=context_values,
        permit_id=permit.id
    )
```

---

## 📊 Template Master Configuration

### Daftar Template Master

| Template Key | Nama | Parameter | Status |
|--------------|------|-----------|--------|
| `permit_ready` | Izin Selesai Diproses | partner_name, permit_type, permit_number, issue_date, download_link | ✅ |
| `document_pending` | Dokumen Baru untuk Tandatangan | jumlah, jenis_izin, nama_pemohon, pendaftaran_id, link_dashboard | ✅ |
| `approval_required` | Dokumen Perlu Approval | nama_pejabat, jenis_izin, nama_pemohon, permit_number, approval_link | ✅ |
| `status_update` | Update Status Perizinan | pendaftaran_id, status_lama, status_baru, timestamp, detail_link | ✅ |
| `reminder` | Reminder Dokumen Pending | jumlah, jenis_izin, nama_pemohon, waktu_pending, link_dashboard | ✅ |
| `permit_expiry_warning` | Peringatan Masa Berlaku | partner_name, permit_type, permit_number, expiry_date, remaining_days, renewal_link, contact_dpmptsp | ✅ |
| `permit_renewal_approved` | Perpanjangan Disetujui | partner_name, permit_type, permit_number_new, start_date, expiry_date, validity_period, download_link | ✅ |

### Parameter Mapping

**Format Generik (Master Template):**
```
{{partner_name}}, izin {{permit_type}} Anda dengan nomor {{permit_number}} telah selesai diproses.
```

**Konversi ke Meta:**
```
{{1}}, izin {{2}} Anda dengan nomor {{3}} telah selesai diproses.
```

**Konversi ke Watzap:**
```json
{
  "1": "John Doe",
  "2": "SIUP",
  "3": "503.517-0186-IX-SIUP-DPM-PPTSP-2018"
}
```

**Konversi ke Fonnte:**
```json
{
  "var1": "John Doe",
  "var2": "SIUP",
  "var3": "503.517-0186-IX-SIUP-DPM-PPTSP-2018"
}
```

---

## 🚀 Langkah Implementasi

### Phase 1: Setup Provider & Template (✅ DONE)

- [x] Konfigurasi provider Fonnte
- [x] Test connection Fonnte
- [x] Setup template master
- [x] Mapping template ke provider

### Phase 2: Migrasi Implementasi (🔄 IN PROGRESS)

- [ ] Update `sicantik_permit_inherit.py` untuk menggunakan dispatcher
- [ ] Update `sicantik_document_inherit.py` untuk menggunakan dispatcher
- [ ] Update cron jobs untuk menggunakan dispatcher
- [ ] Test semua skenario notifikasi

### Phase 3: Testing & Validation

- [ ] Test dengan provider Fonnte
- [ ] Test dengan provider Watzap (jika tersedia)
- [ ] Test fallback mechanism
- [ ] Test error handling

### Phase 4: Production Deployment

- [ ] Deploy ke production
- [ ] Monitor delivery rate
- [ ] Optimize berdasarkan feedback

---

## 📝 Checklist Implementasi

### Pre-Implementation

- [x] Provider Fonnte sudah dikonfigurasi
- [x] Test connection Fonnte berhasil
- [x] Template master sudah dibuat
- [ ] Template Fonnte sudah dibuat di dashboard Fonnte
- [ ] Template ID Fonnte sudah di-mapping ke template master

### Implementation

- [ ] Update `_kirim_notifikasi_izin_selesai()` untuk menggunakan dispatcher
- [ ] Update `_kirim_notifikasi_update_status()` untuk menggunakan dispatcher
- [ ] Update `_kirim_notifikasi_perpanjangan_disetujui()` untuk menggunakan dispatcher
- [ ] Update `_kirim_notifikasi_dokumen_baru()` untuk menggunakan dispatcher
- [ ] Update `_kirim_notifikasi_perlu_approval()` untuk menggunakan dispatcher
- [ ] Update `cron_check_expiring_permits()` untuk menggunakan dispatcher
- [ ] Update `cron_reminder_dokumen_pending()` untuk menggunakan dispatcher

### Post-Implementation

- [ ] Test semua skenario notifikasi
- [ ] Monitor log untuk error
- [ ] Verifikasi pesan terkirim ke WhatsApp
- [ ] Update dokumentasi jika ada perubahan

---

## 🔍 Troubleshooting

### Pesan tidak terkirim

1. **Cek Provider Configuration:**
   - Pastikan provider sudah dikonfigurasi dengan lengkap
   - Pastikan token API valid (test connection berhasil)
   - Pastikan provider status = "Configured"

2. **Cek Template Master:**
   - Pastikan template key sudah benar
   - Pastikan template sudah dikonfigurasi untuk provider yang digunakan
   - Pastikan parameter list sesuai dengan context_values

3. **Cek Partner:**
   - Pastikan partner memiliki nomor WhatsApp
   - Pastikan nomor sudah dinormalisasi dengan benar

4. **Cek Log:**
   - Cek log Odoo untuk error detail
   - Cek response dari provider API

### Error "Template tidak ditemukan"

- Pastikan template master sudah dibuat dengan template_key yang benar
- Pastikan template sudah dikonfigurasi untuk provider yang digunakan
- Pastikan template ID/name sudah benar di mapping provider

### Error "Provider tidak ditemukan"

- Pastikan default provider sudah di-set di Settings
- Pastikan provider aktif dan status = "Configured"
- Pastikan provider memiliki credential yang lengkap

---

## 📚 Referensi

- [Multi-Provider Setup Guide](../addons_odoo/sicantik_whatsapp/docs/MULTI_PROVIDER_SETUP.md)
- [Opt-In Strategy](../addons_odoo/sicantik_whatsapp/docs/WHATSAPP_OPT_IN_STRATEGY.md)
- [WhatsApp Integration Guide](./WHATSAPP_INTEGRATION_GUIDE.md)
- [Fonnte API Documentation](https://docs.fonnte.com)

---

**Last Updated:** 28 November 2025  
**Author:** SICANTIK Development Team

