#!/usr/bin/env bash
# Stage deploy-friendly paths (including ALL of public/media), commit, push to origin/main.
# Run from WSL:  bash scripts/git-stage-and-push-cpanel.sh
#
# Note: optional pathspecs are guarded — a missing .nvmrc or public/fonts must NOT
# stop the script before public/media is added (that was causing leftover untracked media).
set -uo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

git add "$ROOT/scripts/git-stage-and-push-cpanel.sh" 2>/dev/null || true

echo "=== Repo: $ROOT ==="
echo "=== 1. git status (before) ==="
git status -sb

echo "=== 2. Public assets first (cPanel / site runtime) ==="
git add public/media
[[ -d public/js ]] && git add public/js
[[ -d public/fonts ]] && git add public/fonts
git add public/*.css public/*.js 2>/dev/null || true
[[ -f public/admin-notifications.css ]] && git add public/admin-notifications.css

echo "=== 3. Core application ==="
git add .gitignore app bootstrap config database routes resources tests \
  artisan composer.json composer.lock package.json package-lock.json \
  vite.config.js phpunit.xml README.md .env.example .editorconfig .gitattributes

[[ -f .nvmrc ]] && git add .nvmrc

echo "=== 4. Docs & scripts ==="
[[ -d docs ]] && git add docs
[[ -d scripts ]] && git add scripts

echo "=== 5. Root PDFs / guides (if present) ==="
shopt -s nullglob
for f in *.pdf *.docx; do
  [[ -f "$f" ]] && git add -- "$f"
done
shopt -u nullglob

[[ -f CLAUDE.md ]] && git add CLAUDE.md
[[ -f NAVIGATION_GUIDELINES.md ]] && git add NAVIGATION_GUIDELINES.md
[[ -f RUN_SERVER_WSL.md ]] && git add RUN_SERVER_WSL.md
[[ -f generate-legal-pdf.php ]] && git add generate-legal-pdf.php
[[ -f SEO-SYSTEM-DOCUMENTATION.html ]] && git add SEO-SYSTEM-DOCUMENTATION.html
[[ -f SEO-DOCUMENTATION-README.md ]] && git add SEO-DOCUMENTATION-README.md
[[ -f SEO-SYSTEM-QUICK-GUIDE.md ]] && git add SEO-SYSTEM-QUICK-GUIDE.md
[[ -f DOCUMENTATION-PACKAGE-README.md ]] && git add DOCUMENTATION-PACKAGE-README.md
[[ -d image_guide ]] && git add image_guide

echo "=== 6. Status (staged) ==="
git status -sb

echo "=== 7. Commit ==="
if git diff --cached --quiet; then
  echo "Nothing to commit (working tree clean or no staged changes)."
else
  git commit -m "Add public/media, public/js, and deploy assets for cPanel." || { echo "git commit failed"; exit 1; }
fi

echo "=== 8. Push origin main ==="
set -e
git push origin main

echo "=== Done ==="
git status -sb
