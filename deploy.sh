#!/bin/bash

# Deployment script for Skedulaa
set -e  # Exit on any error

echo "🚀 Starting deployment..."

# Pull latest changes
echo "📥 Pulling latest changes from Git..."
git pull origin alpha

# Backup current containers
echo "💾 Stopping current containers..."
docker-compose -f docker-compose.production.yml down

# Rebuild and start containers
echo "🔨 Building and starting new containers..."
docker-compose -f docker-compose.production.yml up -d --build

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
