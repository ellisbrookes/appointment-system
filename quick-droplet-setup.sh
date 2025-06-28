#!/bin/bash

# Quick setup script for your DigitalOcean droplet
# Run this on your droplet as root

set -e

echo "🚀 Setting up DigitalOcean droplet for GitHub Actions..."

# Update system
echo "📦 Updating system..."
apt-get update && apt-get upgrade -y

# Install required packages
echo "🔧 Installing Docker, Git, and other tools..."
apt-get install -y docker.io docker-compose git curl wget unzip jq

# Start Docker
echo "🐳 Starting Docker..."
systemctl start docker
systemctl enable docker

# Create application directory
echo "📁 Setting up application directory..."
mkdir -p /opt/appointment-system
cd /opt/appointment-system

# Clone repository
echo "📥 Cloning repository..."
if [ ! -d ".git" ]; then
    git clone https://github.com/ellisbrookes/appointment-system.git .
else
    git pull origin production-setup
fi

# Create environment file
echo "📝 Creating .env file..."
cat > .env << 'EOF'
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:GENERATE_NEW_KEY_HERE
APP_URL=http://159.65.226.91

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=appointment_system
DB_USERNAME=appointment_user
DB_PASSWORD=secure_db_password_123
DB_ROOT_PASSWORD=secure_root_password_123

REDIS_HOST=redis
REDIS_PORT=6379
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Replace with your actual Stripe keys
STRIPE_KEY=pk_test_PLACEHOLDER_REPLACE_WITH_ACTUAL_KEY
STRIPE_SECRET=sk_test_PLACEHOLDER_REPLACE_WITH_ACTUAL_KEY
STRIPE_WEBHOOK_SECRET=whsec_PLACEHOLDER_REPLACE_WITH_ACTUAL_KEY

MAIL_MAILER=log
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=
MAIL_FROM_ADDRESS=admin@example.com
MAIL_FROM_NAME="Appointment System"
EOF

# Set up GitHub Actions runner
echo "🏃 Setting up GitHub Actions runner..."
mkdir -p /opt/actions-runner
cd /opt/actions-runner

# Download the exact version from GitHub instructions
echo "📥 Downloading GitHub Actions runner v2.325.0..."
curl -o actions-runner-linux-x64-2.325.0.tar.gz -L https://github.com/actions/runner/releases/download/v2.325.0/actions-runner-linux-x64-2.325.0.tar.gz

# Validate hash (optional but recommended)
echo "🔍 Validating download..."
echo "5020da7139d85c776059f351e0de8fdec753affc9c558e892472d43ebeb518f4  actions-runner-linux-x64-2.325.0.tar.gz" | sha256sum -c

# Extract
echo "📦 Extracting runner..."
tar xzf ./actions-runner-linux-x64-2.325.0.tar.gz
rm actions-runner-linux-x64-2.325.0.tar.gz

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
echo "✅ Setup complete! Now run these commands:"
echo ""
echo "1. Configure the runner (run as runner user):"
echo "   sudo -u runner bash"
echo "   cd /opt/actions-runner"
echo "   ./config.sh --url https://github.com/ellisbrookes/appointment-system --token ADKG2IQ3XV2OYZVUMWVVLFLIL5ZMO"
echo ""
echo "2. Start the runner:"
echo "   ./run.sh"
echo ""
echo "3. (Optional) Install as a service for production:"
echo "   exit  # exit from runner user"
echo "   cd /opt/actions-runner"
echo "   sudo ./svc.sh install runner"
echo "   sudo ./svc.sh start"
echo ""
echo "⚠️  Don't forget to:"
echo "   - Edit /opt/appointment-system/.env with your actual values"
echo "   - Replace YOUR_DROPLET_IP with your actual IP"
echo "   - Add your real Stripe keys"
echo ""
echo "🎉 Your droplet is ready for GitHub Actions deployment!"
