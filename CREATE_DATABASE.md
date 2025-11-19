# Cara Create Database di Odoo

## Masalah
Saat create database via web interface (`http://localhost:8065/web/database/create`), Odoo mencoba connect ke PostgreSQL via Unix socket lokal yang tidak tersedia di container Docker.

## Solusi

### Opsi 1: Create Database via Command Line (Recommended)

```bash
# Create database di PostgreSQL
docker exec postgres_companion_standalone psql -U odoo -d postgres -c "CREATE DATABASE nama_database OWNER odoo;"

# Init database dengan base module
docker exec odoo_companion_standalone python3 odoo-bin \
  --config=/etc/odoo/odoo.conf \
  --init=base \
  --stop-after-init \
  -d nama_database
```

### Opsi 2: Gunakan Database yang Sudah Ada

Database `sicantik_companion_standalone` sudah dibuat dan di-init. Langsung login ke database tersebut di `http://localhost:8065`.

### Opsi 3: Fix Web Interface (Temporary Workaround)

Masalahnya adalah saat create database via web, Odoo tidak membaca config dengan benar. Command di docker-compose sudah di-set dengan parameter `--db_host`, `--db_port`, dll, tapi sepertinya web interface masih menggunakan default.

**Workaround**: Set environment variables `PGHOST`, `PGPORT`, dll yang sudah ada di docker-compose.yml. Odoo akan membaca environment variables ini sebagai fallback.

## Verifikasi

Setelah create database, verifikasi:
```bash
# Cek database ada
docker exec postgres_companion_standalone psql -U odoo -l | grep nama_database

# Cek Odoo bisa connect
docker exec odoo_companion_standalone python3 -c "from odoo.sql_db import db_connect; db = db_connect('nama_database'); print('OK')"
```

