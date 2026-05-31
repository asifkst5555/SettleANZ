# Image Width/Height Audit Report

## Scope
All 49 Blade files audited (public + admin views, components).  
**37 `<img>` tags** found in 14 public-view files.  
**4 `<img>` tags** found in 2 admin files (excluded — JS-previews/`display:none`).  

## Summary

| Status | Count |
|--------|-------|
| ✅ Have `width` + `height` | **23** |
| ❌ Missing `width` + `height` | **7** (all dynamic — cannot determine statically) |
| — Fixed this session | **1** |

---

## Fixed This Session

| File | Line | Image | Width | Height |
|------|------|-------|-------|--------|
| `guides/new-to-australia.blade.php` | 1222 | `storage/blog/Moving Checklist...` | 1500 | 1000 |

---

## Already Had Width + Height (23 images)

| File | Line | Width | Height |
|------|------|-------|--------|
| `about.blade.php` | 351 | 666 | 1000 |
| `blog/index.blade.php` | 244 | 1000 | 530 |
| `contact.blade.php` | 313 | 500 | 316 |
| `guides/new-to-australia.blade.php` | 1171 (hero) | 800 | 655 |
| `guides/new-to-australia.blade.php` | 1198 | 700 | 528 |
| `guides/new-to-australia.blade.php` | 1536 | 600 | 650 |
| `guides/settlement-services.blade.php` | 907 (hero) | 600 | 600 |
| `guides/settlement-services.blade.php` | 1116 | 600 | 450 |
| `home.blade.php` | 1084 | 400 | 774 |
| `home.blade.php` | 1105 | 400 | 774 |
| `home.blade.php` | 1126 | 400 | 774 |
| `home.blade.php` | 1147 | 400 | 774 |
| `home.blade.php` | 1170 | 500 | 500 |
| `home.blade.php` | 1254 | 300 | 300 |
| `home.blade.php` | 1269 | 300 | 300 |
| `home.blade.php` | 1284 | 300 | 300 |
| `home.blade.php` | 1299 | 300 | 300 |
| `home.blade.php` | 1352 | 250 | 62 |
| `home.blade.php` | 1353 | 700 | 239 |
| `home.blade.php` | 1354 | 330 | 56 |
| `home.blade.php` | 1355 | 424 | 223 |
| `home.blade.php` | 1356 | 330 | 121 |
| `layouts/app.blade.php` | 1124 | 56 | 56 |
| `layouts/app.blade.php` | 1128 | 56 | 56 |

(Partner logo duplicates at lines 1359–1363 also have width/height — same files as 1352–1356.)

---

## Cannot Fix — Dynamic Images (7)

These reference database records (`$post->image_url`, `$listing->logo`, `$package['image']`).  
Server-side dimension detection or DB columns would be needed.

| File | Line | Source |
|------|------|--------|
| `blog/show.blade.php` | 959 | `$post->image_url` |
| `blog/show.blade.php` | 1052 | `$featuredRecent->image_url` |
| `blog/show.blade.php` | 1072 | `$recentPost->image_url` |
| `blog/index.blade.php` | 276 | `$post->image_url` |
| `directory/show.blade.php` | 13 | `$listing->logo` |
| `guides/settlement-services.blade.php` | 1041 | `$package['image']` |
| `home.blade.php` | 1320 | `$post->image_url` |

---

## Excluded From Audit

| Reason | Count | Files |
|--------|-------|-------|
| Admin area (previews, hidden) | 4 | `admin/blog-posts/partials/form.blade.php` (2), `admin/seo/edit.blade.php` (2) |
| SVGs only (no `<img>`) | — | `directory/partials/listing-icon.blade.php`, orphan guide pages, legal pages, doc pages |

---

## CLS Improvement

- Before: **2 images** had explicit dimensions (the two floating icons at 56×56)
- After: **23 images** have explicit dimensions
- Dynamic images (7) remain at risk — suggest adding `getimagesize()` or storing dimensions in DB on upload
