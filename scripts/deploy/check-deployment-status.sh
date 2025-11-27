#!/bin/bash

# Script untuk check status deployment
# Cek apakah perubahan sudah ter-push ke GitHub dan ter-deploy ke production

set -e

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
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

print_step() {
    echo -e "${BLUE}[STEP]${NC} $1"
}

echo ""
print_info "════════════════════════════════════════"
print_info "Check Deployment Status"
print_info "════════════════════════════════════════"
echo ""

# Cek local commit
print_step "Checking local commits..."
LOCAL_COMMIT=$(git rev-parse HEAD)
LOCAL_COMMIT_SHORT=$(git rev-parse --short HEAD)
LOCAL_MSG=$(git log -1 --pretty=%B)
print_info "Local commit: $LOCAL_COMMIT_SHORT"
print_info "Message: $LOCAL_MSG"
echo ""

# Fetch dari GitHub
print_step "Fetching from GitHub..."
if git fetch origin master 2>&1; then
    print_info "✅ Successfully fetched from GitHub"
else
    print_error "❌ Failed to fetch from GitHub"
    exit 1
fi
echo ""

# Cek remote commit
print_step "Checking remote commits..."
REMOTE_COMMIT=$(git rev-parse origin/master)
REMOTE_COMMIT_SHORT=$(git rev-parse --short origin/master)
REMOTE_MSG=$(git log -1 --pretty=%B origin/master)
print_info "Remote commit: $REMOTE_COMMIT_SHORT"
print_info "Message: $REMOTE_MSG"
echo ""

# Compare
print_step "Comparing local vs remote..."
if [ "$LOCAL_COMMIT" = "$REMOTE_COMMIT" ]; then
    print_info "✅ Local and remote are in sync"
else
    print_warn "⚠️  Local and remote are different"
    
    # Cek apakah local ahead
    LOCAL_AHEAD=$(git rev-list --count origin/master..HEAD 2>/dev/null || echo "0")
    REMOTE_AHEAD=$(git rev-list --count HEAD..origin/master 2>/dev/null || echo "0")
    
    if [ "$LOCAL_AHEAD" -gt 0 ]; then
        print_warn "Local is $LOCAL_AHEAD commit(s) ahead of remote"
        print_info "You need to push: git push origin master"
    fi
    
    if [ "$REMOTE_AHEAD" -gt 0 ]; then
        print_warn "Remote is $REMOTE_AHEAD commit(s) ahead of local"
        print_info "You need to pull: git pull origin master"
    fi
    
    # Show commits difference
    echo ""
    print_step "Commits in local but not in remote:"
    git log --oneline origin/master..HEAD 2>/dev/null || echo "  (none)"
    
    echo ""
    print_step "Commits in remote but not in local:"
    git log --oneline HEAD..origin/master 2>/dev/null || echo "  (none)"
fi

echo ""
print_info "════════════════════════════════════════"
print_info "Status Check Complete"
print_info "════════════════════════════════════════"
echo ""

