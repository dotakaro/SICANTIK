# Deployment Guide - SICANTIK

Panduan lengkap untuk setup dan konfigurasi auto-deployment dari GitHub ke server produksi.

## Daftar Isi

1. [Overview](#overview)
2. [Prerequisites](#prerequisites)
3. [Setup GitHub Repository](#setup-github-repository)
4. [Setup SSH untuk Deployment](#setup-ssh-untuk-deployment)
5. [Konfigurasi GitHub Secrets](#konfigurasi-github-secrets)
6. [Setup Git Hooks untuk Auto-Push](#setup-git-hooks-untuk-auto-push)
7. [Alur Kerja Deployment](#alur-kerja-deployment)
8. [Troubleshooting](#troubleshooting)
9. [Manual Deployment](#manual-deployment)

## Overview

Sistem deployment otomatis ini menggunakan:
- **Git Hooks**: Auto-push ke GitHub setiap commit di branch master
- **GitHub Actions**: Auto-deploy ke server produksi via SSH
- **Docker Compose**: Restart services setelah deployment

### Alur Kerja

```
Developer Commit → Git Hook (auto-push) → GitHub → GitHub Actions → SSH ke Server → Pull Code → Restart Docker
```

## Prerequisites

### Di Local Development Machine

- Git terinstall
- GitHub CLI (`gh`) terinstall (optional, untuk setup repo otomatis)
- Akses ke GitHub repository

### Di Server Produksi

- Linux server dengan SSH access
- Docker dan Docker Compose terinstall
- Git terinstall
- Project sudah di-clone dari GitHub
- User SSH memiliki permission untuk:
  - Pull dari git repository
  - Menjalankan `docker-compose` commands

### Di GitHub

- Repository sudah dibuat
- Akses untuk menambahkan GitHub Secrets
- GitHub Actions enabled (default: enabled)

## Setup GitHub Repository

### Opsi 1: Menggunakan Script Otomatis (Recommended)

```bash
# Install GitHub CLI jika belum ada
# macOS:
brew install gh

# Linux:
# Lihat: https://cli.github.com/manual/installation

# Login ke GitHub
gh auth login

# Jalankan script setup
./scripts/git/setup-github-repo.sh
```

Script akan:
1. Membuat repository baru di GitHub
2. Menambahkan remote origin
3. Push code ke GitHub

### Opsi 2: Manual Setup

```bash
# 1. Buat repository di GitHub.com (via web interface)

# 2. Tambahkan remote origin
git remote add origin https://github.com/USERNAME/REPO_NAME.git

# 3. Push code
git push -u origin master
```

## Setup SSH untuk Deployment

### 1. Generate SSH Key

Jalankan script setup SSH:

```bash
./scripts/deploy/setup-ssh-deploy.sh
```

Script akan:
- Generate SSH key pair (ed25519)
- Menampilkan public key untuk ditambahkan ke server
- Menampilkan private key untuk GitHub Secrets

### 2. Add Public Key ke Server Produksi

**Di server produksi**, jalankan:

```bash
# Login ke server
ssh user@your-server.com

# Tambahkan public key ke authorized_keys
mkdir -p ~/.ssh
nano ~/.ssh/authorized_keys
# Paste public key yang ditampilkan oleh setup-ssh-deploy.sh

# Set permissions
chmod 600 ~/.ssh/authorized_keys
chmod 700 ~/.ssh
```

**Atau gunakan one-liner:**

```bash
echo "PASTE_PUBLIC_KEY_HERE" >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
```

### 3. Test SSH Connection

Dari local machine:

```bash
ssh -i ~/.ssh/github_actions_deploy user@your-server.com
```

Jika berhasil, Anda akan masuk ke server tanpa password prompt.

## Konfigurasi GitHub Secrets

GitHub Secrets digunakan untuk menyimpan informasi sensitif yang diperlukan untuk deployment.

### Langkah-langkah:

1. Buka repository di GitHub.com
2. Klik **Settings** → **Secrets and variables** → **Actions**
3. Klik **New repository secret**
4. Tambahkan secrets berikut:

#### Required Secrets

| Secret Name | Description | Example |
|------------|-------------|---------|
| `SSH_PRIVATE_KEY` | Private SSH key untuk koneksi ke server | (isi dengan private key dari setup-ssh-deploy.sh) |
| `SSH_HOST` | IP atau hostname server produksi | `192.168.1.100` atau `prod.example.com` |
| `SSH_USER` | Username SSH untuk server | `root`, `ubuntu`, atau `deploy` |
| `PROJECT_PATH` | Path lengkap ke project di server | `/var/www/sicantik` atau `/home/deploy/sicantik` |

### Cara Mendapatkan SSH_PRIVATE_KEY

Jalankan script setup SSH:

```bash
./scripts/deploy/setup-ssh-deploy.sh
```

Copy seluruh isi private key yang ditampilkan (termasuk `-----BEGIN` dan `-----END`).

### Verifikasi Secrets

Setelah menambahkan semua secrets, pastikan:
- ✅ Semua 4 secrets sudah ditambahkan
- ✅ Tidak ada typo pada nama secret
- ✅ SSH_PRIVATE_KEY lengkap (termasuk header dan footer)

## Setup Git Hooks untuk Auto-Push

Git hooks akan otomatis push ke GitHub setiap kali ada commit di branch master.

### Install Git Hooks

```bash
./scripts/git/install-hooks.sh
```

Atau manual:

```bash
# Copy hook ke .git/hooks
cp scripts/git/post-commit .git/hooks/post-commit
chmod +x .git/hooks/post-commit
```

### Cara Kerja

- Hook hanya aktif untuk branch `master`
- Push dilakukan secara non-blocking (tidak menunggu hasil)
- Hanya push jika remote adalah GitHub

### Disable Auto-Push (Jika Perlu)

```bash
# Rename hook untuk disable
mv .git/hooks/post-commit .git/hooks/post-commit.disabled

# Atau hapus
rm .git/hooks/post-commit
```

## Alur Kerja Deployment

### 1. Developer Commit

```bash
git add .
git commit -m "feat: add new feature"
```

### 2. Git Hook Auto-Push

Setelah commit, git hook otomatis push ke GitHub:

```
🚀 Auto-pushing to GitHub (branch: master)...
```

### 3. GitHub Actions Trigger

GitHub Actions mendeteksi push ke branch `master` dan memulai workflow.

### 4. Deployment Process

GitHub Actions akan:
1. Checkout code dari GitHub
2. Setup SSH connection
3. Connect ke server produksi via SSH
4. Pull latest code
5. Restart Docker Compose services
6. Health check services

### 5. Deployment Complete

Jika berhasil, Anda akan melihat:
- ✅ Deployment summary di GitHub Actions
- ✅ Services running di server produksi

## Troubleshooting

### Problem: Git Hook Tidak Push Otomatis

**Gejala:**
- Commit berhasil tapi tidak push ke GitHub

**Solusi:**
1. Pastikan hook sudah diinstall:
   ```bash
   ls -la .git/hooks/post-commit
   ```
2. Pastikan hook executable:
   ```bash
   chmod +x .git/hooks/post-commit
   ```
3. Pastikan remote origin sudah dikonfigurasi:
   ```bash
   git remote -v
   ```
4. Test hook manual:
   ```bash
   .git/hooks/post-commit
   ```

### Problem: GitHub Actions Deployment Gagal

**Gejala:**
- GitHub Actions workflow failed
- Error di log GitHub Actions

**Solusi:**

1. **Check SSH Connection:**
   - Pastikan SSH_PRIVATE_KEY benar
   - Pastikan SSH_HOST dan SSH_USER benar
   - Test SSH connection manual:
     ```bash
     ssh -i ~/.ssh/github_actions_deploy user@host
     ```

2. **Check Project Path:**
   - Pastikan PROJECT_PATH benar
   - Pastikan path ada di server:
     ```bash
     ssh user@host "ls -la /path/to/project"
     ```

3. **Check Docker Compose:**
   - Pastikan docker-compose.yml ada di project path
   - Pastikan Docker Compose terinstall di server:
     ```bash
     ssh user@host "docker-compose --version"
     ```

4. **Check Permissions:**
   - Pastikan SSH user bisa pull dari git
   - Pastikan SSH user bisa menjalankan docker-compose

### Problem: Services Tidak Start Setelah Deployment

**Gejala:**
- Deployment berhasil tapi services tidak running

**Solusi:**

1. **Check Docker Compose Logs:**
   ```bash
   ssh user@host "cd /path/to/project && docker-compose logs"
   ```

2. **Check Service Status:**
   ```bash
   ssh user@host "cd /path/to/project && docker-compose ps"
   ```

3. **Manual Restart:**
   ```bash
   ssh user@host "cd /path/to/project && docker-compose restart"
   ```

### Problem: Deployment Rollback

Jika deployment gagal, script akan otomatis rollback ke commit sebelumnya.

**Manual Rollback:**

```bash
# Di server produksi
cd /path/to/project
git log --oneline  # Lihat commit history
git reset --hard COMMIT_HASH  # Rollback ke commit tertentu
docker-compose up -d  # Restart services
```

## Manual Deployment

Jika perlu deploy manual tanpa GitHub Actions:

### Opsi 1: Menggunakan Deployment Script

```bash
# Di server produksi
cd /path/to/project
export PROJECT_PATH=/path/to/project
bash scripts/deploy/deploy-production.sh
```

### Opsi 2: Manual Steps

```bash
# Di server produksi
cd /path/to/project

# Backup database (optional)
bash scripts/backup.sh

# Pull latest code
git fetch origin master
git reset --hard origin/master
git pull origin master

# Restart services
docker-compose down
docker-compose up -d

# Check status
docker-compose ps
docker-compose logs -f
```

## Best Practices

### 1. Branch Strategy

- **master**: Production branch (auto-deploy)
- **develop**: Development branch (tidak auto-deploy)
- **feature/***: Feature branches (tidak auto-deploy)

### 2. Commit Messages

Gunakan conventional commits untuk tracking yang lebih baik:

```
feat: add new feature
fix: fix bug
docs: update documentation
chore: update dependencies
```

### 3. Testing Sebelum Deploy

- Test di development environment dulu
- Review code sebelum merge ke master
- Pastikan semua tests pass

### 4. Monitoring

- Monitor GitHub Actions untuk deployment status
- Monitor server logs setelah deployment
- Setup alerts untuk deployment failures

### 5. Backup

- Backup database sebelum deployment (otomatis jika script backup.sh ada)
- Simpan backup di lokasi yang aman
- Test restore process secara berkala

## Security Considerations

1. **SSH Keys:**
   - Jangan commit SSH keys ke repository
   - Rotate SSH keys secara berkala
   - Gunakan key dengan permission minimal

2. **GitHub Secrets:**
   - Jangan expose secrets di logs
   - Rotate secrets secara berkala
   - Gunakan different keys untuk different environments

3. **Server Access:**
   - Gunakan non-root user untuk deployment jika memungkinkan
   - Limit SSH access dengan firewall
   - Monitor SSH access logs

## Support

Jika mengalami masalah:
1. Check troubleshooting section di atas
2. Review GitHub Actions logs
3. Check server logs: `docker-compose logs`
4. Contact system administrator

## Changelog

- **2025-01-XX**: Initial setup dengan GitHub Actions dan Git Hooks

