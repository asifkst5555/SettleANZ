# SettleANZ Implementation Guide

## 1. What The Brief Is Asking For

The uploaded brief is for a polished content and lead-generation platform, not just a few static pages. It includes:

- a homepage with multiple marketing sections
- long-form guide pages with table of contents and inline CTAs
- affiliate-focused comparison content
- a business directory and featured partners
- migration and contact lead forms
- strong SEO structure
- mobile-first design rules

## 2. Best Laravel Approach

Use Laravel as the application core and split the site into these content areas:

- `Pages`
  Static or semi-static pages such as home, contact, and service landing pages.
- `Guides`
  Structured editorial pages like New to Australia, Housing Guide, Banking Guide.
- `Articles`
  SEO blog posts like money transfer and expat insurance articles.
- `Listings`
  Directory entries for migration agents, relocation companies, and service partners.
- `Lead Forms`
  Captured enquiries from popup forms, contact forms, eligibility checks, and CTA strips.
- `Partners`
  Featured businesses shown on guides and landing pages.

## 3. Recommended Database Models

Start with these Laravel models:

- `Page`
- `Guide`
- `Article`
- `Category`
- `Listing`
- `Partner`
- `Lead`
- `Faq`
- `ComparisonTable`

Helpful extras later:

- `Author`
- `Tag`
- `AffiliateLink`
- `MediaAsset`
- `Testimonial`

## 4. Suggested Routes

Keep routes readable and SEO-friendly:

```php
Route::get('/', HomeController::class);
Route::get('/new-to-australia', GuideController::class);
Route::get('/housing-guide', [GuideController::class, 'housing']);
Route::get('/banking-guide', [GuideController::class, 'banking']);
Route::get('/migration-services', [PageController::class, 'migration']);
Route::get('/contact', [PageController::class, 'contact']);

Route::get('/articles/{slug}', [ArticleController::class, 'show']);
Route::get('/directory/{slug}', [ListingController::class, 'show']);

Route::post('/leads/contact', [LeadController::class, 'contact']);
Route::post('/leads/visa-check', [LeadController::class, 'visaCheck']);
Route::post('/leads/popup', [LeadController::class, 'popup']);
```

## 5. Blade Component Plan

Create reusable Blade components for:

- navigation bar
- footer
- CTA strip
- article hero
- sticky table of contents
- comparison table
- featured partner cards
- lead capture form
- WhatsApp floating button
- trust strip
- author bio

This will keep the homepage, guides, and articles consistent.

## 6. Admin Panel Recommendation

For this brief, a CMS-style admin is important. The cleanest route is:

- use Filament if you want faster content management
- use custom Laravel CRUD only if you want full control and do not mind slower setup

Filament is a strong fit because you will need to manage:

- guide content
- article SEO fields
- listing details
- featured partner selections
- FAQ blocks
- lead records

## 7. Frontend Guidance From The Brief

The design system from the brief should become shared CSS tokens:

- primary teal
- sand neutral
- burnt orange CTA color `#E8773A`
- card radius `12px`
- button radius `8px`
- input radius `6px`
- subtle shadows only
- max content width `1180px`
- strong mobile-first spacing rules

Build a layout system first, then page sections.

## 8. SEO And Content Requirements

The brief has heavy SEO requirements. Plan for these fields on pages and articles:

- meta title
- meta description
- Open Graph title
- Open Graph description
- featured image
- canonical URL
- noindex toggle
- reading time
- last updated date
- FAQ content
- affiliate disclosure text

Also prepare structured content areas for:

- FAQ schema
- article schema
- internal linking
- social share buttons

## 9. Third-Party Integrations To Plan Early

These should be abstracted behind services or config values:

- HubSpot form submission
- WhatsApp click-to-chat links
- Tidio chat widget
- Google Maps embed
- external booking widgets
- affiliate links with tracking and target blank behavior

## 10. Practical Build Order

### Phase 1

- finalize Laravel setup
- install frontend dependencies
- choose Tailwind plus Blade component structure
- set up base layout and theme tokens

### Phase 2

- build homepage sections from the brief
- build guide template with sticky table of contents
- build article template with affiliate blocks

### Phase 3

- create content models and migrations
- add admin panel
- seed sample content

### Phase 4

- add lead forms
- connect HubSpot
- add partner cards and directory pages

### Phase 5

- add SEO controls
- optimize images and Core Web Vitals
- test mobile breakpoints from the brief

## 11. My Recommendation For Your Next Step

The best next implementation step is:

1. install frontend packages
2. set up Tailwind-based design tokens
3. build the shared layout and homepage shell
4. then add the CMS/data layer

That order gives you visible progress quickly without locking the wrong database structure too early.
