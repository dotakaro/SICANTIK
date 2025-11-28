# SICANTIK Notification Automation

Modul untuk sistem notifikasi massal dan otomatis berdasarkan pemicu/event tertentu.

## Fitur

- ✅ Notifikasi berbasis aturan yang dapat dikonfigurasi
- ✅ Pemicu berbasis event (create, write, state change)
- ✅ Kampanye notifikasi massal dengan pemrosesan batch
- ✅ Manajemen antrian pengiriman dengan mekanisme ulang coba
- ✅ Penjadwalan pengiriman (langsung, terjadwal, batch)
- ✅ Pencatatan dan pelacakan lengkap untuk audit
- ✅ Pembatasan laju pengiriman untuk menghindari spam
- ✅ Manajemen daftar hitam nomor telepon

## Instalasi

1. Pastikan modul `sicantik_whatsapp` sudah terinstall
2. Install modul `sicantik_notification_automation`
3. Konfigurasi pemicu dan aturan sesuai kebutuhan

## Penggunaan

### Membuat Pemicu

1. Buka menu: **Notifikasi Otomatis → Konfigurasi → Pemicu**
2. Klik **Buat**
3. Isi informasi:
   - Nama pemicu
   - Kode pemicu (unik)
   - Model yang akan di-monitor
   - Tipe pemicu (on_create, on_write, on_state_change, dll)
   - Kondisi domain (opsional)

### Membuat Aturan

1. Buka menu: **Notifikasi Otomatis → Konfigurasi → Aturan**
2. Klik **Buat**
3. Isi informasi:
   - Nama aturan
   - Pemicu terkait
   - Template key
   - Tipe penerima
   - Penjadwalan
   - Pembatasan laju (opsional)

### Membuat Kampanye

1. Buka menu: **Notifikasi Otomatis → Kampanye**
2. Klik **Buat**
3. Isi informasi:
   - Nama kampanye
   - Aturan terkait
   - Model target
   - Domain target
4. Klik **Mulai Kampanye**

### Monitoring

- **Antrian Pengiriman**: Lihat status pengiriman yang sedang diproses
- **Log Pengiriman**: Lihat history semua pengiriman
- **Cron Jobs**: Monitor scheduled actions untuk cron jobs

## Cron Jobs

Modul ini menggunakan 3 cron jobs:

1. **SICANTIK: Process Notification Queue** (setiap 1 menit, priority 20)
   - Memproses antrian notifikasi yang pending

2. **SICANTIK: Process Campaign Batch** (setiap 5 menit, priority 15)
   - Memproses batch kampanye massal

3. **SICANTIK: Cleanup Old Notification Logs** (setiap hari, priority 5)
   - Membersihkan log lama (> 90 hari)

### Cara Mengelola Cron Jobs

1. Login sebagai Administrator
2. Aktifkan Developer Mode
3. Buka: **Settings → Technical → Automation → Scheduled Actions**
4. Filter berdasarkan nama: "SICANTIK: ..."
5. Lihat status "Last Run" dan "Next Run"
6. Klik "Run Manually" untuk test
7. Lihat "View Logs" untuk detail eksekusi

## Dependencies

- `base`
- `mail`
- `sicantik_connector`
- `sicantik_whatsapp`

## Dokumentasi Lengkap

Lihat file `PERENCANAAN_MODUL_NOTIFICATION_AUTOMATION.md` di root project untuk dokumentasi lengkap.

