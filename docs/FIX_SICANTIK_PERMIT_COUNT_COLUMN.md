# Fix: Kolom sicantik_permit_count Tidak Ada di Database

## 🎯 Masalah

Error: `column res_partner.sicantik_permit_count does not exist`

Error ini terjadi karena kolom `sicantik_permit_count` belum dibuat di database meskipun sudah ada `pre_init_hook` dan `_auto_init()`.

## 🔍 Penyebab

1. Hook mungkin tidak dipanggil saat upgrade
2. `_auto_init()` mungkin tidak dipanggil sebelum field diakses
3. Field ini diakses oleh template portal sebelum kolom dibuat

## ✅ Solusi Cepat

### Opsi 1: Menggunakan Script Bash (Recommended)

```bash
cd /Users/rimba/odoo-dev/SICANTIK
./scripts/fix_sicantik_permit_count_column.sh
```

Script ini akan:
- ✅ Otomatis detect container PostgreSQL
- ✅ Membuat kolom jika belum ada
- ✅ Memberikan feedback yang jelas

### Opsi 2: Menggunakan SQL Manual

Masuk ke container PostgreSQL:

```bash
# Cek container PostgreSQL
docker-compose ps | grep postgres

# Masuk ke container (ganti dengan nama container yang sesuai)
docker exec -it <postgres_container_name> psql -U odoo -d sicantik_companion_standalone

# Jalankan SQL
ALTER TABLE "res_partner" ADD COLUMN IF NOT EXISTS "sicantik_permit_count" int4 DEFAULT 0;
```

Atau langsung:

```bash
docker exec -i <postgres_container_name> psql -U odoo -d sicantik_companion_standalone <<EOF
ALTER TABLE "res_partner" ADD COLUMN IF NOT EXISTS "sicantik_permit_count" int4 DEFAULT 0;
EOF
```

### Opsi 3: Menggunakan File SQL

```bash
docker exec -i <postgres_container_name> psql -U odoo -d sicantik_companion_standalone < scripts/fix_sicantik_permit_count_column.sql
```

## 📋 Langkah Setelah Fix

1. **Buat kolom secara manual** (gunakan salah satu opsi di atas)
2. **Restart Odoo** untuk memastikan perubahan terdeteksi
3. **Upgrade modul** `sicantik_connector` lagi
4. **Verifikasi** bahwa kolom sudah ada dan tidak ada error lagi

## 🔧 Verifikasi

Cek apakah kolom sudah dibuat:

```sql
SELECT column_name, data_type 
FROM information_schema.columns 
WHERE table_name = 'res_partner' 
AND column_name = 'sicantik_permit_count';
```

Harusnya muncul:
```
     column_name          | data_type 
--------------------------+-----------
 sicantik_permit_count    | integer
```

## 📝 Catatan

- Kolom dibuat dengan tipe `int4` (integer 32-bit)
- Default value: `0` (atau NULL jika tidak ada default)
- Setelah kolom dibuat, `_compute_sicantik_permit_count()` akan mengisi nilai secara otomatis

## 🚨 Jika Masih Error

Jika setelah membuat kolom masih ada error:

1. **Restart Odoo**:
   ```bash
   docker-compose restart odoo_sicantik
   ```

2. **Clear cache Odoo**:
   - Masuk ke Odoo sebagai admin
   - Settings → Technical → Database Structure → Models
   - Cari `res.partner` dan klik "Clear Cache"

3. **Upgrade modul lagi**:
   - Apps → Cari `sicantik_connector` → Upgrade

## 🔄 Pencegahan di Masa Depan

Untuk mencegah masalah ini di masa depan:

1. **Selalu buat kolom secara manual** sebelum upgrade jika field baru dengan `store=True`
2. **Gunakan migration script** untuk field-field penting
3. **Test di environment development** sebelum deploy ke production

