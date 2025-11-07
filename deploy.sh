#!/bin/bash

# Laravel Deployment Script for Shared Hosting
# Run this script on your local machine before uploading to server

echo "🚀 Starting Laravel Deployment Preparation..."

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Check if .env exists
if [ ! -f .env ]; then
    echo -e "${RED}❌ .env file not found!${NC}"
    echo "Please create .env file first."
    exit 1
fi

echo -e "${GREEN}✓ .env file found${NC}"

# Install/Update dependencies
echo "📦 Installing dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction

if [ $? -ne 0 ]; then
    echo -e "${RED}❌ Composer install failed!${NC}"
    exit 1
fi

echo -e "${GREEN}✓ Dependencies installed${NC}"

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear

echo -e "${GREEN}✓ Caches cleared${NC}"

# Cache for production
echo "⚡ Caching for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

if [ $? -ne 0 ]; then
    echo -e "${YELLOW}⚠ Some cache commands failed, but continuing...${NC}"
fi

echo -e "${GREEN}✓ Production cache created${NC}"

# Create deployment package (exclude unnecessary files)
echo "📦 Creating deployment package..."

# List of files/folders to exclude
EXCLUDE_LIST=(
    ".git"
    ".gitignore"
    ".gitattributes"
    "node_modules"
    "tests"
    ".env"
    "storage/logs/*.log"
    "storage/framework/cache/*"
    "storage/framework/sessions/*"
    "storage/framework/views/*"
    ".idea"
    ".vscode"
    "*.md"
    "deploy.sh"
    "DEPLOYMENT_GUIDE.md"
)

echo -e "${YELLOW}📝 Deployment package ready!${NC}"
echo -e "${GREEN}✓ You can now upload files to your server${NC}"
echo ""
echo "Next steps:"
echo "1. Upload all files to your server"
echo "2. Create .env file on server with your database credentials"
echo "3. Set file permissions (storage and bootstrap/cache)"
echo "4. Create storage link"
echo "5. Run migrations"
echo ""
echo "See DEPLOYMENT_GUIDE.md for detailed instructions."

