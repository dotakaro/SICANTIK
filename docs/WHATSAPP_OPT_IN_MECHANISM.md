# 📱 Mekanisme Opt-In WhatsApp yang Benar

**Tanggal:** 27 November 2025  
**Status:** ✅ Dokumentasi & Implementasi

---

## 🎯 Overview

Dokumen ini menjelaskan mekanisme opt-in yang benar untuk WhatsApp Business API, khususnya untuk Meta WhatsApp Business API dan Fonnte.

---

## 📋 Meta WhatsApp Business API Opt-In

### ✅ Cara Opt-In yang Valid (Menurut Meta)

Meta WhatsApp Business API **WAJIB** memerlukan opt-in sebelum bisa mengirim template messages. Berikut adalah cara-cara valid untuk mendapatkan opt-in:

#### 1. **User Mengirim Pesan ke WhatsApp Business Account** (Paling Mudah)
- User mengirim pesan ke nomor WhatsApp Business Account (Meta)
- Sistem otomatis mendeteksi pesan inbound via webhook
- Odoo core otomatis remove dari blacklist (artinya opt-in)
- Sistem juga set `whatsapp_opt_in = True` di partner record
- **24-Hour Window**: Setelah user mengirim pesan, kita punya 24 jam untuk mengirim template messages tanpa perlu opt-in formal

#### 2. **Form Pendaftaran dengan Checkbox**
- Saat user mendaftar izin baru, minta persetujuan untuk notifikasi WhatsApp
- Tambahkan checkbox "Saya setuju menerima notifikasi WhatsApp"
- Set `whatsapp_opt_in = True` saat user centang
- Simpan consent di database dengan timestamp

#### 3. **Link/QR Code yang Mengarah ke WhatsApp Business Account**
- Buat link WhatsApp: `https://wa.me/6281234567890?text=Halo`
- Atau QR code yang ketika di-scan membuka chat dengan WhatsApp Business Account
- User klik link/scan QR → membuka chat dengan Meta WhatsApp Business Account
- User mengirim pesan → auto opt-in

#### 4. **Pre-Approval di Meta Business Manager**
- Export daftar nomor yang sudah terdaftar
- Upload ke Meta Business Manager → Phone Numbers → Contacts
- Request approval untuk nomor-nomor tersebut
- Setelah approved, bisa kirim template messages tanpa perlu opt-in manual

### ❌ Cara Opt-In yang TIDAK Valid

1. **Membalas "SETUJU" ke Nomor Fonnte** ❌
   - Ini TIDAK akan membuat user opt-in ke Meta
   - Fonnte adalah gateway terpisah, bukan Meta WhatsApp Business Account
   - Membalas ke Fonnte hanya akan membuat user opt-in ke Fonnte (jika ada mekanisme seperti itu)

2. **Mengirim Pesan ke Nomor Lain (Bukan Meta WhatsApp Business Account)** ❌
   - Hanya pesan yang dikirim ke Meta WhatsApp Business Account yang dianggap valid untuk opt-in

---

## 📋 Fonnte Opt-In

### ✅ Cara Opt-In untuk Fonnte

Fonnte adalah gateway WhatsApp Indonesia yang **tidak memiliki mekanisme opt-in yang ketat** seperti Meta. Namun, untuk best practice:

1. **User Mengirim Pesan ke Nomor Fonnte**
   - User mengirim pesan ke nomor WhatsApp yang terhubung dengan Fonnte
   - Sistem bisa mencatat ini sebagai consent untuk menerima pesan via Fonnte

2. **Webhook Handler untuk Inbound Messages**
   - Fonnte menyediakan webhook untuk menerima pesan inbound
   - Sistem bisa memproses pesan inbound dan mencatat consent

### ⚠️ Catatan Penting

- **Fonnte ≠ Meta**: Opt-in ke Fonnte TIDAK berarti opt-in ke Meta
- **Dua Sistem Terpisah**: Meta dan Fonnte adalah dua provider yang berbeda
- **Routing Otomatis**: Sistem kita sudah memiliki routing otomatis:
  - Jika user sudah opt-in Meta → kirim via Meta
  - Jika belum opt-in Meta → kirim via Fonnte/Watzap (fallback)

---

## 🔄 Mekanisme Opt-In yang Benar untuk Sistem Kita

### Scenario 1: User Baru Mendaftar Izin

```
1. User mendaftar izin baru
   ↓
2. Sistem cek: Apakah nomor sudah opt-in Meta?
   ├─ Ya → Kirim template message via Meta ✅
   └─ Tidak → 
       ├─ Cek 24-hour window?
       │   ├─ Ya → Kirim template message via Meta ✅
       │   └─ Tidak → Kirim pesan opt-in via Fonnte/Watzap
       │       └─ Pesan berisi:
       │           • Penjelasan manfaat notifikasi WhatsApp
       │           • Link WhatsApp Business Account: https://wa.me/6281234567890?text=Halo
       │           • QR Code untuk scan
       │           • Instruksi: "Klik link atau scan QR code untuk mengaktifkan notifikasi"
       └─ Setelah user klik link → kirim pesan ke Meta → auto opt-in ✅
```

### Scenario 2: Pesan Opt-In via Fonnte

**Pesan yang BENAR:**
```
Yth. [Nama Pemohon],

DPMPTSP Kabupaten Karo memberikan layanan notifikasi WhatsApp untuk memudahkan komunikasi terkait perizinan Anda.

Dengan layanan ini, Anda akan menerima:
✅ Notifikasi real-time saat izin selesai diproses
✅ Update status perizinan otomatis
✅ Peringatan masa berlaku izin
✅ Link download dokumen langsung

Untuk mengaktifkan layanan ini, silakan klik link berikut atau scan QR code:

🔗 Link: https://wa.me/6281234567890?text=Halo
📱 QR Code: [QR Code Image]

Setelah Anda mengirim pesan ke nomor WhatsApp Business Account di atas, notifikasi akan aktif secara otomatis.

Terima kasih atas perhatiannya.

DPMPTSP Kabupaten Karo
Kabupaten Karo
```

**Pesan yang SALAH (Implementasi Saat Ini):**
```
...
Untuk mengaktifkan layanan ini, silakan balas pesan ini dengan kata "YA" atau "SETUJU".
...
```
❌ Ini salah karena:
- Membalas ke Fonnte tidak akan membuat user opt-in ke Meta
- Meta tidak akan tahu bahwa user sudah memberikan consent

### Scenario 3: Webhook Handler untuk Inbound Messages

**Untuk Meta:**
- Webhook sudah di-handle oleh Odoo Enterprise: `/whatsapp/webhook/`
- Pesan inbound otomatis di-record di `whatsapp.message`
- Sistem otomatis remove dari blacklist dan set `whatsapp_opt_in = True`

**Untuk Fonnte:**
- Perlu membuat webhook handler baru: `/sicantik/whatsapp/fonnte/webhook`
- Menerima pesan inbound dari Fonnte
- Mencatat pesan di `sicantik.whatsapp.message.log`
- Jika user membalas "YA" atau "SETUJU", bisa:
  - Set flag consent untuk Fonnte (opsional)
  - Kirim pesan follow-up dengan link Meta opt-in

---

## 🔧 Implementasi yang Perlu Diperbaiki

### 1. Perbaiki Pesan Opt-In

**File:** `addons_odoo/sicantik_whatsapp/models/sicantik_permit_inherit.py`

**Method:** `action_send_opt_in_message()`

**Perubahan:**
- Ganti pesan yang meminta balasan "SETUJU" ke Fonnte
- Ganti dengan pesan yang berisi link WhatsApp Business Account (Meta)
- Tambahkan QR code untuk scan (opsional)

### 2. Buat Webhook Handler untuk Fonnte

**File Baru:** `addons_odoo/sicantik_whatsapp/controllers/fonnte_webhook.py`

**Fitur:**
- Endpoint: `/sicantik/whatsapp/fonnte/webhook`
- Menerima pesan inbound dari Fonnte
- Mencatat pesan di `sicantik.whatsapp.message.log`
- Jika pesan berisi "YA" atau "SETUJU", kirim pesan follow-up dengan link Meta opt-in

### 3. Generate Link WhatsApp Business Account

**Method Baru:** `whatsapp_opt_in_manager.py`

**Fitur:**
- Generate link WhatsApp: `https://wa.me/{phone_number}?text={message}`
- Generate QR code untuk link tersebut
- Return link dan QR code untuk digunakan di pesan opt-in

### 4. Update Dokumentasi

- Update `WHATSAPP_OPT_IN_STRATEGY.md` dengan informasi yang benar
- Tambahkan contoh pesan opt-in yang benar
- Tambahkan penjelasan tentang perbedaan Meta dan Fonnte opt-in

---

## 📊 Flow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│              User Baru Mendaftar Izin                        │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│         Cek Opt-In Status (Meta)                           │
│  - whatsapp_opt_in = True?                                 │
│  - 24-hour window aktif?                                   │
└────────────┬───────────────────────────────┬───────────────┘
             │                               │
        ┌────▼────┐                    ┌────▼────┐
        │  Ya     │                    │  Tidak  │
        └────┬────┘                    └────┬────┘
             │                               │
             ▼                               ▼
┌────────────────────────┐    ┌──────────────────────────────┐
│ Kirim via Meta ✅      │    │ Kirim Pesan Opt-In via       │
│ (Template Message)     │    │ Fonnte/Watzap                │
└────────────────────────┘    │                              │
                              │ Pesan berisi:                │
                              │ • Link WhatsApp Business     │
                              │ • QR Code                    │
                              │ • Instruksi: "Klik link..."  │
                              └──────────────┬───────────────┘
                                             │
                                             ▼
                              ┌──────────────────────────────┐
                              │ User Klik Link / Scan QR     │
                              │ → Membuka chat dengan Meta   │
                              └──────────────┬───────────────┘
                                             │
                                             ▼
                              ┌──────────────────────────────┐
                              │ User Mengirim Pesan ke Meta  │
                              │ → Auto Opt-In ✅             │
                              └──────────────┬───────────────┘
                                             │
                                             ▼
                              ┌──────────────────────────────┐
                              │ Sistem Deteksi Pesan Inbound │
                              │ → Set whatsapp_opt_in = True │
                              │ → Kirim template via Meta ✅ │
                              └──────────────────────────────┘
```

---

## ✅ Checklist Implementasi

- [ ] Perbaiki pesan opt-in di `action_send_opt_in_message()`
- [ ] Tambahkan method untuk generate link WhatsApp Business Account
- [ ] Tambahkan method untuk generate QR code
- [ ] Buat webhook handler untuk Fonnte (opsional, untuk tracking)
- [ ] Update dokumentasi
- [ ] Test flow opt-in end-to-end

---

## 📝 Catatan Penting

1. **Meta Opt-In adalah WAJIB** untuk mengirim template messages via Meta
2. **Fonnte tidak memerlukan opt-in ketat**, tapi tetap baik untuk best practice
3. **Dua sistem terpisah**: Opt-in ke Fonnte ≠ Opt-in ke Meta
4. **Routing otomatis**: Sistem sudah handle routing berdasarkan opt-in status
5. **24-Hour Window**: Manfaatkan window ini untuk notifikasi penting setelah user mengirim pesan

---

## 🔗 Referensi

- [Meta WhatsApp Business API - Opt-In Requirements](https://developers.facebook.com/docs/whatsapp/cloud-api/guides/send-messages)
- [Fonnte API Documentation](https://docs.fonnte.com)
- [Odoo Enterprise WhatsApp Module](https://www.odoo.com/app/whatsapp)

