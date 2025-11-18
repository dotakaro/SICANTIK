#!/bin/bash

# Script untuk fix odoo_companion database initialization
# Menghindari conflict dengan container yang sedang running

set -e

DB_NAME="sicantik_companion"
DB_USER="odoo"
DB_PASSWORD="odoo_password_secure"
DB_HOST="postgres_companion"
POSTGRES_CONTAINER="postgres_companion"
ODOO_CONTAINER="odoo_companion"

echo "=========================================="
echo "🔧 Fix Odoo Companion Database"
echo "=========================================="

# Step 1: Stop Odoo container
echo ""
echo "🛑 Step 1: Stopping Odoo container..."
docker stop $ODOO_CONTAINER 2>/dev/null || echo "Container already stopped"

# Step 2: Drop and recreate database
echo ""
echo "🗑️  Step 2: Recreating database..."
docker exec -e PGPASSWORD=$DB_PASSWORD $POSTGRES_CONTAINER \
  psql -U $DB_USER -h $DB_HOST -d postgres \
  -c "SELECT pg_terminate_backend(pid) FROM pg_stat_activity WHERE datname = '$DB_NAME' AND pid <> pg_backend_pid();" 2>/dev/null || true

docker exec -e PGPASSWORD=$DB_PASSWORD $POSTGRES_CONTAINER \
  psql -U $DB_USER -h $DB_HOST -d postgres \
  -c "DROP DATABASE IF EXISTS $DB_NAME;" 2>/dev/null || true

docker exec -e PGPASSWORD=$DB_PASSWORD $POSTGRES_CONTAINER \
  psql -U $DB_USER -h $DB_HOST -d postgres \
  -c "CREATE DATABASE $DB_NAME OWNER $DB_USER ENCODING 'UTF8' TEMPLATE template0;"

echo "✅ Database recreated"

# Step 3: Initialize database dengan temporary container
echo ""
echo "⚙️  Step 3: Initializing database..."
docker run --rm \
  --network sicantik_network \
  -v $(pwd)/addons_odoo:/mnt/extra-addons:ro \
  -v $(pwd)/enterprise:/mnt/enterprise-addons:ro \
  -v $(pwd)/config/odoo.conf:/etc/odoo/odoo.conf:ro \
  sicantik-odoo:18.0 \
  odoo -d $DB_NAME \
    --db_host=$DB_HOST \
    --db_port=5432 \
    --db_user=$DB_USER \
    --db_password=$DB_PASSWORD \
    --stop-after-init \
    --without-demo=all \
    -i base,web 2>&1 | tail -10

echo "✅ Database initialized"

# Step 4: Start Odoo container
echo ""
echo "🚀 Step 4: Starting Odoo container..."
docker start $ODOO_CONTAINER

echo ""
echo "⏳ Waiting for Odoo to start..."
sleep 10

# Step 5: Verify
echo ""
echo "🔍 Step 5: Verifying..."
if curl -s -o /dev/null -w "%{http_code}" http://localhost:8060 | grep -q "200\|302\|303"; then
    echo "✅ Odoo is running successfully!"
    echo ""
    echo "📋 Access Odoo at: http://localhost:8060"
else
    echo "⚠️  Odoo may still be starting. Check logs with:"
    echo "   docker logs $ODOO_CONTAINER"
fi

echo ""
echo "=========================================="

