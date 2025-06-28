#!/bin/bash

# DigitalOcean Droplet Setup Script for Alpha Release
# Run this script on your fresh Ubuntu droplet

set -e

echo "🚀 Setting up DigitalOcean Droplet for Appointment System Alpha Release"

# Update system
echo "📦 Updating system packages..."
sudo apt update && sudo apt upgrade -y

# Install Docker
echo "🐳 Installing Docker..."
sudo apt install -y apt-transport-https ca-certificates curl software-properties-common
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg
echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io

# Install Docker Compose
echo "🔧 Installing Docker Compose..."
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Add current user to docker group
sudo usermod -aG docker $USER

# Install Git
echo "📝 Installing Git..."
sudo apt install -y git

# Create application directory
echo "📁 Creating application directory..."
sudo mkdir -p /opt/appointment-system
sudo chown $USER:$USER /opt/appointment-system

# Clone repository
echo "📥 Cloning repository..."
cd /opt/appointment-system
git clone https://github.com/ellisbrookes/appointment-system.git .

# Switch to production-setup branch
git checkout production-setup

# Create environment file
echo "⚙️ Setting up environment..."
cp .env.production .env

# Get droplet IP address
DROPLET_IP=$(curl -s http://checkip.amazonaws.com)
echo "🌐 Detected droplet IP: $DROPLET_IP"

# Update .env with actual IP
sed -i "s/YOUR_DROPLET_IP/$DROPLET_IP/g" .env

# Generate application key
echo "🔑 Generating application key..."
docker run --rm -v $(pwd):/app -w /app php:8.2-cli php -r "require 'vendor/autoload.php'; echo 'APP_KEY=base64:' . base64_encode(random_bytes(32)) . PHP_EOL;" >> .env

# Set up firewall
echo "🔥 Configuring firewall..."
sudo ufw allow OpenSSH
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw --force enable

# Create SSL directory for future use
mkdir -p ssl

# Set up Docker log rotation
echo "📋 Setting up log rotation..."
sudo tee /etc/docker/daemon.json > /dev/null <<EOF
{
  "log-driver": "json-file",
  "log-opts": {
    "max-size": "10m",
    "max-file": "3"
  }
}
EOF

sudo systemctl restart docker

# Create backup directory
sudo mkdir -p /opt/backups
sudo chown $USER:$USER /opt/backups

# Set up automatic security updates
echo "🔒 Setting up automatic security updates..."
sudo apt install -y unattended-upgrades
sudo dpkg-reconfigure -plow unattended-upgrades

echo ""
echo "✅ Droplet setup complete!"
echo ""
echo "🔧 Next steps:"
echo "1. Update your GitHub repository secrets with:"
echo "   - DO_HOST: $DROPLET_IP"
echo "   - DO_USERNAME: $USER"
echo "   - DO_SSH_KEY: (your private SSH key)"
echo "   - DO_PORT: 22"
echo ""
echo "2. Configure your .env file in /opt/appointment-system/.env"
echo "   - Add your database passwords"
echo "   - Add your Stripe test keys"
echo "   - Add any other required credentials"
echo ""
echo "3. Start the application:"
echo "   cd /opt/appointment-system"
echo "   docker-compose -f docker-compose.prod.yml up -d"
echo ""
echo "4. Your alpha release will be available at: http://$DROPLET_IP"
echo ""
echo "💡 Pro tip: Consider setting up a simple subdomain like alpha.yourdomain.com pointing to $DROPLET_IP"
