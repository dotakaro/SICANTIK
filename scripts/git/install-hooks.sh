#!/bin/bash

# Install Git Hooks untuk Auto-Push

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/../.." && pwd)"

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

print_info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

cd "$PROJECT_ROOT"

# Check if .git directory exists
if [ ! -d ".git" ]; then
    print_warn "Directory ini bukan git repository"
    exit 1
fi

# Create hooks directory if it doesn't exist
mkdir -p .git/hooks

# Copy post-commit hook
if [ -f ".git/hooks/post-commit" ]; then
    print_warn "post-commit hook sudah ada"
    read -p "Ganti dengan hook baru? (y/N): " REPLACE
    if [[ ! "$REPLACE" =~ ^[Yy]$ ]]; then
        print_info "Hook tidak diganti"
        exit 0
    fi
fi

# Install post-commit hook
cp "$SCRIPT_DIR/../.git/hooks/post-commit" .git/hooks/post-commit 2>/dev/null || \
    cat > .git/hooks/post-commit << 'HOOK_EOF'
#!/bin/bash

# Git Post-Commit Hook
# Auto-push ke GitHub setelah commit ke branch master

set -e

# Get current branch
CURRENT_BRANCH=$(git branch --show-current)

# Only auto-push if on master branch
if [ "$CURRENT_BRANCH" != "master" ]; then
    exit 0
fi

# Check if remote origin exists
if ! git remote get-url origin &> /dev/null; then
    exit 0
fi

# Get remote URL
REMOTE_URL=$(git remote get-url origin)

# Only push if remote is GitHub
if [[ ! "$REMOTE_URL" =~ github\.com ]]; then
    exit 0
fi

# Check if we're in a git worktree or submodule
if [ -n "$GIT_DIR" ] || [ -n "$GIT_WORK_TREE" ]; then
    exit 0
fi

# Prevent infinite loop (don't push if this commit was triggered by a push)
if [ -n "$GIT_AUTO_PUSH" ]; then
    exit 0
fi

# Export flag to prevent infinite loop
export GIT_AUTO_PUSH=1

# Log the push attempt
echo ""
echo "🚀 Auto-pushing to GitHub (branch: $CURRENT_BRANCH)..."

# Push to GitHub (non-blocking, don't wait for result)
git push origin "$CURRENT_BRANCH" > /dev/null 2>&1 &

# Exit successfully
exit 0
HOOK_EOF

chmod +x .git/hooks/post-commit

print_info "Git hooks berhasil diinstall!"
print_info "Hook akan auto-push ke GitHub setiap commit di branch master"

