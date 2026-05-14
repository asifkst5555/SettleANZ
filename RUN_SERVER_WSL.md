# Run Server on WSL (Ubuntu)

Use this when you want to run SettleANZ locally from Linux/WSL.

## One-command start (recommended)

After one-time setup, start the app with:

```bash
cd /home/asifk/projects/SettleANZ && bash scripts/run-server.sh
```

If needed, make it executable once:

```bash
chmod +x /home/asifk/projects/SettleANZ/scripts/run-server.sh
```

## 1) Open Ubuntu and go to project

```bash
cd /home/asifk/projects/SettleANZ
```

## 2) Install PHP 8.3 (one-time)

The project requires PHP 8.3+.

```bash
sudo apt-get update
sudo apt-get install -y software-properties-common ca-certificates lsb-release apt-transport-https
sudo add-apt-repository -y ppa:ondrej/php
sudo apt-get update
sudo apt-get install -y php8.3 php8.3-cli php8.3-common php8.3-mbstring php8.3-xml php8.3-curl php8.3-zip php8.3-sqlite3 php8.3-mysql php8.3-bcmath php8.3-intl php8.3-gd
sudo update-alternatives --set php /usr/bin/php8.3
php -v
```

## 3) Install dependencies (first run or after updates)

```bash
composer install
npm install
```

## 4) Environment and app key (first run)

```bash
cp .env.example .env
php artisan key:generate
```

## 5) Database (SQLite local quick start)

```bash
mkdir -p database
touch database/database.sqlite
php artisan migrate
```

## 6) Start dev server

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Open: `http://localhost:8000`

## Optional: frontend watcher in another terminal

```bash
cd /home/asifk/projects/SettleANZ
npm run dev
```

## Quick health checks

```bash
php -v
php artisan --version
php artisan about
```

If `artisan serve` fails with platform check errors, re-check `php -v` and confirm it is 8.3+.
