# 📱 Panduan Mengirim Pesan Text WhatsApp Langsung

**Tanggal:** 28 November 2025  
**Provider:** Fonnte, Watzap.id  
**Status:** ✅ Ready to Use

---

## 🎯 Overview

Setelah koneksi Fonnte berhasil, Anda dapat mengirim pesan text langsung (bukan template) ke pemilik izin untuk:
- Meminta opt-in untuk notifikasi WhatsApp
- Memberikan informasi tentang layanan notifikasi WhatsApp
- Reminder atau pemberitahuan lainnya

---

## 🔧 Cara Menggunakan

### Method 1: Via Action Button di Form Permit

#### A. Kirim Pesan Opt-In

1. Buka form permit yang ingin dikirim pesan opt-in
2. Pastikan permit sudah memiliki partner dengan nomor WhatsApp
3. Klik button **"📱 Kirim Pesan Opt-In"** di header form
4. Sistem akan mengirim pesan text langsung ke nomor WhatsApp partner

**Pesan yang dikirim:**
```
Yth. [Nama Pemohon],

DPMPTSP Kabupaten Karo memberikan layanan notifikasi WhatsApp untuk memudahkan komunikasi terkait perizinan Anda.

Dengan layanan ini, Anda akan menerima:
✅ Notifikasi real-time saat izin selesai diproses
✅ Update status perizinan otomatis
✅ Peringatan masa berlaku izin
✅ Link download dokumen langsung

Untuk mengaktifkan layanan ini, silakan balas pesan ini dengan kata "YA" atau "SETUJU".

Terima kasih atas perhatiannya.

DPMPTSP Kabupaten Karo
Kabupaten Karo
```

#### B. Kirim Informasi Layanan Notifikasi

1. Buka form permit yang ingin dikirim informasi
2. Pastikan permit sudah memiliki partner dengan nomor WhatsApp
3. Klik button **"ℹ️ Info Layanan Notifikasi"** di header form
4. Sistem akan mengirim pesan informasi tentang layanan notifikasi WhatsApp

**Pesan yang dikirim:**
```
Yth. [Nama Pemohon],

📱 Layanan Notifikasi WhatsApp DPMPTSP Kabupaten Karo

Kami memberikan layanan notifikasi WhatsApp untuk memudahkan komunikasi terkait perizinan Anda.

Layanan yang tersedia:
✅ Notifikasi saat izin selesai diproses
✅ Update status perizinan otomatis
✅ Peringatan masa berlaku izin (90, 60, 30, 7 hari sebelum expired)
✅ Link download dokumen langsung
✅ Notifikasi perpanjangan izin

Layanan ini GRATIS dan dapat membantu Anda:
• Tetap update dengan status perizinan Anda
• Tidak ketinggalan informasi penting
• Akses dokumen dengan mudah via WhatsApp

Untuk pertanyaan atau bantuan, silakan hubungi:
📞 DPMPTSP Kabupaten Karo
🌐 https://sicantik.dotakaro.com

Terima kasih atas perhatiannya.

DPMPTSP Kabupaten Karo
Kabupaten Karo
```

---

### Method 2: Via Code (Programmatic)

#### Contoh 1: Kirim Pesan Opt-In

```python
# Di method atau action Odoo
dispatcher = self.env['sicantik.whatsapp.dispatcher']

message = f"""Yth. {permit.applicant_name},

DPMPTSP Kabupaten Karo memberikan layanan notifikasi WhatsApp untuk memudahkan komunikasi terkait perizinan Anda.

Dengan layanan ini, Anda akan menerima:
✅ Notifikasi real-time saat izin selesai diproses
✅ Update status perizinan otomatis
✅ Peringatan masa berlaku izin
✅ Link download dokumen langsung

Untuk mengaktifkan layanan ini, silakan balas pesan ini dengan kata "YA" atau "SETUJU".

Terima kasih atas perhatiannya.

DPMPTSP Kabupaten Karo
Kabupaten Karo"""

result = dispatcher.send_text_message(
    partner_id=permit.partner_id.id,
    message=message,
    provider_type='fonnte'  # Force Fonnte karena mendukung text message
)

if result.get('success'):
    _logger.info(f'✅ Pesan opt-in dikirim via {result.get("provider")}')
else:
    _logger.error(f'❌ Gagal: {result.get("error")}')
```

#### Contoh 2: Kirim Pesan Informasi

```python
# Di method atau action Odoo
dispatcher = self.env['sicantik.whatsapp.dispatcher']

message = f"""Yth. {permit.applicant_name},

📱 Layanan Notifikasi WhatsApp DPMPTSP Kabupaten Karo

Kami memberikan layanan notifikasi WhatsApp untuk memudahkan komunikasi terkait perizinan Anda.

Layanan yang tersedia:
✅ Notifikasi saat izin selesai diproses
✅ Update status perizinan otomatis
✅ Peringatan masa berlaku izin (90, 60, 30, 7 hari sebelum expired)
✅ Link download dokumen langsung
✅ Notifikasi perpanjangan izin

Layanan ini GRATIS dan dapat membantu Anda:
• Tetap update dengan status perizinan Anda
• Tidak ketinggalan informasi penting
• Akses dokumen dengan mudah via WhatsApp

Untuk pertanyaan atau bantuan, silakan hubungi:
📞 DPMPTSP Kabupaten Karo
🌐 https://sicantik.dotakaro.com

Terima kasih atas perhatiannya.

DPMPTSP Kabupaten Karo
Kabupaten Karo"""

result = dispatcher.send_text_message(
    partner_id=permit.partner_id.id,
    message=message,
    provider_type='fonnte'  # Force Fonnte karena mendukung text message
)
```

#### Contoh 3: Kirim Pesan Custom

```python
# Di method atau action Odoo
dispatcher = self.env['sicantik.whatsapp.dispatcher']

# Pesan custom Anda
custom_message = """Yth. Bapak/Ibu,

Ini adalah pesan custom dari DPMPTSP Kabupaten Karo.

Terima kasih."""

result = dispatcher.send_text_message(
    partner_id=partner_id,
    message=custom_message,
    provider_type='fonnte'  # Atau None untuk menggunakan default provider
)
```

---

## 📋 API Reference

### Method: `send_text_message()`

**Location:** `sicantik.whatsapp.dispatcher`

**Signature:**
```python
def send_text_message(self, partner_id, message, provider_type=None):
    """
    Kirim pesan teks langsung (bukan template) via provider
    
    Args:
        partner_id (int): ID partner penerima
        message (str): Teks pesan yang akan dikirim
        provider_type (str, optional): Force provider type ('fonnte', 'watzap', 'meta')
                                      Jika None, gunakan default provider
    
    Returns:
        dict: {
            'success': bool,
            'provider': provider name,
            'message_id': ID pesan (jika sukses),
            'error': error message (jika gagal)
        }
    """
```

**Parameters:**
- `partner_id` (required): ID partner yang akan menerima pesan
- `message` (required): Teks pesan yang akan dikirim
- `provider_type` (optional): 
  - `'fonnte'` - Force menggunakan Fonnte
  - `'watzap'` - Force menggunakan Watzap.id
  - `None` - Gunakan default provider dari Settings

**Returns:**
```python
{
    'success': True,  # atau False
    'provider': 'Fonnte',  # Nama provider yang digunakan
    'message_id': '123456789',  # ID pesan dari provider (jika sukses)
    'error': 'Error message'  # Hanya jika success=False
}
```

**Catatan:**
- Meta WhatsApp Business API **tidak mendukung** text message langsung, hanya template messages
- Jika `provider_type='meta'`, akan raise `UserError` dengan pesan yang jelas
- Untuk text message, gunakan Fonnte atau Watzap.id

---

## 🎨 Contoh Use Cases

### Use Case 1: Broadcast Pesan Opt-In ke Semua Pemilik Izin

```python
# Di cron job atau action server
@api.model
def cron_send_opt_in_broadcast(self):
    """
    Kirim pesan opt-in ke semua pemilik izin yang belum opt-in
    """
    dispatcher = self.env['sicantik.whatsapp.dispatcher']
    
    # Cari semua permit dengan partner yang belum opt-in
    permits = self.env['sicantik.permit'].search([
        ('partner_id', '!=', False),
        ('status', '=', 'active'),
    ])
    
    # Filter yang punya nomor WhatsApp
    permits_with_wa = permits.filtered(
        lambda p: p.partner_id and p.partner_id._get_mobile_or_phone()
    )
    
    message = """Yth. Bapak/Ibu,

DPMPTSP Kabupaten Karo memberikan layanan notifikasi WhatsApp untuk memudahkan komunikasi terkait perizinan Anda.

Untuk mengaktifkan layanan ini, silakan balas pesan ini dengan kata "YA" atau "SETUJU".

Terima kasih.

DPMPTSP Kabupaten Karo"""
    
    success_count = 0
    failed_count = 0
    
    for permit in permits_with_wa[:100]:  # Limit 100 untuk menghindari spam
        try:
            result = dispatcher.send_text_message(
                partner_id=permit.partner_id.id,
                message=message.replace('Bapak/Ibu', permit.applicant_name or 'Bapak/Ibu'),
                provider_type='fonnte'
            )
            
            if result.get('success'):
                success_count += 1
            else:
                failed_count += 1
                _logger.error(f'Gagal kirim ke {permit.applicant_name}: {result.get("error")}')
        except Exception as e:
            failed_count += 1
            _logger.error(f'Error kirim ke {permit.applicant_name}: {str(e)}')
    
    _logger.info(f'Broadcast selesai: {success_count} berhasil, {failed_count} gagal')
```

### Use Case 2: Reminder Manual untuk Izin yang Akan Expired

```python
# Di action button atau wizard
def action_send_expiry_reminder_manual(self):
    """
    Kirim reminder manual untuk izin yang akan expired
    """
    self.ensure_one()
    
    if not self.partner_id:
        return {'warning': 'Permit ini belum memiliki partner'}
    
    dispatcher = self.env['sicantik.whatsapp.dispatcher']
    
    sisa_hari = (self.expiry_date - fields.Date.today()).days if self.expiry_date else 0
    
    message = f"""Yth. {self.applicant_name or self.partner_id.name},

⚠️ Peringatan: Izin Anda akan segera berakhir!

Nomor Izin: {self.permit_number or self.registration_id}
Jenis Izin: {self.permit_type_id.name if self.permit_type_id else 'Tidak diketahui'}
Masa Berlaku: s/d {self.expiry_date.strftime('%d-%m-%Y') if self.expiry_date else 'Tidak diketahui'}
Sisa Waktu: {sisa_hari} hari

Segera lakukan perpanjangan untuk menghindari:
❌ Izin tidak berlaku
❌ Sanksi administrasi
❌ Proses ulang dari awal

Hubungi kami:
📞 DPMPTSP Kabupaten Karo
🌐 https://sicantik.dotakaro.com

Terima kasih.

DPMPTSP Kabupaten Karo
Kabupaten Karo"""
    
    result = dispatcher.send_text_message(
        partner_id=self.partner_id.id,
        message=message,
        provider_type='fonnte'
    )
    
    if result.get('success'):
        return {
            'type': 'ir.actions.client',
            'tag': 'display_notification',
            'params': {
                'title': 'Reminder Dikirim',
                'message': f'✅ Reminder berhasil dikirim ke {self.partner_id.name}',
                'type': 'success',
            }
        }
    else:
        return {
            'type': 'ir.actions.client',
            'tag': 'display_notification',
            'params': {
                'title': 'Error',
                'message': f'Gagal mengirim reminder: {result.get("error")}',
                'type': 'danger',
            }
        }
```

---

## ⚠️ Catatan Penting

### 1. Provider yang Mendukung Text Message

- ✅ **Fonnte** - Mendukung text message langsung
- ✅ **Watzap.id** - Mendukung text message langsung
- ❌ **Meta** - Hanya mendukung template messages (tidak bisa text langsung)

### 2. Rate Limiting

- Fonnte memiliki rate limit untuk pengiriman pesan
- Jangan kirim pesan massal terlalu cepat (disarankan delay 1-2 detik antar pesan)
- Gunakan cron job untuk broadcast massal dengan delay

### 3. Format Nomor Telepon

- Sistem akan otomatis menormalisasi nomor telepon
- Format yang didukung: `081234567890`, `+6281234567890`, `6281234567890`
- Pastikan nomor sudah valid dan terdaftar di WhatsApp

### 4. Error Handling

- Selalu cek `result['success']` sebelum melanjutkan
- Log error untuk troubleshooting
- Berikan feedback yang jelas ke user jika gagal

---

## 🔍 Troubleshooting

### Pesan tidak terkirim

1. **Cek Provider Configuration:**
   - Pastikan provider Fonnte sudah dikonfigurasi dengan lengkap
   - Pastikan token API valid (test connection berhasil)
   - Pastikan provider status = "Configured"

2. **Cek Partner:**
   - Pastikan partner memiliki nomor WhatsApp
   - Pastikan nomor sudah dinormalisasi dengan benar
   - Cek apakah nomor valid di WhatsApp

3. **Cek Log:**
   - Cek log Odoo untuk error detail
   - Cek response dari provider API

### Error "Provider tidak ditemukan"

- Pastikan default provider sudah di-set di Settings
- Pastikan provider aktif dan status = "Configured"
- Atau force provider dengan `provider_type='fonnte'`

### Error "Meta tidak mendukung text message"

- Meta WhatsApp Business API hanya mendukung template messages
- Gunakan Fonnte atau Watzap.id untuk text message langsung
- Atau gunakan `send_template_message()` untuk Meta

---

## 📚 Referensi

- [WhatsApp Messaging Implementation Guide](./WHATSAPP_MESSAGING_IMPLEMENTATION.md)
- [Fonnte API Documentation](https://docs.fonnte.com)
- [Multi-Provider Setup Guide](../addons_odoo/sicantik_whatsapp/docs/MULTI_PROVIDER_SETUP.md)

---

**Last Updated:** 28 November 2025  
**Author:** SICANTIK Development Team

