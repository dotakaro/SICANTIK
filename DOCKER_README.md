# SICANTIK Docker Development Environment

## Overview

This Docker setup provides a complete development environment for the SICANTIK system, including:

- **SICANTIK Web Application** (PHP/CodeIgniter)
- **Odoo 18 Enterprise Companion App**
- **MySQL 8.0** for SICANTIK database
- **PostgreSQL 15** for Odoo
- **MinIO** for document storage
- **Redis** for caching
- **BSRE Connector** for digital signature integration
- **Nginx** reverse proxy
- **Adminer** for database management
- **MailHog** for email testing

## Quick Start

### Prerequisites

- Docker installed and running
- Docker Compose installed
- At least 4GB RAM available
- At least 10GB free disk space

### Starting the Development Environment

1. **Using the startup script (Recommended):**
   ```bash
   ./start-dev.sh
   ```

2. **Using Docker Compose directly:**
   ```bash
   docker-compose up -d
   ```

### Stopping the Environment

```bash
./start-dev.sh stop
# or
docker-compose down
```

## Service URLs

After starting the environment, you can access the services at:

| Service | URL | Description |
|---------|-----|-------------|
| SICANTIK Web | http://localhost:8070 | Main SICANTIK application |
| Odoo Companion | http://localhost:8060 | Odoo ERP companion app |
| Nginx Proxy | http://localhost:8080 | Reverse proxy entry point |
| Adminer | http://localhost:8090 | Database management tool |
| MinIO Console | http://localhost:9000 | Object storage console |
| MinIO API | http://localhost:9001 | MinIO API endpoint |
| BSRE Connector | http://localhost:8020 | Digital signature service |
| MailHog | http://localhost:8025 | Email testing interface |

## Database Connections

### MySQL (SICANTIK)
- **Host:** localhost
- **Port:** 3307
- **Database:** db_office
- **Username:** sicantik_user
- **Password:** sicantik_password

### PostgreSQL (Odoo)
- **Host:** localhost
- **Port:** 5433
- **Database:** sicantik_companion
- **Username:** odoo
- **Password:** odoo_password_secure

## Directory Structure

```
SICANTIK/
├── docker-compose.yml          # Main development configuration
├── docker-compose.prod.yml     # Production configuration template
├── .env                        # Environment variables (auto-created)
├── env.development             # Development environment template
├── start-dev.sh               # Development startup script
├── Dockerfile.sicantik        # SICANTIK application Dockerfile
├── addons/                    # Odoo custom addons
├── enterprise/                # Odoo enterprise addons
├── config/                    # Configuration files
│   └── odoo.conf             # Odoo configuration
├── nginx/                     # Nginx configuration
│   ├── nginx.conf            # Main nginx config
│   └── conf.d/               # Site configurations
├── services/                  # Additional services
│   └── bsre-connector/       # BSRE integration service
├── uploads/                   # SICANTIK file uploads
├── temp/                      # Temporary files
├── logs/                      # Application logs
├── backups/                   # Database backups
└── certs/                     # SSL certificates
```

## Environment Configuration

### Development Environment

The development environment uses the following environment files:

- `.env` - Main environment variables (auto-created from `env.development`)
- `env.development` - Development configuration template

### Key Environment Variables

```bash
# Database Configuration
SICANTIK_MYSQL_HOST=sicantik_mysql
SICANTIK_MYSQL_DATABASE=db_office
SICANTIK_MYSQL_USER=sicantik_user
SICANTIK_MYSQL_PASSWORD=sicantik_password

POSTGRES_HOST=postgres_companion
POSTGRES_DATABASE=sicantik_companion
POSTGRES_USER=odoo
POSTGRES_PASSWORD=odoo_password_secure

# BSRE Configuration
BSRE_API_URL=https://api-sandbox.bsre.id/v1
BSRE_CLIENT_ID=your_client_id_here
BSRE_CLIENT_SECRET=your_client_secret_here
BSRE_ENVIRONMENT=sandbox

# MinIO Configuration
MINIO_ROOT_USER=minioadmin
MINIO_ROOT_PASSWORD=minioadmin123
MINIO_ENDPOINT=minio_storage:9000

# Redis Configuration
REDIS_HOST=redis_cache
REDIS_PORT=6379

# Application Configuration
APP_ENV=development
APP_DEBUG=true
```

## Useful Commands

### Development Script Commands

```bash
# Start environment
./start-dev.sh

# Stop environment
./start-dev.sh stop

# Restart services
./start-dev.sh restart

# View logs
./start-dev.sh logs              # All logs
./start-dev.sh logs odoo         # Specific service logs

# Check status
./start-dev.sh status

# Clean everything (removes volumes)
./start-dev.sh clean

# Show help
./start-dev.sh help
```

### Docker Compose Commands

```bash
# Build and start
docker-compose up -d --build

# Stop services
docker-compose down

# View logs
docker-compose logs -f [service_name]

# Execute commands in containers
docker-compose exec sicantik_web bash
docker-compose exec odoo_companion bash
docker-compose exec sicantik_mysql mysql -u root -p

# Restart specific service
docker-compose restart [service_name]

# View resource usage
docker-compose ps
docker stats
```

## Development Workflow

### 1. Initial Setup

```bash
# Clone repository
git clone <repository-url>
cd SICANTIK

# Make startup script executable
chmod +x start-dev.sh

# Start environment (first time takes longer)
./start-dev.sh
```

### 2. Development

- **SICANTIK Web:** Code in the main directory, changes are reflected immediately
- **Odoo Addons:** Develop in `addons/` directory, restart Odoo to apply changes
- **BSRE Connector:** Develop in `services/bsre-connector/`

### 3. Database Management

- Use Adminer at http://localhost:8090 for GUI database access
- Or connect directly using MySQL/PostgreSQL clients

### 4. File Storage

- **SICANTIK uploads:** Stored in `uploads/` directory
- **MinIO storage:** Accessible via MinIO console at http://localhost:9000
  - Default credentials: minioadmin/minioadmin123

### 5. Email Testing

- MailHog captures all outgoing emails
- View emails at http://localhost:8025
- SMTP server: localhost:1025

## Troubleshooting

### Common Issues

1. **Port Conflicts**
   - Ensure ports 8070, 8069, 8080, 3307, 5433, 9000, 9001, 8020, 8090, 8025, 1025 are available
   - Check with: `lsof -i :<port>`

2. **Permission Issues**
   - Ensure `uploads/` and `temp/` directories are writable
   - Fix with: `chmod -R 755 uploads temp`

3. **Database Connection Issues**
   - Wait for services to fully start (can take 2-3 minutes)
   - Check logs: `./start-dev.sh logs mysql` or `./start-dev.sh logs postgres_companion`

4. **Out of Memory**
   - Increase Docker memory allocation to at least 4GB
   - Monitor with: `docker stats`

5. **Volume Issues**
   - Clean up: `./start-dev.sh clean`
   - Remove specific volumes: `docker-compose down -v`

### Logs and Debugging

```bash
# View all logs
docker-compose logs

# Follow logs for specific service
docker-compose logs -f [service_name]

# Check container status
docker-compose ps

# Inspect container
docker inspect [container_name]

# Access container shell
docker-compose exec [service_name] bash
```

## Production Deployment

For production deployment:

1. Use `docker-compose.prod.yml` as a template
2. Update environment variables with production values
3. Configure SSL certificates in `letsencrypt/` directory
4. Set up proper backup strategies
5. Configure monitoring and logging

### Production Environment Variables

Create `env.production` with production values:

```bash
# Security
MYSQL_ROOT_PASSWORD=your_secure_password
SICANTIK_MYSQL_PASSWORD=your_secure_password
POSTGRES_PASSWORD=your_secure_password
MINIO_ROOT_PASSWORD=your_secure_password
REDIS_PASSWORD=your_secure_password

# BSRE Production
BSRE_API_URL=https://api.bsre.id/v1
BSRE_CLIENT_ID=your_production_client_id
BSRE_CLIENT_SECRET=your_production_client_secret
BSRE_ENVIRONMENT=production

# Application
APP_ENV=production
APP_DEBUG=false
```

## Support

For issues and questions:

1. Check the troubleshooting section above
2. Review container logs for error messages
3. Verify all required files are present
4. Ensure Docker and Docker Compose are up to date

## Security Notes

- Default passwords are for development only
- Change all passwords before production deployment
- Use SSL certificates in production
- Regularly update base Docker images
- Implement proper backup strategies
- Monitor security advisories for all services