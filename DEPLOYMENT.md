# Deployment Guide

## Production Deployment

### Current Setup
- The application runs on Digital Ocean using Docker containers
- Uses `docker-compose.production.yml` for production configuration
- Builds fresh images from current code on each deployment

### How to Deploy

1. **Push your changes to GitHub:**
   ```bash
   git add .
   git commit -m "Your commit message"
   git push origin production-setup
   ```

2. **SSH into your Digital Ocean server:**
   ```bash
   ssh root@your-server-ip
   cd /opt/skedulaa
   ```

3. **Run the deployment script:**
   ```bash
   ./deploy.sh
   ```

### What the deployment script does:
- Pulls latest changes from GitHub
- Stops current containers
- Rebuilds images with new code
- Starts new containers
- Tests if homepage is working
- Cleans up old Docker images

### Manual Deployment (if needed)
```bash
git pull origin production-setup
docker-compose -f docker-compose.production.yml down
docker-compose -f docker-compose.production.yml up -d --build
```

### Checking Status
```bash
# View running containers
docker ps

# View application logs
docker-compose -f docker-compose.production.yml logs -f app

# Test homepage
curl -s -o /dev/null -w "%{http_code}" http://localhost
```

### Troubleshooting
- If deployment fails, the script will attempt to rollback
- Check logs: `docker-compose -f docker-compose.production.yml logs -f`
- If needed, restart: `docker-compose -f docker-compose.production.yml restart`

### Environment Variables
Make sure your `.env` file has all required variables:
- Database credentials
- Stripe keys
- Mail configuration
- App keys
