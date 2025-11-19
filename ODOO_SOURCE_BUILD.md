# Odoo Build dari Source GitHub

## Status

✅ **Build Berhasil**: Odoo saas-18.4 telah di-build dari source GitHub
✅ **Docker Image**: `sicantik-odoo:18.4` 
✅ **Source Code**: `odoo_source/` (dari GitHub branch saas-18.4)

## Konfigurasi

### Dockerfile
- Base image: `python:3.12-slim`
- Source: `odoo_source/` (clone dari GitHub)
- Dependencies: Semua requirements dari Odoo + dependencies untuk SICANTIK modules

### Docker Compose
- Container: `odoo_companion` dan `odoo_companion_standalone`
- Image: `sicantik-odoo:18.4`
- Volumes:
  - `./addons_odoo:/mnt/extra-addons:ro`
  - `./enterprise:/mnt/enterprise-addons:ro`
  - `./config/odoo.conf:/etc/odoo/odoo.conf:ro`

## Catatan Penting

1. **Versi Odoo**: Menggunakan branch `saas-18.4` dari GitHub
2. **Source Code**: Branch `saas-18.4` kompatibel dengan Enterprise Addons 18.4
3. **models.Constraint**: Tersedia di saas-18.4, tidak perlu compatibility layer

## Langkah Selanjutnya

1. Install Enterprise Addons (whatsapp, dll) - langsung bisa digunakan
2. Verifikasi `models.Constraint` tersedia (sudah built-in di saas-18.4)

## Rebuild Image

```bash
# Rebuild image dari source
docker-compose build --no-cache odoo_companion

# Restart container
docker-compose restart odoo_companion odoo_companion_standalone
```

## Update Source Code

```bash
# Update source code dari GitHub
cd odoo_source
git pull origin saas-18.4
cd ..

# Rebuild image
docker-compose build --no-cache odoo_companion
```

