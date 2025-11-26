# Setup Odoo Source di Server Produksi

## 📋 Informasi Penting

Folder `odoo_source/` **tidak di-track Git** karena akan di-pull langsung dari GitHub Odoo resmi.

## 🔧 Setup di Server Produksi

### 1. Clone Odoo Source (Pertama Kali)

```bash
cd /path/to/project

# Clone Odoo 18.4 dari GitHub resmi
git clone https://github.com/odoo/odoo.git odoo_source --branch 18.4 --depth 1
```

### 2. Update Odoo Source (Saat Perlu Update)

```bash
cd /path/to/project/odoo_source

# Pull update dari branch 18.4
git pull origin 18.4
```

### 3. Setup Otomatis (Opsional)

Tambahkan ke script deployment atau cron untuk auto-update:

```bash
#!/bin/bash
# Update odoo_source setiap minggu
cd /path/to/project/odoo_source
git pull origin 18.4
```

## ⚠️ Catatan

- Folder `odoo_source/` ada di `.gitignore` sehingga tidak akan ter-push ke repository
- Folder ini hanya untuk kebutuhan Odoo core, tidak untuk custom modules
- Custom modules ada di folder `addons_odoo/`
- Enterprise modules ada di folder `enterprise/`

## 🔗 Referensi

- Odoo GitHub: https://github.com/odoo/odoo
- Odoo 18.4 Branch: https://github.com/odoo/odoo/tree/18.4

