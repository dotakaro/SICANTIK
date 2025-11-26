#!/bin/bash

# Script untuk menampilkan SSH key yang sudah ada
# Berguna jika Anda sudah punya SSH key di GitHub Secrets dan ingin melihat public key-nya

set -e

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

print_info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_step() {
    echo -e "${BLUE}[STEP]${NC} $1"
}

echo ""
print_info "════════════════════════════════════════"
print_info "SSH Keys yang Tersedia di Laptop Anda"
print_info "════════════════════════════════════════"
echo ""

# Check for github_actions_deploy key
if [ -f "$HOME/.ssh/github_actions_deploy" ]; then
    print_step "1. github_actions_deploy (untuk GitHub Actions):"
    echo "   Private: ~/.ssh/github_actions_deploy"
    echo "   Public:  ~/.ssh/github_actions_deploy.pub"
    echo ""
    echo "   Public Key:"
    cat "$HOME/.ssh/github_actions_deploy.pub"
    echo ""
    echo ""
fi

# List all SSH public keys
print_step "2. Semua SSH Public Keys yang Tersedia:"
echo "─────────────────────────────────────────────────────────"
ls -1 ~/.ssh/*.pub 2>/dev/null | while read pubkey; do
    echo ""
    echo "File: $pubkey"
    echo "Key:"
    cat "$pubkey"
    echo "─────────────────────────────────────────────────────────"
done || echo "Tidak ada public key ditemukan"
echo ""

# Instructions
print_info "════════════════════════════════════════"
print_info "Cara Menggunakan:"
print_info "════════════════════════════════════════"
echo ""
print_step "Jika Anda sudah punya SSH_PRIVATE_KEY di GitHub Secrets:"
echo ""
echo "1. Cari private key yang sesuai dengan public key di atas"
echo "2. Private key biasanya ada di: ~/.ssh/[nama_key] (tanpa .pub)"
echo "3. Pastikan public key yang sesuai sudah ditambahkan ke server production"
echo ""
print_step "Untuk melihat private key (HATI-HATI, jangan share!):"
echo ""
echo "  cat ~/.ssh/[nama_key]"
echo ""
print_step "Jika key sudah ada di GitHub Secrets tapi tidak ada di laptop:"
echo ""
echo "  - Anda perlu generate key baru"
echo "  - Atau gunakan key yang sudah ada di server production"
echo "  - Jalankan: ./scripts/deploy/setup-ssh-deploy.sh"
echo ""

