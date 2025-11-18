# Manual Database Initialization untuk Odoo 18.4

Jika script otomatis tidak berjalan, ikuti langkah-langkah manual berikut:

## Prerequisites

1. Pastikan containers sudah running:
```bash
docker-compose up -d odoo_companion_standalone postgres_companion_standalone
```

2. Cek status containers:
```bash
docker ps | grep -E "(odoo_companion|postgres_companion)"
```

## Langkah 1: Drop Database Lama (jika ada)

```bash
docker exec -e PGPASSWORD=odoo_password_secure postgres_companion_standalone \
  psql -U odoo -h postgres_companion_standalone \
  -c "DROP DATABASE IF EXISTS sicantik_companion_standalone;"
```

## Langkah 2: Buat Database Baru

```bash
docker exec -e PGPASSWORD=odoo_password_secure postgres_companion_standalone \
  psql -U odoo -h postgres_companion_standalone \
  -c "CREATE DATABASE sicantik_companion_standalone OWNER odoo ENCODING 'UTF8';"
```

## Langkah 3: Initialize Database dengan Odoo

```bash
docker exec odoo_companion_standalone odoo -d sicantik_companion_standalone \
  --db_host=postgres_companion_standalone \
  --db_port=5432 \
  --db_user=odoo \
  --db_password=odoo_password_secure \
  --stop-after-init \
  --without-demo=all \
  --init=base,web \
  --admin-password=admin_odoo_secure_2025
```

## Langkah 4: Install Base Modules

```bash
docker exec odoo_companion_standalone odoo -d sicantik_companion_standalone \
  --db_host=postgres_companion_standalone \
  --db_port=5432 \
  --db_user=odoo \
  --db_password=odoo_password_secure \
  --stop-after-init \
  --init=base,web,mail,contacts,portal,website
```

## Langkah 5: Install Enterprise Modules

```bash
docker exec odoo_companion_standalone odoo -d sicantik_companion_standalone \
  --db_host=postgres_companion_standalone \
  --db_port=5432 \
  --db_user=odoo \
  --db_password=odoo_password_secure \
  --stop-after-init \
  --init=whatsapp
```

## Langkah 6: Install Custom Modules

```bash
docker exec odoo_companion_standalone odoo -d sicantik_companion_standalone \
  --db_host=postgres_companion_standalone \
  --db_port=5432 \
  --db_user=odoo \
  --db_password=odoo_password_secure \
  --stop-after-init \
  --init=sicantik_connector,sicantik_tte,sicantik_whatsapp
```

## Langkah 7: Update All Modules

```bash
docker exec odoo_companion_standalone odoo -d sicantik_companion_standalone \
  --db_host=postgres_companion_standalone \
  --db_port=5432 \
  --db_user=odoo \
  --db_password=odoo_password_secure \
  --stop-after-init \
  --update=all
```

## Verifikasi

1. Akses Odoo: http://localhost:8065
2. Login dengan:
   - Username: `admin`
   - Password: `admin_odoo_secure_2025`
3. Cek Apps menu untuk memastikan semua modules terinstall

## Troubleshooting

### Error: Module not found
- Pastikan folder `addons_odoo` dan `enterprise` sudah ter-mount dengan benar
- Cek di Odoo: Settings → Apps → Update Apps List

### Error: Database connection failed
- Pastikan PostgreSQL container running: `docker ps | grep postgres`
- Cek logs: `docker logs postgres_companion_standalone`

### Error: Permission denied
- Pastikan user odoo memiliki akses ke database
- Cek dengan: `docker exec postgres_companion_standalone psql -U odoo -l`

