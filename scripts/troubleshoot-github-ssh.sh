#!/bin/bash

# Script Troubleshooting SSH Key untuk GitHub

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
print_info "Troubleshooting GitHub SSH Connection"
print_info "════════════════════════════════════════"
echo ""

# Step 1: Check SSH keys
print_step "1. Checking SSH keys..."
echo ""

if [ -d ~/.ssh ]; then
    print_info "SSH directory exists: ~/.ssh"
    echo ""
    print_info "Available SSH keys:"
    ls -la ~/.ssh/*.pub 2>/dev/null | while read line; do
        echo "  $line"
    done || echo "  (no public keys found)"
else
    print_error "SSH directory not found: ~/.ssh"
    exit 1
fi

echo ""

# Step 2: Check permissions
print_step "2. Checking file permissions..."
echo ""

if [ -d ~/.ssh ]; then
    SSH_DIR_PERM=$(stat -c "%a" ~/.ssh 2>/dev/null || stat -f "%OLp" ~/.ssh 2>/dev/null)
    if [ "$SSH_DIR_PERM" != "700" ] && [ "$SSH_DIR_PERM" != "755" ]; then
        print_warn "SSH directory permissions: $SSH_DIR_PERM (should be 700)"
        read -p "Fix permissions? (y/N): " FIX_PERM
        if [[ "$FIX_PERM" =~ ^[Yy]$ ]]; then
            chmod 700 ~/.ssh
            print_info "Permissions fixed"
        fi
    else
        print_info "SSH directory permissions: OK ($SSH_DIR_PERM)"
    fi
fi

echo ""

# Step 3: Check SSH config
print_step "3. Checking SSH config..."
echo ""

if [ -f ~/.ssh/config ]; then
    print_info "SSH config exists: ~/.ssh/config"
    echo ""
    print_info "GitHub configuration in SSH config:"
    grep -A 5 "Host github.com" ~/.ssh/config 2>/dev/null || echo "  (no github.com entry found)"
    
    CONFIG_PERM=$(stat -c "%a" ~/.ssh/config 2>/dev/null || stat -f "%OLp" ~/.ssh/config 2>/dev/null)
    if [ "$CONFIG_PERM" != "600" ] && [ "$CONFIG_PERM" != "644" ]; then
        print_warn "SSH config permissions: $CONFIG_PERM (should be 600)"
        read -p "Fix permissions? (y/N): " FIX_CONFIG
        if [[ "$FIX_CONFIG" =~ ^[Yy]$ ]]; then
            chmod 600 ~/.ssh/config
            print_info "Permissions fixed"
        fi
    fi
else
    print_warn "SSH config not found: ~/.ssh/config"
    read -p "Create SSH config? (y/N): " CREATE_CONFIG
    if [[ "$CREATE_CONFIG" =~ ^[Yy]$ ]]; then
        SSH_KEY_PATH="$HOME/.ssh/id_ed25519_github"
        if [ ! -f "$SSH_KEY_PATH" ]; then
            read -p "Enter path to SSH private key (default: ~/.ssh/id_ed25519_github): " CUSTOM_KEY
            SSH_KEY_PATH=${CUSTOM_KEY:-$HOME/.ssh/id_ed25519_github}
        fi
        
        cat > ~/.ssh/config << CONFIG_EOF
Host github.com
    HostName github.com
    User git
    IdentityFile ${SSH_KEY_PATH}
    IdentitiesOnly yes
CONFIG_EOF
        chmod 600 ~/.ssh/config
        print_info "SSH config created"
    fi
fi

echo ""

# Step 4: Test with verbose output
print_step "4. Testing SSH connection with verbose output..."
echo ""

read -p "Test SSH connection to GitHub? (y/N): " TEST_SSH
if [[ "$TEST_SSH" =~ ^[Yy]$ ]]; then
    print_info "Testing connection (this will show detailed debug info)..."
    echo ""
    ssh -vT git@github.com 2>&1 | tee /tmp/github_ssh_test.log
    
    echo ""
    if grep -q "successfully authenticated" /tmp/github_ssh_test.log; then
        print_info "✅ SSH connection successful!"
    elif grep -q "Permission denied" /tmp/github_ssh_test.log; then
        print_error "❌ Permission denied"
        echo ""
        print_step "Possible issues:"
        echo "  1. Public key not added to GitHub"
        echo "  2. Wrong SSH key being used"
        echo "  3. Key permissions incorrect"
        echo ""
        print_step "Check which key is being used:"
        grep "Offering public key" /tmp/github_ssh_test.log || echo "  (no key offered)"
    else
        print_warn "Connection test completed (check output above)"
    fi
fi

echo ""

# Step 5: Show public key for verification
print_step "5. Public keys for verification..."
echo ""

read -p "Show public keys to verify with GitHub? (y/N): " SHOW_KEYS
if [[ "$SHOW_KEYS" =~ ^[Yy]$ ]]; then
    echo ""
    print_info "Public keys (verify these are added to GitHub):"
    echo "─────────────────────────────────────────────────────────"
    for pubkey in ~/.ssh/*.pub; do
        if [ -f "$pubkey" ]; then
            echo ""
            echo "File: $pubkey"
            echo "Key:"
            cat "$pubkey"
            echo "─────────────────────────────────────────────────────────"
        fi
    done
    echo ""
    print_info "Go to: https://github.com/settings/keys"
    print_info "Verify these keys are added to your GitHub account"
fi

echo ""

# Step 6: Fix common issues
print_step "6. Common fixes..."
echo ""

read -p "Apply common fixes? (y/N): " APPLY_FIXES
if [[ "$APPLY_FIXES" =~ ^[Yy]$ ]]; then
    # Fix SSH directory permissions
    chmod 700 ~/.ssh 2>/dev/null || true
    
    # Fix SSH key permissions
    for key in ~/.ssh/id_*; do
        if [ -f "$key" ] && [[ ! "$key" =~ \.pub$ ]]; then
            chmod 600 "$key" 2>/dev/null || true
        fi
    done
    
    # Fix SSH config permissions
    if [ -f ~/.ssh/config ]; then
        chmod 600 ~/.ssh/config
    fi
    
    # Remove old GitHub host key and re-add
    ssh-keygen -R github.com 2>/dev/null || true
    ssh-keyscan github.com >> ~/.ssh/known_hosts 2>/dev/null || true
    
    print_info "Common fixes applied"
fi

echo ""
print_info "════════════════════════════════════════"
print_info "Troubleshooting complete!"
print_info "════════════════════════════════════════"
echo ""
print_info "If still having issues:"
echo "  1. Verify public key is added to GitHub: https://github.com/settings/keys"
echo "  2. Check SSH config is correct"
echo "  3. Try: ssh -T -i ~/.ssh/id_ed25519_github git@github.com"
echo "  4. Check GitHub account has access to repository"

