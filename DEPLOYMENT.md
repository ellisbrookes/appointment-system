# Alpha Release Deployment Guide

This guide will help you deploy the Appointment System alpha release to a DigitalOcean droplet for user testing and feedback.

## 🎯 Alpha Release Goals

- Get early user feedback
- Test core functionality in production
- Validate user workflows
- Identify bugs and improvement areas

## 🚀 Quick Start

### 1. Create DigitalOcean Droplet

1. Go to [DigitalOcean](https://digitalocean.com)
2. Create a new droplet:
   - **Image**: Ubuntu 22.04 LTS
   - **Size**: Basic ($6/month - 1 GB RAM, 1 vCPU, 25 GB SSD)
   - **Region**: Choose closest to your users
   - **Authentication**: SSH Key (recommended)
3. Note down your droplet's IP address

### 2. Set Up the Droplet

SSH into your droplet and run the setup script:

```bash
# SSH into your droplet
ssh root@YOUR_DROPLET_IP

# Download and run setup script
curl -fsSL https://raw.githubusercontent.com/ellisbrookes/appointment-system/production-setup/deploy/setup-droplet.sh | bash

# Log out and back in to apply docker group changes
exit
ssh root@YOUR_DROPLET_IP
```

### 3. Configure GitHub Secrets

Add these secrets to your GitHub repository (Settings → Secrets and variables → Actions):

```
DO_HOST=YOUR_DROPLET_IP
DO_USERNAME=root
DO_SSH_KEY=YOUR_PRIVATE_SSH_KEY
DO_PORT=22
```

### 4. Configure Environment Variables

Edit the environment file on your droplet:

```bash
cd /opt/appointment-system
nano .env
```

Update these important values:
- `DB_PASSWORD=` (set a strong password)
- `STRIPE_KEY=pk_test_...` (your Stripe test public key)
- `STRIPE_SECRET=sk_test_...` (your Stripe test secret key)
- Add any other required credentials

### 5. Start the Application

```bash
cd /opt/appointment-system
docker-compose -f docker-compose.prod.yml up -d
```

### 6. Verify Deployment

Visit `http://YOUR_DROPLET_IP` to see your alpha release!

## 🔄 Automatic Deployment

The GitHub Actions workflow will automatically:

1. **Run tests** on every push to `production-setup` branch
2. **Build Docker image** and push to GitHub Container Registry
3. **Deploy to DigitalOcean** droplet
4. **Notify** deployment status

### Trigger Deployment

- **Push to `production-setup` branch**: Automatic deployment
- **Merge PR to `master`**: Automatic deployment

## 📊 Monitoring Your Alpha

### Application Logs

```bash
# View application logs
cd /opt/appointment-system
docker-compose -f docker-compose.prod.yml logs -f app

# View database logs
docker-compose -f docker-compose.prod.yml logs -f db
```

### System Resources

```bash
# Check Docker containers
docker ps

# Check system resources
htop

# Check disk usage
df -h
```

### Database Access

```bash
# Connect to MySQL
docker-compose -f docker-compose.prod.yml exec db mysql -u appointment_user -p appointment_system_alpha
```

## 🐛 Troubleshooting

### Common Issues

1. **Application not starting**
   ```bash
   docker-compose -f docker-compose.prod.yml logs app
   ```

2. **Database connection errors**
   ```bash
   docker-compose -f docker-compose.prod.yml restart db
   ```

3. **Permission issues**
   ```bash
   sudo chown -R www-data:www-data storage bootstrap/cache
   ```

### Reset Application

```bash
cd /opt/appointment-system
docker-compose -f docker-compose.prod.yml down
docker-compose -f docker-compose.prod.yml up -d
```

## 📈 Scaling for Beta/Production

When you're ready to move beyond alpha:

1. **Get a domain name** and update DNS
2. **Set up SSL certificate** (Let's Encrypt)
3. **Upgrade droplet size** if needed
4. **Set up database backups**
5. **Configure monitoring** (e.g., Uptime Robot)
6. **Switch to production Stripe keys**

## 💰 Cost Estimation

**Alpha Release Monthly Costs:**
- DigitalOcean Droplet (Basic): $6/month
- Total: **~$6/month**

## 🔒 Security Notes

- Droplet has automatic security updates enabled
- Firewall configured for ports 22, 80, 443 only
- Using HTTP for alpha (SSL can be added later)
- Database accessible only from within Docker network

## 📝 Getting User Feedback

Your alpha is now live! Share the IP address with:
- Trusted users and friends
- Beta testers
- Stakeholders

Consider adding:
- Feedback form/widget
- Analytics (Google Analytics)
- User session recording (Hotjar)
- Error tracking (Sentry)

## 🎉 Next Steps

1. **Share your alpha** with users
2. **Collect feedback** actively
3. **Monitor usage** and performance
4. **Iterate quickly** based on feedback
5. **Plan your beta release** with a custom domain

Good luck with your alpha release! 🚀
