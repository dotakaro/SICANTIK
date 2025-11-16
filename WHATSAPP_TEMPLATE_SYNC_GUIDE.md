# 📱 Panduan Sinkronisasi Status Template WhatsApp

## 🔍 Masalah

Template WhatsApp sudah di-approve di Meta Business Manager, tapi status di Odoo masih **Draft** atau **Pending**. Ini terjadi karena:

1. **Webhook tidak ter-trigger** - Meta tidak mengirim notifikasi update status ke Odoo
2. **Webhook tidak terkonfigurasi** - Webhook URL belum di-setup dengan benar di Meta
3. **Template di-submit manual** - Template di-submit langsung dari Meta, bukan dari Odoo

## ✅ Solusi

Ada **3 cara** untuk sync status template dari Meta ke Odoo:

### 1. **Sync Semua Template Sekaligus** (Recommended)

Cara termudah untuk sync semua template:

1. Buka **Settings → WhatsApp → WhatsApp Accounts**
2. Pilih WhatsApp Account yang digunakan
3. Klik button **"Synchronize Templates"** (icon refresh)
4. Tunggu beberapa detik, semua template akan di-sync dari Meta

**Keuntungan:**
- Sync semua template sekaligus
- Update status, quality, dan data template lainnya
- Membuat template baru jika ada di Meta tapi belum ada di Odoo

### 2. **Sync Template Individual**

Untuk sync satu template saja:

1. Buka **WhatsApp → Templates**
2. Pilih template yang ingin di-sync
3. Klik button **"Sync Template"** di form view (atau button **"Sync"** di list view)
4. Status akan ter-update sesuai dengan status di Meta

**Catatan:** Button "Sync Template" hanya muncul jika template sudah punya **WhatsApp Template ID** (sudah di-submit ke Meta).

### 3. **Sync Multiple Template (Bulk)**

Untuk sync beberapa template sekaligus:

1. Buka **WhatsApp → Templates**
2. Pilih beberapa template yang ingin di-sync (gunakan checkbox)
3. Klik **Action** → **Sync Selected Templates from Meta**
4. Semua template yang dipilih akan di-sync

**Keuntungan:**
- Sync hanya template yang dipilih
- Cocok untuk sync template tertentu saja

## 🔧 Troubleshooting

### Template tidak bisa di-sync

**Penyebab:**
- Template belum di-submit ke Meta (tidak punya WhatsApp Template ID)
- WhatsApp Account credentials salah
- Template sudah dihapus dari Meta

**Solusi:**
1. Pastikan template sudah di-submit ke Meta (ada WhatsApp Template ID)
2. Cek credentials WhatsApp Account di Settings
3. Test connection dengan button "Test Credentials"

### Status masih tidak update setelah sync

**Penyebab:**
- Template belum di-approve di Meta
- Template di-reject di Meta
- API Meta sedang bermasalah

**Solusi:**
1. Cek status template di [Meta Business Manager](https://business.facebook.com/wa/manage/message-templates)
2. Jika template di-reject, lihat alasan rejection dan perbaiki template
3. Coba sync lagi setelah beberapa saat

## 📝 Catatan Penting

1. **Webhook Configuration**: Untuk update otomatis, pastikan webhook sudah dikonfigurasi dengan benar di Meta. URL webhook: `https://your-odoo-domain.com/whatsapp/webhook/`

2. **Manual Sync**: Jika webhook tidak bekerja, gunakan manual sync dengan cara di atas

3. **Template Status**: Status template di Odoo akan selalu mengikuti status di Meta setelah sync

4. **Frequency**: Tidak perlu sync terlalu sering. Sync hanya saat:
   - Template baru di-submit
   - Status template berubah di Meta
   - Template tidak ter-update otomatis

## 🎯 Best Practice

1. **Setelah Submit Template:**
   - Tunggu 24-48 jam untuk approval dari Meta
   - Setelah approval, sync template untuk update status

2. **Regular Sync:**
   - Sync semua template seminggu sekali untuk memastikan status up-to-date
   - Atau sync saat ada perubahan status di Meta

3. **Webhook Setup:**
   - Setup webhook untuk update otomatis
   - Test webhook dengan Meta Webhook Tester

---

**Dibuat:** 2025-01-XX  
**Module:** WhatsApp Enterprise (Odoo)  
**Version:** Odoo 18

