#!/bin/bash

# Script untuk setup config production dari template
# Jalankan script ini sekali di server produksi untuk membuat config dari template

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
print_info "Setup Production Config dari Template"
print_info "════════════════════════════════════════"
echo ""

# Cek apakah di dalam project directory
if [ ! -d ".git" ]; then
    print_error "Script ini harus dijalankan di dalam project directory!"
    exit 1
fi

# Setup odoo.conf
if [ ! -f "config_odoo/odoo.conf" ] && [ -f "config_odoo/odoo.conf.example" ]; then
    print_info "Membuat config_odoo/odoo.conf dari template..."
    cp config_odoo/odoo.conf.example config_odoo/odoo.conf
    print_info "✅ File config_odoo/odoo.conf dibuat"
    print_warn "Sekarang edit file config_odoo/odoo.conf sesuai kebutuhan produksi"
    print_warn "File ini tidak akan di-overwrite saat pull dari Git"
elif [ -f "config_odoo/odoo.conf" ]; then
    print_info "File config_odoo/odoo.conf sudah ada"
    print_info "File ini tidak akan di-overwrite saat pull dari Git"
else
    print_error "Template config_odoo/odoo.conf.example tidak ditemukan!"
    exit 1
fi

print_info "════════════════════════════════════════"
print_info "Setup selesai!"
print_info "════════════════════════════════════════"
echo ""

