#!/bin/bash

# Script untuk menghapus Twilio secrets dari Git history

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
print_info "Fix Twilio Secrets di Git History"
print_info "════════════════════════════════════════"
echo ""

print_warn "Ini akan mengubah Git history!"
print_warn "Pastikan Anda sudah backup repository atau siap untuk force push"
echo ""

read -p "Lanjutkan? (y/N): " CONFIRM
if [[ ! "$CONFIRM" =~ ^[Yy]$ ]]; then
    print_info "Dibatalkan"
    exit 0
fi

print_info "Menghapus Twilio secrets dari Git history..."

# Replace secrets di semua commit
git filter-branch --force --tree-filter '
    find . -type f \( -name "*.py" -o -name "*.po" -o -name "*.xml" -o -name "*.pot" \) -exec sed -i "" "s/AC12345678987654321234567898765432/TEST_ACCOUNT_SID_123456789012345678901234567890/g" {} \; 2>/dev/null || true
    find . -type f \( -name "*.py" -o -name "*.po" -o -name "*.xml" -o -name "*.pot" \) -exec sed -i "" "s/AC11111222223333344444555556666677/TEST_ACCOUNT_SID_11111222223333344444555556666677/g" {} \; 2>/dev/null || true
    find . -type f \( -name "*.py" -o -name "*.po" -o -name "*.xml" -o -name "*.pot" \) -exec sed -i "" "s/ACabcde12345abcde12345abcde12345ab/TEST_ACCOUNT_SID_abcde12345abcde12345abcde12345ab/g" {} \; 2>/dev/null || true
' --prune-empty --tag-name-filter cat -- --all

print_info "Membersihkan backup refs..."
git for-each-ref --format="delete %(refname)" refs/original | git update-ref --stdin

print_info "Menjalankan garbage collection..."
git reflog expire --expire=now --all
git gc --prune=now --aggressive

print_info "════════════════════════════════════════"
print_info "Selesai! Secrets sudah dihapus dari history"
print_info "════════════════════════════════════════"
echo ""
print_warn "Sekarang Anda perlu force push:"
print_info "  git push --force-with-lease origin master"
echo ""

