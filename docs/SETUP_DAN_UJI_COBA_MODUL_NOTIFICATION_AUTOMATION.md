# Panduan Setup dan Uji Coba Modul SICANTIK Notification Automation

**Versi:** 1.0.0  
**Tanggal:** 28 November 2025  
**Modul:** `sicantik_notification_automation`

---

## Daftar Isi

1. [Pendahuluan](#1-pendahuluan)
2. [Konsep Dasar](#2-konsep-dasar)
3. [Setup Pemicu (Trigger)](#3-setup-pemicu-trigger)
4. [Setup Aturan (Rule)](#4-setup-aturan-rule)
5. [Setup Kampanye (Campaign)](#5-setup-kampanye-campaign)
6. [Bagaimana Provider WhatsApp Dipilih?](#6-bagaimana-provider-whatsapp-dipilih)
7. [Uji Coba Lengkap](#7-uji-coba-lengkap)
8. [Monitoring dan Troubleshooting](#8-monitoring-dan-troubleshooting)

---

## 1. Pendahuluan

Modul `sicantik_notification_automation` adalah sistem notifikasi massal dan otomatis yang terintegrasi dengan modul `sicantik_whatsapp`. Modul ini memungkinkan Anda untuk:

- Mengkonfigurasi notifikasi otomatis berdasarkan event tertentu
- Mengelola kampanye notifikasi massal
- Memantau pengiriman notifikasi melalui antrian dan log

**Prerequisites:**
- Modul `sicantik_connector` sudah terinstall
- Modul `sicantik_whatsapp` sudah terinstall dan dikonfigurasi
- Minimal 1 provider WhatsApp (Meta, Fonnte, atau Watzap) sudah dikonfigurasi

---

## 2. Konsep Dasar

### 2.1 Alur Kerja

```
Event Terjadi (Odoo Signal)
    ↓
Pemicu (Trigger) Mendeteksi Event
    ↓
Evaluasi Kondisi Pemicu
    ↓
Cari Aturan (Rule) yang Terkait
    ↓
Evaluasi Kondisi Aturan
    ↓
Tentukan Penerima
    ↓
Buat Entri Antrian (Queue)
    ↓
Proses Antrian (via Cron atau Manual)
    ↓
Pilih Provider WhatsApp (via sicantik_whatsapp dispatcher)
    ↓
Kirim Notifikasi
    ↓
Catat di Log
```

### 2.2 Komponen Utama

1. **Pemicu (Trigger)**: Mendefinisikan event yang akan dimonitor (contoh: status izin berubah)
2. **Aturan (Rule)**: Mendefinisikan kapan dan kepada siapa notifikasi dikirim
3. **Kampanye (Campaign)**: Untuk pengiriman massal ke banyak target sekaligus
4. **Antrian (Queue)**: Mengantri pengiriman notifikasi dengan mekanisme retry
5. **Log**: Mencatat semua pengiriman untuk audit

---

## 3. Setup Pemicu (Trigger)

### 3.1 Akses Menu

1. Login ke Odoo sebagai Administrator
2. Buka menu: **SICANTIK → Notifikasi Otomatis → Konfigurasi → Pemicu**
3. Klik **Buat** untuk membuat pemicu baru

### 3.2 Default Pemicu yang Sudah Tersedia

Setelah install modul, ada 6 pemicu default yang sudah tersedia:

1. **Status Izin Berubah** (`permit_state_change`)
   - Model: `sicantik.permit`
   - Tipe: `on_state_change`
   - Field Status: `state`

2. **Izin Mendekati Masa Berlaku** (`permit_expiring`)
   - Model: `sicantik.permit`
   - Tipe: `cron` (setiap hari)
   - Kondisi: Izin yang akan berakhir dalam 30 hari

3. **Dokumen Baru Diupload** (`document_uploaded`)
   - Model: `sicantik.document`
   - Tipe: `on_state_change`
   - Dari: `draft` → Ke: `uploaded`

4. **Dokumen Perlu Ditandatangani** (`document_pending_signature`)
   - Model: `sicantik.document`
   - Tipe: `on_state_change`
   - Ke: `pending_signature`

5. **Dokumen Sudah Ditandatangani** (`document_signed`)
   - Model: `sicantik.document`
   - Tipe: `on_state_change`
   - Ke: `signed`

6. **Reminder Dokumen Pending** (`document_pending_reminder`)
   - Model: `sicantik.document`
   - Tipe: `cron` (setiap hari)
   - Kondisi: Dokumen pending lebih dari 24 jam

### 3.3 Membuat Pemicu Baru

**Contoh: Pemicu untuk Izin yang Disetujui**

1. Klik **Buat**
2. Isi form:
   - **Nama Pemicu**: "Izin Disetujui"
   - **Kode Pemicu**: `permit_approved` (harus unik, huruf/angka/underscore saja)
   - **Aktif**: ✅ Centang
   - **Urutan**: 10
   - **Model**: Pilih "Izin SICANTIK" (`sicantik.permit`)
   - **Tipe Pemicu**: Pilih "Saat Status Berubah"
   - **Field Status**: `state`
   - **Status Dari**: `draft` (atau kosongkan untuk semua)
   - **Status Ke**: `approved`
   - **Deskripsi**: "Pemicu saat izin disetujui"
   - **Kondisi Domain**: `[('active', '=', True)]` (opsional)

3. Klik **Simpan**

### 3.4 Tipe Pemicu yang Tersedia

- **Saat Record Dibuat** (`on_create`): Pemicu saat record baru dibuat
- **Saat Record Diupdate** (`on_write`): Pemicu saat record diupdate
- **Saat Status Berubah** (`on_state_change`): Pemicu saat field status berubah
- **Saat Field Tertentu Berubah** (`on_field_change`): Pemicu saat field tertentu berubah
- **Jadwal Cron** (`cron`): Pemicu berdasarkan jadwal cron
- **Manual** (`manual`): Pemicu yang dipanggil secara manual
- **API Call** (`api`): Pemicu dari API eksternal

---

## 4. Setup Aturan (Rule)

### 4.1 Akses Menu

1. Buka menu: **SICANTIK → Notifikasi Otomatis → Konfigurasi → Aturan**
2. Klik **Buat** untuk membuat aturan baru

### 4.2 Membuat Aturan Baru

**Contoh: Aturan untuk Notifikasi Izin Disetujui**

1. Klik **Buat**
2. Isi form:

   **Tab Informasi Dasar:**
   - **Nama Aturan**: "Notifikasi Izin Disetujui"
   - **Aktif**: ✅ Centang
   - **Urutan**: 10
   - **Pemicu**: Pilih "Status Izin Berubah" (atau pemicu yang sudah dibuat)
   - **Template Key**: `permit_ready` (atau template lain yang sudah ada di `sicantik_whatsapp`)

   **Tab Kondisi:**
   - **Kondisi Domain**: `[('state', '=', 'approved')]` (opsional, untuk filter tambahan)
   - **Kondisi Python**: (kosongkan, atau gunakan untuk kondisi kompleks)

   **Tab Penerima:**
   - **Tipe Penerima**: Pilih salah satu:
     - **Partner (Pemohon)**: Otomatis mengambil partner dari record
     - **Staff DPMPTSP**: Mengambil user dari grup tertentu
     - **Pejabat Berwenang**: Mengambil user dari grup pejabat
     - **Custom (Domain)**: Menggunakan domain custom untuk mencari penerima
     - **Dari Field Record**: Mengambil partner dari field tertentu di record
   
   - Jika memilih **Partner (Pemohon)**: Tidak perlu konfigurasi tambahan
   - Jika memilih **Staff DPMPTSP**: Pilih **Grup Staff** (contoh: "DPMPTSP Staff")
   - Jika memilih **Custom**: Isi **Domain Penerima** (contoh: `[('is_company', '=', False)]`)

   **Tab Penjadwalan:**
   - **Tipe Penjadwalan**: Pilih salah satu:
     - **Langsung**: Kirim segera setelah pemicu terjadi
     - **Terjadwal**: Kirim setelah delay tertentu
     - **Batch (Massal)**: Kirim dalam batch dengan interval tertentu
   
   - Jika **Terjadwal**: Isi **Delay (menit)** (contoh: 5 menit)
   - Jika **Batch**: Isi **Ukuran Batch** (contoh: 100) dan **Interval Batch (menit)** (contoh: 5)

   **Tab Pembatasan & Ulang Coba:**
   - **Aktifkan Pembatasan Laju**: ✅ Centang (default: True)
   - **Max Pengiriman**: 100 (default)
   - **Periode Pembatasan Laju**: Pilih "Per Jam" (default)
   - **Max Ulang Coba**: 3 (default)
   - **Delay Ulang Coba (menit)**: 5 (default)
   - **Hormati Daftar Hitam**: ✅ Centang (default: True)

   **Tab Context Values:**
   - **Kode Persiapan Context**: (kosongkan untuk menggunakan default, atau gunakan kode Python custom)
   
   Contoh kode Python custom:
   ```python
   context = {
       'partner_name': recipient.name or 'Bapak/Ibu',
       'permit_number': record.permit_number or '',
       'permit_type': record.permit_type_name or '',
       'status': record.state or '',
       'applicant_name': record.applicant_name or '',
       'expiry_date': record.expiry_date.strftime('%d-%m-%Y') if record.expiry_date else '',
   }
   ```

3. Klik **Simpan**

### 4.3 Template Key yang Tersedia

Template key harus sesuai dengan template yang sudah ada di modul `sicantik_whatsapp`. Beberapa contoh:

- `permit_ready`: Izin selesai diproses
- `document_pending`: Dokumen baru untuk tandatangan
- `approval_required`: Dokumen perlu approval
- `reminder`: Reminder dokumen pending
- `status_update`: Update status perizinan
- `permit_reminder`: Peringatan masa berlaku izin
- `renewal_approved`: Perpanjangan izin disetujui

**Catatan:** Pastikan template dengan key tersebut sudah ada di **WhatsApp → Konfigurasi → Master Template** atau sudah dibuat di Meta/Fonnte/Watzap.

---

## 5. Setup Kampanye (Campaign)

### 5.1 Akses Menu

1. Buka menu: **SICANTIK → Notifikasi Otomatis → Kampanye**
2. Klik **Buat** untuk membuat kampanye baru

### 5.2 Membuat Kampanye Baru

**Contoh: Kampanye Peringatan Masa Berlaku Izin**

1. Klik **Buat**
2. Isi form:

   **Tab Informasi Dasar:**
   - **Nama Kampanye**: "Peringatan Masa Berlaku Izin 30 Hari"
   - **Deskripsi**: "Mengirim notifikasi kepada semua pemohon yang izinnya akan berakhir dalam 30 hari"
   - **Aturan**: Pilih aturan yang sudah dibuat (contoh: "Notifikasi Izin Disetujui")
   - **Status**: Draft (akan berubah otomatis saat kampanye dimulai)

   **Tab Target:**
   - **Model Target**: `sicantik.permit`
   - **Domain Target**: `[('expiry_date', '<=', (datetime.now() + timedelta(days=30)).strftime('%Y-%m-%d')), ('state', '=', 'approved')]`
   
   **Catatan:** Domain menggunakan format Python. Contoh domain sederhana:
   ```python
   [('state', '=', 'approved'), ('expiry_date', '!=', False)]
   ```

   **Tab Override Template (Opsional):**
   - **Template Key Override**: (kosongkan untuk menggunakan template dari aturan)

   **Tab Penjadwalan:**
   - **Jadwal Pengiriman**: (kosongkan untuk langsung, atau pilih tanggal/waktu tertentu)

3. Klik **Simpan**

### 5.3 Menjalankan Kampanye

1. Buka kampanye yang sudah dibuat
2. Klik **Mulai Kampanye**
3. Sistem akan:
   - Mengevaluasi domain target
   - Membuat entri antrian untuk setiap target
   - Mengubah status menjadi "Berjalan"
4. Pantau progres di tab **Statistik**

### 5.4 Manajemen Kampanye

- **Jeda**: Menjeda kampanye yang sedang berjalan
- **Lanjutkan**: Melanjutkan kampanye yang dijeda
- **Batalkan**: Membatalkan kampanye (hanya untuk yang belum selesai)

---

## 6. Bagaimana Provider WhatsApp Dipilih?

### 6.1 Alur Pemilihan Provider

Modul `sicantik_notification_automation` **TIDAK** memilih provider secara langsung. Sebaliknya, modul ini menggunakan **`sicantik.whatsapp.dispatcher`** dari modul `sicantik_whatsapp` yang memiliki logika pemilihan provider yang canggih.

**Alur Pemilihan Provider:**

```
Antrian Notifikasi Diproses
    ↓
Panggil sicantik.whatsapp.dispatcher.send_template_message()
    ↓
Dispatcher Mengevaluasi:
    1. Apakah partner sudah opt-in ke Meta?
    2. Apakah masih dalam 24 jam window?
    3. Apakah template tersedia di Meta?
    ↓
Jika YA → Gunakan Meta WhatsApp
    ↓
Jika TIDAK → Cari Provider Fallback:
    1. Fonnte (jika aktif dan dikonfigurasi)
    2. Watzap (jika aktif dan dikonfigurasi)
    ↓
Jika Meta Gagal (Error 131042: Payment Issue):
    → Auto-fallback ke Fonnte/Watzap
    ↓
Kirim via Provider yang Dipilih
```

### 6.2 Logika Pemilihan Provider di Dispatcher

Dispatcher menggunakan logika berikut (dari `sicantik_whatsapp`):

1. **Prioritas Meta WhatsApp:**
   - Jika partner sudah opt-in **ATAU** masih dalam 24 jam window
   - Jika template tersedia di Meta
   - Maka gunakan Meta WhatsApp

2. **Fallback ke Fonnte/Watzap:**
   - Jika Meta tidak memenuhi syarat
   - Cari provider Fonnte atau Watzap yang aktif
   - Gunakan provider yang ditemukan pertama

3. **Auto-Fallback saat Error:**
   - Jika Meta mengembalikan error 131042 (Business eligibility payment issue)
   - Sistem otomatis mencoba Fonnte atau Watzap
   - Log error dicatat untuk monitoring

### 6.3 Template Selection

Dispatcher juga memilih template berdasarkan provider:

- **Untuk Meta**: Mencari template dengan prefix `meta_` terlebih dahulu (contoh: `meta_selesai_diproses`), jika tidak ditemukan, gunakan template tanpa prefix
- **Untuk Fonnte/Watzap**: Menggunakan template tanpa prefix (contoh: `selesai_diproses`)

### 6.4 Konfigurasi Provider

Untuk memastikan provider dipilih dengan benar:

1. **Setup Meta WhatsApp:**
   - Buka: **WhatsApp → Konfigurasi → Akun WhatsApp**
   - Buat akun Meta WhatsApp Business
   - Konfigurasi webhook URL
   - Sync template dari Meta

2. **Setup Fonnte:**
   - Buka: **WhatsApp → Konfigurasi → Provider WhatsApp**
   - Buat provider baru dengan tipe "Fonnte"
   - Isi API Token Fonnte
   - Klik "Test Connection" untuk verifikasi

3. **Setup Watzap:**
   - Buka: **WhatsApp → Konfigurasi → Provider WhatsApp**
   - Buat provider baru dengan tipe "Watzap"
   - Isi konfigurasi Watzap
   - Klik "Test Connection" untuk verifikasi

### 6.5 Monitoring Provider yang Digunakan

Anda dapat melihat provider yang digunakan untuk setiap pengiriman:

1. Buka: **SICANTIK → Notifikasi Otomatis → Monitoring → Log Pengiriman**
2. Lihat kolom **Tipe Provider**:
   - `meta`: Meta WhatsApp
   - `fonnte`: Fonnte
   - `watzap`: Watzap

---

## 7. Uji Coba Lengkap

### 7.1 Uji Coba 1: Notifikasi Otomatis Saat Status Izin Berubah

**Tujuan:** Menguji notifikasi otomatis saat status izin berubah dari `draft` ke `approved`.

**Langkah-langkah:**

1. **Persiapan:**
   - Pastikan ada izin dengan status `draft` dan partner memiliki nomor WhatsApp
   - Pastikan template `permit_ready` sudah ada di Master Template

2. **Setup Pemicu:**
   - Buka: **SICANTIK → Notifikasi Otomatis → Konfigurasi → Pemicu**
   - Pastikan pemicu "Status Izin Berubah" aktif
   - Edit jika perlu: Set **Status Dari** = `draft`, **Status Ke** = `approved`

3. **Setup Aturan:**
   - Buka: **SICANTIK → Notifikasi Otomatis → Konfigurasi → Aturan**
   - Klik **Buat**
   - Isi:
     - Nama: "Notifikasi Izin Disetujui"
     - Pemicu: "Status Izin Berubah"
     - Template Key: `permit_ready`
     - Tipe Penerima: "Partner (Pemohon)"
     - Tipe Penjadwalan: "Langsung"
   - Klik **Simpan**

4. **Trigger Event:**
   - Buka: **SICANTIK → Data Perizinan**
   - Pilih izin dengan status `draft`
   - Ubah status menjadi `approved`
   - Klik **Simpan**

5. **Verifikasi:**
   - Buka: **SICANTIK → Notifikasi Otomatis → Monitoring → Antrian Pengiriman**
   - Cari entri antrian dengan partner yang sesuai
   - Status harus `pending` atau `sent`
   - Buka: **SICANTIK → Notifikasi Otomatis → Monitoring → Log Pengiriman**
   - Cari log dengan status `sent`
   - Verifikasi bahwa notifikasi terkirim ke WhatsApp

### 7.2 Uji Coba 2: Kampanye Massal

**Tujuan:** Menguji kampanye massal untuk mengirim notifikasi ke banyak target sekaligus.

**Langkah-langkah:**

1. **Persiapan:**
   - Pastikan ada minimal 5-10 izin dengan status `approved` dan partner memiliki nomor WhatsApp
   - Pastikan template `permit_reminder` sudah ada

2. **Setup Aturan:**
   - Buka: **SICANTIK → Notifikasi Otomatis → Konfigurasi → Aturan**
   - Klik **Buat**
   - Isi:
     - Nama: "Reminder Masa Berlaku Izin"
     - Pemicu: (pilih pemicu cron atau manual)
     - Template Key: `permit_reminder`
     - Tipe Penerima: "Partner (Pemohon)"
     - Tipe Penjadwalan: "Batch (Massal)"
     - Ukuran Batch: 5
     - Interval Batch: 2 menit
   - Klik **Simpan**

3. **Buat Kampanye:**
   - Buka: **SICANTIK → Notifikasi Otomatis → Kampanye**
   - Klik **Buat**
   - Isi:
     - Nama: "Test Kampanye Reminder"
     - Aturan: "Reminder Masa Berlaku Izin"
     - Model Target: `sicantik.permit`
     - Domain Target: `[('state', '=', 'approved'), ('partner_id', '!=', False)]`
   - Klik **Simpan**

4. **Jalankan Kampanye:**
   - Buka kampanye yang sudah dibuat
   - Klik **Mulai Kampanye**
   - Sistem akan membuat entri antrian untuk setiap target

5. **Pantau Progres:**
   - Lihat tab **Statistik** di kampanye
   - Pantau **Total Target**, **Total Terkirim**, **Total Pending**
   - Buka: **SICANTIK → Notifikasi Otomatis → Monitoring → Antrian Pengiriman**
   - Filter berdasarkan kampanye
   - Lihat status pengiriman

6. **Verifikasi:**
   - Tunggu beberapa menit (sesuai interval batch)
   - Cek **Log Pengiriman** untuk melihat hasil pengiriman
   - Verifikasi bahwa notifikasi terkirim ke WhatsApp

### 7.3 Uji Coba 3: Test Send Manual

**Tujuan:** Menguji pengiriman notifikasi secara manual tanpa menunggu pemicu.

**Langkah-langkah:**

1. **Buka Wizard Test:**
   - Buka: **SICANTIK → Notifikasi Otomatis → Konfigurasi → Aturan**
   - Pilih aturan yang ingin diuji
   - Klik **Action** → **Test Send** (jika ada) atau gunakan wizard manual

2. **Alternatif: Gunakan Antrian Manual:**
   - Buka: **SICANTIK → Notifikasi Otomatis → Monitoring → Antrian Pengiriman**
   - Klik **Buat**
   - Isi:
     - Aturan: Pilih aturan yang ingin diuji
     - Penerima: Pilih partner yang memiliki nomor WhatsApp
     - Template Key: Isi template key yang valid
     - Context Values: Isi JSON (contoh: `{"partner_name": "Test User", "permit_number": "TEST/2025/00001"}`)
     - Jadwal Pengiriman: Sekarang
   - Klik **Simpan**

3. **Proses Antrian:**
   - Buka entri antrian yang sudah dibuat
   - Klik **Proses Sekarang**
   - Atau tunggu cron job memproses (setiap 1 menit)

4. **Verifikasi:**
   - Cek status antrian (harus `sent`)
   - Cek **Log Pengiriman**
   - Verifikasi bahwa notifikasi terkirim ke WhatsApp

### 7.4 Uji Coba 4: Monitoring Cron Jobs

**Tujuan:** Memastikan cron jobs berjalan dengan benar.

**Langkah-langkah:**

1. **Akses Scheduled Actions:**
   - Login sebagai Administrator
   - Aktifkan Developer Mode
   - Buka: **Settings → Technical → Automation → Scheduled Actions**
   - Atau: **SICANTIK → Notifikasi Otomatis → Monitoring → Cron Jobs (Scheduled Actions)**

2. **Cari Cron Jobs SICANTIK:**
   - Filter berdasarkan nama: "SICANTIK:"
   - Harus ada 3 cron jobs:
     - **SICANTIK: Process Notification Queue** (setiap 1 menit, priority 20)
     - **SICANTIK: Process Campaign Batch** (setiap 5 menit, priority 15)
     - **SICANTIK: Cleanup Old Notification Logs** (setiap hari, priority 5)

3. **Verifikasi Status:**
   - Pastikan semua cron jobs **Aktif** = ✅
   - Lihat **Last Run** untuk memastikan sudah pernah dieksekusi
   - Lihat **Next Run** untuk melihat jadwal eksekusi berikutnya

4. **Test Manual:**
   - Pilih cron job "SICANTIK: Process Notification Queue"
   - Klik **Run Manually** atau **Execute Now**
   - Cek log untuk melihat hasil eksekusi

5. **Monitor Logs:**
   - Buka: **Settings → Technical → Logging**
   - Filter berdasarkan model: `sicantik.notification.queue`
   - Lihat log eksekusi cron job

---

## 8. Monitoring dan Troubleshooting

### 8.1 Monitoring Antrian Pengiriman

**Akses:**
- **SICANTIK → Notifikasi Otomatis → Monitoring → Antrian Pengiriman**

**Filter yang Berguna:**
- **Status**: Filter berdasarkan `pending`, `processing`, `sent`, `failed`
- **Aturan**: Filter berdasarkan aturan tertentu
- **Kampanye**: Filter berdasarkan kampanye tertentu
- **Penerima**: Filter berdasarkan partner tertentu

**Aksi yang Dapat Dilakukan:**
- **Proses Sekarang**: Memproses entri antrian secara manual
- **Ulang Coba**: Mengulang coba pengiriman yang gagal
- **Batalkan**: Membatalkan pengiriman yang masih pending

### 8.2 Monitoring Log Pengiriman

**Akses:**
- **SICANTIK → Notifikasi Otomatis → Monitoring → Log Pengiriman**

**Informasi yang Tersedia:**
- Partner penerima
- Template yang digunakan
- Status pengiriman (`sent`, `failed`, `skipped`)
- Provider yang digunakan (`meta`, `fonnte`, `watzap`)
- Waktu pengiriman
- Pesan error (jika gagal)
- Waktu proses (ms)

### 8.3 Monitoring Cron Jobs

**Akses:**
- **Settings → Technical → Automation → Scheduled Actions**
- Atau: **SICANTIK → Notifikasi Otomatis → Monitoring → Cron Jobs (Scheduled Actions)**

**Informasi yang Tersedia:**
- Nama cron job
- Model yang dieksekusi
- Method yang dipanggil
- Interval eksekusi
- Status aktif/nonaktif
- Last Run (waktu eksekusi terakhir)
- Next Run (waktu eksekusi berikutnya)
- Priority

**Aksi yang Dapat Dilakukan:**
- **Enable/Disable**: Toggle status aktif
- **Run Manually**: Eksekusi manual tanpa menunggu jadwal
- **Edit**: Ubah konfigurasi cron job
- **View Logs**: Lihat history eksekusi dan error

### 8.4 Troubleshooting Umum

#### Problem: Notifikasi Tidak Terkirim

**Penyebab:**
1. Pemicu tidak aktif
2. Aturan tidak aktif
3. Kondisi pemicu/aturan tidak terpenuhi
4. Partner tidak memiliki nomor WhatsApp
5. Template tidak ditemukan
6. Provider WhatsApp tidak dikonfigurasi
7. Antrian tidak diproses (cron job tidak berjalan)

**Solusi:**
1. Cek status aktif pemicu dan aturan
2. Cek kondisi domain di pemicu dan aturan
3. Pastikan partner memiliki nomor WhatsApp
4. Pastikan template key sesuai dengan template di Master Template
5. Pastikan minimal 1 provider WhatsApp aktif dan dikonfigurasi
6. Cek status cron job "SICANTIK: Process Notification Queue"
7. Cek log error di **Log Pengiriman**

#### Problem: Cron Job Tidak Berjalan

**Penyebab:**
1. Cron job tidak aktif
2. Odoo worker tidak berjalan
3. Next Run belum terlewati

**Solusi:**
1. Aktifkan cron job di Scheduled Actions
2. Restart Odoo worker
3. Klik "Run Manually" untuk test
4. Cek log Odoo untuk error

#### Problem: Provider Tidak Dipilih dengan Benar

**Penyebab:**
1. Provider tidak aktif
2. Provider tidak dikonfigurasi dengan benar
3. Template tidak tersedia di provider

**Solusi:**
1. Cek status provider di **WhatsApp → Konfigurasi → Provider WhatsApp**
2. Test connection provider
3. Pastikan template sudah di-sync ke provider
4. Cek log untuk melihat provider yang digunakan

#### Problem: Antrian Terlalu Banyak Pending

**Penyebab:**
1. Cron job tidak berjalan
2. Rate limiting terlalu ketat
3. Provider mengalami error

**Solusi:**
1. Cek status cron job
2. Kurangi rate limit atau tingkatkan periode
3. Cek error di log pengiriman
4. Proses antrian secara manual jika perlu

### 8.5 Best Practices

1. **Test Sebelum Production:**
   - Selalu test dengan data kecil terlebih dahulu
   - Gunakan wizard test send untuk verifikasi
   - Monitor log sebelum menjalankan kampanye besar

2. **Rate Limiting:**
   - Set rate limit yang wajar untuk menghindari spam
   - Monitor penggunaan provider untuk menghindari limit API

3. **Template Management:**
   - Pastikan template sudah di-sync ke provider sebelum digunakan
   - Gunakan template key yang konsisten
   - Test template sebelum digunakan di production

4. **Monitoring:**
   - Monitor antrian pengiriman secara berkala
   - Cek log pengiriman untuk error
   - Monitor cron jobs untuk memastikan berjalan dengan benar

5. **Error Handling:**
   - Set max retries yang wajar (default: 3)
   - Monitor failed pengiriman dan perbaiki penyebabnya
   - Gunakan daftar hitam untuk nomor yang tidak ingin menerima notifikasi

---

## 9. Kesimpulan

Modul `sicantik_notification_automation` menyediakan sistem notifikasi yang fleksibel dan powerful. Dengan mengikuti panduan ini, Anda dapat:

- Setup pemicu untuk berbagai event
- Konfigurasi aturan notifikasi yang sesuai kebutuhan
- Menjalankan kampanye massal dengan efisien
- Memantau pengiriman melalui antrian dan log
- Troubleshoot masalah dengan mudah

**Catatan Penting:**
- Modul ini bergantung pada `sicantik_whatsapp` untuk pengiriman pesan
- Provider WhatsApp dipilih secara otomatis oleh dispatcher berdasarkan kondisi
- Pastikan cron jobs berjalan dengan benar untuk pemrosesan otomatis
- Monitor log secara berkala untuk memastikan sistem berjalan dengan baik

---

**Dokumen ini akan di-update sesuai perkembangan modul.**

**Versi:** 1.0.0  
**Terakhir Diupdate:** 28 November 2025

