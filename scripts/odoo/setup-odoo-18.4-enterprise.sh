#!/bin/bash
# Setup script for Odoo 18.4 Enterprise with Docker
# Author: SICANTIK Development Team
# Date: $(date +%Y-%m-%d)

set -e

echo "===================================================="
echo "🚀 SETUP ODOO 18.4 ENTERPRISE WITH DOCKER"
echo "===================================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

print_info() {
    echo -e "${YELLOW}ℹ️  $1${NC}"
}

# Check if running in the correct directory
if [ ! -f "docker-compose.yml" ]; then
    print_error "docker-compose.yml not found! Please run this script from the SICANTIK root directory."
    exit 1
fi

print_info "Step 1: Extracting enterprise license..."
if [ -f "enterprise-lic.tar.gz" ]; then
    mkdir -p ./tmp/license
    tar -xzf enterprise-lic.tar.gz -C ./tmp/license/

    # Find the actual license file
    LICENSE_FILE=$(find ./tmp/license -name "*.lic" | head -1)

    if [ -n "$LICENSE_FILE" ]; then
        print_success "Enterprise license found at $LICENSE_FILE"

        # Copy license to enterprise directory
        cp "$LICENSE_FILE" ./enterprise/odoo.lic
        print_success "License copied to ./enterprise/odoo.lic"
    else
        print_error "No license file (.lic) found in the archive!"
        print_info "Will use trial activation instead."
    fi
else
    print_error "enterprise-lic.tar.gz not found!"
    print_info "Will use trial activation instead."
fi

print_info "Step 2: Building Odoo 18.4 Docker image..."
docker-compose build --no-cache odoo_companion_standalone
print_success "Docker image built successfully"

print_info "Step 3: Starting PostgreSQL container..."
docker-compose up -d postgres_companion_standalone
print_success "PostgreSQL container started"

print_info "Step 4: Starting Odoo 18.4 Enterprise container..."
docker-compose up -d odoo_companion_standalone
print_success "Odoo 18.4 Enterprise container started"

print_info "Step 5: Waiting for Odoo to initialize (this may take a few minutes)..."
sleep 30

# Check if Odoo is running
if curl -s http://localhost:8065 > /dev/null; then
    print_success "Odoo 18.4 Enterprise is running and accessible!"
    print_info "URL: http://localhost:8065"
    print_info "Default database: Create during first access"
    print_info "Admin password: admin_odoo_secure_2025"
else
    print_error "Odoo is not responding. Please check the logs with:"
    print_info "docker-compose logs odoo_companion_standalone"
    exit 1
fi

# Cleanup temporary files
rm -rf ./tmp/license

print_info "Step 6: Checking Enterprise modules..."
MODULE_COUNT=$(docker exec odoo_companion_standalone ls /mnt/enterprise-addons/ | wc -l)
print_success "Found $MODULE_COUNT Enterprise modules in /mnt/enterprise-addons/"

# If license was found, check if it's properly detected
if [ -f "./enterprise/odoo.lic" ]; then
    print_success "Enterprise license is available at ./enterprise/odoo.lic"
    print_info "After creating a database, go to Settings to activate the Enterprise Edition"
else
    print_info "No license file found. You can:"
    print_info "1. Request a trial (30 days) from the Odoo interface"
    print_info "2. Activate with your Odoo.com credentials"
    print_info "3. Place a license file in ./enterprise/odoo.lic and restart the container"
fi

echo ""
echo "===================================================="
echo "🎉 ODOO 18.4 ENTERPRISE SETUP COMPLETE!"
echo "===================================================="
echo ""
echo "Next steps:"
echo "1. Access Odoo at http://localhost:8065"
echo "2. Create a new database"
echo "3. Update the Apps list"
echo "4. Install Enterprise modules as needed"
echo "5. Activate Enterprise Edition in Settings"
echo ""
echo "Useful commands:"
echo "- View logs: docker-compose logs -f odoo_companion_standalone"
echo "- Restart: docker-compose restart odoo_companion_standalone"
echo "- Stop: docker-compose stop odoo_companion_standalone"
echo ""
print_success "Setup completed successfully! 🚀"
