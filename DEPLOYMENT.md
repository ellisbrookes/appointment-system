# Production Deployment Guide

## Quick Setup

### 1. Environment Setup
```bash
# Copy the production environment template
cp .env.production.example .env.production

# Edit with your actual values
nano .env.production
```

### 2. Required Environment Variables
- `DB_USERNAME` - Database user
- `DB_PASSWORD` - Database password  
- `DB_DATABASE` - Database name (default: appointments)
- `MYSQL_ROOT_PASSWORD` - MySQL root password
- `APP_KEY` - Laravel application key (generate with: `php artisan key:generate --show`)
- `APP_URL` - Your server URL (e.g., http://159.65.226.91)
- `STRIPE_KEY` - Stripe publishable key (pk_test_... or pk_live_...)
- `STRIPE_SECRET` - Stripe secret key (sk_test_... or sk_live_...)
- `STRIPE_WEBHOOK_SECRET` - Stripe webhook secret (whsec_...)

### 3. Deploy
```bash
# Start the application on port 80
docker-compose --env-file .env.production up -d

# Stop the application
docker-compose --env-file .env.production down
```

## Port Configuration

The application runs on port 80 by default in production, so you can access it directly via your server IP without specifying a port.

## Security Notes

- Never commit `.env.production` or any files containing real credentials
- The `.gitignore` file protects these files automatically
- Use strong passwords for database credentials
- Generate a unique `APP_KEY` for each deployment

## Database Fixes Applied

This deployment includes fixes for common database connection issues:
- Environment variables are properly passed to containers
- Database connection retry logic in startup script
- Proper network configuration between containers
