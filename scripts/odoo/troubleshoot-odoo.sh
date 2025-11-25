# Troubleshooting script for Odoo 18.4 Enterprise
# Author: SICANTIK Development Team
# Date: $(date +%Y-%m-%d)

set -e

echo "===================================================="
echo "🔍 ODOO 18.4 ENTERPRISE TROUBLESHOOTING"
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
    print_error "docker-compose.yml not found! Please run this script from SICANTIK root directory."
    exit 1
fi

print_step "Checking Docker containers..."
CONTAINER_STATUS=$(docker-compose ps)

echo "$CONTAINER_STATUS"

# Check if Odoo container is running
ODOO_CONTAINER=$(echo "$CONTAINER_STATUS" | grep "odoo" | grep "Up" || echo "")
if [ -n "$ODOO_CONTAINER" ]; then
    ODOO_CONTAINER_NAME=$(echo "$ODOO_CONTAINER" | awk '{print $1}')
    print_success "Odoo container is running: $ODOO_CONTAINER_NAME"
else
    print_error "Odoo container is not running!"
    print_info "Starting Odoo container..."
    docker-compose up -d
    sleep 30
fi

print_step "Checking Odoo logs for errors..."
ERROR_LOGS=$(docker logs "$(echo "$CONTAINER_STATUS" | grep "odoo" | awk '{print $1}')" 2>&1 | grep -E "ERROR|CRITICAL|Traceback" | tail -10)

if [ -n "$ERROR_LOGS" ]; then
    print_error "Found errors in Odoo logs:"
    echo "$ERROR_LOGS"
else
    print_success "No critical errors found in recent logs"
fi

print_step "Checking database connection..."
DB_STATUS=$(docker exec postgres pg_isready -U odoo 2>/dev/null || echo "Connection failed")
if [[ "$DB_STATUS" == *"accepting connections"* ]]; then
    print_success "Database connection is working"
else
    print_error "Database connection issue detected!"
    echo "$DB_STATUS"
fi

print_step "Checking mounted directories..."
MOUNTS=$(docker inspect "$(echo "$CONTAINER_STATUS" | grep "odoo" | awk '{print $1}')" --format='{{range .Mounts}}{{.Destination}}:{{.Source}}{{"\n"}}{{end}}' 2>/dev/null)

if [ -n "$MOUNTS" ]; then
    print_success "Mounted directories:"
    echo "$MOUNTS"
else
    print_error "No mount information available"
fi

print_step "Checking addons paths..."
ADDONS_PATHS=$(docker exec "$(echo "$CONTAINER_STATUS" | grep "odoo" | awk '{print $1}')" cat /etc/odoo/odoo.conf | grep addons_path 2>/dev/null || echo "Not found")

if [ -n "$ADDONS_PATHS" ]; then
    print_success "Addons paths configured:"
    echo "$ADDONS_PATHS"
else
    print_error "Addons paths not configured or accessible"
fi

print_step "Checking custom modules availability..."
CUSTOM_MODULES=$(docker exec "$(echo "$CONTAINER_STATUS" | grep "odoo" | awk '{print $1}')" ls -la /mnt/extra-addons/ 2>/dev/null || echo "Not accessible")

if [ -n "$CUSTOM_MODULES" ] && [[ "$CUSTOM_MODULES" != *"Not accessible"* ]]; then
    print_success "Custom modules directory accessible"
    echo "$CUSTOM_MODULES"
else
    print_error "Custom modules directory not accessible"
fi

print_step "Checking enterprise modules availability..."
ENTERPRISE_MODULES_COUNT=$(docker exec "$(echo "$CONTAINER_STATUS" | grep "odoo" | awk '{print $1}')" ls /mnt/enterprise-addons/ 2>/dev/null | wc -l || echo "0")

if [ "$ENTERPRISE_MODULES_COUNT" -gt 0 ]; then
    print_success "Found $ENTERPRISE_MODULES_COUNT Enterprise modules"
else
    print_error "Enterprise modules not accessible or not found"
fi

print_step "Checking port accessibility..."
if curl -s http://localhost:8065 > /dev/null; then
    print_success "Odoo web interface is accessible at http://localhost:8065"
else
    print_error "Odoo web interface is not accessible"
fi

print_step "Checking resource usage..."
RESOURCE_USAGE=$(docker stats --no-stream --format "table {{.Container}}\t{{.CPUPerc}}\t{{.MemUsage}}" | grep -E "odoo|postgres" || echo "Not available")

if [ -n "$RESOURCE_USAGE" ]; then
    print_success "Resource usage:"
    echo "$RESOURCE_USAGE"
else
    print_error "Resource usage not available"
fi

echo ""
echo "===================================================="
echo "🔧 COMMON FIXES"
echo "===================================================="

echo ""
print_info "1. To fix indentation errors in sicantik_tte module:"
echo "   docker exec -it \$(docker-compose ps -q odoo) bash"
echo "   cd /mnt/extra-addons/sicantik_tte"
echo "   python3 -m py_compile wizard/*.py models/*.py"
echo ""

print_info "2. To fix missing .pyc files:"
echo "   docker exec -it \$(docker-compose ps -q odoo) bash"
echo "   find /mnt/extra-addons -name \"*.pyc\" -delete"
echo "   find /mnt/extra-addons -name \"__pycache__\" -exec rm -rf {} +"
echo ""

print_info "3. To restart Odoo:"
echo "   docker-compose restart odoo"
echo ""

print_info "4. To rebuild Odoo image:"
echo "   docker-compose build --no-cache odoo"
echo ""

print_info "5. To view detailed logs:"
echo "   docker logs -f \$(docker-compose ps -q odoo)"
echo ""

print_info "6. To update a specific module:"
echo "   docker exec \$(docker-compose ps -q odoo) python3 odoo-bin -d [database] -u [module_name] --stop-after-init"
echo ""

echo ""
print_info "For module installation issues, check:"
echo "1. __manifest__.py is valid JSON"
echo "2. All required dependencies are installed"
echo "3. No syntax errors in Python files"
echo "4. Proper file permissions"
echo ""

print_success "Troubleshooting completed! 🚀"
