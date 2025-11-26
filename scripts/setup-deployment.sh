#!/bin/bash

# Setup Deployment Script untuk SICANTIK
# Script ini memandu user melalui semua langkah setup deployment sesuai DEPLOYMENT.md

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Functions
print_header() {
    echo ""
    echo -e "${CYAN}════════════════════════════════════════════════════════════${NC}"
    echo -e "${CYAN}$1${NC}"
    echo -e "${CYAN}════════════════════════════════════════════════════════════${NC}"
    echo ""
}

print_info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_step() {
    echo -e "${BLUE}[STEP]${NC} $1"
}

print_success() {
    echo -e "${GREEN}✅${NC} $1"
}

print_fail() {
    echo -e "${RED}❌${NC} $1"
}

# Get script directory
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"

cd "$PROJECT_ROOT"

print_header "SICANTIK Deployment Setup"
print_info "Script ini akan memandu Anda melalui setup deployment otomatis"
print_info "Ikuti langkah-langkah berikut sesuai DEPLOYMENT.md"
echo ""

# Step 1: Check Git Repository
print_step "1. Memeriksa Git Repository..."
if [ ! -d ".git" ]; then
    print_error "Directory ini bukan git repository"
    exit 1
fi

REMOTE_URL=$(git remote get-url origin 2>/dev/null || echo "")
if [ -z "$REMOTE_URL" ]; then
    print_warn "Remote origin belum dikonfigurasi"
    read -p "Apakah Anda ingin setup GitHub repository sekarang? (y/N): " SETUP_REPO
    if [[ "$SETUP_REPO" =~ ^[Yy]$ ]]; then
        print_info "Menjalankan setup GitHub repository..."
        bash "$SCRIPT_DIR/git/setup-github-repo.sh" || {
            print_error "Setup GitHub repository gagal"
            exit 1
        }
    else
        print_warn "Anda perlu setup GitHub repository terlebih dahulu"
        print_info "Jalankan: ./scripts/git/setup-github-repo.sh"
        exit 1
    fi
else
    print_success "Remote origin: $REMOTE_URL"
fi

# Step 2: Install Git Hooks
print_step "2. Install Git Hooks untuk Auto-Push..."
if [ -f ".git/hooks/post-commit" ]; then
    print_success "Git hook sudah terinstall"
else
    print_info "Menginstall git hook..."
    bash "$SCRIPT_DIR/git/install-hooks.sh" || {
        print_error "Install git hook gagal"
        exit 1
    }
fi

# Step 3: Setup SSH untuk Deployment
print_step "3. Setup SSH untuk Deployment..."
read -p "Apakah Anda sudah memiliki SSH key untuk deployment? (y/N): " HAS_SSH_KEY
if [[ ! "$HAS_SSH_KEY" =~ ^[Yy]$ ]]; then
    print_info "Menjalankan setup SSH..."
    bash "$SCRIPT_DIR/deploy/setup-ssh-deploy.sh" || {
        print_error "Setup SSH gagal"
        exit 1
    }
    echo ""
    print_warn "PENTING: Simpan informasi SSH key yang ditampilkan di atas!"
    print_warn "Anda akan membutuhkannya untuk konfigurasi GitHub Secrets"
    echo ""
    read -p "Tekan Enter untuk melanjutkan..."
else
    print_success "SSH key sudah ada"
fi

# Step 4: Konfigurasi GitHub Secrets
print_step "4. Konfigurasi GitHub Secrets..."
echo ""
print_info "Sekarang Anda perlu menambahkan secrets di GitHub:"
echo ""
print_info "1. Buka repository di GitHub.com:"
echo "   https://github.com/$(git remote get-url origin | sed -E 's/.*github.com[:/](.*)\.git/\1/')"
echo ""
print_info "2. Klik Settings → Secrets and variables → Actions"
echo ""
print_info "3. Tambahkan secrets berikut:"
echo ""
echo "   ${CYAN}SSH_PRIVATE_KEY${NC}"
echo "   - Private SSH key untuk koneksi ke server"
echo "   - Dapatkan dari: ~/.ssh/github_actions_deploy"
echo ""
echo "   ${CYAN}SSH_HOST${NC}"
echo "   - IP atau hostname server produksi"
echo "   - Contoh: 192.168.1.100 atau prod.example.com"
echo ""
echo "   ${CYAN}SSH_USER${NC}"
echo "   - Username SSH untuk server"
echo "   - Contoh: root, ubuntu, atau deploy"
echo ""
echo "   ${CYAN}PROJECT_PATH${NC}"
echo "   - Path lengkap ke project di server"
echo "   - Contoh: /var/www/sicantik atau /home/deploy/sicantik"
echo ""

read -p "Apakah Anda sudah menambahkan semua secrets di GitHub? (y/N): " SECRETS_ADDED
if [[ ! "$SECRETS_ADDED" =~ ^[Yy]$ ]]; then
    print_warn "Anda perlu menambahkan secrets di GitHub terlebih dahulu"
    print_info "Lihat DEPLOYMENT.md untuk panduan lengkap"
    echo ""
    read -p "Tekan Enter setelah menambahkan secrets..."
fi

# Step 5: Verifikasi Setup
print_step "5. Verifikasi Setup..."
echo ""

# Check GitHub Actions workflow
if [ -f ".github/workflows/deploy-production.yml" ]; then
    print_success "GitHub Actions workflow sudah ada"
else
    print_fail "GitHub Actions workflow tidak ditemukan"
fi

# Check git hook
if [ -f ".git/hooks/post-commit" ] && [ -x ".git/hooks/post-commit" ]; then
    print_success "Git hook sudah terinstall dan executable"
else
    print_fail "Git hook tidak ditemukan atau tidak executable"
fi

# Check SSH key
if [ -f "$HOME/.ssh/github_actions_deploy" ]; then
    print_success "SSH key untuk deployment sudah ada"
else
    print_warn "SSH key untuk deployment tidak ditemukan"
fi

# Step 6: Test Setup (Optional)
print_step "6. Test Setup (Optional)..."
read -p "Apakah Anda ingin test SSH connection ke server? (y/N): " TEST_SSH
if [[ "$TEST_SSH" =~ ^[Yy]$ ]]; then
    read -p "SSH host: " SSH_HOST
    read -p "SSH user: " SSH_USER
    
    print_info "Testing SSH connection..."
    if ssh -i "$HOME/.ssh/github_actions_deploy" -o StrictHostKeyChecking=no -o ConnectTimeout=5 "${SSH_USER}@${SSH_HOST}" "echo 'SSH connection successful!'" 2>/dev/null; then
        print_success "SSH connection test berhasil!"
    else
        print_fail "SSH connection test gagal"
        print_info "Pastikan:"
        print_info "  1. Public key sudah ditambahkan ke server's ~/.ssh/authorized_keys"
        print_info "  2. SSH service berjalan di server"
        print_info "  3. Firewall mengizinkan koneksi SSH"
    fi
fi

# Summary
print_header "Setup Summary"
print_info "Setup deployment sudah selesai!"
echo ""
print_info "Langkah selanjutnya:"
echo ""
print_step "1. Pastikan semua GitHub Secrets sudah ditambahkan"
print_step "2. Pastikan server produksi sudah dikonfigurasi dengan benar"
print_step "3. Test deployment dengan membuat commit di branch master"
echo ""
print_info "Alur kerja deployment:"
echo "  Commit → Git Hook (auto-push) → GitHub → GitHub Actions → SSH → Server → Docker Restart"
echo ""
print_info "Untuk informasi lebih lanjut, lihat DEPLOYMENT.md"
echo ""
print_success "Setup selesai! 🎉"
