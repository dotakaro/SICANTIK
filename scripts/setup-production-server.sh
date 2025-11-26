#!/bin/bash

# Script Setup Server Production untuk SICANTIK
# Script ini membantu setup awal server production sebelum GitHub Actions bisa deploy

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

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

# Get project path from argument or use default
PROJECT_PATH="${1:-/var/www/sicantik}"

echo ""
print_info "════════════════════════════════════════"
print_info "Setup Server Production - SICANTIK"
print_info "════════════════════════════════════════"
echo ""
print_info "Project path: $PROJECT_PATH"
echo ""

# Check if running as root
if [ "$EUID" -eq 0 ]; then
    print_warn "Running as root. Consider using a non-root user."
fi

# Step 1: Install Docker
print_step "1. Checking Docker installation..."
if ! command -v docker &> /dev/null; then
    print_info "Docker not found. Installing Docker..."
    curl -fsSL https://get.docker.com -o /tmp/get-docker.sh
    sudo sh /tmp/get-docker.sh
    sudo usermod -aG docker $USER
    print_warn "Docker installed. Please logout and login again to use Docker without sudo."
else
    print_info "Docker already installed: $(docker --version)"
fi

# Step 2: Install Docker Compose
print_step "2. Checking Docker Compose installation..."
if ! command -v docker-compose &> /dev/null; then
    print_info "Docker Compose not found. Installing Docker Compose..."
    DOCKER_COMPOSE_VERSION="2.24.0"
    sudo curl -L "https://github.com/docker/compose/releases/download/v${DOCKER_COMPOSE_VERSION}/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
    sudo chmod +x /usr/local/bin/docker-compose
    print_info "Docker Compose installed: $(docker-compose --version)"
else
    print_info "Docker Compose already installed: $(docker-compose --version)"
fi

# Step 3: Install Git
print_step "3. Checking Git installation..."
if ! command -v git &> /dev/null; then
    print_info "Git not found. Installing Git..."
    sudo apt update
    sudo apt install git -y
    print_info "Git installed: $(git --version)"
else
    print_info "Git already installed: $(git --version)"
fi

# Step 4: Clone Repository
print_step "4. Setting up project directory..."
if [ ! -d "$PROJECT_PATH" ]; then
    print_info "Project directory not found. Creating..."
    sudo mkdir -p "$PROJECT_PATH"
    sudo chown $USER:$USER "$PROJECT_PATH"
    
    read -p "Clone repository from GitHub? (y/N): " CLONE_REPO
    if [[ "$CLONE_REPO" =~ ^[Yy]$ ]]; then
        read -p "GitHub repository URL (default: git@github.com:dotakaro/SICANTIK.git): " REPO_URL
        REPO_URL=${REPO_URL:-git@github.com:dotakaro/SICANTIK.git}
        
        print_info "Cloning repository..."
        git clone "$REPO_URL" "$PROJECT_PATH"
        print_info "Repository cloned successfully"
    else
        print_warn "Repository not cloned. Please clone manually later."
    fi
else
    print_info "Project directory already exists"
    if [ -d "$PROJECT_PATH/.git" ]; then
        print_info "Git repository already initialized"
    else
        print_warn "Directory exists but not a git repository"
        read -p "Initialize git repository? (y/N): " INIT_GIT
        if [[ "$INIT_GIT" =~ ^[Yy]$ ]]; then
            cd "$PROJECT_PATH"
            read -p "GitHub repository URL: " REPO_URL
            git init
            git remote add origin "$REPO_URL"
            git fetch origin master
            git reset --hard origin/master
        fi
    fi
fi

# Step 5: Setup Docker Compose
print_step "5. Setting up Docker Compose..."
cd "$PROJECT_PATH" || {
    print_error "Failed to navigate to project directory"
    exit 1
}

if [ ! -f "docker-compose.yml" ]; then
    print_error "docker-compose.yml not found in $PROJECT_PATH"
    print_info "Please ensure repository is cloned correctly"
    exit 1
fi

print_info "Validating docker-compose.yml..."
docker-compose config > /dev/null && print_info "docker-compose.yml is valid" || {
    print_error "docker-compose.yml has errors"
    exit 1
}

# Step 6: Build and Start Services
print_step "6. Building Docker images..."
read -p "Build Docker images now? (y/N): " BUILD_IMAGES
if [[ "$BUILD_IMAGES" =~ ^[Yy]$ ]]; then
    docker-compose build
    print_info "Docker images built successfully"
fi

print_step "7. Starting Docker Compose services..."
read -p "Start Docker Compose services now? (y/N): " START_SERVICES
if [[ "$START_SERVICES" =~ ^[Yy]$ ]]; then
    docker-compose up -d
    print_info "Services started"
    
    print_info "Waiting for services to be healthy..."
    sleep 10
    
    print_info "Service status:"
    docker-compose ps
fi

# Step 7: Setup Summary
echo ""
print_info "════════════════════════════════════════"
print_info "Setup Summary"
print_info "════════════════════════════════════════"
echo ""
print_info "Project path: $PROJECT_PATH"
print_info "Docker: $(docker --version 2>/dev/null || echo 'Not installed')"
print_info "Docker Compose: $(docker-compose --version 2>/dev/null || echo 'Not installed')"
print_info "Git: $(git --version 2>/dev/null || echo 'Not installed')"
echo ""
print_step "Next steps:"
echo ""
echo "1. Update GitHub Secrets dengan PROJECT_PATH: $PROJECT_PATH"
echo "2. Pastikan SSH public key sudah ditambahkan ke server"
echo "3. Test deployment dengan commit kecil"
echo ""
print_info "Setup complete! 🎉"

