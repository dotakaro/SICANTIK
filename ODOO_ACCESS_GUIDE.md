# Odoo Access Guide - Fix ERR_TOO_MANY_REDIRECTS

## 🔍 Masalah

Error `ERR_TOO_MANY_REDIRECTS` saat mengakses `http://localhost:8060` atau `http://localhost:8065`.

## ✅ Solusi

### Option 1: Akses langsung ke `/web` path (Recommended)

**Untuk odoo_companion (port 8060):**
```
http://localhost:8060/web
```

**Untuk odoo_companion_standalone (port 8065):**
```
http://localhost:8065/web
```

### Option 2: Fix database initialization

Jika database belum ter-initialize dengan benar, jalankan:

```bash
cd /Users/rimba/odoo-dev/SICANTIK
./scripts/fix_odoo_companion.sh
```

Script ini akan:
1. Stop container
2. Recreate database
3. Initialize dengan base & web modules
4. Restart container

### Option 3: Gunakan odoo_companion_standalone (port 8065)

Database `sicantik_companion_standalone` sudah berhasil di-initialize. Gunakan:

```
http://localhost:8065/web
```

## 🔧 Troubleshooting

### Cek status container:
```bash
docker ps | grep odoo_companion
```

### Cek logs:
```bash
docker logs odoo_companion --tail 50
docker logs odoo_companion_standalone --tail 50
```

### Cek database:
```bash
docker exec -e PGPASSWORD=odoo_password_secure postgres_companion \
  psql -U odoo -h postgres_companion -d sicantik_companion \
  -c "SELECT COUNT(*) FROM ir_module_module WHERE state='installed';"
```

### Restart container:
```bash
docker restart odoo_companion
# atau
docker restart odoo_companion_standalone
```

## 📋 Default Credentials

Setelah pertama kali login, Odoo akan meminta password baru untuk user `admin`.

## 🚀 Next Steps

Setelah bisa akses Odoo:

1. **Login dengan admin** (password akan di-set saat pertama kali)
2. **Install modules melalui Apps menu:**
   - Base: `mail`, `contacts`, `portal`, `website`
   - Enterprise: `whatsapp` (jika diperlukan)
   - Custom: `sicantik_connector`, `sicantik_tte`, `sicantik_whatsapp`

3. **Update Apps List:**
   - Settings → Apps → Update Apps List

## ⚠️ Catatan

- Beberapa enterprise modules mungkin tidak kompatibel dengan Odoo 18.4
- Install hanya modules yang diperlukan untuk SICANTIK
- Jika ada error saat install modules, skip modules yang bermasalah

