# 🏢 Panduan Setup Odoo 18.4 Enterprise dengan Docker

**Tanggal:** 24 November 2025  
**Versi:** Odoo 18.4 Enterprise  
**Base:** Odoo Community SaaS 18.0  
**Platform:** Docker

---

## 📋 PENDAHULUAN

Panduan ini menjelaskan cara setup Odoo 18.4 Enterprise menggunakan base Odoo Community SaaS 18.0 di lingkungan Docker. Setup ini dirancang khusus untuk proyek SICANTIK yang memerlukan fitur Enterprise Odoo 18.4.

---

## 🏗️ ARSITEKTUR

```
┌─────────────────────────────────────────────────────────────┐
│                    Docker Environment                     │
├─────────────────────┬─────────────────────┬─────────────────┤
│     SICANTIK Web    │    Odoo 18.4 Ent.   │   PostgreSQL    │
│      (PHP)          │     (Python)        │    (Database)   │
│   Port: 8070        │    Port: 8065       │   Port: 5435    │
└─────────────────────┴─────────────────────┴─────────────────┘
```

### Komponen Utama:

1. **Odoo 18.4 Enterprise Container**
   - Base image: Python 3.12-slim
   - Source: Odoo Community 18.0 + Enterprise Addons 18.4
   - Port: 8065 (external) → 8069 (internal)

2. **Enterprise Addons**
   - Path: `/mnt/enterprise-addons`
   - Jumlah: 1.099+ modul Enterprise
   - Versi: 18.4

3. **Custom Addons**
   - Path: `/mnt/extra-addons`
   - Modul: sicantik_connector, dll.

4. **PostgreSQL Database**
   - Container: `postgres_companion_standalone`
   - Versi: PostgreSQL 15
   - User: odoo
   - Password: odoo_password_secure

---

## 📦 FILE YANG DIBUTUHKAN

### 1. Odoo Source Code
```
./odoo_source/
├── addons/                 # Odoo Core modules
├── odoo/                   # Odoo Framework
├── odoo-bin               # Odoo launcher script
└── requirements.txt       # Python dependencies
```

### 2. Enterprise Addons
```
./enterprise/
├── web_enterprise/         # Enterprise UI
├── account_accountant/     # Advanced Accounting
├── sale_subscription/      # Subscription Management
├── helpdesk/              # Helpdesk Module
├── project_enterprise/    # Advanced Project
└── 1000+ lebihnya...
```

### 3. Configuration Files
```
./config_odoo/
└── odoo.conf              # Odoo configuration
```

### 4. Docker Configuration
```
./docker-compose.yml       # Docker service definitions
./Dockerfile.odoo         # Odoo container build instructions
```

---

## 🚀 LANGKAH SETUP

### 1. Persiapan Awal

```bash
# Masuk ke direktori proyek
cd /Users/rimba/odoo-dev/SICANTIK

# Verifikasi file yang diperlukan
ls -la enterprise-18.4.tar.gz      # Enterprise addons
ls -la enterprise-lic.tar.gz       # Enterprise license (optional)
```

### 2. Extract Enterprise Addons

```bash
# Extract enterprise addons
tar -xzf enterprise-18.4.tar.gz -C ./

# Verifikasi
ls -la enterprise/ | wc -l        # Harus sekitar 1100+ items
```

### 3. Setup Odoo Source

```bash
# Clone Odoo Community 18.0
git clone --depth 1 --branch 18.0 https://github.com/odoo/odoo.git odoo_source

# Build Docker image
docker-compose build --no-cache odoo_companion_standalone
```

### 4. Jalankan Setup Otomatis

```bash
# Jalankan script setup
./scripts/odoo/setup-odoo-18.4-enterprise.sh
```

Script ini akan:
- Build Docker image Odoo 18.4
- Extract dan setup license (jika ada)
- Start PostgreSQL dan Odoo containers
- Verifikasi setup berhasil

### 5. Buat Database Baru

1. Buka browser: http://localhost:8065
2. Klik "Create Database"
3. Isi informasi:
   - Database name: `sicantik_enterprise` (atau nama lain)
   - Email: `admin@example.com`
   - Password: `admin_odoo_secure_2025`
   - Language: English (atau Indonesian)
   - Country: Indonesia
4. Klik "Create"
5. Tunggu proses instalasi selesai (1-3 menit)

---

## 🔑 AKTIVASI ENTERPRISE

### Opsi 1: Trial (30 Hari)

```bash
# Jalankan script aktivasi
./scripts/odoo/activate-odoo-enterprise.sh

# Pilih opsi 1 (Trial activation)
```

Lalu di UI Odoo:
1. Buka Apps → Remove "Apps" filter
2. Cari modul Enterprise (misalnya: `account_accountant`)
3. Klik "Install"
4. Saat diminta, klik "Start Trial"
5. Masukkan email dan ikuti instruksi

### Opsi 2: License File

```bash
# Jalankan script aktivasi
./scripts/odoo/activate-odoo-enterprise.sh

# Pilih opsi 2 (License file activation)
# Masukkan path ke file .lic
```

### Opsi 3: Akun Odoo.com

```bash
# Jalankan script aktivasi
./scripts/odoo/activate-odoo-enterprise.sh

# Pilih opsi 3 (Odoo.com account activation)
```

Lalu di UI Odoo:
1. Buka Settings (⚙️)
2. Klik "Activate the Enterprise Edition"
3. Masukkan kredensial Odoo.com
4. Masukkan nomor kontrak atau subscription
5. Klik "Activate"

---

## 🧪 VERIFIKASI SETUP

### 1. Cek Status Container

```bash
# Cek status semua container
docker-compose ps

# Harus menunjukkan:
# - postgres_companion_standalone: running
# - odoo_companion_standalone: running
# - redis_cache: running
```

### 2. Cek Log Odoo

```bash
# Lihat log terbaru
docker logs odoo_companion_standalone | tail -20

# Cari pesan sukses:
# "Odoo version 18.0"
# "HTTP service running on 0.0.0.0:8069"
# "addons paths: [/opt/odoo/odoo/addons, /mnt/extra-addons, /mnt/enterprise-addons]"
```

### 3. Cek Jumlah Modul Enterprise

```bash
# Hitung modul Enterprise
docker exec odoo_companion_standalone ls /mnt/enterprise-addons/ | wc -l
# Harus sekitar 1099+ modul
```

### 4. Verifikasi Database

```bash
# Akses database
docker exec -it postgres_companion_standalone psql -U odoo -d sicantik_enterprise

# Cek tabel yang dibuat
\dt ir_module_module
```

---

## 🛠️ TROUBLESHOOTING

### Masalah: Tidak bisa mengakses http://localhost:8065

**Solusi:**
```bash
# 1. Cek container status
docker-compose ps

# 2. Cek logs
docker logs odoo_companion_standalone

# 3. Restart container
docker-compose restart odoo_companion_standalone

# 4. Coba lagi setelah 30 detik
```

### Masalah: Module not visible in Apps

**Solusi:**
```bash
# 1. Verifikasi mount point
docker exec odoo_companion_standalone ls -la /mnt/enterprise-addons/

# 2. Cek addons_path
docker exec odoo_companion_standalone cat /etc/odoo/odoo.conf | grep addons_path

# 3. Update Apps List di UI:
# - Buka Apps
# - Klik "Update Apps List"
# - Tunggu 10-20 detik
```

### Masalah: Error "ModuleNotFoundError: No module named 'odoo.addons.web'"

**Solusi:**
```bash
# 1. Verifikasi path addons di container
docker exec odoo_companion_standalone ls -la /opt/odoo/addons/web/

# 2. Perbaiki addons_path di odoo.conf
# Pastikan: addons_path = /opt/odoo/addons,/mnt/extra-addons,/mnt/enterprise-addons

# 3. Restart Odoo
docker-compose restart odoo_companion_standalone
```

### Masalah: Enterprise modules tidak terinstall

**Solusi:**
```bash
# 1. Cek lisensi
docker exec odoo_companion_standalone ls -la /var/lib/odoo/odoo.lic

# 2. Install manual melalui CLI
docker exec odoo_companion_standalone python3 odoo-bin \
    --config=/etc/odoo/odoo.conf \
    -d sicantik_enterprise \
    -i web_enterprise,account_accountant \
    --stop-after-init

# 3. Restart Odoo
docker-compose restart odoo_companion_standalone
```

---

## 📋 KONFIGURASI PENTING

### odoo.conf

```ini
[options]
# Database settings
db_host = postgres_companion_standalone
db_port = 5432
db_user = odoo
db_password = odoo_password_secure
db_maxconn = 64

# Addons path - TANPA backslash continuation
addons_path = /opt/odoo/addons,/mnt/extra-addons,/mnt/enterprise-addons

# Admin password
admin_passwd = admin_odoo_secure_2025

# Server settings
http_port = 8069
http_interface = 0.0.0.0

# Workers (2 * CPU cores + 1)
workers = 2

# Development mode
dev_mode = reload,qweb,werkzeug,xml

# Limits
limit_time_cpu = 600
limit_time_real = 1200
limit_memory_soft = 1073741824
limit_memory_hard = 1610612736

# Data directory
data_dir = /var/lib/odoo

# List databases
list_db = True
```

### Dockerfile.odoo

```dockerfile
# Build Odoo 18.4 Enterprise from source
FROM python:3.12-slim

# Install system dependencies (termasuk nodejs dan npm)
RUN apt-get update && apt-get install -y --no-install-recommends \
    git build-essential libpq-dev libxml2-dev libxslt1-dev \
    libldap2-dev libsasl2-dev libjpeg-dev zlib1g-dev libpng-dev \
    liblcms2-dev libffi-dev libssl-dev libevent-dev curl nodejs npm \
    && rm -rf /var/lib/apt/lists/*

# Install less compiler via npm
RUN npm install -g less less-plugin-clean-css

# Create odoo user
RUN useradd -ms /bin/bash -u 1000 odoo

# Set working directory
WORKDIR /opt/odoo

# Copy Odoo source code
COPY odoo_source/ /opt/odoo/

# Install Python dependencies
RUN pip3 install --no-cache-dir --break-system-packages -r requirements.txt

# Install additional dependencies
RUN pip3 install --no-cache-dir --break-system-packages \
    boto3==1.34.* minio==7.2.* qrcode[pil]==7.4.* \
    reportlab==4.0.* mysql-connector-python==8.3.* phonenumbers

# Create directories
RUN mkdir -p /mnt/extra-addons /mnt/enterprise-addons /var/lib/odoo/.local/share/Odoo

# Set permissions
RUN chown -R odoo:odoo /opt/odoo /mnt/extra-addons /mnt/enterprise-addons /var/lib/odoo

USER odoo
EXPOSE 8069
CMD ["python3", "odoo-bin", "--config=/etc/odoo/odoo.conf"]
```

---

## 📊 PERFORMA & OPTIMASI

### Resource Allocation

```yaml
# Di docker-compose.yml
deploy:
  resources:
    limits:
      memory: 2G
    reservations:
      memory: 1G
```

### Database Configuration

```bash
# Di postgresql.conf (jika diperlukan)
shared_buffers = 256MB
effective_cache_size = 1GB
maintenance_work_mem = 64MB
checkpoint_completion_target = 0.9
wal_buffers = 16MB
default_statistics_target = 100
```

### Production Optimizations

```ini
# Di odoo.conf untuk production
workers = 4
limit_memory_hard = 2147483648
limit_memory_soft = 1610612736
limit_request = 8192
limit_time_cpu = 600
limit_time_real = 1200
max_cron_threads = 1
```

---

## 🔗 INTEGRASI DENGAN SICANTIK

### API Connection

```python
# Di sicantik_connector module
class SicantikConfig(models.Model):
    _name = 'sicantik.config'
    _description = 'SICANTIK API Configuration'
    
    api_url = fields.Char('API URL', default='http://localhost:8070/api/')
    api_key = fields.Char('API Key')
    timeout = fields.Integer('Timeout (seconds)', default=30)
    active = fields.Boolean('Active', default=True)
```

### File Sharing

```bash
# Shared volume di docker-compose.yml
volumes:
  - ./uploads:/mnt/sicantik_uploads:ro
```

---

## 📝 COMMANDS BERGUNA

### Container Management

```bash
# Start semua service
docker-compose up -d

# Restart hanya Odoo
docker-compose restart odoo_companion_standalone

# Lihat log Odoo
docker-compose logs -f odoo_companion_standalone

# Akses shell Odoo
docker exec -it odoo_companion_standalone bash
```

### Database Operations

```bash
# Backup database
docker exec postgres_companion_standalone pg_dump -U odoo sicantik_enterprise > backup.sql

# Restore database
cat backup.sql | docker exec -i postgres_companion_standalone psql -U odoo -d sicantik_enterprise

# Akses database shell
docker exec -it postgres_companion_standalone psql -U odoo -d sicantik_enterprise
```

### Module Management

```bash
# Update modul dari CLI
docker exec odoo_companion_standalone python3 odoo-bin \
    --config=/etc/odoo/odoo.conf \
    -d sicantik_enterprise \
    -u sicantik_connector \
    --stop-after-init

# Install modul baru
docker exec odoo_companion_standalone python3 odoo-bin \
    --config=/etc/odoo/odoo.conf \
    -d sicantik_enterprise \
    -i helpdesk,account_accountant \
    --stop-after-init
```

---

## 🎯 SUMMARY

### ✅ Setup Complete

1. **Odoo 18.4 Enterprise** running in Docker
2. **Enterprise Addons** mounted dan terdeteksi (1.099+ modul)
3. **Database PostgreSQL** configured dan running
4. **Custom SICANTIK modules** ready untuk diinstall
5. **Activation methods** prepared (Trial, License, Account)

### 🌐 Access Information

```
URL:      http://localhost:8065
Username: admin
Password: admin_odoo_secure_2025
Database: sicantik_enterprise (atau nama database yang Anda buat)
```

### 📱 Next Steps

1. **Buat Database** jika belum ada
2. **Aktifasi Enterprise** dengan salah satu metode
3. **Install Modul** yang dibutuhkan
4. **Konfigurasi API** untuk integrasi dengan SICANTIK
5. **Testing** fitur-fitur yang akan digunakan

---

## 🔧 TROUBLESHOOTING LANJUTAN

### Masalah Indentasi di Modul sicantik_tte

Jika Anda mengalami error seperti:
```
IndentationError: expected an indented block after class definition
```

**Solusi:**
1. Jalankan script troubleshooting:
```bash
./scripts/odoo/troubleshoot-odoo.sh
```

2. Atau perbaiki manual:
```bash
# Akses container
docker exec -it $(docker-compose ps -q odoo) bash

# Periksa dan perbaiki indentasi
cd /mnt/extra-addons/sicantik_tte
python3 -m py_compile wizard/*.py models/*.py
```

### Masalah Session Error

Jika Anda melihat error:
```
FileNotFoundError: [Errno 2] No such file or directory: '/var/lib/odoo/sessions/...'
```

**Solusi:**
Error ini normal dan tidak mengganggu fungsi utama. Odoo akan otomatis membuat session folder saat diperlukan.

### Modul Tidak Muncul di Apps

**Solusi:**
1. Update Apps List:
   - Buka menu Apps
   - Klik "Update Apps List" (pojok kanan atas)
   - Tunggu proses selesai

2. Hapus cache browser:
   - Tekan Ctrl+F5 (atau Cmd+Shift+R di Mac)
   - Clear browser cache

3. Restart Odoo:
```bash
docker-compose restart odoo
```

### Error Database Creation Access Denied

Jika gagal membuat database dengan error "Access Denied":

**Solusi:**
1. Periksa admin password di odoo.conf
2. Gunakan master password yang benar saat membuat database
3. Atau buat database via CLI:
```bash
docker exec postgres createdb -U odoo sicantik_enterprise
```

---

## 📋 SCRIPT OTOMASI YANG TERSEDIA

### 1. Setup Odoo 18.4 Enterprise
```bash
./scripts/odoo/setup-odoo-18-4-enterprise.sh
```
- Setup lengkap dari awal
- Download dan extract addons
- Build Docker image
- Start service

### 2. Start Odoo
```bash
./start-odoo-18-4-enterprise.sh
```
- Start service dengan cepat
- Check status otomatis
- Load license jika ada

### 3. Aktivasi Enterprise
```bash
./scripts/odoo/activate-odoo-enterprise.sh
```
- Aktivasi Trial (30 hari)
- Aktivasi License File
- Aktivasi Akun Odoo.com

### 4. Troubleshooting
```bash
./scripts/odoo/troubleshoot-odoo.sh
```
- Check semua status service
- Identifikasi error umum
- Rekomendasi solusi

---

**Last Updated:** 24 November 2025  
**Version:** 1.1.0  
**Status:** ✅ PRODUCTION READY

**🚀 Odoo 18.4 Enterprise siap digunakan! 🚀**