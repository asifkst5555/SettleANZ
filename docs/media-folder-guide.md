# Media Folder Guide

Put website media files inside `public/media`.

Recommended structure:
- `public/media/brand/logos`
  Use for the main SettleANZ logo, alternate logo versions, favicon source files.
- `public/media/hero`
  Use for homepage hero images and page header images.
- `public/media/founder`
  Use for founder photos and bio/profile images.
- `public/media/guides/blog`
  Use for blog card thumbnails and blog cover images.
- `public/media/guides/articles`
  Use for article inline images and guide-specific visuals.
- `public/media/partners/logos`
  Use for partner and affiliate logos.
- `public/media/directory/listings`
  Use for directory business logos and listing gallery images.
- `public/media/icons`
  Use only for custom brand icons if needed.
- `public/media/uploads`
  Use as a general holding folder for files you have not sorted yet.

Suggested file naming:
- lowercase only
- use hyphens instead of spaces
- examples:
  - `settleanz-logo-primary.png`
  - `homepage-hero-sydney.jpg`
  - `founder-asifk.jpg`
  - `wise-logo.svg`
  - `southern-cross-migration-logo.png`

Important:
- Anything inside `public/` is directly accessible by URL.
- Example URL:
  `/media/hero/homepage-hero-sydney.jpg`
- For Blade templates, use:
  `{{ asset('media/hero/homepage-hero-sydney.jpg') }}`
