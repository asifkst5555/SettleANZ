#!/usr/bin/env bash
set -euo pipefail

PROJECT_DIR="/home/asifk/projects/SettleANZ"
MINOR_REQUIRED=3

cd "$PROJECT_DIR"

if ! command -v php >/dev/null 2>&1; then
  echo "Error: php is not installed. Install PHP 8.3+ first."
  exit 1
fi

PHP_MINOR="$(php -r 'echo PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION;')"
PHP_MAJOR="${PHP_MINOR%%.*}"
PHP_MIN="${PHP_MINOR##*.}"

if [[ "$PHP_MAJOR" -lt 8 ]] || [[ "$PHP_MAJOR" -eq 8 && "$PHP_MIN" -lt "$MINOR_REQUIRED" ]]; then
  echo "Error: PHP 8.3+ is required. Current: $PHP_MINOR"
  echo "See RUN_SERVER_WSL.md for install steps."
  exit 1
fi

if [[ ! -f ".env" ]]; then
  cp .env.example .env
  php artisan key:generate
fi

mkdir -p database
touch database/database.sqlite

echo "Starting Laravel on http://localhost:8000"
exec php artisan serve --host=0.0.0.0 --port=8000
