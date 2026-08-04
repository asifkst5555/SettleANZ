#!/bin/bash
# ============================================================================
# deploy.sh — Post-pull automation for Hostinger Shared Hosting
# Run this AFTER every git pull on the production server.
#
# Usage:
#   cd /home/settleanz
#   bash deploy.sh
# ============================================================================

set -euo pipefail

APP_DIR="$(cd "$(dirname "$0")" && pwd)"
WEB_ROOT="/home/u123456789/public_html"   # ← CHANGE THIS to your actual path
PHP="php"
COMPOSER="composer"
NPM="npm"

echo "========================================"
echo "  SettleANZ — Deployment Script"
echo "  App dir: $APP_DIR"
echo "  Web root: $WEB_ROOT"
echo "========================================"
echo ""

# ── 1. Composer ──────────────────────────────────────────────────────────────
echo "[1/8] Installing Composer dependencies (no dev)..."
$COMPOSER install --no-dev --optimize-autoloader --no-interaction
echo "  ✓ Done"
echo ""

# ── 2. Optional: NPM build ───────────────────────────────────────────────────
# Uncomment if you want to rebuild assets on the server.
# On Hostinger shared hosting the build can be slow — you may prefer to
# commit the built assets in public/build/ instead.
#
# echo "[2/8] Building Vite assets..."
# $NPM ci --production
# $NPM run build
# echo "  ✓ Done"

# ── 3. Environment ───────────────────────────────────────────────────────────
echo "[3/8] Ensuring .env exists..."
if [ ! -f "$APP_DIR/.env" ]; then
    cp "$APP_DIR/.env.example" "$APP_DIR/.env"
    echo "  ⚠ Copied .env.example to .env — configure it now!"
fi
echo "  ✓ Done"
echo ""

# ── 4. Artisan optimize ──────────────────────────────────────────────────────
echo "[4/8] Clearing old caches..."
$PHP "$APP_DIR/artisan" optimize:clear
echo "  ✓ Done"
echo ""

echo "[5/8] Building optimized caches..."
$PHP "$APP_DIR/artisan" config:cache
$PHP "$APP_DIR/artisan" route:cache
$PHP "$APP_DIR/artisan" view:cache
$PHP "$APP_DIR/artisan" event:cache
echo "  ✓ Done"
echo ""

# ── 5. Storage link ──────────────────────────────────────────────────────────
echo "[6/8] Creating storage symlink..."
rm -f "$APP_DIR/public/storage"
$PHP "$APP_DIR/artisan" storage:link
echo "  ✓ Done"
echo ""

# ── 6. Permissions ───────────────────────────────────────────────────────────
echo "[7/8] Setting permissions..."
chmod -R 775 "$APP_DIR/storage"
chmod -R 775 "$APP_DIR/bootstrap/cache"
chmod -R 775 "$APP_DIR/public/build"
echo "  ✓ Done"
echo ""

# ── 7. Verify symlinks in public_html ────────────────────────────────────────
echo "[8/8] Verifying public_html symlinks..."
SYMLINKS_OK=true

check_symlink() {
    local target="$1"
    local link="$2"
    if [ ! -L "$link" ]; then
        echo "  ⚠ MISSING: $link"
        SYMLINKS_OK=false
    elif [ ! -e "$link" ]; then
        echo "  ⚠ BROKEN: $link (target missing)"
        SYMLINKS_OK=false
    fi
}

check_symlink "$APP_DIR/public/index.php" "$WEB_ROOT/index.php"
check_symlink "$APP_DIR/public/.htaccess" "$WEB_ROOT/.htaccess"
check_symlink "$APP_DIR/public/build" "$WEB_ROOT/build"
check_symlink "$APP_DIR/storage/app/public" "$WEB_ROOT/storage"
check_symlink "$APP_DIR/public/media" "$WEB_ROOT/media"
check_symlink "$APP_DIR/public/favicon.ico" "$WEB_ROOT/favicon.ico"
check_symlink "$APP_DIR/public/apple-touch-icon.png" "$WEB_ROOT/apple-touch-icon.png"
check_symlink "$APP_DIR/public/site.webmanifest" "$WEB_ROOT/site.webmanifest"
check_symlink "$APP_DIR/public/robots.txt" "$WEB_ROOT/robots.txt"

if [ "$SYMLINKS_OK" = true ]; then
    echo "  ✓ All symlinks OK"
else
    echo ""
    echo "  ⚠ Some symlinks are missing."
    echo "     Run  bash setup-symlinks.sh  to create them."
fi
echo ""

echo "========================================"
echo "  Deployment complete!"
echo "========================================"
