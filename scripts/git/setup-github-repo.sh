#!/bin/bash

# Setup GitHub Repository untuk SICANTIK Project
# Script ini akan membuat GitHub repository baru dan mengkonfigurasi remote

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if GitHub CLI is installed
if ! command -v gh &> /dev/null; then
    print_error "GitHub CLI (gh) tidak terinstall."
    echo "Install dengan:"
    echo "  macOS: brew install gh"
    echo "  Linux: https://cli.github.com/manual/installation"
    echo ""
    echo "Atau gunakan manual setup:"
    echo "  1. Buat repository di GitHub.com"
    echo "  2. Jalankan: git remote add origin https://github.com/USERNAME/REPO.git"
    exit 1
fi

# Check if user is logged in to GitHub
if ! gh auth status &> /dev/null; then
    print_warn "Belum login ke GitHub CLI"
    print_info "Menjalankan: gh auth login"
    gh auth login
fi

# Get current directory name as default repo name
DEFAULT_REPO_NAME=$(basename "$(pwd)")

# Prompt for repository name
echo ""
read -p "Nama repository GitHub [${DEFAULT_REPO_NAME}]: " REPO_NAME
REPO_NAME=${REPO_NAME:-$DEFAULT_REPO_NAME}

# Prompt for description
read -p "Deskripsi repository (optional): " REPO_DESCRIPTION

# Prompt for visibility
echo ""
echo "Pilih visibility repository:"
echo "  1) Public"
echo "  2) Private"
read -p "Pilihan [1]: " VISIBILITY_CHOICE
VISIBILITY_CHOICE=${VISIBILITY_CHOICE:-1}

if [ "$VISIBILITY_CHOICE" = "2" ]; then
    VISIBILITY="private"
else
    VISIBILITY="public"
fi

# Get GitHub username
GITHUB_USER=$(gh api user --jq .login)
print_info "GitHub username: ${GITHUB_USER}"

# Check if remote already exists
if git remote get-url origin &> /dev/null; then
    CURRENT_REMOTE=$(git remote get-url origin)
    print_warn "Remote 'origin' sudah ada: ${CURRENT_REMOTE}"
    read -p "Ganti remote origin? (y/N): " REPLACE_REMOTE
    if [[ "$REPLACE_REMOTE" =~ ^[Yy]$ ]]; then
        git remote remove origin
    else
        print_info "Menggunakan remote yang sudah ada"
        exit 0
    fi
fi

# Create repository on GitHub
print_info "Membuat repository GitHub: ${REPO_NAME}"

if [ -n "$REPO_DESCRIPTION" ]; then
    gh repo create "$REPO_NAME" --description "$REPO_DESCRIPTION" --"$VISIBILITY" --source=. --remote=origin --push
else
    gh repo create "$REPO_NAME" --"$VISIBILITY" --source=. --remote=origin --push
fi

if [ $? -eq 0 ]; then
    print_info "Repository berhasil dibuat!"
    print_info "URL: https://github.com/${GITHUB_USER}/${REPO_NAME}"
    
    # Set default branch to master if not already set
    CURRENT_BRANCH=$(git branch --show-current)
    if [ "$CURRENT_BRANCH" != "master" ] && [ "$CURRENT_BRANCH" != "main" ]; then
        print_warn "Branch saat ini: ${CURRENT_BRANCH}"
        print_info "Disarankan menggunakan branch 'master' atau 'main'"
    fi
    
    # Push current branch
    print_info "Mengecek apakah perlu push..."
    if [ -n "$(git status --porcelain)" ]; then
        print_warn "Ada perubahan yang belum di-commit"
        read -p "Commit perubahan sekarang? (y/N): " COMMIT_NOW
        if [[ "$COMMIT_NOW" =~ ^[Yy]$ ]]; then
            git add .
            read -p "Commit message: " COMMIT_MSG
            git commit -m "${COMMIT_MSG}"
        fi
    fi
    
    # Check if branch is already pushed
    if ! git rev-parse --abbrev-ref --symbolic-full-name @{u} &> /dev/null; then
        print_info "Push branch ${CURRENT_BRANCH} ke GitHub..."
        git push -u origin "${CURRENT_BRANCH}"
    else
        print_info "Branch sudah terhubung dengan remote"
    fi
    
    print_info ""
    print_info "Setup selesai! Repository GitHub sudah dikonfigurasi."
    print_info "Selanjutnya:"
    print_info "  1. Setup GitHub Secrets untuk deployment (lihat DEPLOYMENT.md)"
    print_info "  2. Install git hook untuk auto-push: ./scripts/git/install-hooks.sh"
else
    print_error "Gagal membuat repository"
    exit 1
fi

