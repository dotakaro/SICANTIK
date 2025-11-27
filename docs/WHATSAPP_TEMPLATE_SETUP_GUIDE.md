# 📋 Panduan Setup Template WhatsApp untuk Fonnte

**Tanggal:** 28 November 2025  
**Provider:** Fonnte  
**Status:** Setup Required

---

## 🎯 Overview

Setelah migrasi ke multi-provider dispatcher, semua notifikasi WhatsApp sekarang menggunakan template master yang kompatibel dengan semua provider. Untuk menggunakan Fonnte, Anda perlu membuat template di dashboard Fonnte dan mengupdate mapping di Odoo.

---

## 📝 Daftar Template yang Perlu Dibuat di Fonnte

### 1. **izin_selesai_diproses** (permit_ready)

**Parameter:**
- `{{partner_name}}` - Nama pemohon
- `{{permit_number}}` - Nomor izin
- `{{permit_type}}` - Jenis izin
- `{{status}}` - Status izin

**Contoh Pesan:**
```
Yth. {{partner_name}},

Izin Anda dengan nomor {{permit_number}} telah selesai diproses.

Jenis Izin: {{permit_type}}
Status: {{status}}

Silakan ambil dokumen izin di kantor DPMPTSP Kabupaten Karo.

Terima kasih.
```

**Cara Setup di Fonnte:**
1. Login ke https://fonnte.com
2. Buka menu **Templates** atau **Template Message**
3. Klik **Buat Template Baru**
4. Isi:
   - **Nama Template:** `izin_selesai_diproses`
   - **Kategori:** Notifikasi
   - **Pesan:** Copy contoh pesan di atas
   - **Parameter:** Tambahkan 4 parameter sesuai list di atas
5. Simpan dan tunggu approval (jika diperlukan)
6. Copy **Template ID** dari dashboard Fonnte
7. Update di Odoo: **WhatsApp → Konfigurasi → Master Templates → Izin Selesai Diproses**
   - Isi field **Fonnte Template ID** dengan Template ID dari dashboard
   - Set **Fonnte Status** menjadi `configured`

---

### 2. **peringatan_masa_berlaku** (permit_reminder)

**Parameter:**
- `{{partner_name}}` - Nama pemohon
- `{{permit_number}}` - Nomor izin
- `{{permit_type}}` - Jenis izin
- `{{expiry_date}}` - Tanggal berakhir (format: DD-MM-YYYY)
- `{{days_remaining}}` - Sisa hari (angka)
- `{{renewal_link}}` - Link perpanjangan
- `{{contact_info}}` - Kontak DPMPTSP

**Contoh Pesan:**
```
Yth. {{partner_name}},

Izin Anda akan segera berakhir:

Nomor Izin: {{permit_number}}
Jenis Izin: {{permit_type}}
Masa Berlaku: {{expiry_date}}
Sisa: {{days_remaining}} hari

Segera lakukan perpanjangan:
{{renewal_link}}

Informasi: {{contact_info}}

Terima kasih.
```

**Cara Setup:** Sama seperti template 1, gunakan nama `peringatan_masa_berlaku`

---

### 3. **update_status** (status_update)

**Parameter:**
- `{{partner_name}}` - Nama pemohon
- `{{permit_number}}` - Nomor izin
- `{{permit_type}}` - Jenis izin
- `{{new_status}}` - Status baru
- `{{update_date}}` - Tanggal update (format: DD-MM-YYYY HH:MM)

**Contoh Pesan:**
```
Yth. {{partner_name}},

Status perizinan Anda telah diupdate:

Nomor: {{permit_number}}
Jenis: {{permit_type}}
Status Baru: {{new_status}}
Tanggal Update: {{update_date}}

Terima kasih.
```

**Cara Setup:** Sama seperti template 1, gunakan nama `update_status`

---

### 4. **perpanjangan_disetujui** (renewal_approved)

**Parameter:**
- `{{partner_name}}` - Nama pemohon
- `{{permit_number}}` - Nomor izin
- `{{permit_type}}` - Jenis izin
- `{{new_expiry_date}}` - Tanggal berakhir baru (format: DD-MM-YYYY)

**Contoh Pesan:**
```
Yth. {{partner_name}},

Selamat! Perpanjangan izin Anda telah disetujui.

Nomor Izin: {{permit_number}}
Jenis Izin: {{permit_type}}
Masa Berlaku Baru: s/d {{new_expiry_date}}

Dokumen perpanjangan dapat diambil di kantor DPMPTSP.

Terima kasih.
```

**Cara Setup:** Sama seperti template 1, gunakan nama `perpanjangan_disetujui`

---

### 5. **dokumen_baru** (document_pending)

**Parameter:**
- `{{jumlah}}` - Jumlah dokumen pending (angka)
- `{{jenis_izin}}` - Jenis izin
- `{{nama_pemohon}}` - Nama pemohon
- `{{pendaftaran_id}}` - ID pendaftaran
- `{{link_dashboard}}` - Link dashboard

**Contoh Pesan:**
```
🔔 Notifikasi Dokumen Baru

Ada {{jumlah}} dokumen menunggu tanda tangan digital:

1. {{jenis_izin}} - {{nama_pemohon}}
   No: {{pendaftaran_id}}

Silakan proses melalui dashboard:
🔗 {{link_dashboard}}

SICANTIK Companion
```

**Cara Setup:** Sama seperti template 1, gunakan nama `dokumen_baru`

---

### 6. **approval_required** (approval_required)

**Parameter:**
- `{{nama_pejabat}}` - Nama pejabat
- `{{jenis_izin}}` - Jenis izin
- `{{nama_pemohon}}` - Nama pemohon
- `{{permit_number}}` - Nomor surat
- `{{approval_link}}` - Link approval

**Contoh Pesan:**
```
🔐 Approval Required

Yth. {{nama_pejabat}},

Dokumen berikut memerlukan persetujuan Anda:
📋 {{jenis_izin}}
👤 Pemohon: {{nama_pemohon}}
📄 No: {{permit_number}}

Approve via:
🔗 {{approval_link}}

SICANTIK Companion
```

**Cara Setup:** Sama seperti template 1, gunakan nama `approval_required`

---

### 7. **reminder** (reminder)

**Parameter:**
- `{{jumlah}}` - Jumlah dokumen (angka)
- `{{jenis_izin}}` - Jenis izin
- `{{nama_pemohon}}` - Nama pemohon
- `{{waktu_pending}}` - Waktu pending (contoh: "2 hari" atau "5 jam")
- `{{link_dashboard}}` - Link dashboard

**Contoh Pesan:**
```
⏰ Reminder: Dokumen Pending

{{jumlah}} dokumen belum diproses:

- {{jenis_izin}} ({{nama_pemohon}})
  Pending sejak: {{waktu_pending}}

Segera proses: {{link_dashboard}}

SICANTIK Companion
```

**Cara Setup:** Sama seperti template 1, gunakan nama `reminder`

---

## 🔧 Update Template Master di Odoo

Setelah membuat template di dashboard Fonnte, update mapping di Odoo:

### Langkah-langkah:

1. **Buka Odoo → WhatsApp → Konfigurasi → Master Templates**

2. **Untuk setiap template master:**
   - Klik template yang ingin diupdate
   - Buka tab **Fonnte**
   - Isi **Fonnte Template ID** dengan Template ID dari dashboard Fonnte
   - Set **Fonnte Status** menjadi `configured`
   - Klik **Save**

3. **Verifikasi:**
   - Pastikan semua 7 template sudah memiliki **Fonnte Template ID**
   - Pastikan **Fonnte Status** = `configured` untuk semua template

---

## 📊 Checklist Setup Template

### Template Master di Odoo (✅ Sudah Ada)

- [x] `permit_ready` - Izin Selesai Diproses
- [x] `permit_reminder` - Peringatan Masa Berlaku Izin
- [x] `status_update` - Update Status Perizinan
- [x] `renewal_approved` - Perpanjangan Izin Disetujui
- [x] `document_pending` - Dokumen Baru untuk Tandatangan
- [x] `approval_required` - Dokumen Perlu Approval
- [x] `reminder` - Reminder Dokumen Pending

### Template di Dashboard Fonnte (⏳ Perlu Dibuat)

- [ ] `izin_selesai_diproses` - Buat di dashboard Fonnte
- [ ] `peringatan_masa_berlaku` - Buat di dashboard Fonnte
- [ ] `update_status` - Buat di dashboard Fonnte
- [ ] `perpanjangan_disetujui` - Buat di dashboard Fonnte
- [ ] `dokumen_baru` - Buat di dashboard Fonnte
- [ ] `approval_required` - Buat di dashboard Fonnte
- [ ] `reminder` - Buat di dashboard Fonnte

### Mapping Template ID di Odoo (⏳ Perlu Diupdate)

- [ ] Update `permit_ready` dengan Fonnte Template ID
- [ ] Update `permit_reminder` dengan Fonnte Template ID
- [ ] Update `status_update` dengan Fonnte Template ID
- [ ] Update `renewal_approved` dengan Fonnte Template ID
- [ ] Update `document_pending` dengan Fonnte Template ID
- [ ] Update `approval_required` dengan Fonnte Template ID
- [ ] Update `reminder` dengan Fonnte Template ID

---

## 🧪 Testing

Setelah semua template sudah dikonfigurasi:

1. **Test dengan Test Multi-Provider Wizard:**
   - Buka **WhatsApp → Konfigurasi → Test Multi-Provider**
   - Pilih partner dengan nomor WhatsApp
   - Pilih template key (misalnya `permit_ready`)
   - Klik **Run Test**
   - Verifikasi pesan terkirim ke WhatsApp

2. **Test dengan Trigger Aktual:**
   - Buat/update permit dengan status `active`
   - Verifikasi notifikasi terkirim ke WhatsApp
   - Cek log Odoo untuk melihat provider yang digunakan

---

## 📚 Referensi

- [Fonnte API Documentation](https://docs.fonnte.com)
- [WhatsApp Messaging Implementation Guide](./WHATSAPP_MESSAGING_IMPLEMENTATION.md)
- [Multi-Provider Setup Guide](../addons_odoo/sicantik_whatsapp/docs/MULTI_PROVIDER_SETUP.md)

---

**Last Updated:** 28 November 2025  
**Author:** SICANTIK Development Team

