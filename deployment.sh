#!/usr/bin/env bash

set -euo pipefail

cd "$(dirname "${BASH_SOURCE[0]}")"

composer install
php artisan optimize:clear
php artisan storage:link --force
php artisan optimize