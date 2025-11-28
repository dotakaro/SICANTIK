# Troubleshooting: WhatsApp Error 131042 - Business Eligibility Payment Issue

## Deskripsi Masalah

Error **131042: Business eligibility payment issue** terjadi ketika Meta WhatsApp Business API tidak dapat memproses pengiriman pesan template karena masalah terkait pembayaran di akun WhatsApp Business Anda.

### Gejala

- Template message gagal dikirim meskipun:
  - Nomor penerima sudah opt-in
  - Masih dalam 24-hour window
  - Template sudah approved
  - Nomor tidak dalam blacklist
- Error message: `131042: Business eligibility payment issue`
- Status pesan: `Gagal` atau `Error Diidentifikasi`

## Penyebab Umum

### 1. Metode Pembayaran Belum Ditambahkan

**Penyebab:**
- Akun WhatsApp Business belum memiliki metode pembayaran yang ditautkan
- Meta memerlukan metode pembayaran untuk mengirim template messages (berbayar)

**Solusi:**
1. Masuk ke **Meta Business Manager**: https://business.facebook.com/
2. Pilih **WhatsApp Business Account** Anda
3. Navigasikan ke **Pengaturan Bisnis** → **Pembayaran**
4. Klik **Tambahkan Metode Pembayaran**
5. Masukkan detail kartu kredit/debit yang valid
6. Setujui syarat dan ketentuan

### 2. Metode Pembayaran Tidak Ditetapkan sebagai Default

**Penyebab:**
- Metode pembayaran sudah ditambahkan, tapi tidak ditetapkan sebagai default
- Sistem tidak tahu metode pembayaran mana yang harus digunakan

**Solusi:**
1. Di bagian **Pembayaran** pada Meta Business Manager
2. Pilih metode pembayaran yang ingin digunakan
3. Klik **Set as Default** atau **Tandai sebagai Default**
4. Pastikan status menunjukkan "Default" atau "Aktif"

### 3. Saldo Tertunggak atau Masalah Pembayaran

**Penyebab:**
- Ada saldo tertunggak dari penggunaan sebelumnya
- Kartu kredit/debit kedaluwarsa atau ditolak
- Limit kartu kredit terlampaui

**Solusi:**
1. Periksa bagian **Penagihan** di Meta Business Manager
2. Lihat **Riwayat Transaksi** untuk melihat saldo tertunggak
3. Selesaikan pembayaran yang tertunggak
4. Periksa status kartu kredit/debit:
   - Pastikan kartu masih aktif
   - Pastikan tidak kedaluwarsa
   - Pastikan limit masih tersedia
5. Jika perlu, tambahkan metode pembayaran baru

### 4. Akun WhatsApp Business Belum Terverifikasi

**Penyebab:**
- Akun WhatsApp Business belum terverifikasi oleh Meta
- Proses verifikasi bisnis belum selesai

**Solusi:**
1. Periksa status verifikasi di **Meta Business Manager** → **Business Info**
2. Lengkapi proses verifikasi bisnis jika belum selesai
3. Tunggu persetujuan dari Meta (biasanya 1-3 hari kerja)

## Langkah-Langkah Verifikasi

### 1. Cek Status Pembayaran di Meta Business Manager

```
1. Login ke https://business.facebook.com/
2. Pilih WhatsApp Business Account Anda
3. Klik "Settings" (Pengaturan)
4. Pilih "Billing" (Penagihan) atau "Payment Methods" (Metode Pembayaran)
5. Periksa:
   - Apakah ada metode pembayaran yang terdaftar?
   - Apakah metode pembayaran ditetapkan sebagai default?
   - Apakah ada saldo tertunggak?
   - Apakah status pembayaran "Active"?
```

### 2. Cek Status WhatsApp Business Account

```
1. Di Meta Business Manager, pilih WhatsApp Business Account
2. Klik "Settings" → "Account Info"
3. Periksa:
   - Status verifikasi bisnis
   - Status akun WhatsApp
   - Limit pengiriman pesan
```

### 3. Uji Pengiriman Pesan

Setelah memperbaiki masalah pembayaran:
1. Coba kirim template message ke nomor yang sudah opt-in
2. Periksa log Odoo untuk melihat apakah error masih terjadi
3. Jika masih error, tunggu beberapa menit (Meta perlu waktu untuk memperbarui status)

## Penyebab Khusus: Template Kategori Marketing

### Error 131042 untuk Template Kategori Marketing

**Penyebab:**
- Template dikategorikan sebagai **Marketing** oleh Meta
- Template kategori Marketing memerlukan payment method yang lebih ketat
- Meta memiliki kebijakan otomatis untuk mengubah kategori template dari Utility ke Marketing jika tidak mengikuti aturan

**Mengapa Template Diubah ke Marketing?**

Meta akan otomatis mengubah kategori template ke Marketing jika:
- Template mengandung emoji marketing (🔔, ⏰, 🔐, dll)
- Template mengandung ajakan promosional ("Silakan ambil", "Segera lakukan", dll)
- Template tidak fokus pada informasi transaksi spesifik
- Template menggunakan bahasa promosional ("Selamat!", "Jangan lewatkan", dll)

**Solusi: Gunakan Template dengan Prefix `izin_`**

Kami sudah membuat template baru dengan prefix `izin_` yang mengikuti aturan Meta untuk kategori **Utility**:

1. **izin_selesai_diproses** - Izin Selesai Diproses
2. **izin_update_status** - Update Status Perizinan
3. **izin_dokumen_baru** - Dokumen Baru untuk Tandatangan
4. **izin_perlu_approval** - Dokumen Perlu Approval
5. **izin_reminder** - Reminder Dokumen Pending
6. **izin_peringatan_berlaku** - Peringatan Masa Berlaku Izin
7. **izin_perpanjangan_disetujui** - Perpanjangan Izin Disetujui

**Cara Menggunakan Template Baru:**

1. **Buat Template di Meta Business Manager:**
   - Login ke Meta Business Manager
   - Buka **WhatsApp → Message Templates**
   - Klik **Create Template**
   - Pilih kategori **Utility** (bukan Marketing)
   - Gunakan nama template dengan prefix `izin_` (contoh: `izin_selesai_diproses`)
   - Copy body dari template master di Odoo (WhatsApp → Konfigurasi → Master Templates)
   - Submit untuk review

2. **Update Master Template di Odoo:**
   - Menu: WhatsApp → Konfigurasi → Master Templates
   - Edit template yang sesuai
   - Pastikan `meta_template_name` menggunakan nama dengan prefix `izin_`
   - Set status menjadi "Configured"

3. **Verifikasi Template:**
   - Pastikan template di Meta Business Manager sudah **Approved**
   - Pastikan kategori template adalah **Utility** (bukan Marketing)
   - Test pengiriman pesan dengan template baru

**Dokumentasi Lengkap:**
- Lihat `docs/META_UTILITY_TEMPLATE_RULES.md` untuk aturan lengkap tentang template Utility
- Lihat `docs/WHATSAPP_MESSAGING_IMPLEMENTATION.md` untuk informasi tentang master templates

## Solusi Alternatif: Fallback ke Provider Lain

Jika masalah pembayaran Meta tidak bisa segera diselesaikan, sistem kita sudah memiliki mekanisme fallback:

### Menggunakan Fonnte atau Watzap

Sistem akan otomatis fallback ke Fonnte atau Watzap jika:
- Meta gagal mengirim (termasuk error 131042)
- Provider Meta tidak tersedia
- Template Meta belum dikonfigurasi

**Cara Mengaktifkan Fallback:**

1. **Pastikan Provider Fonnte/Watzap Terkonfigurasi:**
   - Menu: WhatsApp → Konfigurasi → Profil Provider
   - Tambahkan atau edit provider Fonnte/Watzap
   - Isi API Key dan konfigurasi lainnya

2. **Pastikan Template Sudah Dikonfigurasi:**
   - Menu: WhatsApp → Konfigurasi → Master Template
   - Edit template yang ingin digunakan
   - Isi `fonnte_template_name` atau `watzap_template_name`
   - Set status menjadi "Configured"

3. **Sistem Akan Otomatis Fallback:**
   - Jika Meta gagal, sistem akan mencoba Fonnte
   - Jika Fonnte tidak tersedia, sistem akan mencoba Watzap
   - Logging akan menunjukkan provider mana yang digunakan

## Error Handling di Sistem

Sistem kita sudah memiliki error handling untuk payment-related errors:

### Retry Logic

Error 131042 **TIDAK** termasuk dalam retryable errors, karena:
- Masalah pembayaran tidak akan teratasi dengan retry
- Perlu intervensi manual untuk memperbaiki masalah pembayaran

### Fallback Mechanism

Sistem akan:
1. Mencoba mengirim via Meta terlebih dahulu
2. Jika gagal dengan error 131042, log error dan catat di database
3. **TIDAK** otomatis fallback (karena perlu konfirmasi admin)
4. Admin bisa manual retry atau menggunakan provider lain

## Rekomendasi

### Untuk Admin Sistem

1. **Monitor Error Logs:**
   - Periksa log Odoo secara berkala untuk error 131042
   - Set up alert jika banyak error payment-related

2. **Proaktif Cek Status Pembayaran:**
   - Cek status pembayaran di Meta Business Manager setiap minggu
   - Pastikan metode pembayaran masih aktif dan valid
   - Monitor saldo dan limit kartu kredit

3. **Siapkan Provider Backup:**
   - Konfigurasi Fonnte atau Watzap sebagai backup
   - Pastikan template sudah dikonfigurasi untuk semua provider
   - Test pengiriman via backup provider secara berkala

### Untuk Developer

1. **Tambahkan Monitoring:**
   - Track error 131042 secara khusus
   - Alert admin jika error rate tinggi
   - Dashboard untuk melihat status pembayaran Meta

2. **Implementasi Auto-Fallback (Opsional):**
   - Pertimbangkan untuk auto-fallback ke Fonnte/Watzap jika error 131042
   - Atau set up cron job untuk retry dengan provider lain

## Referensi

- [Meta Business Manager - Payment Methods](https://business.facebook.com/settings/payments)
- [WhatsApp Business API - Billing](https://developers.facebook.com/docs/whatsapp/business-management-api/billing)
- [WhatsApp API Error Codes](https://developers.facebook.com/docs/whatsapp/cloud-api/support/error-codes)

## Video Tutorial

- [WhatsApp Template Message not delivering? Add Payment Method in WhatsApp Cloud API](https://www.youtube.com/watch?v=eqw4J8x2h1A)

## FAQ

**Q: Apakah error 131042 bisa di-retry otomatis?**
A: Tidak, error ini memerlukan intervensi manual untuk memperbaiki masalah pembayaran di Meta Business Manager.

**Q: Apakah sistem akan otomatis fallback ke Fonnte/Watzap jika Meta gagal?**
A: Saat ini tidak, karena perlu konfirmasi admin. Tapi admin bisa manual retry dengan provider lain.

**Q: Berapa lama waktu yang dibutuhkan setelah memperbaiki pembayaran?**
A: Biasanya 5-15 menit, tapi bisa sampai 1 jam tergantung update dari Meta.

**Q: Apakah semua template akan gagal jika ada masalah pembayaran?**
A: Ya, semua template message via Meta akan gagal sampai masalah pembayaran diperbaiki.

**Q: Apakah session message (dalam 24h window) juga akan gagal?**
A: Tidak, session message tidak memerlukan pembayaran. Hanya template message yang berbayar.

**Q: Mengapa template saya diubah ke Marketing oleh Meta?**
A: Meta memiliki kebijakan otomatis untuk mengubah kategori template ke Marketing jika template tidak mengikuti aturan Utility. Lihat `docs/META_UTILITY_TEMPLATE_RULES.md` untuk aturan lengkap.

**Q: Bagaimana cara membuat template yang tidak akan diubah ke Marketing?**
A: Gunakan template dengan prefix `izin_` yang sudah dibuat sesuai aturan Meta Utility. Template ini sudah dioptimalkan untuk tetap dalam kategori Utility.

**Q: Apakah template dengan prefix `izin_` akan selalu kategori Utility?**
A: Tidak ada jaminan 100%, tapi dengan mengikuti aturan Meta Utility (tidak ada emoji marketing, fokus pada transaksi spesifik, bahasa formal), kemungkinan besar akan tetap Utility. Meta akan review dan menentukan kategori akhir.

