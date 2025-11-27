# Fix Divergent Branches di Server Produksi

## 🔧 Solusi Langsung dengan Perintah Git

Jika Anda tidak bisa pull karena divergent branches, gunakan perintah berikut:

### Langkah 1: Backup Config File (PENTING!)

```bash
cd /opt/sicantik

# Backup odoo.conf jika ada
if [ -f "config_odoo/odoo.conf" ]; then
    cp config_odoo/odoo.conf config_odoo/odoo.conf.backup.$(date +%Y%m%d_%H%M%S)
    echo "✅ Config sudah di-backup"
fi
```

### Langkah 2: Fetch Latest dari GitHub

```bash
git fetch origin master
```

### Langkah 3: Reset ke origin/master (Discard Local Changes)

```bash
# Reset hard ke origin/master (akan discard semua local changes)
git reset --hard origin/master
```

### Langkah 4: Restore Config File

```bash
# Restore config dari backup terbaru
if ls config_odoo/odoo.conf.backup.* 1> /dev/null 2>&1; then
    LATEST_BACKUP=$(ls -t config_odoo/odoo.conf.backup.* | head -1)
    cp "$LATEST_BACKUP" config_odoo/odoo.conf
    echo "✅ Config sudah di-restore dari backup"
fi
```

## 📝 Perintah Lengkap (Copy-Paste)

```bash
cd /opt/sicantik

# Backup config
[ -f "config_odoo/odoo.conf" ] && cp config_odoo/odoo.conf config_odoo/odoo.conf.backup.$(date +%Y%m%d_%H%M%S)

# Fetch dan reset
git fetch origin master
git reset --hard origin/master

# Restore config
if ls config_odoo/odoo.conf.backup.* 1> /dev/null 2>&1; then
    LATEST_BACKUP=$(ls -t config_odoo/odoo.conf.backup.* | head -1)
    cp "$LATEST_BACKUP" config_odoo/odoo.conf
fi

echo "✅ Selesai! Branch sudah di-sync dengan origin/master"
```

## ⚠️ Catatan

- Perintah `git reset --hard` akan **menghapus semua local changes** yang belum di-commit
- Config file `odoo.conf` akan di-backup dan di-restore otomatis
- Setelah ini, pull berikutnya akan berjalan normal

## 🔄 Setelah Fix

Setelah berhasil, Anda bisa pull normal lagi:

```bash
git pull origin master
```

Atau gunakan script deployment:

```bash
bash scripts/deploy/deploy-production.sh
```

