#!/bin/bash

# Script untuk fix odoo_source di server produksi
# Jalankan script ini di server produksi jika masih ada konflik

set -e

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

print_info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

echo ""
print_info "════════════════════════════════════════"
print_info "Fix odoo_source di Server Produksi"
print_info "════════════════════════════════════════"
echo ""

# Cek apakah di dalam project directory
if [ ! -d ".git" ]; then
    print_error "Script ini harus dijalankan di dalam project directory!"
    exit 1
fi

print_warn "Ini akan menghapus folder odoo_source/"
print_warn "Folder ini akan di-clone langsung dari GitHub Odoo jika diperlukan"
echo ""

read -p "Lanjutkan? (y/N): " CONFIRM
if [[ ! "$CONFIRM" =~ ^[Yy]$ ]]; then
    print_info "Dibatalkan"
    exit 0
fi

# Hapus folder odoo_source
if [ -d "odoo_source" ]; then
    print_info "Menghapus folder odoo_source..."
    rm -rf odoo_source/
    print_info "✅ Folder odoo_source dihapus"
else
    print_info "Folder odoo_source tidak ditemukan"
fi

# Clean Git untuk memastikan tidak ada file untracked
print_info "Membersihkan Git..."
git clean -fd odoo_source/ 2>/dev/null || true

print_info "════════════════════════════════════════"
print_info "Selesai! Sekarang bisa pull lagi"
print_info "════════════════════════════════════════"
echo ""
print_info "Jalankan: git pull origin master"
echo ""
print_info "Jika perlu odoo_source, clone dari GitHub Odoo:"
print_info "  git clone https://github.com/odoo/odoo.git odoo_source --branch 18.4 --depth 1"
echo ""

