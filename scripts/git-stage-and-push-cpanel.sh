#!/usr/bin/env bash
# Stage deploy-friendly paths (including public/media), commit, and push to origin/main.
# Run from WSL:  bash scripts/git-stage-and-push-cpanel.sh
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

git add "$ROOT/scripts/git-stage-and-push-cpanel.sh" 2>/dev/null || true

echo "=== Repo: $ROOT ==="
echo "=== 1. git status (before) ==="
git status -sb

echo "=== 2. Core application ==="
git add .gitignore app bootstrap config database routes resources tests \
  artisan composer.json composer.lock package.json package-lock.json \
  vite.config.js phpunit.xml README.md .env.example .editorconfig .gitattributes .nvmrc

echo "=== 3. Docs & scripts ==="
git add docs scripts 2>/dev/null || true

echo "=== 4. Public assets (media, CSS, JS) ==="
git add public/media public/js public/fonts 2>/dev/null || true
git add public/*.css public/*.js 2>/dev/null || true
git add public/admin-notifications.css 2>/dev/null || true

echo "=== 5. Root files (legal PDF, guides, tooling) ==="
git add *.pdf *.docx 2>/dev/null || true
git add CLAUDE.md NAVIGATION_GUIDELINES.md RUN_SERVER_WSL.md generate-legal-pdf.php 2>/dev/null || true
git add SEO-SYSTEM-DOCUMENTATION.html SEO-DOCUMENTATION-README.md SEO-SYSTEM-QUICK-GUIDE.md DOCUMENTATION-PACKAGE-README.md 2>/dev/null || true
git add image_guide 2>/dev/null || true

echo "=== 6. Status (staged) ==="
git status -sb

echo "=== 7. Commit ==="
if git diff --cached --quiet; then
  echo "Nothing to commit (working tree clean or no staged changes)."
else
  git commit -m "Deploy-ready: app, public/media, and assets for cPanel."
fi

echo "=== 8. Push origin main ==="
git push origin main

echo "=== Done ==="
git status -sb
