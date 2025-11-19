# Upgrade Odoo ke Versi 18.4 Enterprise

## Status Saat Ini

- **Odoo yang terinstall**: 18.0-20251106 (Community Edition)
- **Enterprise Addons**: 18.4 (memerlukan Odoo Enterprise 18.4)
- **Masalah**: `models.Constraint` tidak tersedia di Odoo 18.0 CE

## Solusi

### Opsi 1: Install Odoo Enterprise 18.4 (Recommended)

Jika Anda memiliki **Odoo Enterprise License**, ikuti langkah berikut:

1. **Download Odoo Enterprise 18.4**
   - Akses Odoo Enterprise portal
   - Download installer atau source code untuk versi 18.4

2. **Build Docker Image dari Source**
   ```bash
   # Jika punya source code Odoo Enterprise 18.4
   # Update Dockerfile.odoo untuk build dari source
   ```

3. **Atau Install Secara Manual**
   - Install Odoo Enterprise 18.4 di server
   - Update docker-compose.yml untuk menggunakan installation path

### Opsi 2: Downgrade Enterprise Addons ke Versi 18.0

Jika tidak memiliki license Enterprise, downgrade enterprise addons:

1. Download enterprise addons versi 18.0 (kompatibel dengan Odoo CE 18.0)
2. Replace folder `enterprise/` dengan versi 18.0

### Opsi 3: Patch Odoo 18.0 CE (Temporary)

**TIDAK DISARANKAN** - Hanya untuk development/testing:

Tambahkan compatibility layer di custom module untuk convert `models.Constraint` ke `_sql_constraints`.

## Verifikasi Versi

Setelah upgrade, verifikasi versi:
```bash
docker exec odoo_companion_standalone odoo --version
# Harus menunjukkan: 18.4-xxxxx
```

## Catatan Penting

- Odoo Community Edition **TIDAK** memiliki versi 18.4
- Hanya Odoo Enterprise yang memiliki versi patch seperti 18.4
- Enterprise addons 18.4 **WAJIB** menggunakan Odoo Enterprise 18.4

