#!/bin/bash

# SICANTIK Development Environment Startup Script
# This script starts all services for development environment

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if Docker is running
check_docker() {
    if ! docker info > /dev/null 2>&1; then
        print_error "Docker is not running. Please start Docker first."
        exit 1
    fi
    print_success "Docker is running"
}

# Check if docker-compose is available
check_docker_compose() {
    if ! command -v docker-compose &> /dev/null && ! docker compose version &> /dev/null; then
        print_error "docker-compose is not installed or not in PATH"
        exit 1
    fi

    # Use docker compose if available, otherwise docker-compose
    if docker compose version &> /dev/null; then
        COMPOSE_CMD="docker compose"
    else
        COMPOSE_CMD="docker-compose"
    fi
    print_success "Using: $COMPOSE_CMD"
}

# Create necessary directories
create_directories() {
    print_status "Creating necessary directories..."

    directories=(
        "uploads"
        "temp"
        "logs"
        "logs/nginx"
        "logs/odoo"
        "logs/bsre"
        "backups"
        "certs"
    )

    for dir in "${directories[@]}"; do
        if [ ! -d "$dir" ]; then
            mkdir -p "$dir"
            print_status "Created directory: $dir"
        fi
    done

    print_success "All necessary directories created or already exist"
}

# Check environment files
check_env_files() {
    print_status "Checking environment files..."

    if [ ! -f ".env" ]; then
        print_warning ".env file not found. Using default values from env.development"
        if [ -f "env.development" ]; then
            cp env.development .env
            print_success "Copied env.development to .env"
        else
            print_error "env.development file not found!"
            exit 1
        fi
    fi

    if [ ! -f "env.development" ]; then
        print_error "env.development file not found!"
        exit 1
    fi

    print_success "Environment files check completed"
}

# Check required files
check_required_files() {
    print_status "Checking required files..."

    required_files=(
        "Dockerfile.sicantik"
        "docker-compose.yml"
        "nginx/nginx.conf"
        "services/bsre-connector/Dockerfile"
        "config/odoo.conf"
    )

    for file in "${required_files[@]}"; do
        if [ ! -f "$file" ]; then
            print_error "Required file not found: $file"
            exit 1
        fi
    done

    print_success "All required files found"
}

# Build and start services
start_services() {
    print_status "Building and starting Docker services..."

    # Stop any existing services
    $COMPOSE_CMD down

    # Build images
    print_status "Building Docker images..."
    $COMPOSE_CMD build --no-cache

    # Start services
    print_status "Starting all services..."
    $COMPOSE_CMD up -d

    print_success "All services started successfully"
}

# Wait for services to be ready
wait_for_services() {
    print_status "Waiting for services to be ready..."

    # Wait for MySQL
    print_status "Waiting for MySQL to be ready..."
    while ! $COMPOSE_CMD exec -T sicantik_mysql mysqladmin ping -h"localhost" --silent; do
        echo -n "."
        sleep 2
    done
    echo ""
    print_success "MySQL is ready"

    # Wait for PostgreSQL
    print_status "Waiting for PostgreSQL to be ready..."
    while ! $COMPOSE_CMD exec -T postgres_companion pg_isready -U odoo; do
        echo -n "."
        sleep 2
    done
    echo ""
    print_success "PostgreSQL is ready"

    # Wait for Redis
    print_status "Waiting for Redis to be ready..."
    while ! $COMPOSE_CMD exec -T redis_cache redis-cli ping; do
        echo -n "."
        sleep 2
    done
    echo ""
    print_success "Redis is ready"
}

# Show service status and URLs
show_status() {
    print_status "Service Status:"
    echo ""

    echo "🌐 Web Applications:"
    echo "  • SICANTIK Web:     http://localhost:8070"
    echo "  • Odoo Companion:   http://localhost:8069"
    echo "  • Nginx Proxy:      http://localhost:8080"
    echo ""

    echo "🗄️  Database Management:"
    echo "  • Adminer:          http://localhost:8090"
    echo "  • MySQL:            localhost:3307"
    echo "  • PostgreSQL:       localhost:5433"
    echo ""

    echo "📁 Storage & Services:"
    echo "  • MinIO Console:    http://localhost:9000"
    echo "  • MinIO API:        http://localhost:9001"
    echo "  • BSRE Connector:   http://localhost:8020"
    echo ""

    echo "📧 Email Testing:"
    echo "  • MailHog Web UI:   http://localhost:8025"
    echo "  • MailHog SMTP:     localhost:1025"
    echo ""

    echo "🔧 Useful Commands:"
    echo "  • View logs:        $COMPOSE_CMD logs -f [service_name]"
    echo "  • Stop all:         $COMPOSE_CMD down"
    echo "  • Restart:          $COMPOSE_CMD restart [service_name]"
    echo "  • Execute shell:     $COMPOSE_CMD exec [service_name] bash"
    echo ""

    print_success "Development environment is ready! 🚀"
}

# Main execution
main() {
    echo "🚀 Starting SICANTIK Development Environment..."
    echo "=============================================="
    echo ""

    check_docker
    check_docker_compose
    create_directories
    check_env_files
    check_required_files
    start_services
    wait_for_services
    show_status
}

# Handle script arguments
case "${1:-}" in
    "stop")
        print_status "Stopping all services..."
        $COMPOSE_CMD down
        print_success "All services stopped"
        ;;
    "restart")
        print_status "Restarting all services..."
        $COMPOSE_CMD restart
        print_success "All services restarted"
        ;;
    "logs")
        $COMPOSE_CMD logs -f "${2:-}"
        ;;
    "status")
        $COMPOSE_CMD ps
        ;;
    "clean")
        print_warning "This will remove all containers, networks, and volumes!"
        read -p "Are you sure? (y/N): " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            print_status "Cleaning up Docker resources..."
            $COMPOSE_CMD down -v --remove-orphans
            docker system prune -f
            print_success "Cleanup completed"
        fi
        ;;
    "help"|"-h"|"--help")
        echo "SICANTIK Development Environment Script"
        echo ""
        echo "Usage: $0 [COMMAND]"
        echo ""
        echo "Commands:"
        echo "  (no args)  Start the development environment"
        echo "  stop       Stop all services"
        echo "  restart    Restart all services"
        echo "  logs       Show logs (optional: specify service name)"
        echo "  status     Show service status"
        echo "  clean      Remove all containers, networks, and volumes"
        echo "  help       Show this help message"
        echo ""
        ;;
    "")
        main
        ;;
    *)
        print_error "Unknown command: $1"
        echo "Use '$0 help' for available commands"
        exit 1
        ;;
esac
