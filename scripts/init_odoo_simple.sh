#!/bin/bash

# Script sederhana untuk initialize database Odoo 18.4
# Hanya install modules yang diperlukan untuk SICANTIK

set -e

DB_NAME="sicantik_companion_standalone"
DB_USER="odoo"
DB_PASSWORD="odoo_password_secure"
DB_HOST="postgres_companion_standalone"
DB_PORT="5432"
ODOO_CONTAINER="odoo_companion_standalone"
POSTGRES_CONTAINER="postgres_companion_standalone"
ADMIN_PASSWORD="admin_odoo_secure_2025"

echo "=========================================="
echo "🚀 Initialize Odoo 18.4 Database (Simple)"
echo "=========================================="

# Step 1: Drop & Create Database
echo ""
echo "📝 Step 1: Creating database..."
docker exec -e PGPASSWORD=$DB_PASSWORD $POSTGRES_CONTAINER psql -U $DB_USER -h $DB_HOST -d postgres -c "SELECT pg_terminate_backend(pid) FROM pg_stat_activity WHERE datname = '$DB_NAME' AND pid <> pg_backend_pid();" 2>/dev/null || true
docker exec -e PGPASSWORD=$DB_PASSWORD $POSTGRES_CONTAINER psql -U $DB_USER -h $DB_HOST -d postgres -c "DROP DATABASE IF EXISTS $DB_NAME;" 2>/dev/null || true
docker exec -e PGPASSWORD=$DB_PASSWORD $POSTGRES_CONTAINER psql -U $DB_USER -h $DB_HOST -d postgres -c "CREATE DATABASE $DB_NAME OWNER $DB_USER ENCODING 'UTF8' TEMPLATE template0;"
echo "✅ Database created"

# Step 2: Initialize dengan base & web saja
echo ""
echo "⚙️  Step 2: Initializing database..."
docker exec $ODOO_CONTAINER odoo -d $DB_NAME \
    --db_host=$DB_HOST \
    --db_port=$DB_PORT \
    --db_user=$DB_USER \
    --db_password=$DB_PASSWORD \
    --stop-after-init \
    --without-demo=all \
    -i base,web

echo "✅ Database initialized"

# Step 3: Install modules melalui Odoo Apps (manual)
echo ""
echo "=========================================="
echo "✅ Database initialization complete!"
echo "=========================================="
echo ""
echo "📋 Database Information:"
echo "   Database Name: $DB_NAME"
echo "   Odoo URL: http://localhost:8065"
echo ""
echo "🚀 Next Steps:"
echo "   1. Access Odoo at http://localhost:8065"
echo "   2. Login dengan admin (password akan di-set saat pertama kali login)"
echo "   3. Install modules melalui Apps menu:"
echo "      - mail, contacts, portal, website (base modules)"
echo "      - whatsapp (enterprise module)"
echo "      - sicantik_connector, sicantik_tte, sicantik_whatsapp (custom modules)"
echo ""
echo "⚠️  Note: Beberapa enterprise modules mungkin tidak kompatibel dengan Odoo 18.4"
echo "   Install hanya modules yang diperlukan untuk SICANTIK"
echo ""
echo "=========================================="

