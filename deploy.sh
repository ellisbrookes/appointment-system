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
# Handle any local file conflicts by stashing them first
git add -A
git stash push -m "Auto-stash before deployment $(date)"
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

# Check if containers are healthy first
echo "🔍 Checking container health..."
CONTAINER_HEALTH=$(docker inspect --format='{{.State.Health.Status}}' skedulaa-app-production)
echo "📊 Container health status: $CONTAINER_HEALTH"

# Wait a bit longer if still starting
if [ "$CONTAINER_HEALTH" = "starting" ]; then
    echo "⏳ Container still starting, waiting additional 30 seconds..."
    sleep 30
    CONTAINER_HEALTH=$(docker inspect --format='{{.State.Health.Status}}' skedulaa-app-production)
    echo "📊 Updated container health status: $CONTAINER_HEALTH"
fi

# Check if health endpoint is working
echo "🔍 Testing health endpoint..."
HEALTH_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/health)
echo "📊 Health endpoint returned: $HEALTH_CODE"

# Check if homepage is working
echo "🔍 Testing homepage..."
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost)
echo "📊 Homepage returned: $HTTP_CODE"

# Show recent logs if there's an issue
if [ "$HTTP_CODE" != "200" ]; then
    echo "⚠️  Non-200 response, checking recent logs..."
    echo "📋 Last 10 lines of application logs:"
    docker logs --tail 10 skedulaa-app-production
fi

if [ "$HTTP_CODE" = "200" ]; then
    echo "✅ Deployment successful! Homepage is responding with 200."
else
    echo "❌ Deployment failed! Homepage returned $HTTP_CODE"
    echo "🔄 Rolling back..."
    docker-compose -f docker-compose.yml -f docker-compose.production.yml down
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
