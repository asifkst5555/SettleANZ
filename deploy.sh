#!/bin/bash
set -e

echo "🚀 Starting manual deployment..."

# 1. Put site in maintenance mode (optional, prevents users from getting errors during build)
php artisan down || true

# 2. Pull latest code from GitHub
echo "📥 Pulling latest changes from GitHub..."
git pull origin main

# 3. Install Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

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
php artisan migrate --force

# 6. Optimize and clear cache
echo "🧹 Optimizing config, routes, and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Bring site back online
php artisan up

echo "✅ Deployment completed successfully!"
