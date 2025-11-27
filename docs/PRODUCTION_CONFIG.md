# Konfigurasi Production

## 📋 Informasi Penting

File `config_odoo/odoo.conf` **tidak di-track Git** untuk memungkinkan konfigurasi berbeda antara environment.

## 🔧 Setup di Server Produksi

### 1. Setup Config Pertama Kali

```bash
cd /path/to/project

# Buat config dari template
bash scripts/deploy/setup-production-config.sh

# Edit config sesuai kebutuhan produksi
nano config_odoo/odoo.conf
```

### 2. Edit Config di Production

File `config_odoo/odoo.conf` di server produksi **tidak akan di-overwrite** saat pull dari Git.

Anda bisa edit langsung:

```bash
cd /path/to/project
nano config_odoo/odoo.conf
# atau
vi config_odoo/odoo.conf
```

### 3. Update Template (Jika Ada Perubahan)

Jika ada perubahan di template `config_odoo/odoo.conf.example`:

1. **Di development/local**: Update `config_odoo/odoo.conf.example`
2. **Di production**: Manual merge perubahan yang diperlukan ke `config_odoo/odoo.conf`

Atau gunakan diff untuk melihat perbedaan:

```bash
cd /path/to/project
diff config_odoo/odoo.conf.example config_odoo/odoo.conf
```

## ⚠️ Catatan Penting

- File `config_odoo/odoo.conf` ada di `.gitignore` sehingga tidak ter-push ke repository
- Script deployment (`deploy-production.sh`) otomatis backup dan restore config sebelum/setelah pull
- Template `config_odoo/odoo.conf.example` di-track Git sebagai referensi
- Setiap pull akan membuat backup otomatis dengan timestamp

## 🔄 Backup Otomatis

Script deployment otomatis membuat backup sebelum pull:

```
config_odoo/odoo.conf.backup.20251126_215500
```

Backup terbaru akan di-restore setelah pull selesai.

## 📝 Contoh Konfigurasi Production

Beberapa setting yang biasanya berbeda di production:

```ini
# Database settings
db_host = postgres
db_password = <production_password>

# Server settings
http_port = 8069
http_interface = 0.0.0.0

# Workers (production: 2 * CPU cores + 1)
workers = 4

# Logging (production: info atau warning)
log_level = info

# Development mode (production: disable)
dev_mode = 
```

## 🔗 Referensi

- Template config: `config_odoo/odoo.conf.example`
- Script setup: `scripts/deploy/setup-production-config.sh`
- Script deployment: `scripts/deploy/deploy-production.sh`

