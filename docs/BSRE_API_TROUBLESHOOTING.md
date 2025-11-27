# BSRE API Error 500 - Troubleshooting Guide

## Masalah
BSRE API mengembalikan error 500 (Internal Server Error) saat proses tanda tangan dokumen.

## Analisis Error 500

Error 500 dari BSRE API biasanya berarti:
- **Masalah di sisi server BSRE** (bukan di kode kita)
- Server BSRE mengalami internal error saat memproses request
- Perlu dicek log server BSRE untuk detail error

## Checklist Troubleshooting

### 1. Verifikasi Payload Format
Pastikan payload sudah sesuai dengan dokumentasi Postman:

```json
{
    "nik": "1206012804770002",
    "passphrase": "Kasih007!!",
    "signatureProperties": [{
        "imageBase64": "...",
        "tampilan": "VISIBLE",
        "page": 1,
        "originX": 505.0,
        "originY": 772.0,
        "width": 80.0,
        "height": 60.0,
        "location": "null",
        "reason": "null",
        "contactInfo": "null"
    }],
    "file": ["..."]
}
```

**Yang sudah diperbaiki:**
- ✅ Urutan field: `nik`, `passphrase`, `signatureProperties`, `file`
- ✅ Urutan field di `signatureProperties`: `imageBase64` di urutan pertama
- ✅ Field `contactInfo`, `location`, `reason` dengan nilai `"null"`
- ✅ Tidak menyertakan field `email` yang kosong jika menggunakan NIK

### 2. Verifikasi Authentication
Pastikan username dan password untuk Basic Auth sudah benar:
- Username: `esign` (dari informasi user)
- Password: `qwerty` (dari informasi user)
- Pastikan credentials masih valid di sistem BSRE

### 3. Verifikasi NIK dan Passphrase
Pastikan:
- NIK: `1206012804770002` masih valid di sistem BSRE
- Passphrase: `Kasih007!!` masih benar untuk NIK tersebut
- NIK dan passphrase sudah terdaftar dan aktif di sistem BSRE

### 4. Verifikasi File PDF
Pastikan:
- File PDF valid (bukan corrupt)
- File PDF tidak terlalu besar
- File PDF dalam format yang didukung BSRE API

### 5. Cek Log Server BSRE
**PENTING:** Error 500 biasanya masalah di sisi server BSRE. Perlu:
- Hubungi administrator BSRE untuk melihat log server mereka
- Minta detail error dari sisi server BSRE
- Verifikasi apakah server BSRE sedang maintenance atau ada masalah

## Langkah Selanjutnya

Jika semua checklist di atas sudah benar tapi masih error 500:

1. **Hubungi Administrator BSRE**
   - Minta mereka memeriksa log server untuk detail error
   - Verifikasi credentials masih valid
   - Verifikasi NIK dan passphrase masih aktif

2. **Test dengan Postman**
   - Gunakan contoh request dari Postman collection
   - Pastikan request dari Postman berhasil
   - Bandingkan payload dari Postman dengan payload dari Odoo

3. **Cek Versi API**
   - Pastikan menggunakan endpoint yang benar: `/api/v2/sign/pdf`
   - Verifikasi versi API yang digunakan masih didukung

## Catatan Penting

- Error 500 = Internal Server Error dari BSRE API
- Payload yang dikirim sudah sesuai dokumentasi Postman
- Masalah kemungkinan besar di sisi server BSRE, bukan di kode kita
- Perlu koordinasi dengan administrator BSRE untuk troubleshooting lebih lanjut

