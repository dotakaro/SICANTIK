#!/bin/bash

# Script untuk mengatasi konflik odoo_source di server produksi
# Gunakan script ini di server produksi sebelum pull

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
print_info "Fix odoo_source Conflict di Server Produksi"
print_info "════════════════════════════════════════"
echo ""

# Cek apakah di dalam project directory
if [ ! -d ".git" ]; then
    print_error "Script ini harus dijalankan di dalam project directory!"
    exit 1
fi

print_warn "Ini akan menghapus file-file untracked di odoo_source/"
print_warn "File-file tersebut akan diganti dengan versi dari Git"
echo ""

read -p "Lanjutkan? (y/N): " CONFIRM
if [[ ! "$CONFIRM" =~ ^[Yy]$ ]]; then
    print_info "Dibatalkan"
    exit 0
fi

# Backup odoo_source jika diperlukan (optional)
if [ -d "odoo_source" ]; then
    print_info "Membuat backup odoo_source..."
    BACKUP_DIR="odoo_source_backup_$(date +%Y%m%d_%H%M%S)"
    cp -r odoo_source "$BACKUP_DIR" 2>/dev/null || true
    print_info "Backup dibuat di: $BACKUP_DIR"
fi

# Hapus file-file untracked di odoo_source
print_info "Menghapus file untracked di odoo_source/..."
git clean -fd odoo_source/ 2>/dev/null || true

# Reset odoo_source ke versi Git
print_info "Reset odoo_source ke versi Git..."
git checkout HEAD -- odoo_source/ 2>/dev/null || true

# Hapus folder odoo_source jika masih ada konflik
if [ -d "odoo_source" ] && git status odoo_source/ 2>/dev/null | grep -q "untracked"; then
    print_warn "Masih ada file untracked, menghapus folder odoo_source..."
    rm -rf odoo_source/
    print_info "Folder odoo_source dihapus, akan di-restore dari Git"
fi

print_info "════════════════════════════════════════"
print_info "Selesai! Sekarang bisa pull lagi"
print_info "════════════════════════════════════════"
echo ""
print_info "Jalankan: git pull origin master"
echo ""

