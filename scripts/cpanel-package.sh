#!/usr/bin/env bash

set -euo pipefail

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
OUTPUT_ROOT="$PROJECT_ROOT/deploy/cpanel"
APP_DIR="$OUTPUT_ROOT/settleanz-app"

echo "Preparing cPanel package in: $OUTPUT_ROOT"
rm -rf "$OUTPUT_ROOT"
mkdir -p "$APP_DIR"

cd "$PROJECT_ROOT"

if ! command -v php >/dev/null 2>&1; then
    echo "Error: php is required."
    exit 1
fi

if ! php -r 'exit(version_compare(PHP_VERSION, "8.3.0", ">=") ? 0 : 1);'; then
    echo "Error: PHP 8.3+ is required for this project."
    exit 1
fi

if ! command -v composer >/dev/null 2>&1; then
    echo "Error: composer is required."
    exit 1
fi

if ! command -v npm >/dev/null 2>&1; then
    echo "Error: npm is required."
    exit 1
fi

echo "Installing production PHP dependencies..."
composer install --no-dev --optimize-autoloader

echo "Installing JS dependencies and building assets..."
npm ci
npm run build

echo "Caching Laravel metadata for production..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "Copying Laravel app (outside public_html)..."
rsync -a \
    --exclude ".git" \
    --exclude ".github" \
    --exclude ".idea" \
    --exclude ".vscode" \
    --exclude "node_modules" \
    --exclude "tests" \
    --exclude "docs" \
    --exclude "deploy" \
    --exclude ".env" \
    "$PROJECT_ROOT/" "$APP_DIR/"

cat > "$OUTPUT_ROOT/UPLOAD-STEPS.txt" <<'TXT'
1) Upload "settleanz-app" outside public_html (recommended target: /home/USERNAME/settleanz-app).
2) Point the Hostinger document root to /home/USERNAME/settleanz-app/public.
3) Set production .env values, then run:
   php artisan migrate --force
   php artisan storage:link
TXT

echo "cPanel package ready:"
echo " - App folder: $APP_DIR"
echo " - Steps: $OUTPUT_ROOT/UPLOAD-STEPS.txt"
