# SettleANZ

Laravel 13 starter project for the SettleANZ website.

For a quick Linux/WSL server run guide, see `RUN_SERVER_WSL.md`.
For one-command server startup, run `bash scripts/run-server.sh` from the project root.

This repository has been scaffolded in WSL and is ready for the first build phase. The uploaded brief describes a content-heavy migration and relocation website with guides, directory listings, lead capture, affiliate content, SEO requirements, and partner integrations.

## Recommended Stack

- Laravel 13
- Blade for the marketing site and content templates
- MySQL or MariaDB for production
- SQLite for simple local development if you want it
- Vite for frontend assets
- Tailwind CSS for fast implementation of the design system
- Filament or a custom admin panel for managing pages, articles, listings, guides, and leads

## cPanel-Friendly Approach

This project is being prepared for shared hosting and cPanel deployment.

Key rules:

- build frontend assets locally with Node 22 before upload
- upload compiled `public/build` assets to the server
- use MySQL in production, not SQLite
- keep the Laravel app outside `public_html` when possible
- expose only the `public` directory to the web

See [docs/cpanel-deployment.md](/home/asifk/projects/SettleANZ/docs/cpanel-deployment.md) for the deployment guide.

Quick packaging command:

```bash
cd /home/asifk/projects/SettleANZ
bash scripts/cpanel-package.sh
```

That generates a deployment bundle in `deploy/cpanel`.

## Local Setup

Use the WSL terminal, not Windows Composer, because this project lives in `/home/asifk/projects/SettleANZ`.

```bash
cd /home/asifk/projects/SettleANZ
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
npm run dev
php artisan serve
```

## Important Note For VS Code

Open the folder through WSL in VS Code:

```bash
code /home/asifk/projects/SettleANZ
```

If you open the project from `\\wsl$...` and use Windows PHP or Composer, you may hit path issues.

## Suggested Build Phases

1. Set up the design system, layouts, and shared components.
2. Build the public pages: home, guides, housing, banking, migration, contact.
3. Create admin-managed content models for articles, listings, partners, and lead forms.
4. Add SEO fields, social metadata, schema-ready content blocks, and affiliate link handling.
5. Add external integrations like HubSpot, WhatsApp click-to-chat, and chat widgets.

## Next Documents

- [docs/implementation-guide.md](/home/asifk/projects/SettleANZ/docs/implementation-guide.md)
- [docs/cpanel-deployment.md](/home/asifk/projects/SettleANZ/docs/cpanel-deployment.md)
