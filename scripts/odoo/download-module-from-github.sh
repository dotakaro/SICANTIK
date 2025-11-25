#!/bin/bash
# Download and setup Odoo module from GitHub
# Author: SICANTIK Development Team
# Date: $(date +%Y-%m-%d)

set -e

echo "===================================================="
echo "📥 DOWNLOAD MODULE FROM GITHUB"
echo "===================================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[1;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

print_info() {
    echo -e "${YELLOW}ℹ️  $1${NC}"
}

print_step() {
    echo -e "${BLUE}🔧 $1${NC}"
}

# Check if running in the correct directory
if [ ! -f "docker-compose.yml" ]; then
    print_error "docker-compose.yml not found! Please run this script from SICANTIK root directory."
    exit 1
fi

# Default values
MODULE_NAME="sicantik_tte"
GITHUB_REPO="https://github.com/dotakaro/sicantik_odoo_modules.git"
MODULE_DIR="addons_odoo"

# Parse command line arguments
while [[ $# -gt 0 ]]; do
    case $1 in
        -n|--name)
            MODULE_NAME="$2"
            shift 2
            ;;
        -r|--repo)
            GITHUB_REPO="$2"
            shift 2
            ;;
        -d|--dir)
            MODULE_DIR="$2"
            shift 2
            ;;
        -b|--branch)
            BRANCH="$2"
            shift 2
            ;;
        -h|--help)
            echo "Usage: $0 [OPTIONS]"
            echo ""
            echo "Options:"
            echo "  -n, --name NAME    Module name (default: sicantik_tte)"
            echo "  -r, --repo URL     GitHub repository URL"
            echo "  -d, --dir DIR      Target directory (default: addons_odoo)"
            echo "  -b, --branch BRANCH  Git branch (default: main)"
            echo "  -h, --help          Show this help message"
            echo ""
            echo "Examples:"
            echo "  $0 -n sicantik_tte -r https://github.com/user/repo.git"
            echo "  $0 -n sicantik_tte -r https://github.com/user/repo.git -b develop"
            exit 0
            ;;
        *)
            print_error "Unknown option: $1"
            echo "Use -h or --help for usage information."
            exit 1
            ;;
    esac
done

# Set default branch if not specified
if [ -z "$BRANCH" ]; then
    BRANCH="main"
fi

print_step "Downloading module: $MODULE_NAME"
print_step "From repository: $GITHUB_REPO"
print_step "To directory: $MODULE_DIR"
print_step "Using branch: $BRANCH"
echo ""

# Create target directory if it doesn't exist
if [ ! -d "$MODULE_DIR" ]; then
    print_step "Creating directory: $MODULE_DIR"
    mkdir -p "$MODULE_DIR"
fi

# Change to target directory
cd "$MODULE_DIR"

# Backup existing module if it exists
if [ -d "$MODULE_NAME" ]; then
    print_info "Backing up existing module to ${MODULE_NAME}_backup_$(date +%Y%m%d_%H%M%S)"
    mv "$MODULE_NAME" "${MODULE_NAME}_backup_$(date +%Y%m%d_%H%M%S)"
fi

# Clone the module from GitHub
print_step "Cloning module from GitHub..."
git clone -b "$BRANCH" "$GITHUB_REPO" "$MODULE_NAME"

# Check if clone was successful
if [ $? -ne 0 ]; then
    print_error "Failed to clone module from GitHub!"
    exit 1
fi

# Change to module directory
cd "$MODULE_NAME"

# Check if we're in a git repository
if [ ! -d ".git" ]; then
    print_error "Module was not cloned correctly!"
    exit 1
fi

print_success "Module cloned successfully!"

# Display module information
echo ""
print_step "Module Information:"
echo "Name: $MODULE_NAME"
echo "Repository: $GITHUB_REPO"
echo "Branch: $BRANCH"
echo "Directory: $(pwd)"
echo ""

# Check for __manifest__.py
if [ -f "__manifest__.py" ]; then
    print_success "__manifest__.py found!"

    # Display module info from manifest
    print_step "Module Details:"
    python3 -c "
import json
try:
    with open('__manifest__.py', 'r') as f:
        content = f.read()
        # Parse JSON content (strip comments if any)
        content = content.split('# -*- coding: utf-8 -*-')[1].strip()
        if content.startswith('{') and content.endswith('}'):
            manifest = json.loads(content)
            print(f\"Name: {manifest.get('name', 'Unknown')}\")
            print(f\"Version: {manifest.get('version', 'Unknown')}\")
            print(f\"Summary: {manifest.get('summary', 'No summary')}\")
            print(f\"Author: {manifest.get('author', 'Unknown')}\")
            print(f\"Depends: {', '.join(manifest.get('depends', []))}\")
        else:
            print('Invalid JSON format')
except Exception as e:
    print(f'Error parsing manifest: {e}')
"
    echo ""
else
    print_error "__manifest__.py not found!"
fi

# Check for models directory
if [ -d "models" ]; then
    print_success "Models directory found!"
    MODEL_COUNT=$(find models -name "*.py" | grep -v __pycache__ | grep -v __init__ | wc -l)
    echo "Found $MODEL_COUNT model files"
    echo ""
else
    print_info "No models directory found"
fi

# Check for views directory
if [ -d "views" ]; then
    print_success "Views directory found!"
    VIEW_COUNT=$(find views -name "*.xml" | wc -l)
    echo "Found $VIEW_COUNT view files"
    echo ""
else
    print_info "No views directory found"
fi

# Check for wizards directory
if [ -d "wizard" ]; then
    print_success "Wizard directory found!"
    WIZARD_COUNT=$(find wizard -name "*.py" | grep -v __pycache__ | grep -v __init__ | wc -l)
    echo "Found $WIZARD_COUNT wizard files"
    echo ""
else
    print_info "No wizard directory found"
fi

# Go back to the project root
cd ..

# Clear Python cache
print_step "Clearing Python cache..."
find "$MODULE_DIR/$MODULE_NAME" -name "*.pyc" -delete 2>/dev/null || true
find "$MODULE_DIR/$MODULE_NAME" -name "__pycache__" -exec rm -rf {} + 2>/dev/null || true

print_success "Python cache cleared!"

# Restart Odoo to load the new module
print_step "Restarting Odoo to load the new module..."
docker-compose restart odoo_sicantik

print_success "Odoo restarted!"

# Display next steps
echo ""
echo "===================================================="
echo "🎉 MODULE DOWNLOAD COMPLETE!"
echo "===================================================="
echo ""
print_info "Next steps:"
print_info "1. Access Odoo at http://localhost:8065"
print_info "2. Login to your database"
print_info "3. Go to Apps"
print_info "4. Update Apps List"
print_info "5. Search for '$MODULE_NAME'"
print_info "6. Click Install"
echo ""
print_info "Useful commands:"
print_info "- View logs: docker-compose logs -f odoo_sicantik"
print_info "- Restart Odoo: docker-compose restart odoo_sicantik"
print_info "- Access container: docker exec -it odoo_sicantik bash"
echo ""
print_success "Module '$MODULE_NAME' is ready for installation! 🚀"
