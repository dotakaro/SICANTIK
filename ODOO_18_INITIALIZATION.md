# Odoo 18.4 Database Initialization Guide

Panduan lengkap untuk membuat database Odoo 18.4 baru dari awal setelah upgrade enterprise modules.

## 🎯 Tujuan

Membuat database Odoo 18.4 baru dengan:
- ✅ Database fresh tanpa data lama
- ✅ Semua base modules terinstall
- ✅ Enterprise modules (whatsapp) terinstall
- ✅ Custom modules SICANTIK terinstall
- ✅ Semua modules dalam versi terbaru

## 📋 Prerequisites

1. **Docker & Docker Compose** sudah terinstall
2. **Enterprise modules** sudah di-extract di folder `enterprise/`
3. **Custom modules** sudah ada di folder `addons_odoo/`
4. **Containers** sudah running

## 🚀 Quick Start (Otomatis)

### Option 1: Menggunakan Script Otomatis

```bash
cd /Users/rimba/odoo-dev/SICANTIK
./scripts/init_odoo_database.sh
```

Script akan:
1. ✅ Drop database lama (jika ada)
2. ✅ Buat database baru
3. ✅ Initialize dengan Odoo base
4. ✅ Install base modules
5. ✅ Install enterprise modules
6. ✅ Install custom modules
7. ✅ Update semua modules

### Option 2: Manual (Step by Step)

Ikuti panduan di `scripts/init_odoo_database_manual.md`

## 📦 Modules yang akan diinstall

### Base Modules
- `base` - Core Odoo functionality
- `web` - Web interface
- `mail` - Email & messaging
- `contacts` - Contact management
- `portal` - Portal access
- `website` - Website builder

### Enterprise Modules
- `whatsapp` - WhatsApp Business integration

### Custom Modules
- `sicantik_connector` - SICANTIK API integration
- `sicantik_tte` - Digital signature (TTE BSRE)
- `sicantik_whatsapp` - WhatsApp notifications

## ⚙️ Configuration

### Database Settings
- **Database Name:** `sicantik_companion_standalone`
- **Database User:** `odoo`
- **Database Password:** `odoo_password_secure`
- **PostgreSQL Host:** `postgres_companion_standalone`
- **PostgreSQL Port:** `5432`

### Odoo Settings
- **Admin Username:** `admin`
- **Admin Password:** `admin_odoo_secure_2025`
- **Odoo URL:** `http://localhost:8065`
- **Addons Path:** 
  - `/mnt/extra-addons` (custom modules)
  - `/mnt/enterprise-addons` (enterprise modules)
  - `/usr/lib/python3/dist-packages/odoo/addons` (core modules)

## 🔍 Verification

Setelah initialization selesai:

1. **Akses Odoo:**
   ```
   http://localhost:8065
   ```

2. **Login:**
   - Username: `admin`
   - Password: `admin_odoo_secure_2025`

3. **Cek Modules:**
   - Settings → Apps → Update Apps List
   - Cari modules: `sicantik_connector`, `sicantik_tte`, `sicantik_whatsapp`
   - Pastikan status: **Installed** ✅

4. **Cek Database:**
   ```bash
   docker exec postgres_companion_standalone psql -U odoo -d sicantik_companion_standalone -c "\dt" | head -20
   ```

## 🔧 Post-Installation Configuration

### 1. Configure SICANTIK Connector
1. Buka menu **SICANTIK → Configuration**
2. Isi API endpoint: `http://perizinan.karokab.go.id/backoffice/api/`
3. Test koneksi
4. Sync permit types
5. Sync permits

### 2. Configure MinIO Storage
1. Buka menu **SICANTIK → MinIO Configuration**
2. Isi:
   - Host: `minio_storage`
   - Port: `9000`
   - Access Key: `minioadmin`
   - Secret Key: `minioadmin123`
3. Test koneksi
4. Create bucket jika belum ada

### 3. Configure WhatsApp Business Account
1. Install module `sicantik_whatsapp` (jika belum)
2. Buka menu **WhatsApp → Settings**
3. Configure WhatsApp Business Account
4. Sync templates dari Meta
5. Setup opt-in untuk nomor penerima

## 🐛 Troubleshooting

### Error: Container not running
```bash
docker-compose up -d odoo_companion_standalone postgres_companion_standalone
```

### Error: Database already exists
```bash
docker exec -e PGPASSWORD=odoo_password_secure postgres_companion_standalone \
  psql -U odoo -h postgres_companion_standalone \
  -c "DROP DATABASE sicantik_companion_standalone;"
```

### Error: Module not found
1. Cek apakah folder ter-mount dengan benar:
   ```bash
   docker exec odoo_companion_standalone ls -la /mnt/extra-addons
   docker exec odoo_companion_standalone ls -la /mnt/enterprise-addons
   ```

2. Update Apps List di Odoo:
   - Settings → Apps → Update Apps List

### Error: Permission denied
```bash
docker exec postgres_companion_standalone psql -U odoo -c "GRANT ALL PRIVILEGES ON DATABASE sicantik_companion_standalone TO odoo;"
```

### Logs untuk debugging
```bash
# Odoo logs
docker logs odoo_companion_standalone -f

# PostgreSQL logs
docker logs postgres_companion_standalone -f
```

## 📝 Notes

- ⚠️ **WARNING:** Script ini akan **menghapus database lama** jika ada
- 💾 **Backup:** Pastikan backup database sebelum menjalankan script jika ada data penting
- 🔄 **Update:** Setelah initialization, semua modules akan di-update ke versi terbaru
- 🚀 **Performance:** Proses initialization memakan waktu 5-15 menit tergantung spesifikasi server

## 🔗 Related Files

- `scripts/init_odoo_database.sh` - Script otomatis
- `scripts/init_odoo_database_manual.md` - Panduan manual
- `config_odoo/odoo.conf` - Konfigurasi Odoo
- `docker-compose.yml` - Docker Compose configuration

## ✅ Checklist

Setelah initialization selesai, pastikan:

- [ ] Database berhasil dibuat
- [ ] Bisa login ke Odoo dengan admin/admin_odoo_secure_2025
- [ ] Semua base modules terinstall
- [ ] Enterprise module whatsapp terinstall
- [ ] Custom modules (sicantik_connector, sicantik_tte, sicantik_whatsapp) terinstall
- [ ] SICANTIK connector bisa connect ke API
- [ ] MinIO storage bisa connect
- [ ] WhatsApp Business Account terkonfigurasi

---

**Last Updated:** 2025-11-18
**Odoo Version:** 18.4 Enterprise Edition

