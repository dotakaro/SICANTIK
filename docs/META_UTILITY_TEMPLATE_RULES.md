# Aturan Meta WhatsApp Business API untuk Template Kategori Utility

**Tanggal:** 28 November 2025  
**Status:** Dokumentasi Resmi  
**Kategori:** Utility Template Guidelines

---

## Tujuan

Dokumen ini menjelaskan aturan Meta WhatsApp Business API untuk template kategori **Utility**, yang memastikan template tidak akan diubah otomatis ke kategori **Marketing** oleh Meta.

---

## Mengapa Penting?

Meta memiliki kebijakan otomatis untuk mengubah kategori template dari Utility ke Marketing jika template tidak mengikuti aturan. Template kategori Marketing memerlukan payment method yang lebih ketat dan dapat menyebabkan error 131042 (Business eligibility payment issue).

---

## Aturan Nama Template

### Format yang Benar

- ✅ **Huruf kecil**: Semua karakter harus huruf kecil
- ✅ **Garis bawah sebagai pemisah**: Gunakan `_` untuk memisahkan kata
- ✅ **Tanpa spasi**: Tidak boleh ada spasi dalam nama template
- ✅ **Tanpa karakter khusus**: Hindari karakter seperti `-`, `.`, `@`, dll

### Contoh Nama Template yang Benar

```
izin_selesai_diproses
izin_update_status
izin_dokumen_baru
izin_perlu_approval
izin_reminder
izin_peringatan_berlaku
izin_perpanjangan_disetujui
```

### Contoh Nama Template yang Salah

```
❌ Izin Selesai Diproses  (ada spasi dan huruf kapital)
❌ izin-selesai-diproses  (menggunakan dash)
❌ izin.selesai.diproses  (menggunakan titik)
❌ Izin_Selesai_Diproses  (huruf kapital)
```

---

## Aturan Isi Template (Body)

### Kategori Utility: Definisi Meta

Template Utility harus berisi informasi tentang:
- ✅ **Transaksi spesifik**: Konfirmasi pesanan, update status transaksi/perizinan
- ✅ **Akun**: Update status akun/perizinan, perubahan informasi
- ✅ **Permintaan pelanggan**: Respons terhadap permintaan spesifik dari pelanggan

### Yang HARUS Ada (Utility-Compliant)

1. **Informasi Faktual tentang Transaksi**
   - Nomor transaksi/perizinan
   - Tanggal transaksi
   - Status transaksi
   - Detail spesifik tentang transaksi

2. **Bahasa Formal dan Profesional**
   - Gunakan "Kepada" bukan "Yth." (lebih formal)
   - Hindari bahasa kasual atau promosional
   - Gunakan kalimat informatif, bukan ajakan

3. **Detail Transaksi Spesifik**
   - Nomor izin/perizinan
   - Tanggal spesifik
   - Status yang jelas
   - Informasi kontak (jika diperlukan)

4. **Footer dengan Nama Instansi**
   - Sertakan nama instansi di footer
   - Ini menunjukkan bahwa ini adalah informasi resmi, bukan promosi

### Yang HARUS Dihindari (Akan Diubah ke Marketing)

1. **Emoji Marketing**
   - ❌ 🔔 (notifikasi)
   - ❌ ⏰ (reminder)
   - ❌ 🔐 (approval)
   - ❌ 📋, 👤, 📄 (dekoratif)
   - ❌ ✅ (checklist promosional)

2. **Ajakan Bertindak Promosional**
   - ❌ "Silakan ambil..." (untuk customer)
   - ❌ "Segera lakukan..." (ajakan promosional)
   - ❌ "Jangan lewatkan..." (promosi)
   - ✅ "Dokumen dapat diambil..." (informasi faktual)
   - ✅ "Silakan akses..." (untuk staff internal - OK)

3. **Konten Promosional**
   - ❌ "Selamat!" (ekspresi promosional)
   - ❌ "Jangan lewatkan kesempatan"
   - ❌ "Dapatkan diskon"
   - ❌ "Promo khusus untuk Anda"

4. **Konten Umum/Tidak Spesifik**
   - ❌ "Terima kasih atas kepercayaan Anda"
   - ❌ "Kami menghargai bisnis Anda"
   - ✅ Fokus pada informasi transaksi spesifik

---

## Perbandingan: Utility vs Marketing

### Template Utility (Benar)

```
Kepada {{partner_name}},

Permohonan izin Anda telah selesai diproses.

Nomor Izin: {{permit_number}}
Jenis Izin: {{permit_type}}
Status: {{status}}
Tanggal Selesai: {{completion_date}}

Dokumen izin dapat diambil di kantor DPMPTSP Kabupaten Karo.

Terima kasih.
DPMPTSP Kabupaten Karo
```

**Ciri-ciri:**
- ✅ Fokus pada informasi faktual tentang transaksi
- ✅ Tidak ada emoji
- ✅ Tidak ada ajakan promosional
- ✅ Detail transaksi spesifik
- ✅ Footer dengan nama instansi

### Template Marketing (Salah - Akan Diubah oleh Meta)

```
🔔 Notifikasi Dokumen Baru

Yth. {{partner_name}},

Selamat! Izin Anda telah selesai diproses.

Nomor Izin: {{permit_number}}

Silakan ambil dokumen izin di kantor DPMPTSP Kabupaten Karo.

Jangan lewatkan kesempatan untuk menggunakan layanan kami!

Terima kasih.
```

**Ciri-ciri:**
- ❌ Menggunakan emoji marketing (🔔)
- ❌ Menggunakan "Selamat!" (ekspresi promosional)
- ❌ Menggunakan "Silakan ambil" (ajakan bertindak)
- ❌ Menggunakan "Jangan lewatkan kesempatan" (promosi)
- ❌ Tidak ada detail transaksi spesifik

---

## Checklist Template Utility-Compliant

Sebelum membuat template di Meta Business Manager, pastikan:

- [ ] **Nama Template:**
  - [ ] Menggunakan huruf kecil
  - [ ] Menggunakan garis bawah sebagai pemisah
  - [ ] Tidak ada spasi
  - [ ] Tidak ada karakter khusus

- [ ] **Isi Template:**
  - [ ] Tidak mengandung emoji marketing
  - [ ] Fokus pada informasi faktual tentang transaksi/perizinan
  - [ ] Tidak mengandung ajakan promosional (kecuali untuk staff internal)
  - [ ] Sertakan detail transaksi spesifik (nomor, tanggal, status)
  - [ ] Menggunakan bahasa formal dan profesional
  - [ ] Footer dengan nama instansi

- [ ] **Kategori Template:**
  - [ ] Pilih kategori **Utility** saat membuat template di Meta Business Manager
  - [ ] Jangan pilih **Marketing** atau **Authentication**

---

## Contoh Template Utility-Compliant

### Template 1: izin_selesai_diproses

**Kategori:** Utility (Transaction Update)

**Body:**
```
Kepada {{partner_name}},

Permohonan izin Anda telah selesai diproses.

Nomor Izin: {{permit_number}}
Jenis Izin: {{permit_type}}
Status: {{status}}
Tanggal Selesai: {{completion_date}}

Dokumen izin dapat diambil di kantor DPMPTSP Kabupaten Karo.

Terima kasih.
DPMPTSP Kabupaten Karo
```

**Parameter:** `["partner_name", "permit_number", "permit_type", "status", "completion_date"]`

**Mengapa Utility-Compliant:**
- ✅ Informasi faktual tentang transaksi spesifik
- ✅ Detail lengkap (nomor, jenis, status, tanggal)
- ✅ Tidak ada emoji
- ✅ Tidak ada ajakan promosional
- ✅ Footer dengan nama instansi

### Template 2: izin_update_status

**Kategori:** Utility (Account Update)

**Body:**
```
Kepada {{partner_name}},

Status permohonan izin Anda telah diperbarui.

Nomor: {{permit_number}}
Jenis: {{permit_type}}
Status Baru: {{new_status}}
Tanggal Update: {{update_date}}
Alasan: {{update_reason}}

Untuk informasi lebih lanjut, hubungi DPMPTSP Kabupaten Karo.

Terima kasih.
DPMPTSP Kabupaten Karo
```

**Parameter:** `["partner_name", "permit_number", "permit_type", "new_status", "update_date", "update_reason"]`

**Mengapa Utility-Compliant:**
- ✅ Update status akun/perizinan
- ✅ Detail lengkap tentang perubahan
- ✅ Informasi kontak untuk follow-up
- ✅ Tidak ada emoji atau promosi

### Template 3: izin_dokumen_baru

**Kategori:** Utility (Customer Request)

**Body:**
```
Kepada {{staff_name}},

Ada dokumen baru yang memerlukan tindakan Anda.

Jenis Izin: {{permit_type}}
Pemohon: {{applicant_name}}
Nomor Pendaftaran: {{registration_id}}
Tanggal: {{document_date}}

Silakan akses dashboard untuk memproses dokumen.

DPMPTSP Kabupaten Karo
```

**Parameter:** `["staff_name", "permit_type", "applicant_name", "registration_id", "document_date"]`

**Catatan:** Template ini untuk staff internal, jadi "Silakan akses" diperbolehkan karena ini instruksi internal, bukan promosi ke customer.

---

## Proses Persetujuan Template di Meta

### Langkah-Langkah

1. **Buat Template di Meta Business Manager**
   - Pilih kategori **Utility**
   - Gunakan nama dengan format yang benar (huruf kecil, garis bawah)
   - Isi body sesuai aturan Utility

2. **Submit untuk Review**
   - Meta akan meninjau template dalam 1-3 hari kerja
   - Meta akan menentukan kategori akhir

3. **Jika Diubah ke Marketing**
   - Meta akan mengubah kategori jika template tidak sesuai aturan Utility
   - Anda akan menerima notifikasi tentang perubahan kategori
   - Anda dapat mengajukan appeal dalam 60 hari

### Mengajukan Appeal

Jika template Anda diubah ke Marketing padahal seharusnya Utility:

1. Login ke Meta Business Manager
2. Buka **Business Support Home**
3. Pilih template yang ingin di-appeal
4. Klik **Request Review**
5. Jelaskan mengapa template seharusnya Utility (bukan Marketing)
6. Tunggu review dari Meta (biasanya 1-3 hari kerja)

---

## Best Practices

### 1. Gunakan Detail Transaksi Spesifik

**Benar:**
```
Nomor Izin: 503/DPMPTSP/2024/001
Tanggal Selesai: 15 November 2024
Status: Aktif
```

**Salah:**
```
Izin Anda telah selesai.
Silakan ambil dokumen.
```

### 2. Hindari Bahasa Promosional

**Benar:**
```
Dokumen izin dapat diambil di kantor DPMPTSP Kabupaten Karo.
```

**Salah:**
```
Silakan ambil dokumen izin Anda sekarang!
Jangan lewatkan kesempatan ini!
```

### 3. Gunakan Footer Instansi

**Benar:**
```
Terima kasih.
DPMPTSP Kabupaten Karo
```

**Salah:**
```
Terima kasih atas kepercayaan Anda.
Kami menghargai bisnis Anda.
```

### 4. Fokus pada Informasi Faktual

**Benar:**
```
Status permohonan izin Anda telah diperbarui.
Status Baru: Disetujui
Tanggal Update: 15 November 2024
```

**Salah:**
```
Kabar baik! Izin Anda telah disetujui!
Selamat! Anda telah mendapatkan izin!
```

---

## Troubleshooting

### Template Diubah ke Marketing oleh Meta

**Penyebab:**
- Template mengandung konten promosional
- Template tidak fokus pada transaksi spesifik
- Template menggunakan emoji marketing
- Template menggunakan ajakan bertindak promosional

**Solusi:**
1. Review template dan identifikasi bagian yang promosional
2. Hapus emoji marketing
3. Ganti ajakan promosional dengan informasi faktual
4. Tambahkan detail transaksi spesifik
5. Buat template baru dengan kategori Utility
6. Submit untuk review ulang

### Error 131042 Setelah Template Diubah ke Marketing

**Penyebab:**
- Template diubah ke Marketing oleh Meta
- Marketing template memerlukan payment method yang lebih ketat

**Solusi:**
1. Perbaiki payment method di Meta Business Manager
2. Atau buat template baru dengan kategori Utility yang benar
3. Gunakan template baru untuk pengiriman

---

## Referensi

- [Meta WhatsApp Business API - Template Categories](https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates)
- [Meta Template Guidelines](https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates/guidelines)
- [Meta Template Appeal Process](https://www.facebook.com/business/help/2055875911147364)

---

## Template yang Sudah Dibuat

Template berikut sudah dibuat dengan prefix `izin_` dan mengikuti aturan Utility:

1. `izin_selesai_diproses` - Izin Selesai Diproses
2. `izin_update_status` - Update Status Perizinan
3. `izin_dokumen_baru` - Dokumen Baru untuk Tandatangan
4. `izin_perlu_approval` - Dokumen Perlu Approval
5. `izin_reminder` - Reminder Dokumen Pending
6. `izin_peringatan_berlaku` - Peringatan Masa Berlaku Izin
7. `izin_perpanjangan_disetujui` - Perpanjangan Izin Disetujui

Semua template ini dapat ditemukan di:
- **Odoo:** WhatsApp → Konfigurasi → Master Templates
- **Meta Business Manager:** WhatsApp → Message Templates

---

**Dokumen ini akan diperbarui jika ada perubahan aturan dari Meta.**

