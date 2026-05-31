# Internal Linking Audit Report

## Summary

| Page | Route | Inbound Links Before | Inbound Links After | Change |
|------|-------|---------------------|---------------------|--------|
| `/housing` | `guides.housing` | **0** | **6** | +6 |
| `/banking` | `guides.banking` | **0** | **7** | +7 |
| `/migration-services` | `guides.migration-services` | **0** | **4** | +4 |

All three pages were confirmed orphans — zero internal inbound links from any public page, despite being indexed in the sitemap.

---

## Changes Made

### 1. Footer — `layouts/app.blade.php` (lines 986–993)

Added all three pages to the "Quick Links" footer list.

| File | Line | Link |
|------|------|------|
| `layouts/app.blade.php` | 991 | `<a href="/housing">Housing Guide</a>` |
| `layouts/app.blade.php` | 992 | `<a href="/banking">Banking Guide</a>` |
| `layouts/app.blade.php` | 993 | `<a href="/migration-services">Migration Services</a>` |

---

### 2. Home Page Topic Guides Section — `home.blade.php` (new section)

Added a "Topic Guides" strip with button-style links to all five guide pages.

| File | Line | Links |
|------|------|-------|
| `home.blade.php` | 1348–1358 | New to Australia, Settlement Services, **Housing Guide**, **Banking Guide**, **Migration Services** |

---

### 3. New to Australia Guide — `guides/new-to-australia.blade.php`

| Line | Context | Added Link |
|------|---------|-----------|
| 1244 | "Pro tip: Need short-term accommodation…" | → `/housing` |
| 1374 | "Open a bank account" — "Most banks require your TFN…" | → `/banking` |
| 1520 | FAQ — "How long does it take to get permanent residency?" | → `/migration-services` |

---

### 4. Settlement Services — `guides/settlement-services.blade.php`

| Line | Context | Added Link |
|------|---------|-----------|
| 943 | "Settle into daily life" — "rental search" | → `/housing` |
| 943 | "Settle into daily life" — "banking order" | → `/banking` |

---

### 5. Housing Guide — `guides/housing.blade.php`

| Line | Context | Added Link |
|------|---------|-----------|
| 49 | "How Australian Renting Works" — "bank statements" | → `/banking` |
| 112 | "Next Steps" section (new) | → `/banking`, `/migration-services` |

---

### 6. Banking Guide — `guides/banking.blade.php`

| Line | Context | Added Link |
|------|---------|-----------|
| 99 | "Next Steps" section (new) | → `/housing` |

---

### 7. Migration Services — `guides/migration-services.blade.php`

| Line | Context | Added Link |
|------|---------|-----------|
| 136 | "Next Steps" section (new) | → `/housing`, `/banking` |

---

## Inbound Link Map

### `/housing` — 6 inbound links

| Source Page | Context |
|------------|---------|
| Footer (every page) | Quick Links |
| Home page | Topic Guides section |
| New to Australia guide | Pro tip → short-term accommodation |
| Settlement services | "rental search" in overview |
| Banking guide | Next Steps section |
| Migration services | Next Steps section |

### `/banking` — 7 inbound links

| Source Page | Context |
|------------|---------|
| Footer (every page) | Quick Links |
| Home page | Topic Guides section |
| New to Australia guide | "Open a bank account" step |
| Settlement services | "banking order" in overview |
| Housing guide | "bank statements" in rental section |
| Housing guide | Next Steps section |
| Migration services | Next Steps section |

### `/migration-services` — 4 inbound links

| Source Page | Context |
|------------|---------|
| Footer (every page) | Quick Links |
| Home page | Topic Guides section |
| Housing guide | Next Steps section |
| New to Australia guide | FAQ → visa pathways |

---

## Outbound Links (from each orphan page)

| Page | Links To |
|------|---------|
| `/housing` | `/banking`, `/migration-services` |
| `/banking` | `/new-to-australia`, `/housing` |
| `/migration-services` | `/housing`, `/banking` |

All three pages now form a bidirectional cross-linking triangle with each other, plus inbound links from the footer, home page, new-to-australia guide, and settlement-services page.

---

## Files Modified

| File | Changes |
|------|---------|
| `resources/views/layouts/app.blade.php` | +3 footer links |
| `resources/views/home.blade.php` | +1 new "Topic Guides" section with 5 button links |
| `resources/views/guides/new-to-australia.blade.php` | +3 contextual links (housing, banking, migration) |
| `resources/views/guides/settlement-services.blade.php` | +2 contextual links (rental search → housing, banking order → banking) |
| `resources/views/guides/housing.blade.php` | +1 contextual link (bank statements → banking), +1 new Next Steps section |
| `resources/views/guides/banking.blade.php` | +1 new Next Steps section (→ housing) |
| `resources/views/guides/migration-services.blade.php` | +1 new Next Steps section (→ housing, banking) |
