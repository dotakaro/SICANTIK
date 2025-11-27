#!/bin/bash
# Script untuk memperbaiki error 'ContainerConfig' di Docker Compose

set -e

echo "🔧 Memperbaiki error Docker Compose 'ContainerConfig'..."

# 1. Stop dan remove container yang bermasalah
echo "📦 Menghentikan dan menghapus container odoo_sicantik..."
docker-compose stop odoo_sicantik 2>/dev/null || true
docker-compose rm -f odoo_sicantik 2>/dev/null || true

# 2. Remove image yang bermasalah (optional, jika perlu rebuild)
echo "🗑️  Menghapus image lama (optional)..."
read -p "Hapus image sicantik-odoo:18.4? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    docker rmi sicantik-odoo:18.4 2>/dev/null || true
    echo "✅ Image dihapus"
fi

# 3. Rebuild image dari scratch
echo "🔨 Rebuild image dari scratch..."
docker-compose build --no-cache odoo_sicantik

# 4. Start container dengan --remove-orphans
echo "🚀 Menjalankan container dengan --remove-orphans..."
docker-compose up -d --remove-orphans odoo_sicantik

echo "✅ Selesai! Cek status dengan: docker-compose ps"

