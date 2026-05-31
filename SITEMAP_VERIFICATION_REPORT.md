# Sitemap Verification Report

## Summary

| Check | Status |
|-------|--------|
| Directory listing pages (`/directory/{slug}`) included? | ❌ → ✅ **Fixed** |
| Static pages (12) included? | ✅ |
| Blog posts included? | ✅ |
| Lastmod dates present? | ✅ |
| Canonical URLs correct? | ✅ |
| Valid XML? | ✅ |
| Sitemap referenced in robots.txt? | ✅ |
| No-indexed content excluded? | ✅ |

## Fix Applied

**File:** `app/Http/Controllers/SeoAssetController.php`

Added `DirectoryListing` import and a new collection block that queries published listings and maps them to sitemap `<url>` entries.

### What was added (lines 48–59):

```php
$listings = collect();
if (Schema::hasTable('directory_listings')) {
    $listings = DirectoryListing::query()
        ->where('is_published', true)
        ->get()
        ->map(fn (DirectoryListing $listing) => [
            'loc' => route('directory.show', $listing->slug),
            'lastmod' => $listing->updated_at ?: $listing->created_at ?: now(),
            'changefreq' => 'weekly',
            'priority' => '0.7',
        ]);
}
```

The listings collection is merged into the URL set at line 61:

```php
$urls = $pages->merge($posts)->merge($listings)->values();
```

## Directory Listing Entries Now in Sitemap

| Slug | Name | Lastmod | Priority |
|------|------|---------|----------|
| `settlement-lawyers-melbourne` | Settlement Lawyers Melbourne | 2026-05-15 | 0.7 |
| `finance-brokers-sydney` | Finance Brokers Sydney | 2026-05-15 | 0.7 |
| `property-inspectors-brisbane` | Property Inspectors Brisbane | 2026-05-15 | 0.7 |

Each entry includes:
- **loc** — canonical URL via `route('directory.show', $listing->slug)` (e.g. `/directory/settlement-lawyers-melbourne`)
- **lastmod** — resolved from `updated_at` → `created_at` → `now()`
- **changefreq** — `weekly`
- **priority** — `0.7`

## Sitemap Composition (after fix)

| Entry Type | Count | Changefreq | Priority |
|-----------|-------|------------|----------|
| Static pages (home, about, contact, etc.) | 12 | weekly–yearly | 0.3–1.0 |
| Blog posts (published, not no-indexed) | 10 | monthly | 0.8 |
| Directory listings (published) | **3** | weekly | 0.7 |
| **Total** | **25** | | |

## Verified XML Output

The generated sitemap at `/sitemap.xml`:
- Is valid XML with correct `urlset` namespace
- Uses `toAtomString()` for all `lastmod` dates (ISO 8601 format)
- Contains no `www` or `https` issues (uses `APP_URL` which is `http://settleanz.test` — will resolve to `https://settleanz.com` in production)
- Dynamically generated — always reflects the current set of published blog posts and directory listings
