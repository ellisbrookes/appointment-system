#!/bin/bash

# Setup script for self-hosted GitHub Actions runner on DigitalOcean droplet
# Run this script on your DigitalOcean droplet as root

set -e

echo "🚀 Setting up self-hosted GitHub Actions runner..."

# Update system
echo "📦 Updating system packages..."
apt-get update && apt-get upgrade -y

# Install required packages
echo "🔧 Installing required packages..."
apt-get install -y \
    docker.io \
    docker-compose \
    git \
    curl \
    wget \
    unzip \
    jq

# Start and enable Docker
echo "🐳 Starting Docker service..."
systemctl start docker
systemctl enable docker

# Create application directory
echo "📁 Creating application directory..."
mkdir -p /opt/appointment-system
cd /opt/appointment-system

# Clone your repository (replace with your actual repo URL)
echo "📥 Cloning repository..."
if [ ! -d ".git" ]; then
    git clone https://github.com/ellisbrookes/appointment-system.git .
else
    echo "Repository already exists, pulling latest changes..."
    git pull origin production-setup
fi

# Create environment file template
echo "📝 Creating environment file template..."
cat > .env << 'EOF'
# Laravel Configuration
APP_ENV=production
APP_DEBUG=false
APP_KEY=
APP_URL=http://your-droplet-ip

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=appointment_system
DB_USERNAME=appointment_user

DB_PASSWORD=your_db_password_here
DB_ROOT_PASSWORD=***REMOVED***_here

# Redis Configuration
REDIS_HOST=redis
REDIS_PORT=6379
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Stripe Configuration (replace with your actual keys)
STRIPE_KEY=pk_test_PLACEHOLDER_REPLACE_WITH_ACTUAL_KEY
STRIPE_SECRET=sk_test_PLACEHOLDER_REPLACE_WITH_ACTUAL_KEY
STRIPE_WEBHOOK_SECRET=whsec_PLACEHOLDER_REPLACE_WITH_ACTUAL_KEY

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=your_mail_host
MAIL_PORT=587
MAIL_USERNAME=your_mail_username

MAIL_PASSWORD=_mail_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="Appointment System"
EOF

echo "⚠️  IMPORTANT: Please edit /opt/appointment-system/.env with your actual configuration values!"

# Create GitHub Actions runner directory
echo "🏃 Setting up GitHub Actions runner..."
mkdir -p /opt/actions-runner
cd /opt/actions-runner

# Download latest runner
echo "📥 Downloading GitHub Actions runner..."
RUNNER_VERSION=$(curl -s https://api.github.com/repos/actions/runner/releases/latest | jq -r '.tag_name' | sed 's/v//')
curl -o actions-runner-linux-x64.tar.gz -L "https://github.com/actions/runner/releases/download/v${RUNNER_VERSION}/actions-runner-linux-x64-${RUNNER_VERSION}.tar.gz"

# Extract runner
echo "📦 Extracting runner..."
tar xzf ./actions-runner-linux-x64.tar.gz
rm actions-runner-linux-x64.tar.gz

# Create runner user
echo "👤 Creating runner user..."
if ! id "runner" &>/dev/null; then
    useradd -m -s /bin/bash runner
    usermod -aG docker runner
fi

# Set permissions
chown -R runner:runner /opt/actions-runner
chown -R runner:runner /opt/appointment-system

echo ""
echo "✅ Setup completed!"
echo ""
echo "🔧 Next steps:"
echo "1. Edit /opt/appointment-system/.env with your actual configuration"
echo "2. Get your runner registration token from GitHub:"
echo "   - Go to https://github.com/ellisbrookes/appointment-system/settings/actions/runners"
echo "   - Click 'New self-hosted runner'"
echo "   - Copy the registration token"
echo "3. Run as the runner user:"
echo "   sudo -u runner bash"
echo "   cd /opt/actions-runner"
echo "   ./config.sh --url https://github.com/ellisbrookes/appointment-system --token YOUR_TOKEN_HERE"
echo "   ./run.sh"
echo ""
echo "💡 For production, consider setting up the runner as a service:"
echo "   sudo ./svc.sh install runner"
echo "   sudo ./svc.sh start"
