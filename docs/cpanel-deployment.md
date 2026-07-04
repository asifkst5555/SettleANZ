# Hostinger Deployment Guide

This project now follows the standard Laravel production layout for shared hosting:

- the Laravel root contains `artisan`, `app/`, `bootstrap/`, `config/`, `database/`, `public/`, `resources/`, `routes/`, `storage/`, `vendor/`, and `composer.json`
- `storage/` stays a real directory
- only `public/storage` is a symlink, and it points to `storage/app/public`

## 1. Hosting Requirements

- PHP 8.3 or higher
- MySQL or MariaDB
- SSH access preferred
- ability to set the document root to the Laravel `public/` directory

## 2. Deployment Model

Do not upload the Laravel source into `public_html` and then move folders around.

Upload the application outside `public_html`, then point the web root at the app's `public/` directory.

Example layout:

```text
/home/USERNAME/settleanz-app
/home/USERNAME/settleanz-app/public
```

Document root:

```text
/home/USERNAME/settleanz-app/public
```

## 3. Package For Upload

Run this locally from WSL:

```bash
cd /home/asifk/projects/SettleANZ
bash scripts/cpanel-package.sh
```

It creates a deploy bundle at `deploy/cpanel/settleanz-app`.

## 4. Production `.env` Values

Use values like these on Hostinger:

```env
APP_NAME=SettleANZ
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_user
DB_PASSWORD=your_password

FILESYSTEM_DISK=public
CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database
LOG_CHANNEL=stack
LOG_LEVEL=error
```

## 5. First Server Commands

After the files are in place, run:

```bash
php artisan storage:link
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

If the `storage/` directory or `bootstrap/cache/` is missing or not writable, create/fix them first:

```bash
mkdir -p storage/framework/cache storage/framework/sessions storage/framework/testing storage/framework/views storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

## 6. Hostinger Shared Hosting Checklist

- keep the project root outside `public_html`
- point the domain or subdomain to `public/`
- build frontend assets locally, not on the host
- use the deployment script in this repo for repeatable updates
- never create a second `storage` copy or rename it to `storage.2`, `storage.3`, and so on

## 7. Deployment Script

The server-side deployment script is `deploy.sh` in the project root.

It:

- pulls the latest Git changes
- installs Composer dependencies
- builds assets when `npm` is available
- creates Laravel runtime directories if needed
- keeps `storage/` as a real directory
- creates only the `public/storage` symlink

Run it from the Laravel root on the server.
