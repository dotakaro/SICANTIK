# Troubleshooting: Balasan Otomatis Pesan Persetujuan WhatsApp

## Masalah

User mengirim pesan "Ya Saya Setuju" ke nomor WhatsApp Meta Business Account, tapi tidak ada balasan otomatis dari Odoo.

## Analisis Log

Untuk menganalisis masalah ini, cek log Odoo dengan filter berikut:

### 1. Cek Apakah Pesan Inbound Terdeteksi

Cari log dengan pattern:
```
🔍 [DEBUG] whatsapp.message.create() dipanggil
🔍 [DEBUG] Memproses pesan ID=...
```

**Yang harus muncul:**
- `message_type=inbound`
- `mobile=` (nomor pengirim)
- `body=` (isi pesan)

**Jika tidak muncul:**
- Webhook Meta mungkin tidak dikonfigurasi dengan benar
- Pesan inbound tidak sampai ke Odoo
- Cek webhook URL di Meta Business Manager

### 2. Cek Apakah Body Terdeteksi

Cari log dengan pattern:
```
🔍 [DEBUG] Body text untuk Message ID=...
```

**Yang harus muncul:**
- Body text yang berisi "ya saya setuju" atau variasi lainnya

**Jika body kosong:**
- `mail_message_id` mungkin belum terisi saat `create()` dipanggil
- Body mungkin terisi setelah `create()` selesai
- Perlu gunakan `@api.model_create_multi` dengan delay atau `@api.onchange`

### 3. Cek Apakah Pesan Persetujuan Terdeteksi

Cari log dengan pattern:
```
🔍 [DEBUG] Is consent message: True
✅ Pesan persetujuan terdeteksi
```

**Jika `Is consent message: False`:**
- Keyword tidak cocok dengan isi pesan
- Body mungkin dalam format HTML yang perlu di-decode
- Cek keyword yang digunakan: `['ya saya setuju', 'setuju', 'saya setuju', ...]`

### 4. Cek Apakah `_send_consent_reply()` Dipanggil

Cari log dengan pattern:
```
🔍 [DEBUG] _send_consent_reply() dipanggil
```

**Jika tidak muncul:**
- `is_consent_message` adalah `False`
- Ada error sebelum `_send_consent_reply()` dipanggil
- Cek log error sebelumnya

### 5. Cek Apakah WhatsApp Account Ditemukan

Cari log dengan pattern:
```
🔍 [DEBUG] WhatsApp Account ditemukan: ...
```

**Jika tidak muncul:**
- `wa_account_id` tidak terisi di pesan inbound
- Cek apakah WhatsApp Account sudah dikonfigurasi dengan benar

### 6. Cek Apakah Balasan Terkirim

Cari log dengan pattern:
```
✅ Balasan persetujuan terkirim ke ...
```

**Jika tidak muncul:**
- Ada error saat mengirim balasan
- Cek log error dengan pattern: `❌ Error saat mengirim balasan persetujuan`

## Kemungkinan Masalah dan Solusi

### Masalah 1: Body Tidak Terdeteksi

**Gejala:**
- Log menunjukkan `Body text untuk Message ID=...: ...` kosong atau tidak sesuai

**Penyebab:**
- `mail_message_id` belum terisi saat `create()` dipanggil
- Body ada di `mail_message_id.body`, bukan langsung di `whatsapp.message.body`

**Solusi:**
- Gunakan `message.mail_message_id.body` untuk membaca body
- Tambahkan delay atau gunakan `@api.onchange` untuk membaca body setelah `mail_message_id` terisi

### Masalah 2: Keyword Tidak Cocok

**Gejala:**
- Log menunjukkan `Is consent message: False` padahal pesan berisi "Ya Saya Setuju"

**Penyebab:**
- Body dalam format HTML yang perlu di-decode
- Keyword case-sensitive atau format berbeda

**Solusi:**
- Pastikan `html2plaintext()` digunakan untuk decode HTML
- Pastikan `.lower()` digunakan untuk case-insensitive matching
- Tambahkan lebih banyak variasi keyword

### Masalah 3: WhatsApp Account Tidak Ditemukan

**Gejala:**
- Log menunjukkan `⚠️ Tidak bisa kirim balasan: WhatsApp Account tidak ditemukan`

**Penyebab:**
- `wa_account_id` tidak terisi di pesan inbound
- WhatsApp Account belum dikonfigurasi

**Solusi:**
- Cek apakah WhatsApp Account sudah dikonfigurasi di Odoo
- Cek apakah webhook Meta sudah dikonfigurasi dengan benar
- Pastikan `wa_account_id` terisi saat pesan inbound dibuat

### Masalah 4: Error Saat Mengirim Balasan

**Gejala:**
- Log menunjukkan `❌ Error saat mengirim balasan persetujuan`

**Penyebab:**
- WhatsAppApi error
- Format send_vals tidak sesuai
- Parent message ID tidak ditemukan

**Solusi:**
- Cek error message lengkap di log
- Pastikan format `send_vals` sesuai dengan yang diharapkan WhatsAppApi
- Pastikan `msg_uid` terisi di pesan inbound untuk reply

## Langkah Debugging

1. **Aktifkan Logging Detail:**
   - Pastikan log level di `odoo.conf` adalah `INFO` atau `DEBUG`
   - Filter log untuk module `sicantik_whatsapp`

2. **Test dengan Pesan Sederhana:**
   - Kirim pesan "SETUJU" (tanpa "Ya Saya")
   - Cek apakah keyword terdeteksi

3. **Cek Webhook Meta:**
   - Pastikan webhook URL benar: `https://sicantik.dotakaro.com/whatsapp/webhook/`
   - Pastikan webhook sudah verified di Meta Business Manager
   - Cek apakah webhook menerima request dari Meta

4. **Cek Database:**
   - Query `whatsapp.message` untuk melihat pesan inbound yang baru dibuat
   - Cek apakah `message_type='inbound'` dan `mobile_number_formatted` terisi
   - Cek apakah `mail_message_id` terisi dan `body` ada di `mail.message`

5. **Test Manual:**
   - Buat `whatsapp.message` manual dengan `message_type='inbound'`
   - Set `mail_message_id` dengan body yang berisi "Ya Saya Setuju"
   - Cek apakah `_send_consent_reply()` dipanggil

## Contoh Log yang Benar

```
🔍 [DEBUG] whatsapp.message.create() dipanggil dengan 1 pesan
   [0] message_type=inbound, mobile=+6281234567890, body=...
🔍 [DEBUG] whatsapp.message.create() selesai, 1 pesan dibuat
🔍 [DEBUG] Memproses pesan ID=123, type=inbound, mobile=+6281234567890, body=...
🔍 [DEBUG] Body text untuk Message ID=123: ya saya setuju menerima pesan notifikasi dari dpmptsp...
🔍 [DEBUG] Is consent message: True untuk Message ID=123
✅ Pesan persetujuan terdeteksi untuk nomor +6281234567890 (Message ID: 123)
   Isi pesan: ya saya setuju menerima pesan notifikasi dari dpmptsp...
🔍 [DEBUG] _send_consent_reply() dipanggil untuk Message ID=123, Mobile=+6281234567890
🔍 [DEBUG] WhatsApp Account ditemukan: WhatsApp Business Account (ID: 1)
🔍 [DEBUG] Parent message ID ditemukan: wamid.xxx...
✅ Balasan persetujuan terkirim ke +6281234567890 (Message UID: wamid.yyy...)
```

## File yang Terlibat

- `addons_odoo/sicantik_whatsapp/models/whatsapp_message_inherit.py` - Override `whatsapp.message.create()`
- `enterprise/whatsapp/models/whatsapp_account.py` - Method `_process_messages()` untuk memproses webhook
- `enterprise/whatsapp/models/discuss_channel.py` - Method `_notify_thread()` untuk membuat `whatsapp.message`
- `enterprise/whatsapp/controller/main.py` - Webhook controller `/whatsapp/webhook/`

## Catatan Penting

- Pesan inbound dibuat melalui `discuss_channel.message_post()` yang membuat `mail.message` terlebih dahulu
- `whatsapp.message` memiliki relasi ke `mail.message` melalui `mail_message_id`
- Body sebenarnya ada di `mail.message.body`, bukan langsung di `whatsapp.message.body`
- `whatsapp.message.body` adalah related field ke `mail_message_id.body`
- Saat `create()` dipanggil, `mail_message_id` mungkin sudah terisi atau belum, tergantung timing

