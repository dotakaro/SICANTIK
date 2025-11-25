#!/bin/bash

# Deployment Script untuk Server Produksi
# Script ini akan dijalankan di server produksi untuk pull code dan restart services

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
PROJECT_PATH="${PROJECT_PATH:-/path/to/project}"  # Override dengan environment variable
BACKUP_ENABLED="${BACKUP_ENABLED:-true}"
ROLLBACK_ON_FAILURE="${ROLLBACK_ON_FAILURE:-true}"

# Functions
print_info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_step() {
    echo -e "${BLUE}[STEP]${NC} $1"
}

# Check if running as root (not recommended for Docker)
if [ "$EUID" -eq 0 ]; then
    print_warn "Running as root. Consider using a non-root user."
fi

# Navigate to project directory
if [ ! -d "$PROJECT_PATH" ]; then
    print_error "Project path not found: $PROJECT_PATH"
    print_info "Set PROJECT_PATH environment variable or edit this script"
    exit 1
fi

cd "$PROJECT_PATH" || {
    print_error "Failed to navigate to project directory"
    exit 1
}

print_info "Project directory: $PROJECT_PATH"

# Store current commit for rollback
CURRENT_COMMIT=$(git rev-parse HEAD)
print_info "Current commit: $(git rev-parse --short HEAD)"

# Backup database before deployment
if [ "$BACKUP_ENABLED" = "true" ] && [ -f "scripts/backup.sh" ]; then
    print_step "Creating database backup..."
    if bash scripts/backup.sh; then
        print_info "Backup created successfully"
    else
        print_warn "Backup failed, but continuing deployment..."
    fi
fi

# Pull latest code
print_step "Pulling latest code from GitHub..."
git fetch origin master || {
    print_error "Failed to fetch from origin"
    exit 1
}

# Check if there are any changes
if [ "$(git rev-parse HEAD)" = "$(git rev-parse origin/master)" ]; then
    print_info "Already up to date. No deployment needed."
    exit 0
fi

# Show what will be deployed
print_info "Deploying changes:"
git log --oneline HEAD..origin/master | head -5

# Pull latest changes
print_step "Updating code..."
git reset --hard origin/master || {
    print_error "Failed to reset to origin/master"
    if [ "$ROLLBACK_ON_FAILURE" = "true" ]; then
        print_warn "Rolling back to previous commit..."
        git reset --hard "$CURRENT_COMMIT"
    fi
    exit 1
}

# Show deployed commit
DEPLOYED_COMMIT=$(git rev-parse --short HEAD)
DEPLOYED_MSG=$(git log -1 --pretty=%B)
print_info "Deployed commit: $DEPLOYED_COMMIT"
print_info "Message: $DEPLOYED_MSG"

# Check if docker-compose.yml exists
if [ ! -f "docker-compose.yml" ]; then
    print_error "docker-compose.yml not found"
    if [ "$ROLLBACK_ON_FAILURE" = "true" ]; then
        print_warn "Rolling back..."
        git reset --hard "$CURRENT_COMMIT"
    fi
    exit 1
fi

# Restart Docker Compose services
print_step "Restarting Docker Compose services..."

# Stop services gracefully
print_info "Stopping services..."
if docker-compose down; then
    print_info "Services stopped successfully"
else
    print_warn "Some services may not have stopped cleanly"
fi

# Start services
print_info "Starting services..."
if docker-compose up -d; then
    print_info "Services started successfully"
else
    print_error "Failed to start Docker Compose services"
    if [ "$ROLLBACK_ON_FAILURE" = "true" ]; then
        print_warn "Rolling back to previous commit..."
        git reset --hard "$CURRENT_COMMIT"
        docker-compose up -d || print_error "Failed to start services after rollback"
    fi
    exit 1
fi

# Wait for services to be healthy
print_step "Waiting for services to be healthy..."
sleep 15

# Health check
print_step "Checking service health..."

# Check Odoo service
if docker-compose ps | grep -q "odoo_sicantik.*Up"; then
    print_info "✅ Odoo service is running"
else
    print_error "❌ Odoo service is not running"
    if [ "$ROLLBACK_ON_FAILURE" = "true" ]; then
        print_warn "Rolling back..."
        git reset --hard "$CURRENT_COMMIT"
        docker-compose up -d
    fi
    exit 1
fi

# Check SICANTIK web service
if docker-compose ps | grep -q "sicantik_web.*Up"; then
    print_info "✅ SICANTIK web service is running"
else
    print_warn "⚠️  SICANTIK web service may not be running"
fi

# Check MySQL service
if docker-compose ps | grep -q "sicantik_mysql.*Up"; then
    print_info "✅ MySQL service is running"
else
    print_warn "⚠️  MySQL service may not be running"
fi

# Check MinIO service
if docker-compose ps | grep -q "minio_storage.*Up"; then
    print_info "✅ MinIO service is running"
else
    print_warn "⚠️  MinIO service may not be running"
fi

# Show service status
print_step "Service status:"
docker-compose ps

# Deployment summary
echo ""
print_info "════════════════════════════════════════"
print_info "✅ Deployment completed successfully!"
print_info "════════════════════════════════════════"
print_info "Commit: $DEPLOYED_COMMIT"
print_info "Time: $(date)"
print_info "════════════════════════════════════════"

