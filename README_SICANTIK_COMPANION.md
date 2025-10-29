# 🚀 SICANTIK Companion App
## Document Management & Digital Signature Platform

### 📋 Overview
SICANTIK Companion App adalah aplikasi pendamping yang menambahkan fungsionalitas digital signature dan document management ke existing SICANTIK tanpa mengubah kode yang ada.

### 🎯 Fitur Utama
- **Document Management**: Import dan manage PDF yang sudah di-generate SICANTIK
- **Digital Signature**: Integrasi TTE BSRE untuk penandatanganan elektronik
- **QR Verification**: Sistem verifikasi dokumen dengan QR code
- **Public Portal**: Portal publik untuk verifikasi keaslian dokumen
- **Workflow Management**: Workflow penandatanganan multi-level

### 🏗️ Arsitektur
```
SICANTIK (Existing) → Generate PDF → File System
                                        ↓
Companion App → Monitor → Import → Sign → QR Code → Public Verification
```

### 🛠️ Tech Stack
- **Platform**: Odoo 18 Enterprise
- **Database**: PostgreSQL (Odoo) + MySQL (SICANTIK read-only)
- **Storage**: MinIO untuk document repository
- **Cache**: Redis
- **Integration**: BSRE API untuk digital signature
- **Deployment**: Docker Compose

### 📦 Quick Start

#### 1. Prerequisites
```bash
# Docker dan Docker Compose
docker --version
docker-compose --version

# Clone atau setup project directory
mkdir sicantik-companion && cd sicantik-companion
```

#### 2. Configuration
```bash
# Copy environment template
cp .env.companion .env

# Edit konfigurasi sesuai environment
nano .env

# Update path ke SICANTIK uploads directory
# Update database connection ke SICANTIK MySQL
# Setup BSRE credentials
```

#### 3. Deploy Environment
```bash
# Start all services
docker-compose -f docker-compose.companion.yml up -d

# Check services status
docker-compose -f docker-compose.companion.yml ps

# View logs
docker-compose -f docker-compose.companion.yml logs -f
```

#### 4. Access Applications
- **Odoo Companion**: http://localhost:8018
- **MinIO Console**: http://localhost:9000
- **Nginx Proxy**: http://localhost:8080
- **BSRE Service**: http://localhost:8020

### 🔧 Development Setup

#### Directory Structure
```
sicantik-companion/
├── addons/                     # Custom Odoo modules
│   ├── sicantik_connector/     # Database integration
│   ├── sicantik_tte/          # Digital signature
│   └── sicantik_verification/ # QR verification
├── config/                    # Odoo configuration
├── nginx/                     # Nginx configuration
├── bsre-service/             # BSRE connector service
├── certs/                    # SSL certificates
├── docker-compose.companion.yml
├── .env.companion
└── README_SICANTIK_COMPANION.md
```

#### Custom Modules Development
```bash
# Create new module
mkdir -p addons/sicantik_custom
cd addons/sicantik_custom

# Module structure
mkdir models views data security static
touch __init__.py __manifest__.py
```

### 🔐 Security Configuration

#### Database Security
```sql
-- Create read-only user untuk SICANTIK database
CREATE USER 'readonly_user'@'%' IDENTIFIED BY 'secure_password';
GRANT SELECT ON db_office.* TO 'readonly_user'@'%';
FLUSH PRIVILEGES;
```

#### BSRE Certificate Setup
```bash
# Place BSRE certificates
mkdir -p certs
cp your_bsre_certificate.p12 certs/bsre.p12
chmod 600 certs/bsre.p12
```

### 📊 Monitoring & Maintenance

#### Health Checks
```bash
# Check all services
docker-compose -f docker-compose.companion.yml ps

# Check specific service logs
docker-compose -f docker-compose.companion.yml logs odoo_companion

# Database connection test
docker-compose -f docker-compose.companion.yml exec mysql_client mysql -h SICANTIK_HOST -u readonly_user -p
```

#### Backup Strategy
```bash
# Backup Odoo database
docker-compose -f docker-compose.companion.yml exec postgres_companion pg_dump -U odoo sicantik_companion > backup_$(date +%Y%m%d).sql

# Backup MinIO documents
docker-compose -f docker-compose.companion.yml exec minio_documents mc mirror /data /backup/minio_$(date +%Y%m%d)
```

### 🔄 Integration Workflow

#### 1. Document Detection
- Monitor SICANTIK `tmpermohonan` table untuk `c_izin_selesai = 1`
- Detect PDF files di SICANTIK upload directory
- Import metadata ke Odoo document management

#### 2. Signing Process
- Create signing queue untuk dokumen baru
- Multi-level approval workflow
- BSRE API integration untuk digital signing
- Store signed document dengan metadata

#### 3. Verification System
- Generate QR code untuk setiap signed document
- Public verification portal tanpa login required
- Real-time verification dengan BSRE validation

### 🚨 Troubleshooting

#### Common Issues
```bash
# Database connection issues
docker-compose -f docker-compose.companion.yml logs postgres_companion

# BSRE API connection issues
docker-compose -f docker-compose.companion.yml logs bsre_connector

# File permission issues
sudo chown -R 101:101 volumes/odoo_data
```

#### Performance Optimization
```bash
# Increase Odoo workers
echo "workers = 4" >> config/odoo.conf

# Optimize PostgreSQL
echo "shared_buffers = 256MB" >> volumes/postgres_data/postgresql.conf
```

### 📞 Support & Documentation

#### API Documentation
- **Odoo API**: http://localhost:8018/web/api/v1/docs
- **BSRE Connector**: http://localhost:8020/docs
- **Public Verification**: http://localhost:8080/verify/docs

#### Logs Location
- **Odoo**: `docker-compose logs odoo_companion`
- **BSRE**: `volumes/bsre_logs/`
- **Nginx**: `docker-compose logs nginx_proxy`

### 🏷️ Tags
`#sicantik` `#odoo` `#digital-signature` `#bsre` `#document-management` `#docker`

---
**Version**: 1.0.0  
**Last Updated**: Januari 2025  
**Status**: Development Ready
