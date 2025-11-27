# Troubleshooting TTE Verification 404

## 🔍 Masalah: 404 Not Found saat akses URL verifikasi

Jika URL verifikasi mengembalikan 404, ikuti langkah troubleshooting berikut:

## ✅ Checklist

### 1. Pastikan Website Module Ter-install

```bash
# Di Odoo, buka Apps
# Cari "Website" 
# Pastikan status: Installed ✅
```

Jika belum ter-install:
- Install module `website` dari Apps menu
- Restart Odoo setelah install

### 2. Pastikan Module sicantik_tte Ter-install

```bash
# Di Odoo, buka Apps
# Cari "SICANTIK TTE"
# Pastikan status: Installed ✅
```

Jika belum ter-install atau perlu update:
- Update module `sicantik_tte`
- Restart Odoo

### 3. Restart Odoo Service

Setelah install/update module, restart Odoo:

```bash
# Di server produksi
cd /opt/sicantik
docker-compose restart odoo_sicantik
```

### 4. Cek Log Odoo

```bash
# Di server produksi
docker-compose logs -f odoo_sicantik | grep VERIFY
```

Atau cek log lengkap:
```bash
docker-compose logs odoo_sicantik | tail -100
```

### 5. Test Route Langsung

Test apakah route terdaftar:

```bash
# Di server produksi, akses langsung ke Odoo (bukan via nginx)
curl -v http://localhost:8065/sicantik/tte/verify/DOC/2025/00001
```

Jika berhasil di localhost tapi tidak via domain, masalahnya di nginx reverse proxy.

### 6. Cek Nginx Configuration

Pastikan nginx forward request ke Odoo:

```nginx
location /sicantik/tte/ {
    proxy_pass http://localhost:8065/sicantik/tte/;
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
}
```

### 7. Cek Dokumen di Database

Pastikan dokumen ada dan state-nya benar:

```python
# Di Odoo shell atau via UI
document = env['sicantik.document'].search([('document_number', '=', 'DOC/2025/00001')])
print(f"Found: {bool(document)}")
if document:
    print(f"State: {document.state}")
    print(f"Verification URL: {document.verification_url}")
```

### 8. Test Route Registration

Di Odoo, buka:
- Settings → Technical → Actions → Controllers
- Cari route `/sicantik/tte/verify`
- Pastikan route terdaftar

## 🔧 Solusi Umum

### Jika Route Tidak Terdaftar

1. **Update Module**:
   ```bash
   # Di Odoo UI
   Apps → Update Apps List
   Apps → SICANTIK TTE → Upgrade
   ```

2. **Restart Odoo**:
   ```bash
   docker-compose restart odoo_sicantik
   ```

3. **Cek Controller Import**:
   Pastikan `addons_odoo/sicantik_tte/__init__.py` mengimport controllers:
   ```python
   from . import controllers
   ```

### Jika Route Terdaftar Tapi Masih 404

1. **Cek Website Module**: Pastikan module `website` ter-install
2. **Cek Nginx**: Pastikan nginx forward request dengan benar
3. **Cek Odoo Config**: Pastikan `proxy_mode = True` di `odoo.conf` jika menggunakan reverse proxy

## 📝 Catatan

- Route menggunakan `auth='public'` jadi tidak perlu login
- Route menggunakan `website=True` jadi memerlukan module `website`
- Route menggunakan `csrf=False` untuk public access
- Tidak ada hubungan dengan MinIO - verifikasi hanya membaca dari database

## 🆘 Jika Masih Error

1. Cek log Odoo untuk error detail
2. Test route langsung ke Odoo (bypass nginx)
3. Pastikan semua module dependencies ter-install
4. Restart Odoo setelah perubahan

