# Setup Server Production - SICANTIK

## ⚠️ Penting

**GitHub Actions TIDAK akan setup server dari awal!** 

GitHub Actions hanya akan:
- ✅ Pull code terbaru dari GitHub
- ✅ Restart Docker Compose services
- ❌ **TIDAK** clone repository
- ❌ **TIDAK** install Docker/Docker Compose
- ❌ **TIDAK** setup environment variables
- ❌ **TIDAK** setup database

**Server production harus di-setup manual terlebih dahulu sebelum GitHub Actions bisa deploy otomatis.**

## 📋 Prerequisites di Server Production

Sebelum GitHub Actions bisa deploy, server harus memiliki:

1. ✅ **Docker** terinstall
2. ✅ **Docker Compose** terinstall
3. ✅ **Git** terinstall
4. ✅ **Project sudah di-clone** dari GitHub
5. ✅ **SSH access** dengan public key dari laptop Anda
6. ✅ **Environment variables** dikonfigurasi (jika diperlukan)

## 🚀 Langkah-Langkah Setup Server Production

### 1. Install Docker & Docker Compose

**Di server production:**

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/download/v2.24.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Verify installation
docker --version
docker-compose --version

# Add user to docker group (optional, untuk tidak perlu sudo)
sudo usermod -aG docker $USER
# Logout dan login lagi untuk apply group changes
```

### 2. Install Git

```bash
# Install Git
sudo apt install git -y

# Configure Git (optional)
git config --global user.name "Your Name"
git config --global user.email "your.email@example.com"
```

### 3. Clone Repository dari GitHub

```bash
# Pilih lokasi untuk project (contoh: /var/www/sicantik atau /home/deploy/sicantik)
PROJECT_PATH="/var/www/sicantik"  # Ganti dengan path yang diinginkan

# Buat directory
sudo mkdir -p $PROJECT_PATH
sudo chown $USER:$USER $PROJECT_PATH

# Clone repository
cd $PROJECT_PATH
git clone git@github.com:dotakaro/SICANTIK.git .

# Atau jika menggunakan HTTPS:
# git clone https://github.com/dotakaro/SICANTIK.git .
```

**Catatan:** Path ini harus sama dengan `PROJECT_PATH` di GitHub Secrets!

### 4. Setup SSH Key untuk GitHub (Jika Menggunakan SSH)

```bash
# Generate SSH key untuk GitHub (jika belum ada)
ssh-keygen -t ed25519 -C "your.email@example.com" -f ~/.ssh/id_ed25519_github

# Tampilkan public key
cat ~/.ssh/id_ed25519_github.pub

# Tambahkan public key ke GitHub:
# 1. Buka: https://github.com/settings/keys
# 2. Klik "New SSH key"
# 3. Paste public key di atas
```

### 5. Setup Environment Variables (Jika Diperlukan)

```bash
cd $PROJECT_PATH

# Buat file .env jika diperlukan
cp .env.example .env  # Jika ada template
nano .env

# Edit environment variables sesuai kebutuhan
# Contoh:
# MYSQL_ROOT_PASSWORD=your_secure_password
# MINIO_ACCESS_KEY=your_access_key
# MINIO_SECRET_KEY=your_secret_key
```

### 6. Setup Docker Compose

```bash
cd $PROJECT_PATH

# Pastikan docker-compose.yml ada
ls -la docker-compose.yml

# Test docker-compose (dry-run)
docker-compose config

# Build images (jika ada custom Dockerfile)
docker-compose build

# Start services untuk pertama kali
docker-compose up -d

# Check status
docker-compose ps

# Check logs
docker-compose logs -f
```

### 7. Setup Database (Jika Diperlukan)

```bash
# Jika ada script setup database
cd $PROJECT_PATH

# Import database (jika ada)
# docker-compose exec sicantik_mysql mysql -u root -p db_office < database.sql

# Atau jika menggunakan volume dengan init script
# Database akan otomatis di-setup dari docker-entrypoint-initdb.d
```

### 8. Verifikasi Setup

```bash
# Check semua services running
docker-compose ps

# Check logs untuk error
docker-compose logs

# Test web access (jika port sudah di-expose)
curl http://localhost:8070  # SICANTIK web
curl http://localhost:8017  # Odoo (jika ada)
```

### 9. Setup Firewall (Jika Diperlukan)

```bash
# Allow SSH
sudo ufw allow 22/tcp

# Allow web ports (sesuai kebutuhan)
sudo ufw allow 8070/tcp  # SICANTIK web
sudo ufw allow 8017/tcp  # Odoo (jika perlu akses dari luar)

# Enable firewall
sudo ufw enable
sudo ufw status
```

### 10. Test GitHub Actions Deployment

Setelah semua setup selesai, test deployment:

```bash
# Di laptop Anda, buat commit kecil
echo "# Test deployment" >> TEST.md
git add TEST.md
git commit -m "test: test production deployment"
```

GitHub Actions akan:
1. Pull code terbaru
2. Restart Docker Compose services
3. Check health status

Cek status di: https://github.com/dotakaro/SICANTIK/actions

## 🔧 Script Setup Otomatis

Untuk memudahkan, buat script setup di server:

```bash
# Buat file: scripts/setup-production-server.sh
cat > scripts/setup-production-server.sh << 'EOF'
#!/bin/bash
set -e

PROJECT_PATH="${1:-/var/www/sicantik}"

echo "Setting up production server at $PROJECT_PATH..."

# Install Docker
if ! command -v docker &> /dev/null; then
    echo "Installing Docker..."
    curl -fsSL https://get.docker.com -o get-docker.sh
    sudo sh get-docker.sh
fi

# Install Docker Compose
if ! command -v docker-compose &> /dev/null; then
    echo "Installing Docker Compose..."
    sudo curl -L "https://github.com/docker/compose/releases/download/v2.24.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
    sudo chmod +x /usr/local/bin/docker-compose
fi

# Clone repository
if [ ! -d "$PROJECT_PATH" ]; then
    echo "Cloning repository..."
    sudo mkdir -p $PROJECT_PATH
    sudo chown $USER:$USER $PROJECT_PATH
    git clone git@github.com:dotakaro/SICANTIK.git $PROJECT_PATH
fi

cd $PROJECT_PATH

# Build and start services
echo "Building Docker images..."
docker-compose build

echo "Starting services..."
docker-compose up -d

echo "Setup complete!"
docker-compose ps
EOF

chmod +x scripts/setup-production-server.sh
```

## 📝 Checklist Setup Server

Sebelum GitHub Actions bisa deploy, pastikan:

- [ ] Docker terinstall dan running
- [ ] Docker Compose terinstall
- [ ] Git terinstall
- [ ] Repository sudah di-clone ke `PROJECT_PATH`
- [ ] `PROJECT_PATH` sama dengan yang di GitHub Secrets
- [ ] `docker-compose.yml` ada di project directory
- [ ] SSH public key dari laptop sudah ditambahkan ke server
- [ ] Docker Compose services bisa start manual
- [ ] Port yang diperlukan sudah di-expose (jika perlu akses dari luar)
- [ ] Firewall dikonfigurasi dengan benar

## 🆘 Troubleshooting

### Problem: GitHub Actions gagal dengan "Project path not found"

**Solusi:**
- Pastikan `PROJECT_PATH` di GitHub Secrets benar
- Pastikan path ada di server: `ssh user@host "ls -la $PROJECT_PATH"`

### Problem: Docker Compose tidak terinstall

**Solusi:**
- Install Docker Compose di server
- Pastikan `docker-compose` command bisa dijalankan tanpa sudo (atau user ada di docker group)

### Problem: Services tidak start setelah deployment

**Solusi:**
- Check logs: `docker-compose logs`
- Check docker-compose.yml syntax: `docker-compose config`
- Pastikan semua environment variables sudah dikonfigurasi

### Problem: Permission denied saat pull dari GitHub

**Solusi:**
- Setup SSH key untuk GitHub di server
- Atau gunakan HTTPS dengan personal access token

## 📚 Referensi

- `DEPLOYMENT.md` - Panduan lengkap deployment
- `docker-compose.yml` - Konfigurasi Docker Compose
- `.github/workflows/deploy-production.yml` - GitHub Actions workflow

