#!/bin/bash

# Script untuk fix divergent branches di server produksi
# Jalankan script ini jika git pull gagal karena divergent branches

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
print_info "Fix Divergent Branches di Server Produksi"
print_info "════════════════════════════════════════"
echo ""

# Cek apakah di dalam project directory
if [ ! -d ".git" ]; then
    print_error "Script ini harus dijalankan di dalam project directory!"
    exit 1
fi

# Cek status
print_info "Current branch: $(git branch --show-current)"
print_info "Current commit: $(git rev-parse --short HEAD)"
print_info "Remote commit: $(git rev-parse --short origin/master 2>/dev/null || echo 'N/A')"

# Backup config files
print_step "Backing up production config files..."
if [ -f "config_odoo/odoo.conf" ]; then
    BACKUP_FILE="config_odoo/odoo.conf.backup.$(date +%Y%m%d_%H%M%S)"
    cp config_odoo/odoo.conf "$BACKUP_FILE" || true
    print_info "Backed up odoo.conf to $BACKUP_FILE"
fi

print_warn "Ini akan reset ke origin/master dan discard semua local changes!"
print_warn "Config files sudah di-backup"
echo ""

read -p "Lanjutkan? (y/N): " CONFIRM
if [[ ! "$CONFIRM" =~ ^[Yy]$ ]]; then
    print_info "Dibatalkan"
    exit 0
fi

# Fetch latest
print_info "Fetching latest from origin..."
git fetch origin master || {
    print_error "Failed to fetch from origin"
    exit 1
}

# Reset ke origin/master
print_info "Resetting to origin/master..."
git reset --hard origin/master || {
    print_error "Failed to reset to origin/master"
    exit 1
}

# Restore config files
print_info "Restoring production config files..."
if ls config_odoo/odoo.conf.backup.* 1> /dev/null 2>&1; then
    LATEST_BACKUP=$(ls -t config_odoo/odoo.conf.backup.* 2>/dev/null | head -1)
    if [ -n "$LATEST_BACKUP" ] && [ -f "$LATEST_BACKUP" ]; then
        cp "$LATEST_BACKUP" config_odoo/odoo.conf || true
        print_info "Restored odoo.conf from backup"
    fi
fi

print_info "════════════════════════════════════════"
print_info "Selesai! Branch sudah di-sync dengan origin/master"
print_info "════════════════════════════════════════"
echo ""

