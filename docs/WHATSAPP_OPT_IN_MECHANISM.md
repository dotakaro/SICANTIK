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
- **⚠️ PENTING**: Setelah 24 jam berlalu, kita TIDAK bisa kirim template message lagi kecuali:
  - User sudah opt-in formal (ada consent yang tercatat di database)
  - User mengirim pesan inbound lagi (reset 24-hour window)
  - Template message yang sudah pre-approved untuk nomor tersebut di Meta Business Manager

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

### Scenario 2: Pesan Opt-In via Fonnte (Flow Sederhana)

**Flow yang BENAR (Tanpa Response YA/TIDAK):**
1. Kirim pesan via Fonnte dengan link WhatsApp Business Account
2. User klik link → membuka chat dengan Meta WhatsApp Business Account
3. User mengirim pesan → auto opt-in ke Meta ✅
4. Sistem bisa kirim template messages via Meta

**Pesan yang BENAR:**
```
Yth. [Nama Pemohon],

DPMPTSP Kabupaten Karo memberikan layanan notifikasi WhatsApp untuk memudahkan komunikasi terkait perizinan Anda.

Dengan layanan ini, Anda akan menerima:
✅ Notifikasi real-time saat izin selesai diproses
✅ Update status perizinan otomatis
✅ Peringatan masa berlaku izin
✅ Link download dokumen langsung

Untuk mengaktifkan layanan ini, silakan klik link berikut:

🔗 https://wa.me/6281234567890?text=Halo

Setelah Anda mengirim pesan ke nomor WhatsApp Business Account di atas, notifikasi akan aktif secara otomatis.

Terima kasih atas perhatiannya.

DPMPTSP Kabupaten Karo
Kabupaten Karo
```

**Catatan:**
- ✅ Tidak perlu minta response YA/TIDAK
- ✅ Langsung kirim link, user klik → langsung opt-in
- ✅ Lebih sederhana dan cepat
- ✅ Sesuai dengan kebijakan Meta (user harus mengirim pesan ke WhatsApp Business Account)

**Pesan yang SALAH (Implementasi Lama):**
```
...
Untuk mengaktifkan layanan ini, silakan balas pesan ini dengan kata "YA" atau "SETUJU".
...
```
❌ Ini salah karena:
- Membalas ke Fonnte tidak akan membuat user opt-in ke Meta
- Meta tidak akan tahu bahwa user sudah memberikan consent
- Membutuhkan 2 kali response (balas Fonnte + kirim ke Meta)

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

- [x] Perbaiki pesan opt-in di `action_send_opt_in_message()` - Flow sederhana tanpa response YA/TIDAK
- [x] Tambahkan method untuk generate link WhatsApp Business Account
- [ ] Tambahkan method untuk generate QR code (opsional)
- [ ] Buat webhook handler untuk Fonnte (opsional, untuk tracking)
- [x] Update dokumentasi dengan penjelasan 24-hour window
- [ ] Test flow opt-in end-to-end

---

## 📋 FAQ: 24-Hour Window

### Q: Setelah 24 jam, apakah masih bisa kirim template message?

**A: TIDAK**, setelah 24 jam berlalu sejak pesan inbound terakhir, kita **TIDAK bisa kirim template message lagi** kecuali:

1. **User sudah opt-in formal** (ada consent yang tercatat di database dengan `whatsapp_opt_in = True`)
2. **User mengirim pesan inbound lagi** (reset 24-hour window)
3. **Template message yang sudah pre-approved** untuk nomor tersebut di Meta Business Manager

### Q: Bagaimana cara memastikan user opt-in sebelum 24 jam habis?

**A:**
- Kirim pesan opt-in via Fonnte dengan link WhatsApp Business Account
- User klik link → kirim pesan ke Meta → auto opt-in
- Setelah opt-in, sistem bisa kirim template messages kapan saja (tidak terbatas 24 jam)

### Q: Apakah 24-hour window berlaku untuk semua jenis pesan?

**A:**
- **24-hour window**: Hanya untuk template messages
- **Setelah 24 jam**: Hanya bisa kirim template messages yang sudah pre-approved
- **Session messages** (pesan bebas): Hanya bisa dikirim dalam 24-hour window

### Q: Bagaimana jika user tidak klik link opt-in?

**A:**
- User tetap bisa menerima notifikasi via Fonnte/Watzap (fallback provider)
- Tapi tidak bisa menerima template messages via Meta
- Sistem akan otomatis route ke provider yang sesuai berdasarkan opt-in status

### Q: Apakah Fonnte mendukung button messages seperti Meta?

**A:**
- Fonnte adalah gateway WhatsApp Indonesia yang lebih sederhana
- Tidak memiliki fitur button/list messages seperti Meta
- Tapi kita bisa kirim text message dengan link yang jelas
- Link WhatsApp (`https://wa.me/...`) akan otomatis menjadi clickable di WhatsApp

---

## 🔧 Cara Membuat Opt-In Formal

### Flow Opt-In Formal Otomatis

Sistem sudah diatur untuk **otomatis mencatat opt-in formal** ketika user mengirim pesan inbound ke Meta WhatsApp Business Account. Berikut cara kerjanya:

#### 1. **Kirim Pesan Opt-In via Fonnte**

Gunakan button **"📱 Kirim Pesan Opt-In"** di form permit untuk mengirim pesan dengan link WhatsApp Business Account:

```
Yth. [Nama Pemohon],

DPMPTSP Kabupaten Karo memberikan layanan notifikasi WhatsApp untuk memudahkan komunikasi terkait perizinan Anda.

Dengan layanan ini, Anda akan menerima:
✅ Notifikasi real-time saat izin selesai diproses
✅ Update status perizinan otomatis
✅ Peringatan masa berlaku izin
✅ Link download dokumen langsung

Untuk mengaktifkan layanan ini, silakan klik link berikut:

🔗 https://wa.me/6281234567890?text=Halo

Setelah Anda mengirim pesan ke nomor WhatsApp Business Account di atas, notifikasi akan aktif secara otomatis.

Terima kasih atas perhatiannya.

DPMPTSP Kabupaten Karo
Kabupaten Karo
```

#### 2. **User Klik Link dan Kirim Pesan**

- User klik link → membuka chat dengan Meta WhatsApp Business Account
- User kirim pesan apa saja (misalnya "Halo")
- Meta mengirim webhook ke Odoo dengan pesan inbound

#### 3. **Sistem Otomatis Mencatat Opt-In Formal**

Setelah Odoo core memproses pesan inbound:

1. **Odoo Core**: Remove nomor dari blacklist (artinya opt-in)
2. **Sistem Kita**: 
   - Cari partner berdasarkan nomor WhatsApp
   - Set `whatsapp_opt_in = True` di partner record
   - Set `whatsapp_opt_in_date` dengan timestamp saat ini
   - Log opt-in untuk tracking

#### 4. **Setelah Opt-In Formal Tercatat**

✅ **Bisa kirim template messages kapan saja** (tidak terbatas 24 jam)
✅ **Opt-in tercatat permanen** di database dengan timestamp
✅ **Tidak perlu khawatir 24-hour window** habis

### Implementasi Teknis

**File:** `addons_odoo/sicantik_whatsapp/models/whatsapp_message_inherit.py`

```python
@api.model_create_multi
def create(self, vals_list):
    """
    Override create untuk memastikan opt-in formal tercatat
    setelah pesan inbound dibuat oleh Odoo core.
    """
    messages = super().create(vals_list)
    
    for message in messages:
        if message.message_type == 'inbound' and message.mobile_number_formatted:
            # Panggil opt-in manager untuk set opt-in formal
            opt_in_manager = self.env['whatsapp.opt.in.manager']
            opt_in_manager.auto_opt_in_from_inbound_message(message.id)
    
    return messages
```

**File:** `addons_odoo/sicantik_whatsapp/models/whatsapp_opt_in_manager.py`

```python
def auto_opt_in_from_inbound_message(self, whatsapp_message_id):
    """
    Auto opt-in formal ketika user mengirim pesan inbound ke Meta WhatsApp Business Account
    """
    # Cari partner berdasarkan nomor WhatsApp
    # Set whatsapp_opt_in = True jika belum
    # Catat timestamp opt-in
```

### Verifikasi Opt-In Formal

Untuk memverifikasi bahwa opt-in formal sudah tercatat:

1. **Cek di Partner Record:**
   - Buka form partner
   - Cek field **"WhatsApp Notifications"** = ✅ (True)
   - Cek field **"Opt-in Date"** = timestamp saat opt-in

2. **Cek di Log:**
   ```
   ✅ Opt-in formal tercatat untuk [Nama Partner] ([Nomor]) dari pesan inbound WhatsApp Business Account
   ```

3. **Test Kirim Template Message:**
   - Setelah opt-in formal tercatat, coba kirim template message
   - Harusnya bisa kirim kapan saja (tidak terbatas 24 jam)

### Troubleshooting

**Q: Opt-in formal tidak tercatat setelah user kirim pesan?**

**A:** Cek beberapa hal:
1. Pastikan webhook Meta sudah dikonfigurasi dengan benar
2. Pastikan nomor WhatsApp di partner record sesuai dengan nomor yang mengirim pesan
3. Cek log Odoo untuk error messages
4. Pastikan module `sicantik_whatsapp` sudah di-upgrade

**Q: Partner tidak ditemukan saat proses opt-in?**

**A:** 
- Pastikan nomor WhatsApp di partner record sudah diisi dengan benar
- Sistem akan mencari partner dengan berbagai format nomor (dengan/tanpa +, spasi, dll)
- Jika partner tidak ditemukan, opt-in tidak bisa dicatat tapi pesan tetap diproses oleh Odoo core

**Q: Bagaimana jika user tidak klik link opt-in?**

**A:**
- User tetap bisa menerima notifikasi via Fonnte/Watzap (fallback provider)
- Tapi tidak bisa menerima template messages via Meta
- Sistem akan otomatis route ke provider yang sesuai berdasarkan opt-in status

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

