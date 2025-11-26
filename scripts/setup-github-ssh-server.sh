#!/bin/bash

# Script Setup SSH Key untuk GitHub di Server Production

set -e

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
print_info "Setup SSH Key untuk GitHub"
print_info "════════════════════════════════════════"
echo ""

# Check if SSH key already exists
SSH_KEY_PATH="$HOME/.ssh/id_ed25519_github"

if [ -f "$SSH_KEY_PATH" ]; then
    print_info "SSH key already exists: $SSH_KEY_PATH"
    read -p "Generate new key? (y/N): " GENERATE_NEW
    if [[ ! "$GENERATE_NEW" =~ ^[Yy]$ ]]; then
        print_info "Using existing SSH key"
    else
        mv "$SSH_KEY_PATH" "${SSH_KEY_PATH}.backup.$(date +%Y%m%d_%H%M%S)"
        mv "${SSH_KEY_PATH}.pub" "${SSH_KEY_PATH}.pub.backup.$(date +%Y%m%d_%H%M%S)" 2>/dev/null || true
    fi
fi

# Generate SSH key if it doesn't exist
if [ ! -f "$SSH_KEY_PATH" ]; then
    print_step "Generating new SSH key..."
    
    read -p "Email/comment for SSH key (default: server-production@sicantik): " KEY_COMMENT
    KEY_COMMENT=${KEY_COMMENT:-server-production@sicantik}
    
    ssh-keygen -t ed25519 -C "$KEY_COMMENT" -f "$SSH_KEY_PATH" -N "" || {
        echo "Failed to generate SSH key"
        exit 1
    }
    
    print_info "SSH key generated successfully"
fi

# Display public key
echo ""
print_step "Public SSH Key (add this to GitHub):"
echo "─────────────────────────────────────────────────────────"
cat "${SSH_KEY_PATH}.pub"
echo "─────────────────────────────────────────────────────────"
echo ""

# Setup SSH config
print_step "Setting up SSH config..."
mkdir -p ~/.ssh
chmod 700 ~/.ssh

if ! grep -q "Host github.com" ~/.ssh/config 2>/dev/null; then
    cat >> ~/.ssh/config << CONFIG_EOF

Host github.com
    HostName github.com
    User git
    IdentityFile ${SSH_KEY_PATH}
    IdentitiesOnly yes
CONFIG_EOF
    chmod 600 ~/.ssh/config
    print_info "SSH config updated"
else
    print_info "SSH config already has github.com entry"
fi

# Instructions
echo ""
print_info "════════════════════════════════════════"
print_info "Next Steps:"
print_info "════════════════════════════════════════"
echo ""
print_step "1. Add Public Key to GitHub:"
echo "   - Go to: https://github.com/settings/keys"
echo "   - Click 'New SSH key'"
echo "   - Title: SICANTIK Production Server"
echo "   - Key: (copy the public key shown above)"
echo ""
print_step "2. Test SSH Connection:"
read -p "Test SSH connection to GitHub now? (y/N): " TEST_SSH
if [[ "$TEST_SSH" =~ ^[Yy]$ ]]; then
    print_step "Testing SSH connection..."
    ssh -T git@github.com 2>&1 | grep -q "successfully authenticated" && {
        print_info "✅ SSH connection successful!"
    } || {
        echo "⚠️  SSH connection test completed (may show warning, that's OK)"
    }
fi

echo ""
print_info "Setup complete!"
print_info "You can now clone repository with:"
print_info "  git clone git@github.com:dotakaro/SICANTIK.git /opt/sicantik"
