# SEO Fix Report — SettleANZ

## Overview

This report documents SEO fixes for duplicate page titles, duplicate meta descriptions, and pagination SEO issues found during an SEO audit of SettleANZ.

## Files Modified

| File | Change |
|---|---|
| `app/Http/Controllers/BlogController.php` | Added pagination-aware metadata generation in `index()` |
| `app/Http/Controllers/DirectoryController.php` | Added pagination-aware metadata generation in `index()` |

No other files required changes. The layout (`resources/views/layouts/app.blade.php`) already handles SEO rendering correctly — it was only missing pagination-aware data from controllers.

---

## Duplicate Title Issues Found

### Root Cause

The controllers for `/blog` and `/directory` hardcoded static title and meta description strings. When search engines or users visited paginated URLs like `/blog?page=2` or `/directory?page=3`:

- Same `<title>` was rendered for all pages
- Same `<meta name="description">` was rendered for all pages
- Canonical tag fell back to `request()->url()` (no query string), pointing paginated pages to page 1

### Example Before Fix

```
URL: /blog
<title>Australia Expat Blog | Practical Guides & Real Experiences</title>

URL: /blog?page=2
<title>Australia Expat Blog | Practical Guides & Real Experiences</title>  ← DUPLICATE
```

### Example After Fix

```
URL: /blog
<title>Australia Expat Blog | Practical Guides & Real Experiences</title>

URL: /blog?page=2
<title>Australia Expat Blog | Practical Guides & Real Experiences - Page 2</title>  ← UNIQUE

URL: /blog?page=3
<title>Australia Expat Blog | Practical Guides & Real Experiences - Page 3</title>  ← UNIQUE
```

---

## Pagination Fix Details

### BlogController (`app/Http/Controllers/BlogController.php:16-42`)

**Before:**
```php
public function index(): View
{
    $posts = $this->posts();

    $seo = Schema::hasTable('page_seo') ? PageSeo::forPage('blog') : null;

    return view('blog.index', [
        'metaTitle'      => $seo?->meta_title       ?: 'The SettleANZ Blog...',
        'metaDescription'=> $seo?->meta_description ?: 'Practical guides...',
        'metaCanonical'  => $seo?->canonical_url    ?: null,
        // ...
    ]);
}
```

**After:**
```php
public function index(): View
{
    $posts = $this->posts();
    $page = max(1, (int) request()->input('page', 1));

    $seo = Schema::hasTable('page_seo') ? PageSeo::forPage('blog') : null;

    $metaTitle = $seo?->meta_title ?: 'The SettleANZ Blog...';
    $metaDescription = $seo?->meta_description ?: 'Practical guides...';

    if ($page > 1) {
        $metaTitle .= ' - Page ' . $page;
        $metaDescription = rtrim(rtrim($metaDescription, '.'), '.') . '. Page ' . $page . '.';
    }

    return view('blog.index', [
        'metaTitle'      => $metaTitle,
        'metaDescription'=> $metaDescription,
        'metaCanonical'  => $page > 1
            ? request()->url() . '?page=' . $page
            : ($seo?->canonical_url ?: null),
        // ...
    ]);
}
```

### DirectoryController (`app/Http/Controllers/DirectoryController.php:17-38`)

**Before:**
```php
public function index(): View
{
    $listings = $this->listings();

    return view('directory.index', [
        'metaTitle' => 'Find Trusted Expat Services...',
        'metaDescription' => 'Curated expat-friendly businesses...',
        // ...
    ]);
}
```

**After:**
```php
public function index(): View
{
    $listings = $this->listings();
    $page = max(1, (int) request()->input('page', 1));

    $metaTitle = 'Find Trusted Expat Services...';
    $metaDescription = 'Curated expat-friendly businesses...';

    if ($page > 1) {
        $metaTitle .= ' - Page ' . $page;
        $metaDescription = rtrim(rtrim($metaDescription, '.'), '.') . '. Page ' . $page . '.';
    }

    return view('directory.index', [
        'metaTitle' => $metaTitle,
        'metaDescription' => $metaDescription,
        'metaCanonical' => $page > 1
            ? request()->url() . '?page=' . $page
            : null,
        // ...
    ]);
}
```

---

## Canonical URL Improvements

### Before

- Canonical fell back to `request()->url()` (no query string)
- `/blog?page=2` canonical → `https://settleanz.com/blog` (pointed to page 1)
- `/directory?page=3` canonical → `https://settleanz.com/directory` (pointed to page 1)

### After

- Page 1 canonical → Current URL without query params (or admin-configured canonical)
- Page 2+ canonical → Current URL with `?page=N`
- `/blog?page=2` canonical → `https://settleanz.com/blog?page=2` (self-canonicalizes)
- `/directory?page=3` canonical → `https://settleanz.com/directory?page=3` (self-canonicalizes)

The layout already handles canonical correctly:
```php
$seoCanonical = $metaCanonical ?? request()->url();
```

By passing an explicit `$metaCanonical` for paginated pages, we ensure each page self-canonicalizes.

---

## Meta Description Fix

### Before

```
Page 1: Practical guides, honest advice, and real insights for expats in Australia and New Zealand.
Page 2: Practical guides, honest advice, and real insights for expats in Australia and New Zealand.  ← DUPLICATE
```

### After

```
Page 1: Practical guides, honest advice, and real insights for expats in Australia and New Zealand.
Page 2: Practical guides, honest advice, and real insights for expats in Australia and New Zealand. Page 2.
Page 3: Practical guides, honest advice, and real insights for expats in Australia and New Zealand. Page 3.
```

---

## H1 Tag Verification

All public-facing views were inspected and confirmed to have exactly **one** `<h1>` tag:

| Page | H1 Content |
|---|---|
| `/` | Landing in a New Country Shouldn't Feel Like Guessing Every Step! |
| `/blog` | Your Guide to a Better Life in Australia |
| `/blog/{slug}` | `{{ $post->title }}` (dynamic, per-article) |
| `/directory` | Find trusted expat services in Australia |
| `/directory/{slug}` | `{{ $listing->name }}` (dynamic, per-listing) |
| `/new-to-australia` | Just Arrived in Australia? |
| `/settlement-services` | The only independent concierge for new arrivals in Australia |
| `/housing` | Finding a Home in Australia as an Expat |
| `/banking` | Banking in Australia as an Expat - The Complete Guide |
| `/migration-services` | Australian Visa Help for Expats & Migrants |
| `/about` | I Built the Guide I Wish I Had in 2001 |
| `/contact` | Get in Touch |
| `/privacy-policy` | Privacy Policy |
| `/terms-of-service` | Terms of Service |

**No duplicate H1 issues found.** The main layout (`resources/views/layouts/app.blade.php`) contains no `<h1>` tags — only `<h2>` and `<h3>` elements for UI components (chat panel, modals, footer).

---

## Blog Post SEO Verification

Each blog post has per-article SEO fields stored in the `blog_posts` database table:

| Field | Source |
|---|---|
| `meta_title` | Per-article or falls back to `{{ $post->title }} | SettleANZ Blog` |
| `meta_description` | Per-article or falls back to `$post->excerpt` |
| `canonical_url` | Per-article or falls back to `route('blog.show', $post->slug)` |
| `og_title` / `og_description` / `og_image` | Per-article or auto-resolved |

Each blog article page renders:
- Unique `<title>` (from `meta_title` or `$post->title`)
- Unique `<meta description>` (from `meta_description` or `$post->excerpt`)
- Unique `<link rel="canonical">` (from `canonical_url` or article route)
- Unique `<h1>` (from `$post->title`)

---

## Metadata Generation Architecture

The SEO metadata flow:

```
Database (page_seo / blog_posts)
    |
    v
Controller / Route Closure
  - PageController::seo('pageKey', $defaultTitle, $defaultDesc)
  - BlogController::index() -> PageSeo::forPage('blog')
  - BlogController::show()  -> $post->meta_title (per-article)
  - Route closures          -> PageSeo::forPage('new-to-australia')
  - DirectoryController     -> hardcoded strings
    |
    v
resources/views/layouts/app.blade.php  (lines 7-21)
  - Computes $seoTitle, $seoDescription, $seoCanonical, etc.
  - Renders: <title>, <meta description>, <link canonical>,
             Open Graph, Twitter Card, JSON-LD
```

### Controllers using database-backed SEO (`PageSeo` model)

| Route | Controller Method | Page Key |
|---|---|---|
| `/` | `PageController::home()` | `home` |
| `/settlement-services` | `PageController::settlementServices()` | `settlement-services` |
| `/migration-services` | `PageController::migrationServices()` | `migration-services` |
| `/contact` | `PageController::contact()` | `contact` |
| `/about` | `PageController::about()` | `about` |
| `/blog` | `BlogController::index()` | `blog` |

### Controllers using inline hardcoded strings

| Route | Controller |
|---|---|
| `/directory` | `DirectoryController::index()` |
| `/directory/{slug}` | `DirectoryController::show()` |
| `/housing` | Route closure |
| `/banking` | Route closure |

### Routes with inline closures using database-backed SEO

| Route | Page Key |
|---|---|
| `/new-to-australia` | `new-to-australia` |
| `/privacy-policy` | `privacy-policy` |
| `/terms-of-service` | `terms-of-service` |

---

## Additional SEO Recommendations

### 1. Add `rel="next"` / `rel="prev"` (optional)
While Google deprecated `rel=next/prev` in 2019, some search engines still support it. Self-canonicalization (implemented above) is now the recommended approach.

### 2. Add paginated URLs to sitemap
Consider adding paginated blog/directory URLs to the sitemap (`SeoAssetController.php`) to help search engines discover paginated content.

### 3. Add `?page=N` to Open Graph URLs for paginated pages
The OG URL currently inherits from `$seoCanonical`, so this is already handled.

### 4. Consider actual pagination
The blog page fetches all posts with `->get()` and uses client-side "Load More" with `is-hidden` CSS. For large datasets, consider switching to server-side pagination with `->paginate()`.

### 5. Add PageSeo support to DirectoryController
The directory listing page uses hardcoded metadata instead of the `PageSeo` database model. Consider refactoring to use `PageSeo::forPage('directory')` for admin-editable SEO.

### 6. 404 on non-existent page parameters
Consider returning 404 for very large page numbers (e.g., `/blog?page=9999`). Currently these render the same content as page 1.
