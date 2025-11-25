#!/bin/bash

# Setup Deployment - Interactive Guide
# Script ini akan memandu setup deployment step-by-step

set -e

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m'

print_header() {
    echo ""
    echo -e "${BLUE}════════════════════════════════════════════════════════${NC}"
    echo -e "${BLUE} $1${NC}"
    echo -e "${BLUE}════════════════════════════════════════════════════════${NC}"
    echo ""
}

print_step() {
    echo -e "${GREEN}[STEP]${NC} $1"
}

print_info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"

cd "$PROJECT_ROOT"

print_header "Setup Auto-Push dan Auto-Deploy ke Produksi"

# Step 1: Setup GitHub Repository
print_header "Langkah 1: Setup GitHub Repository"

if git remote get-url origin &> /dev/null; then
    CURRENT_REMOTE=$(git remote get-url origin)
    print_info "Remote 'origin' sudah ada: $CURRENT_REMOTE"
    read -p "Setup repository baru atau gunakan yang sudah ada? (new/existing) [existing]: " REPO_CHOICE
    REPO_CHOICE=${REPO_CHOICE:-existing}
    
    if [ "$REPO_CHOICE" = "new" ]; then
        print_step "Menjalankan setup GitHub repository..."
        bash "$SCRIPT_DIR/git/setup-github-repo.sh"
    else
        print_info "Menggunakan remote yang sudah ada"
    fi
else
    print_step "Menjalankan setup GitHub repository..."
    bash "$SCRIPT_DIR/git/setup-github-repo.sh"
fi

# Step 2: Setup SSH untuk Deployment
print_header "Langkah 2: Setup SSH untuk Deployment"

print_step "Menjalankan setup SSH..."
bash "$SCRIPT_DIR/deploy/setup-ssh-deploy.sh"

print_info ""
print_info "PENTING: Setelah script selesai, ikuti instruksi untuk:"
print_info "  1. Menambahkan public key ke server produksi"
print_info "  2. Menambahkan private key ke GitHub Secrets"

read -p "Tekan Enter setelah selesai setup SSH..."

# Step 3: Install Git Hooks
print_header "Langkah 3: Install Git Hooks"

print_step "Menginstall git hooks untuk auto-push..."
bash "$SCRIPT_DIR/git/install-hooks.sh"

# Step 4: Konfigurasi GitHub Secrets
print_header "Langkah 4: Konfigurasi GitHub Secrets"

print_info "Sekarang Anda perlu menambahkan secrets di GitHub:"
echo ""
print_step "1. Buka repository di GitHub.com"
print_step "2. Klik Settings → Secrets and variables → Actions"
print_step "3. Klik 'New repository secret'"
echo ""
print_info "Tambahkan secrets berikut:"
echo ""
echo "  Secret Name: SSH_PRIVATE_KEY"
echo "  Value: (copy dari output setup-ssh-deploy.sh)"
echo ""
echo "  Secret Name: SSH_HOST"
echo "  Value: IP atau hostname server produksi"
echo ""
echo "  Secret Name: SSH_USER"
echo "  Value: Username SSH (contoh: root, ubuntu, deploy)"
echo ""
echo "  Secret Name: PROJECT_PATH"
echo "  Value: Path lengkap ke project di server (contoh: /var/www/sicantik)"
echo ""

read -p "Tekan Enter setelah selesai menambahkan GitHub Secrets..."

# Summary
print_header "Setup Selesai!"

print_info "Ringkasan setup:"
echo ""
print_step "✅ GitHub Repository: $(git remote get-url origin 2>/dev/null || echo 'Belum dikonfigurasi')"
print_step "✅ Git Hooks: Installed"
print_step "✅ SSH Keys: Generated (lihat ~/.ssh/github_actions_deploy)"
echo ""
print_info "Langkah selanjutnya:"
echo ""
print_step "1. Pastikan semua GitHub Secrets sudah ditambahkan"
print_step "2. Test deployment dengan commit ke branch master:"
echo "   git add ."
echo "   git commit -m 'test: test deployment'"
echo ""
print_step "3. Monitor deployment di GitHub Actions tab"
echo ""
print_info "Dokumentasi lengkap: lihat DEPLOYMENT.md"
echo ""

