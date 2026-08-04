#!/bin/bash
# ============================================================================
# setup-symlinks.sh — One-time symlink setup for Hostinger Shared Hosting
#
# Creates symlinks in public_html/ pointing to the Laravel app which resides
# OUTSIDE the web root. Run ONCE after the initial deploy.
#
# Usage:
#   cd /home/settleanz
#   bash setup-symlinks.sh
# ============================================================================

set -euo pipefail

APP_DIR="$(cd "$(dirname "$0")" && pwd)"
WEB_ROOT="/home/u123456789/public_html"   # ← CHANGE THIS to your actual path

echo "========================================"
echo "  SettleANZ — Symlink Setup"
echo "  App dir: $APP_DIR"
echo "  Web root: $WEB_ROOT"
echo "========================================"
echo ""

# Safety check — don't run if public_html is inside the repo
if echo "$WEB_ROOT" | grep -q "^$APP_DIR"; then
    echo "ERROR: WEB_ROOT ($WEB_ROOT) is inside APP_DIR ($APP_DIR)"
    echo "This would create recursive symlinks. Fix the paths."
    exit 1
fi

create_symlink() {
    local target="$1"
    local linkpath="$2"
    local label="${3:-$(basename "$linkpath")}"

    # Remove existing file/dir/symlink
    if [ -L "$linkpath" ] || [ -f "$linkpath" ] || [ -d "$linkpath" ]; then
        rm -rf "$linkpath"
        echo "  Removed existing: $label"
    fi

    # Verify target exists
    if [ ! -e "$target" ]; then
        echo "  ⚠ SKIPPED: target missing — $target"
        return
    fi

    ln -s "$target" "$linkpath"
    echo "  ✓ Created: $label → $target"
}

echo "Creating symlinks..."
echo ""

create_symlink "$APP_DIR/public/index.php"   "$WEB_ROOT/index.php"    "index.php"
create_symlink "$APP_DIR/public/.htaccess"   "$WEB_ROOT/.htaccess"    ".htaccess"
create_symlink "$APP_DIR/public/build"       "$WEB_ROOT/build"        "build/"
create_symlink "$APP_DIR/storage/app/public" "$WEB_ROOT/storage"      "storage/"
create_symlink "$APP_DIR/public/media"       "$WEB_ROOT/media"        "media/"
create_symlink "$APP_DIR/public/favicon.ico"          "$WEB_ROOT/favicon.ico"          "favicon.ico"
create_symlink "$APP_DIR/public/favicon.png"          "$WEB_ROOT/favicon.png"          "favicon.png"
create_symlink "$APP_DIR/public/favicon-48x48.png"    "$WEB_ROOT/favicon-48x48.png"    "favicon-48x48.png"
create_symlink "$APP_DIR/public/favicon-96x96.png"    "$WEB_ROOT/favicon-96x96.png"    "favicon-96x96.png"
create_symlink "$APP_DIR/public/icon-192.png"         "$WEB_ROOT/icon-192.png"         "icon-192.png"
create_symlink "$APP_DIR/public/icon-512.png"         "$WEB_ROOT/icon-512.png"         "icon-512.png"
create_symlink "$APP_DIR/public/apple-touch-icon.png" "$WEB_ROOT/apple-touch-icon.png" "apple-touch-icon.png"
create_symlink "$APP_DIR/public/site.webmanifest"     "$WEB_ROOT/site.webmanifest"     "site.webmanifest"
create_symlink "$APP_DIR/public/robots.txt"          "$WEB_ROOT/robots.txt"          "robots.txt"
create_symlink "$APP_DIR/public/css"                 "$WEB_ROOT/css"                  "css/"
create_symlink "$APP_DIR/public/js"                  "$WEB_ROOT/js"                   "js/"
create_symlink "$APP_DIR/public/site.css"            "$WEB_ROOT/site.css"             "site.css"
create_symlink "$APP_DIR/public/site.js"             "$WEB_ROOT/site.js"              "site.js"

echo ""
echo "Verifying..."
echo ""

for linkpath in "$WEB_ROOT/index.php" "$WEB_ROOT/.htaccess" "$WEB_ROOT/build" \
                "$WEB_ROOT/storage" "$WEB_ROOT/media" "$WEB_ROOT/favicon.ico" \
                "$WEB_ROOT/robots.txt"; do
    if [ -L "$linkpath" ]; then
        echo "  OK: $(basename "$linkpath") → $(readlink "$linkpath")"
    else
        echo "  MISSING: $linkpath"
    fi
done

echo ""
echo "========================================"
echo "  Symlink setup complete!"
echo "  Run  bash deploy.sh  to finalize."
echo "========================================"
