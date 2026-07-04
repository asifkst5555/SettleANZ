#!/bin/bash
set -euo pipefail

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PHP_BIN="${PHP_BIN:-/opt/alt/php83/usr/bin/php}"

cd "$PROJECT_ROOT"

echo "🚀 Starting manual deployment..."

if [[ -L storage ]]; then
    echo "Repairing storage symlink back into a real directory..."
    temp_storage="$(mktemp -d)"
    rsync -a storage/ "$temp_storage/"
    rm storage
    mv "$temp_storage" storage
fi

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/testing storage/framework/views storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache

if [[ -d public/storage && ! -L public/storage ]]; then
    echo "Migrating legacy public/storage contents into storage/app/public..."
    mkdir -p storage/app/public
    rsync -a public/storage/ storage/app/public/
    rm -rf public/storage
fi

echo "📥 Pulling latest changes from GitHub..."
git pull origin main

echo "📦 Installing Composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

if command -v npm >/dev/null 2>&1; then
    echo "⚡ Building assets (NPM)..."
    npm ci
    npm run build
else
    echo "⚠️ NPM is not installed on this server. Skipping asset compilation."
fi

echo "🗄️ Running database migrations..."
$PHP_BIN artisan migrate --force

echo "🧹 Optimizing config, routes, and views..."
$PHP_BIN artisan config:clear
$PHP_BIN artisan route:clear
$PHP_BIN artisan view:clear
$PHP_BIN artisan event:clear
$PHP_BIN artisan storage:link --force
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache

$PHP_BIN artisan event:cache

echo "✅ Deployment completed successfully!"
