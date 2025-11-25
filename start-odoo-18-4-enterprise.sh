#!/bin/bash
# Start Odoo 18.4 Enterprise with Docker
# Author: SICANTIK Development Team
# Date: $(date +%Y-%m-%d)

set -e

echo "===================================================="
echo "🚀 START ODOO 18.4 ENTERPRISE"
echo "===================================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[1;34m'
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

print_step() {
    echo -e "${BLUE}🔧 $1${NC}"
}

# Check if running in the correct directory
if [ ! -f "docker-compose.yml" ]; then
    print_error "docker-compose.yml not found! Please run this script from the SICANTIK root directory."
    exit 1
fi

# Check if enterprise addons exist
if [ ! -d "enterprise" ] || [ -z "$(ls -A enterprise)" ]; then
    print_error "Enterprise addons not found!"
    print_info "Please run the setup script first:"
    print_info "./scripts/odoo/setup-odoo-18.4-enterprise.sh"
    exit 1
fi

# Check if odoo_source exists
if [ ! -d "odoo_source" ] || [ -z "$(ls -A odoo_source)" ]; then
    print_error "Odoo source code not found!"
    print_info "Please run the setup script first:"
    print_info "./scripts/odoo/setup-odoo-18.4-enterprise.sh"
    exit 1
fi

print_step "Starting PostgreSQL container..."
docker-compose up -d postgres_companion_standalone
print_success "PostgreSQL container started"

print_step "Starting Redis cache..."
docker-compose up -d redis_cache
print_success "Redis cache container started"

print_step "Starting Odoo 18.4 Enterprise container..."
docker-compose up -d odoo_companion_standalone
print_success "Odoo 18.4 Enterprise container started"

print_step "Waiting for Odoo to initialize (this may take a few minutes)..."
sleep 30

# Check if Odoo is running
MAX_RETRIES=10
RETRY_COUNT=0
ODOO_RUNNING=false

while [ $RETRY_COUNT -lt $MAX_RETRIES ]; do
    if curl -s http://localhost:8065 > /dev/null; then
        ODOO_RUNNING=true
        break
    fi

    print_info "Waiting for Odoo to start... (attempt $((RETRY_COUNT+1))/$MAX_RETRIES)"
    sleep 30
    RETRY_COUNT=$((RETRY_COUNT+1))
done

if [ "$ODOO_RUNNING" = true ]; then
    print_success "Odoo 18.4 Enterprise is running and accessible!"
    print_info "URL: http://localhost:8065"
else
    print_error "Odoo is not responding. Please check the logs with:"
    print_info "docker-compose logs odoo_companion_standalone"
    exit 1
fi

print_step "Checking Enterprise modules..."
MODULE_COUNT=$(docker exec odoo_companion_standalone ls /mnt/enterprise-addons/ | wc -l)
print_success "Found $MODULE_COUNT Enterprise modules in /mnt/enterprise-addons/"

# Check if license is available
if [ -f "./enterprise/odoo.lic" ]; then
    print_success "Enterprise license is available at ./enterprise/odoo.lic"
elif [ -f "enterprise-lic.tar.gz" ]; then
    print_info "License archive found. Extracting..."
    mkdir -p ./tmp/license
    tar -xzf enterprise-lic.tar.gz -C ./tmp/license/

    LICENSE_FILE=$(find ./tmp/license -name "*.lic" | head -1)

    if [ -n "$LICENSE_FILE" ]; then
        cp "$LICENSE_FILE" ./enterprise/odoo.lic
        print_success "License copied to ./enterprise/odoo.lic"

        # Restart Odoo to load the license
        print_info "Restarting Odoo to load the license..."
        docker-compose restart odoo_companion_standalone
        sleep 30
    else
        print_info "No license file found in the archive."
    fi

    rm -rf ./tmp/license
else
    print_info "No license file found. You can:"
    print_info "1. Request a trial (30 days) from the Odoo interface"
    print_info "2. Activate with your Odoo.com credentials"
    print_info "3. Place a license file in ./enterprise/odoo.lic and restart the container"
fi

echo ""
echo "===================================================="
echo "🎉 ODOO 18.4 ENTERPRISE IS RUNNING!"
echo "===================================================="
echo ""
print_info "Next steps:"
print_info "1. Access Odoo at http://localhost:8065"
print_info "2. Create a new database if needed"
print_info "3. Update the Apps list"
print_info "4. Install Enterprise modules as needed"
print_info "5. Activate Enterprise Edition in Settings"
echo ""
print_info "For activation, run:"
print_info "./scripts/odoo/activate-odoo-enterprise.sh"
echo ""
print_info "Useful commands:"
print_info "- View logs: docker-compose logs -f odoo_companion_standalone"
print_info "- Restart: docker-compose restart odoo_companion_standalone"
print_info "- Stop: docker-compose stop odoo_companion_standalone"
echo ""
print_success "Odoo 18.4 Enterprise started successfully! 🚀"
