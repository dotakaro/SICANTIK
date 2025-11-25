#!/bin/bash
# Activation script for Odoo 18.4 Enterprise
# Author: SICANTIK Development Team
# Date: $(date +%Y-%m-%d)

set -e

echo "===================================================="
echo "🔑 ACTIVATE ODOO 18.4 ENTERPRISE"
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

# Check if Odoo is running
if ! curl -s http://localhost:8065 > /dev/null; then
    print_error "Odoo is not running! Please start it with:"
    print_info "docker-compose up -d odoo_companion_standalone"
    exit 1
fi

print_success "Odoo 18.4 Enterprise is running!"

# Get database name from user
echo ""
print_step "Please enter your database name:"
read -p "Database name: " DB_NAME

if [ -z "$DB_NAME" ]; then
    print_error "Database name cannot be empty!"
    exit 1
fi

# Check if database exists
DB_EXISTS=$(docker exec postgres_companion_standalone psql -U odoo -tAc "SELECT 1 FROM pg_database WHERE datname='$DB_NAME'" 2>/dev/null || echo "0")

if [ "$DB_EXISTS" != "1" ]; then
    print_error "Database '$DB_NAME' does not exist!"
    print_info "Please create the database first through the Odoo web interface:"
    print_info "1. Open http://localhost:8065/web/database/selector"
    print_info "2. Click 'Create Database'"
    print_info "3. Enter '$DB_NAME' as the database name"
    print_info "4. Set admin password: admin_odoo_secure_2025"
    print_info "5. Select your language and country"
    print_info "6. Click 'Create'"
    exit 1
fi

print_success "Database '$DB_NAME' exists!"

echo ""
print_step "Please choose an activation method:"
echo "1) Trial activation (30 days)"
echo "2) License file activation"
echo "3) Odoo.com account activation"
read -p "Your choice (1-3): " CHOICE

case $CHOICE in
    1)
        print_info "Setting up trial activation..."
        print_info "After this script completes:"
        print_info "1. Open http://localhost:8065/web?#action=base.module_action"
        print_info "2. Remove 'Apps' filter"
        print_info "3. Search for any Enterprise module (e.g., 'account_accountant')"
        print_info "4. Click 'Install'"
        print_info "5. When prompted for Enterprise activation, click 'Start Trial'"
        print_info "6. Enter your email and follow the instructions"
        ;;
    2)
        print_info "License file activation..."
        read -p "Enter path to license file (.lic): " LICENSE_PATH

        if [ ! -f "$LICENSE_PATH" ]; then
            print_error "License file not found at '$LICENSE_PATH'!"
            exit 1
        fi

        # Copy license to container
        docker cp "$LICENSE_PATH" odoo_companion_standalone:/var/lib/odoo/odoo.lic
        print_success "License copied to container"

        # Restart Odoo to load the license
        print_info "Restarting Odoo to load the license..."
        docker-compose restart odoo_companion_standalone
        print_success "Odoo restarted"

        print_info "Please wait a minute for Odoo to fully start..."
        sleep 30

        print_info "Enterprise should now be activated!"
        print_info "You can verify by:"
        print_info "1. Opening http://localhost:8065/web?#action=base.module_action"
        print_info "2. Looking for 'Enterprise' badge in the top-right corner"
        ;;
    3)
        print_info "Odoo.com account activation..."
        print_info "After this script completes:"
        print_info "1. Open http://localhost:8065/web?#action=base.module_action_ui"
        print_info "2. Click 'Settings' in the top-right menu"
        print_info "3. Click 'Activate the Enterprise Edition'"
        print_info "4. Enter your Odoo.com credentials"
        print_info "5. Enter your contract or subscription number"
        print_info "6. Click 'Activate'"
        ;;
    *)
        print_error "Invalid choice! Please enter 1, 2, or 3."
        exit 1
        ;;
esac

echo ""
print_step "Installing key Enterprise modules..."

# Install key Enterprise modules
MODULES="web_enterprise,account_accountant,sale_subscription,helpdesk,project_enterprise,documents"

docker exec odoo_companion_standalone python3 odoo-bin \
    --config=/etc/odoo/odoo.conf \
    -d "$DB_NAME" \
    -i "$MODULES" \
    --stop-after-init

if [ $? -eq 0 ]; then
    print_success "Key Enterprise modules installed successfully!"
else
    print_info "Some modules might have installation issues. Please check the logs:"
    print_info "docker logs odoo_companion_standalone"
    print_info "You can also install modules manually from the web interface."
fi

echo ""
print_step "Configuring enterprise settings..."

# Set enterprise settings
docker exec odoo_companion_standalone python3 -c "
import odoo
odoo.tools.config['addons_path'] = ['/opt/odoo/addons', '/mnt/extra-addons', '/mnt/enterprise-addons']
import odoo.registry
registry = odoo.registry.Registry('$DB_NAME')
with registry.cursor() as cr:
    env = odoo.api.Environment(cr, 1, {})
    # Enable enterprise features
    if env['ir.config_parameter'].sudo().get_param('database.expiration_reason') != 'enterprise':
        env['ir.config_parameter'].sudo().set_param('database.expiration_reason', 'enterprise')
        env['ir.config_parameter'].sudo().set_param('database.enterprise_code', 'enterprise')
    print('Enterprise configuration applied')
"

print_success "Enterprise settings configured!"

echo ""
echo "===================================================="
echo "🎉 ODOO 18.4 ENTERPRISE ACTIVATION COMPLETE!"
echo "===================================================="
echo ""
print_info "Next steps:"
print_info "1. Open http://localhost:8065/web"
print_info "2. Select your database: $DB_NAME"
print_info "3. Login with admin credentials"
print_info "4. Explore Enterprise features"
echo ""
print_info "Useful commands:"
print_info "- View logs: docker logs -f odoo_companion_standalone"
print_info "- Restart: docker-compose restart odoo_companion_standalone"
print_info "- Access DB shell: docker exec -it postgres_companion_standalone psql -U odoo -d $DB_NAME"
echo ""
print_success "Enterprise activation completed! 🚀"
