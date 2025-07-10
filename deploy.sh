#!/bin/bash

# Deployment script for Skedulaa
set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

echo "🚀 Starting deployment..."

# Check if .env file exists
if [ ! -f ".env" ]; then
    print_error ".env file not found!"
    echo "Please create a .env file based on .env.example"
    exit 1
fi

print_status ".env file found"

# Test docker-compose configuration BEFORE pulling/deploying
echo ""
echo "🔍 Testing Docker Compose Configuration..."
print_status "Testing unified docker-compose configuration..."
if docker-compose -f docker-compose.yml -f docker-compose.production.yml config > /dev/null 2>&1; then
    print_status "Configuration is valid"
else
    print_error "Docker compose configuration has errors"
    docker-compose -f docker-compose.yml -f docker-compose.production.yml config
    exit 1
fi

# Pull latest changes
echo ""
echo "📥 Pulling latest changes from Git..."
git pull origin alpha

# Backup current containers
echo "💾 Stopping current containers..."
docker-compose -f docker-compose.yml -f docker-compose.production.yml down

# Pull latest images and start containers
echo "🔨 Pulling latest images and starting containers..."
docker-compose -f docker-compose.yml -f docker-compose.production.yml pull
docker-compose -f docker-compose.yml -f docker-compose.production.yml up -d

# Wait for health check
echo "⏳ Waiting for containers to be healthy..."
sleep 30

# Check if homepage is working
echo "🔍 Testing homepage..."
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost)

if [ "$HTTP_CODE" = "200" ]; then
    echo "✅ Deployment successful! Homepage is responding with 200."
else
    echo "❌ Deployment failed! Homepage returned $HTTP_CODE"
    echo "🔄 Rolling back..."
    docker-compose -f docker-compose.production.yml down
    exit 1
fi

# Clean up old Docker images
echo "🧹 Cleaning up old Docker images..."
docker image prune -f

echo "🎉 Deployment completed successfully!"
echo ""
echo "📋 Next steps:"
echo "   • Your application is running on http://$(curl -s ifconfig.me)"
echo "   • To deploy future changes, run: ./deploy.sh"
echo "   • To view logs, run: docker-compose -f docker-compose.production.yml logs -f"
