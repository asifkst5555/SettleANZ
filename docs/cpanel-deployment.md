# cPanel Deployment Guide

This project can be deployed to cPanel safely, but the server should be treated as a production target, not a build machine.

## 1. Hosting Requirements

Minimum recommendations:

- PHP `8.3` or higher
- MySQL or MariaDB database
- Composer access preferred
- SSH access preferred but not required
- ability to change document root preferred

## 2. Important Rule For cPanel

Do not depend on Node.js or `npm run dev` on the server.

For cPanel deployment:

1. build assets locally
2. upload the compiled project
3. run Laravel in production mode

Use Node `22` locally. This repo includes `.nvmrc` for that.

## 3. Local Build Before Upload

Run these commands in WSL on your machine:

```bash
cd /home/asifk/projects/SettleANZ
nvm use 22
composer install --no-dev --optimize-autoloader
npm install
npm run build
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

After `npm run build`, the compiled assets will be inside `public/build` and are ready to upload.

## 4. Production `.env` Values

Use MySQL in cPanel production. Example:

```env
APP_NAME=SettleANZ
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_cpanel_db_name
DB_USERNAME=your_cpanel_db_user
DB_PASSWORD=your_cpanel_db_password

CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database
LOG_CHANNEL=stack
LOG_LEVEL=error
```

Notes:

- `APP_DEBUG` must be `false`
- use the real domain in `APP_URL`
- `file` drivers are usually simpler on shared hosting than Redis

## 5. Best Deployment Structure

Best option:

- put the Laravel app in a folder outside `public_html`
- point the domain or subdomain document root to the app's `public` folder

Example structure:

```text
/home/USERNAME/settleanz-app
/home/USERNAME/settleanz-app/public
```

Then set the document root to:

```text
/home/USERNAME/settleanz-app/public
```

This is the cleanest and safest Laravel setup on cPanel.

## 6. If cPanel Does Not Let You Point To `public/`

Fallback option:

- upload the Laravel app outside `public_html`, for example `/home/USERNAME/settleanz-app`
- copy the contents of `public/` into `/home/USERNAME/public_html`
- edit `public_html/index.php` so the paths point to the real app folder

Example path changes inside `public_html/index.php`:

```php
require __DIR__.'/../settleanz-app/vendor/autoload.php';
$app = require_once __DIR__.'/../settleanz-app/bootstrap/app.php';
```

The exact folder name must match your hosting path.

## 7. File Upload Checklist

Upload these project parts:

- `app`
- `bootstrap`
- `config`
- `database`
- `public`
- `resources`
- `routes`
- `storage`
- `vendor`
- `.env`
- `artisan`
- `composer.json`
- `composer.lock`

Do not upload:

- `node_modules`
- local cache junk
- development-only files you do not need

## 8. First Commands On The Server

If SSH or Terminal is available in cPanel, run:

```bash
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

If the app key is already set in `.env`, you do not need to generate it again.

## 9. Permissions

Typical safe permissions:

- folders `755`
- files `644`
- `storage` and `bootstrap/cache` must be writable

If Laravel shows permission errors, fix those two areas first.

## 10. Practical Recommendation For This Project

For SettleANZ, use this workflow:

1. develop locally in WSL
2. build frontend assets locally with Node 22
3. use MySQL for production on cPanel
4. upload the full Laravel app with built assets
5. point the domain to the `public` directory if cPanel allows it

That will keep the project much more stable than trying to build on the hosting server.
