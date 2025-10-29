#!/bin/bash

# SICANTIK Odoo Companion - Quick Start Script
# This script starts only the Odoo companion app with PostgreSQL

echo "🚀 Starting SICANTIK Odoo Companion..."
echo ""

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Error: Docker is not running"
    echo "Please start Docker Desktop first"
    exit 1
fi

# Stop existing containers (if any)
echo "🛑 Stopping existing containers..."
docker-compose stop odoo_companion_standalone postgres_companion_standalone 2>/dev/null

# Start services
echo "🔄 Starting Odoo Companion services..."
docker-compose up -d postgres_companion_standalone
echo "⏳ Waiting for PostgreSQL to be ready (10 seconds)..."
sleep 10

docker-compose up -d odoo_companion_standalone

echo ""
echo "✅ SICANTIK Odoo Companion started successfully!"
echo ""
echo "📋 Service Information:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🌐 Odoo Web Interface:  http://localhost:8065"
echo "🗄️  PostgreSQL:          localhost:5435"
echo "📁 Custom Addons:        ./addons_odoo (mapped to /mnt/extra-addons)"
echo "🏢 Enterprise Addons:    ./enterprise (mapped to /mnt/enterprise-addons)"
echo "⚙️  Configuration:        ./config_odoo/odoo.conf"
echo ""
echo "🔐 Default Credentials:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Username: admin"
echo "Password: admin_odoo_secure_2025"
echo "Database: sicantik_companion_standalone"
echo ""
echo "📦 Installing SICANTIK Connector Module:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "1. Open http://localhost:8065"
echo "2. Login with credentials above"
echo "3. Go to Apps menu"
echo "4. Remove 'Apps' filter"
echo "5. Click 'Update Apps List'"
echo "6. Search for 'SICANTIK'"
echo "7. Click 'Install' on SICANTIK Connector"
echo ""
echo "📊 View Logs:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "docker-compose logs -f odoo_companion_standalone"
echo ""
echo "🛑 Stop Services:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "docker-compose stop odoo_companion_standalone postgres_companion_standalone"
echo ""
echo "🔄 Restart Services:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "docker-compose restart odoo_companion_standalone"
echo ""
echo "✨ Happy coding! ✨"

