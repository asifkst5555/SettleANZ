# Navigation Guidelines

This project currently keeps some public pages live but intentionally hides them from the header navigation.

## Current hidden header items

- `Housing` -> `/housing`
- `Banking` -> `/banking`
- `Migration` -> `/migration-services`

These pages and routes are still present in the codebase and should not be deleted unless there is a product decision to retire them fully.

## How header visibility works

Header items are defined in `app/Support/SiteDefaults.php`.

Each item supports:

- `label`
- `href`
- `visible`

The layout receives navigation links through:

- `SiteDefaults::visibleNavItems()`

That method filters `navItems()` and shows only items where `visible` is `true`.

## How to re-enable hidden pages later

1. Open `app/Support/SiteDefaults.php`.
2. Find the relevant item inside `navItems()`.
3. Change `visible` from `false` to `true`.
4. Reload the site and confirm both desktop and mobile navigation.

## Why this approach

- Keeps routes and templates intact for future releases.
- Avoids deleting content that may be reused in the next version.
- Keeps header behavior consistent across desktop and mobile menus.
- Makes future enable/disable changes low-risk and easy to understand.
