#!/bin/bash
set -e

# Define PHP 8.3 binary path on Hostinger
PHP_BIN="/opt/alt/php83/usr/bin/php"

echo "🚀 Starting manual deployment..."

# 1. Put site in maintenance mode (optional)
$PHP_BIN artisan down || true

# 2. Pull latest code from GitHub
echo "📥 Pulling latest changes from GitHub..."
git pull origin main

# 3. Install Composer dependencies
echo "📦 Installing Composer dependencies..."
$PHP_BIN $(which composer) install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# 4. Build assets (checks if npm is available)
if command -v npm &> /dev/null
then
    echo "⚡ Building assets (NPM)..."
    npm install
    npm run build
else
    echo "⚠️ NPM is not installed on this server. Skipping asset compilation."
fi

# 5. Run database migrations
echo "🗄️ Running database migrations..."
$PHP_BIN artisan migrate --force

# 6. Optimize and clear cache
echo "🧹 Optimizing config, routes, and views..."
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache

# 7. Bring site back online
$PHP_BIN artisan up

echo "✅ Deployment completed successfully!"
