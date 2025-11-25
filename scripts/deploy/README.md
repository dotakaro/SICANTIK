# Deployment Scripts

Scripts untuk deployment otomatis ke server produksi.

## Quick Start

### 1. Setup GitHub Repository

```bash
./scripts/git/setup-github-repo.sh
```

### 2. Setup SSH untuk Deployment

```bash
./scripts/deploy/setup-ssh-deploy.sh
```

Ikuti instruksi untuk:
- Menambahkan public key ke server
- Menambahkan private key ke GitHub Secrets

### 3. Install Git Hooks

```bash
./scripts/git/install-hooks.sh
```

### 4. Konfigurasi GitHub Secrets

Tambahkan secrets berikut di GitHub repository:
- `SSH_PRIVATE_KEY`: Private SSH key
- `SSH_HOST`: IP atau hostname server
- `SSH_USER`: Username SSH
- `PROJECT_PATH`: Path lengkap ke project di server

## Manual Deployment

Jika perlu deploy manual:

```bash
# Di server produksi
cd /path/to/project
export PROJECT_PATH=/path/to/project
bash scripts/deploy/deploy-production.sh
```

## Dokumentasi Lengkap

Lihat [DEPLOYMENT.md](../../DEPLOYMENT.md) untuk dokumentasi lengkap.

