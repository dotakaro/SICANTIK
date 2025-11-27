# Troubleshooting 404 Not Found untuk Route Verifikasi TTE

## Masalah
URL verifikasi `/sicantik/tte/verify/DOC/2025/00001` mengembalikan 404 Not Found.

## Langkah Troubleshooting

### 1. Pastikan Module Ter-Install dan Ter-Upgrade

```bash
# Di Odoo UI:
Apps → Update Apps List
Apps → SICANTIK TTE → Upgrade
```

### 2. Restart Odoo Service

```bash
docker-compose restart odoo_sicantik
```

### 3. Test Route Test (Memastikan Controller Ter-Load)

Setelah deploy, test route test terlebih dahulu:
```
https://sicantik.dotakaro.com/sicantik/test
```

Jika route test ini **BERHASIL**, berarti controller ter-load dengan benar.
Jika route test ini **404**, berarti ada masalah dengan:
- Module belum ter-install/upgrade
- Controller tidak ter-load
- Route tidak terdaftar

### 4. Cek Log Odoo Saat Akses URL

```bash
docker-compose logs -f odoo_sicantik | grep -E "(VERIFY|TEST|Route|404)"
```

**Yang harus muncul jika route terpanggil:**
```
[VERIFY] ✅✅✅ ROUTE HIT! Controller method called! ✅✅✅
[VERIFY] Request untuk verifikasi dokumen: DOC/2025/00001
```

**Jika tidak muncul log ini**, berarti route tidak terpanggil sama sekali (masalah routing).

### 5. Cek Apakah Website Module Ter-Install

Route menggunakan `website=True`, jadi module `website` HARUS ter-install:

```bash
# Di Odoo UI:
Apps → Website → Install (jika belum)
```

### 6. Cek Nginx Configuration

Pastikan Nginx forward request dengan benar ke Odoo:

```nginx
location /sicantik/ {
    proxy_pass http://odoo_sicantik:8069;
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
}
```

### 7. Test Langsung ke Odoo (Bypass Nginx)

Test langsung ke Odoo container:

```bash
# Dari server production
curl -v "http://localhost:8069/sicantik/tte/verify/DOC/2025/00001"
# atau
curl -v "http://localhost:8069/sicantik/test"
```

Jika langsung ke Odoo **BERHASIL** tapi via Nginx **404**, berarti masalah di Nginx.

### 8. Cek Odoo Config

Pastikan di `odoo.conf`:
```ini
proxy_mode = True  # Jika menggunakan reverse proxy
```

### 9. Cek Route Terdaftar di Odoo

Di Odoo UI:
- Settings → Technical → Actions → Controllers
- Cari route `/sicantik/tte/verify`
- Pastikan route terdaftar

### 10. Force Reload Routes

Jika route tidak terdaftar, coba:
1. Restart Odoo dengan `-u sicantik_tte`:
   ```bash
   docker-compose exec odoo_sicantik odoo-bin -u sicantik_tte -d sicantik --stop-after-init
   docker-compose restart odoo_sicantik
   ```

## Solusi Berdasarkan Gejala

### Gejala: Route Test `/sicantik/test` 404
**Penyebab**: Controller tidak ter-load atau module tidak ter-install
**Solusi**:
1. Pastikan `addons_odoo/sicantik_tte/__init__.py` mengimport controllers
2. Pastikan `addons_odoo/sicantik_tte/controllers/__init__.py` mengimport main
3. Upgrade module: `Apps → SICANTIK TTE → Upgrade`
4. Restart Odoo

### Gejala: Route Test `/sicantik/test` BERHASIL tapi `/sicantik/tte/verify` 404
**Penyebab**: Masalah spesifik dengan route verifikasi
**Solusi**:
1. Cek log Odoo untuk error spesifik
2. Pastikan document_number ada di database
3. Pastikan dokumen sudah di-sign (state = 'signed' atau 'verified')

### Gejala: Langsung ke Odoo BERHASIL tapi via Nginx 404
**Penyebab**: Masalah konfigurasi Nginx
**Solusi**:
1. Periksa konfigurasi Nginx
2. Pastikan `proxy_pass` benar
3. Reload Nginx: `nginx -s reload`

### Gejala: Tidak ada log `[VERIFY]` sama sekali
**Penyebab**: Route tidak terpanggil (masalah routing Odoo)
**Solusi**:
1. Pastikan module `website` ter-install
2. Pastikan `website=True` di decorator route
3. Force upgrade module dengan `-u sicantik_tte`
4. Restart Odoo

## Checklist Final

- [ ] Module `sicantik_tte` ter-install dan ter-upgrade
- [ ] Module `website` ter-install
- [ ] Route test `/sicantik/test` berhasil
- [ ] Log Odoo menunjukkan `[VERIFY] ✅✅✅ ROUTE HIT!`
- [ ] Nginx configuration benar
- [ ] Odoo config `proxy_mode = True` (jika pakai reverse proxy)
- [ ] Dokumen ada di database dengan state 'signed' atau 'verified'
- [ ] Odoo service sudah di-restart setelah perubahan

## Kontak Support

Jika masih 404 setelah semua langkah di atas:
1. Screenshot log Odoo saat akses URL
2. Screenshot hasil test route `/sicantik/test`
3. Screenshot konfigurasi Nginx
4. Screenshot Apps → SICANTIK TTE (status install)

