# Laravel Structure Migration Notes

This repository was restored to the official Laravel deployment layout.

## What changed

- Restored the Laravel root structure under the project root.
- Kept all web assets under `public/` only.
- Removed the Hostinger `public_html` fallback flow.
- Removed runtime path detection and custom web-root detection.
- Standardized blog uploads on `Storage::disk('public')`.
- Restored `storage/` as a real directory with Laravel ignore files.
- Added the missing `config/view.php` file.
- Replaced the old deployment helper with `deployment.sh`.
- Removed Hostinger-specific packaging and fallback documentation.

## Current deployment flow

1. Upload the Laravel project as a normal Laravel application.
2. Point the web root to `public/`.
3. Run `deployment.sh` on the server.
4. Ensure `public/storage` is the only symlink.

## Notes

- `storage/` must remain a real directory.
- `bootstrap/cache/` must remain writable.
- All cached or generated framework files should stay out of version control.