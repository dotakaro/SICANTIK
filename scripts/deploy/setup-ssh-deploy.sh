#!/bin/bash

# Setup SSH untuk GitHub Actions Deployment
# Script ini membantu setup SSH key dan konfigurasi untuk deployment otomatis

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Functions
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
print_info "SSH Setup untuk GitHub Actions Deployment"
print_info "════════════════════════════════════════"
echo ""

# Check if SSH key already exists
SSH_KEY_NAME="github_actions_deploy"
SSH_KEY_PATH="$HOME/.ssh/${SSH_KEY_NAME}"

if [ -f "${SSH_KEY_PATH}" ]; then
    print_warn "SSH key already exists: ${SSH_KEY_PATH}"
    read -p "Generate new key? (y/N): " GENERATE_NEW
    if [[ ! "$GENERATE_NEW" =~ ^[Yy]$ ]]; then
        print_info "Using existing SSH key"
    else
        print_step "Backing up existing key..."
        mv "${SSH_KEY_PATH}" "${SSH_KEY_PATH}.backup.$(date +%Y%m%d_%H%M%S)"
        mv "${SSH_KEY_PATH}.pub" "${SSH_KEY_PATH}.pub.backup.$(date +%Y%m%d_%H%M%S)" 2>/dev/null || true
    fi
fi

# Generate SSH key if it doesn't exist
if [ ! -f "${SSH_KEY_PATH}" ]; then
    print_step "Generating new SSH key..."
    
    read -p "SSH key comment (default: github-actions-deploy): " KEY_COMMENT
    KEY_COMMENT=${KEY_COMMENT:-github-actions-deploy}
    
    ssh-keygen -t ed25519 -C "$KEY_COMMENT" -f "${SSH_KEY_PATH}" -N "" || {
        print_error "Failed to generate SSH key"
        exit 1
    }
    
    print_info "SSH key generated successfully"
else
    print_info "Using existing SSH key"
fi

# Display public key
echo ""
print_step "Public SSH Key (add this to server authorized_keys):"
echo "─────────────────────────────────────────────────────────"
cat "${SSH_KEY_PATH}.pub"
echo "─────────────────────────────────────────────────────────"
echo ""

# Display private key for GitHub Secrets
echo ""
print_step "Private SSH Key (add this to GitHub Secrets as SSH_PRIVATE_KEY):"
echo "─────────────────────────────────────────────────────────"
cat "${SSH_KEY_PATH}"
echo "─────────────────────────────────────────────────────────"
echo ""

# Instructions
print_info "════════════════════════════════════════"
print_info "Setup Instructions:"
print_info "════════════════════════════════════════"
echo ""
print_step "1. Add Public Key to Server:"
echo "   Run this command on your production server:"
echo ""
echo "   mkdir -p ~/.ssh"
echo "   echo '$(cat "${SSH_KEY_PATH}.pub")' >> ~/.ssh/authorized_keys"
echo "   chmod 600 ~/.ssh/authorized_keys"
echo "   chmod 700 ~/.ssh"
echo ""
print_step "2. Add Private Key to GitHub Secrets:"
echo "   - Go to your GitHub repository"
echo "   - Settings → Secrets and variables → Actions"
echo "   - Click 'New repository secret'"
echo "   - Name: SSH_PRIVATE_KEY"
echo "   - Value: (copy the private key shown above)"
echo ""
print_step "3. Add Other Required Secrets:"
echo "   - SSH_HOST: Your production server IP or hostname"
echo "   - SSH_USER: SSH username (e.g., root, ubuntu, deploy)"
echo "   - PROJECT_PATH: Full path to project on server (e.g., /var/www/sicantik)"
echo ""
print_step "4. Test SSH Connection:"
read -p "Test SSH connection now? (y/N): " TEST_SSH
if [[ "$TEST_SSH" =~ ^[Yy]$ ]]; then
    read -p "SSH host: " SSH_HOST
    read -p "SSH user: " SSH_USER
    
    print_step "Testing SSH connection..."
    ssh -i "${SSH_KEY_PATH}" -o StrictHostKeyChecking=no "${SSH_USER}@${SSH_HOST}" "echo 'SSH connection successful!'" && {
        print_info "✅ SSH connection test successful!"
    } || {
        print_error "❌ SSH connection test failed"
        print_info "Make sure:"
        print_info "  1. Public key is added to server's ~/.ssh/authorized_keys"
        print_info "  2. SSH service is running on server"
        print_info "  3. Firewall allows SSH connections"
    }
fi

echo ""
print_info "════════════════════════════════════════"
print_info "Setup complete!"
print_info "════════════════════════════════════════"

