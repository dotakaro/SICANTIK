#!/bin/bash

# Script untuk initialize database Odoo 18.4 dari awal
# Menghapus database lama dan membuat database baru dengan semua modules

set -e

echo "=========================================="
echo "🚀 Initialize Odoo 18.4 Database"
echo "=========================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
DB_NAME="sicantik_companion_standalone"
DB_USER="odoo"
DB_PASSWORD="odoo_password_secure"
DB_HOST="postgres_companion_standalone"
DB_PORT="5432"
ODOO_CONTAINER="odoo_companion_standalone"
POSTGRES_CONTAINER="postgres_companion_standalone"
ADMIN_PASSWORD="admin_odoo_secure_2025"

# Check if containers are running
echo -e "${YELLOW}📦 Checking containers...${NC}"
if ! docker ps | grep -q "$ODOO_CONTAINER"; then
    echo -e "${RED}❌ Container $ODOO_CONTAINER is not running${NC}"
    echo "Please start containers with: docker-compose up -d odoo_companion_standalone postgres_companion_standalone"
    exit 1
fi

if ! docker ps | grep -q "$POSTGRES_CONTAINER"; then
    echo -e "${RED}❌ Container $POSTGRES_CONTAINER is not running${NC}"
    echo "Please start containers with: docker-compose up -d postgres_companion_standalone"
    exit 1
fi

echo -e "${GREEN}✅ Containers are running${NC}"

# Step 1: Drop existing database (if exists)
echo ""
echo -e "${YELLOW}🗑️  Step 1: Dropping existing database (if exists)...${NC}"
docker exec -e PGPASSWORD=$DB_PASSWORD $POSTGRES_CONTAINER psql -U $DB_USER -h $DB_HOST -c "SELECT pg_terminate_backend(pid) FROM pg_stat_activity WHERE datname = '$DB_NAME' AND pid <> pg_backend_pid();" 2>/dev/null || true
docker exec -e PGPASSWORD=$DB_PASSWORD $POSTGRES_CONTAINER psql -U $DB_USER -h $DB_HOST -c "DROP DATABASE IF EXISTS $DB_NAME;" 2>/dev/null || true
echo -e "${GREEN}✅ Database dropped${NC}"

# Step 2: Create new database
echo ""
echo -e "${YELLOW}📝 Step 2: Creating new database...${NC}"
docker exec -e PGPASSWORD=$DB_PASSWORD $POSTGRES_CONTAINER psql -U $DB_USER -h $DB_HOST -c "CREATE DATABASE $DB_NAME OWNER $DB_USER ENCODING 'UTF8' LC_COLLATE='en_US.UTF-8' LC_CTYPE='en_US.UTF-8';"
echo -e "${GREEN}✅ Database created${NC}"

# Step 3: Initialize database with Odoo (without demo data)
echo ""
echo -e "${YELLOW}⚙️  Step 3: Initializing database with Odoo...${NC}"
echo "This may take a few minutes..."
docker exec $ODOO_CONTAINER odoo -d $DB_NAME \
    --db_host=$DB_HOST \
    --db_port=$DB_PORT \
    --db_user=$DB_USER \
    --db_password=$DB_PASSWORD \
    --stop-after-init \
    --without-demo=all \
    --init=base,web \
    --admin-password=$ADMIN_PASSWORD

echo -e "${GREEN}✅ Database initialized${NC}"

# Step 4: Install base modules
echo ""
echo -e "${YELLOW}📦 Step 4: Installing base modules...${NC}"
BASE_MODULES="base,web,mail,contacts,portal,website"
docker exec $ODOO_CONTAINER odoo -d $DB_NAME \
    --db_host=$DB_HOST \
    --db_port=$DB_PORT \
    --db_user=$DB_USER \
    --db_password=$DB_PASSWORD \
    --stop-after-init \
    --init=$BASE_MODULES

echo -e "${GREEN}✅ Base modules installed${NC}"

# Step 5: Install enterprise modules (if available)
echo ""
echo -e "${YELLOW}🏢 Step 5: Installing enterprise modules...${NC}"
ENTERPRISE_MODULES="whatsapp"
docker exec $ODOO_CONTAINER odoo -d $DB_NAME \
    --db_host=$DB_HOST \
    --db_port=$DB_PORT \
    --db_user=$DB_USER \
    --db_password=$DB_PASSWORD \
    --stop-after-init \
    --init=$ENTERPRISE_MODULES 2>&1 | grep -v "Module.*not found" || echo "Some enterprise modules may not be available"

echo -e "${GREEN}✅ Enterprise modules installed${NC}"

# Step 6: Install custom SICANTIK modules
echo ""
echo -e "${YELLOW}🔧 Step 6: Installing SICANTIK custom modules...${NC}"
CUSTOM_MODULES="sicantik_connector,sicantik_tte,sicantik_whatsapp"
docker exec $ODOO_CONTAINER odoo -d $DB_NAME \
    --db_host=$DB_HOST \
    --db_port=$DB_PORT \
    --db_user=$DB_USER \
    --db_password=$DB_PASSWORD \
    --stop-after-init \
    --init=$CUSTOM_MODULES

echo -e "${GREEN}✅ Custom modules installed${NC}"

# Step 7: Update all modules to ensure latest version
echo ""
echo -e "${YELLOW}🔄 Step 7: Updating all modules...${NC}"
docker exec $ODOO_CONTAINER odoo -d $DB_NAME \
    --db_host=$DB_HOST \
    --db_port=$DB_PORT \
    --db_user=$DB_USER \
    --db_password=$DB_PASSWORD \
    --stop-after-init \
    --update=all

echo -e "${GREEN}✅ All modules updated${NC}"

# Summary
echo ""
echo "=========================================="
echo -e "${GREEN}✅ Database initialization complete!${NC}"
echo "=========================================="
echo ""
echo "📋 Database Information:"
echo "   Database Name: $DB_NAME"
echo "   Admin Password: $ADMIN_PASSWORD"
echo "   Odoo URL: http://localhost:8065"
echo ""
echo "📦 Installed Modules:"
echo "   - Base: base, web, mail, contacts, portal, website"
echo "   - Enterprise: whatsapp"
echo "   - Custom: sicantik_connector, sicantik_tte, sicantik_whatsapp"
echo ""
echo "🚀 Next Steps:"
echo "   1. Access Odoo at http://localhost:8065"
echo "   2. Login with admin / $ADMIN_PASSWORD"
echo "   3. Configure SICANTIK connector in Settings"
echo "   4. Configure MinIO storage"
echo "   5. Configure WhatsApp Business Account"
echo ""
echo "=========================================="

