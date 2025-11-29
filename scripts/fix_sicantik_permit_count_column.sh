#!/bin/bash
# Script untuk membuat kolom sicantik_permit_count di res_partner
# Jalankan script ini jika pre_init_hook dan _auto_init() tidak bekerja

set -e

echo "🔧 Membuat kolom sicantik_permit_count di res_partner..."

# Cek apakah menggunakan Docker Compose
if command -v docker-compose &> /dev/null; then
    echo "📦 Menggunakan Docker Compose..."
    
    # Cek apakah container postgres ada
    if docker-compose ps | grep -q postgres; then
        DB_CONTAINER=$(docker-compose ps | grep postgres | awk '{print $1}' | head -n 1)
        DB_NAME="${DB_NAME:-sicantik}"
        DB_USER="${DB_USER:-odoo}"
        
        echo "✅ Container PostgreSQL ditemukan: $DB_CONTAINER"
        echo "📊 Database: $DB_NAME"
        echo "👤 User: $DB_USER"
        
        # Buat kolom jika belum ada
        docker exec -i "$DB_CONTAINER" psql -U "$DB_USER" -d "$DB_NAME" <<EOF
-- Buat kolom jika belum ada
DO \$\$
BEGIN
    IF NOT EXISTS (
        SELECT FROM information_schema.columns 
        WHERE table_schema = 'public' 
        AND table_name = 'res_partner' 
        AND column_name = 'sicantik_permit_count'
    ) THEN
        ALTER TABLE "res_partner" ADD COLUMN "sicantik_permit_count" int4;
        RAISE NOTICE '✅ Kolom sicantik_permit_count berhasil dibuat';
    ELSE
        RAISE NOTICE 'ℹ️  Kolom sicantik_permit_count sudah ada';
    END IF;
END \$\$;
EOF
        
        echo "✅ Selesai!"
    else
        echo "❌ Container PostgreSQL tidak ditemukan"
        echo "💡 Pastikan Docker Compose sudah running: docker-compose up -d"
        exit 1
    fi
else
    echo "❌ Docker Compose tidak ditemukan"
    echo "💡 Silakan jalankan SQL secara manual:"
    echo ""
    cat <<'SQL'
-- Jalankan SQL ini di PostgreSQL:
ALTER TABLE "res_partner" ADD COLUMN IF NOT EXISTS "sicantik_permit_count" int4;
SQL
    exit 1
fi

