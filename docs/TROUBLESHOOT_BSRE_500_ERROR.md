# Troubleshooting BSRE API 500 Error

## Masalah
BSRE API mengembalikan error 500 saat proses tanda tangan dokumen:
```
BSRE API Error 500: {'timestamp': '...', 'status': 500, 'error': 'Internal Server Error', 'path': '/api/v2/sign/pdf'}
```

## Analisis Log

Dari log yang ada, payload sudah lengkap dan benar:
- ✅ NIK: terisi
- ✅ Passphrase: terisi
- ✅ File: ada dengan ukuran valid
- ✅ signatureProperties: ada dengan imageBase64

Tapi BSRE API tetap mengembalikan 500.

## Kemungkinan Penyebab

### 1. Masalah di Sisi BSRE API Server
- Internal error di server BSRE
- Server BSRE sedang maintenance
- Masalah dengan credentials atau authentication

### 2. Format Payload Tidak Sesuai
- Format JSON tidak sesuai dengan yang diharapkan BSRE API
- Field yang diharapkan BSRE API berbeda dengan yang dikirim
- Masalah dengan encoding atau format data

### 3. Masalah dengan File PDF
- File PDF corrupt atau format tidak didukung
- File PDF terlalu besar atau terlalu kecil
- File PDF memiliki struktur yang tidak kompatibel

### 4. Masalah dengan Koordinat atau Ukuran Signature
- Koordinat di luar bounds halaman PDF
- Ukuran signature tidak valid
- Format koordinat tidak sesuai

## Langkah Troubleshooting

### 1. Cek Log Lengkap Saat Signing

```bash
docker-compose logs -f odoo_sicantik | grep -E "(BSRE|Signing|Payload|imageBase64|Signature\[|ERROR|500)"
```

**Yang harus dicari:**
- `📋 Payload base:` - Apakah payload base valid?
- `📐 SIGNATURE PARAMETERS` - Apakah koordinat valid?
- `✅ Signature properties validated` - Apakah signature properties valid?
- `📤 BSRE API Request Payload` - Apakah payload lengkap?
- `BSRE API Response: 500` - Response dari BSRE
- `Response body:` - Detail error dari BSRE

### 2. Validasi Payload Manual

Dari log, pastikan:
- `nik` atau `email` terisi (tidak keduanya kosong)
- `passphrase` terisi
- `file` array tidak kosong dan berisi base64 PDF yang valid
- `signatureProperties` array tidak kosong
- `signatureProperties[0].imageBase64` tidak kosong dan valid base64

### 3. Test dengan File PDF Lain

Coba dengan file PDF yang berbeda untuk memastikan bukan masalah file:
- File PDF sederhana (1 halaman, tanpa gambar kompleks)
- File PDF yang sudah pernah berhasil di-sign sebelumnya

### 4. Hubungi Administrator BSRE API

Jika semua payload sudah benar tapi masih error 500:
- Hubungi administrator BSRE API untuk memeriksa log server mereka
- Minta detail error dari sisi server BSRE
- Verifikasi credentials dan authentication

### 5. Cek Dokumentasi BSRE API

Pastikan format payload sesuai dengan dokumentasi BSRE API v2:
- Field names harus exact match
- Data types harus sesuai (string, number, array)
- Format base64 harus valid

## Checklist Debugging

- [ ] Log lengkap saat signing sudah diambil
- [ ] Payload base sudah divalidasi (nik/email, passphrase, file)
- [ ] Signature properties sudah divalidasi (koordinat, ukuran, imageBase64)
- [ ] Koordinat dalam bounds (originX + width ≤ PAGE_WIDTH, originY + height ≤ PAGE_HEIGHT)
- [ ] imageBase64 tidak kosong dan valid base64
- [ ] Sudah dicoba dengan file PDF yang berbeda
- [ ] Sudah menghubungi administrator BSRE API

## Solusi Sementara

Jika error 500 masih terjadi setelah semua validasi:

1. **Coba dengan signature INVISIBLE** (tanpa image):
   - Set `signature_visible = False` di konfigurasi BSRE
   - Coba sign lagi

2. **Coba dengan koordinat default**:
   - Gunakan preset position (bottom_left, top_right, dll)
   - Hindari custom position untuk sementara

3. **Coba dengan ukuran signature kecil**:
   - Set `signature_size = 'small'` (80x60)
   - Coba sign lagi

4. **Cek versi BSRE API**:
   - Pastikan menggunakan endpoint yang benar (`/api/v2/sign/pdf`)
   - Verifikasi versi API yang digunakan

## Catatan Penting

- Error 500 dari BSRE API biasanya berarti masalah di sisi server BSRE
- Payload yang dikirim sudah divalidasi dan lengkap
- Perlu koordinasi dengan administrator BSRE API untuk troubleshooting lebih lanjut

