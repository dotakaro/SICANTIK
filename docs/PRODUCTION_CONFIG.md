# Konfigurasi Production

## 📋 Informasi Penting

File `config_odoo/odoo.conf` **di-track Git** untuk memastikan versi local selalu sama dengan production.

## 🔧 Cara Mengubah Config

### Edit di Local (Development)

1. Edit file `config_odoo/odoo.conf` di local
2. Commit perubahan:
   ```bash
   git add config_odoo/odoo.conf
   git commit -m "chore(config): update odoo.conf untuk production"
   git push origin master
   ```
3. Perubahan akan otomatis ter-deploy ke production via GitHub Actions

### Workflow

```
Local Edit → Commit → Push → GitHub Actions → Production Deploy
```

## ⚠️ Catatan Penting

- File `config_odoo/odoo.conf` di-track Git, jadi semua perubahan harus di-commit
- Edit di local, commit dan push, maka akan ter-sync ke production
- Template `config_odoo/odoo.conf.example` tetap ada sebagai referensi
- Tidak ada backup otomatis - semua perubahan langsung ter-apply

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

