# SettleANZ — Brand Guidelines

**Version:** 1.0  
**Date:** June 2026  
**Prepared for:** SettleANZ  
**Document Type:** Brand Identity & Design System Reference

---

## Table of Contents

1. [Brand Identity](#1-brand-identity)
2. [Logo Guidelines](#2-logo-guidelines)
3. [Color Palette](#3-color-palette)
4. [Typography](#4-typography)
5. [UI Components](#5-ui-components)
6. [Spacing System](#6-spacing-system)
7. [Responsive Design](#7-responsive-design)
8. [Iconography](#8-iconography)
9. [Imagery Style](#9-imagery-style)
10. [Accessibility](#10-accessibility)
11. [Technical Design System](#11-technical-design-system)
12. [Design Principles](#12-design-principles)

---

## 1. Brand Identity

### 1.1 Company Name
**SettleANZ**

A migration and relocation guidance platform for people building a new life in Australia and New Zealand.

### 1.2 Brand Positioning
SettleANZ is the practical, warm, and human alternative to generic government checklists and impersonal relocation agencies. It exists to help new arrivals skip the expensive, stressful mistakes of the first year by providing clear, step-by-step guidance rooted in lived experience.

**Tagline:** *Warm, practical migration and relocation guidance for people building a new life in Australia and New Zealand.*

### 1.3 Brand Personality

| Trait | Description |
|-------|-------------|
| **Warm** | Empathetic, approachable, human-first tone. Not corporate or cold. |
| **Practical** | Actionable, clear, step-by-step. No fluff or theory. |
| **Trustworthy** | Lived experience backed. Honest about costs and challenges. |
| **Encouraging** | Hopeful without being naive. "You can do this — here is how." |
| **Professional** | Polished design, reliable content, well-structured systems. |

### 1.4 Visual Style
- Clean, modern, and accessible.
- Teal-dominant with warm orange accents.
- Light, airy backgrounds (sand/cream tone).
- Rounded corners on cards and buttons.
- Soft shadow layering for depth.
- Minimalist with generous whitespace.
- Photography features real people and authentic settings.

### 1.5 Design Principles
1. **Clarity First** — Every element serves a purpose. No decorative excess.
2. **Human Warmth** — Colors, imagery, and tone should feel welcoming, not institutional.
3. **Consistency** — Reusable components, predictable patterns, systematic spacing.
4. **Mobile-First Responsiveness** — Every page works beautifully on any device.
5. **Accessibility** — Contrast ratios, font sizes, and interactions meet WCAG standards.

---

## 2. Logo Guidelines

### 2.1 Main Logo
The SettleANZ brand is represented as wordmark text using the brand name **SettleANZ** styled in the *Plus Jakarta Sans* font family. No graphic/symbol mark has been identified in the current codebase.

**⚠ Inferred: A dedicated SVG logo file is not present in the public assets. The brand is rendered via text in Blade templates.**

**Current implementation (from `resources/views/layouts/app.blade.php:914`):**
```html
<a class="brand" href="/" aria-label="SettleANZ home">
  <span>
    <strong>SettleANZ</strong>
    <small>Settlement & Relocation Support</small>
  </span>
</a>
```

### 2.2 Text Logo Specifications

| Property | Value |
|----------|-------|
| Font | Plus Jakarta Sans Bold (700) |
| Font Size | 30px (desktop), 18px (mobile) |
| Color | `#0b7a75` (default), `#ffffff` (homepage alternative) |
| Subtitle Font | Inter, 12px, `#607080` |
| Letter Spacing | None (default) |

### 2.3 Logo Variations

| Variation | Usage | Color |
|-----------|-------|-------|
| **Default** | Internal pages with white header | `#0b7a75` |
| **Light** | Homepage hero with dark background | `#ffffff` |
| **Footer** | Footer section on dark teal bg | `#ffffff` (via heading) |

### 2.4 Logo Usage Rules
- Do not alter the font, color, or spacing of the wordmark.
- Do not place the logo on busy backgrounds without sufficient contrast.
- Maintain the hierarchical relationship between the brand name and subtitle.

### 2.5 Clear Space Requirements
- Minimum clear space around the logo: **16px** (1rem) on all sides.
- On mobile, reduce to **8px** minimum.

### 2.6 Minimum Size Requirements
- Desktop: **Brand name must never appear smaller than 18px.**
- Mobile: **Brand name must never appear smaller than 16px.**

---

## 3. Color Palette

### 3.1 Primary Colors

| Swatch | Name | HEX | RGB | HSL | Usage |
|--------|------|-----|-----|-----|-------|
| 🟢 | Primary Brand | `#0b7a75` | `rgb(11, 122, 117)` | `hsl(177, 83%, 26%)` | Headings, links, buttons, icons, branding elements |
| 🟢 | Primary Dark | `#065e5b` | `rgb(6, 94, 91)` | `hsl(178, 88%, 20%)` | Footer backgrounds, hover states, dark variants |

### 3.2 Secondary Colors

| Swatch | Name | HEX | RGB | HSL | Usage |
|--------|------|-----|-----|-----|-------|
| 🟠 | CTA Accent | `#e8773a` | `rgb(232, 119, 58)` | `hsl(21, 79%, 57%)` | Primary buttons, badges, active states, highlights |
| 🟠 | Accent Dark | `#f27d2b` | `rgb(242, 125, 43)` | `hsl(25, 88%, 56%)` | Testimonial icon, hover/active variants |
| 🟢 | Light Brand Fill | `#e6f4f3` | `rgb(230, 244, 243)` | `hsl(176, 39%, 93%)` | Icon backgrounds, accent section fills, subtle highlights |

### 3.3 Neutral / Background Colors

| Swatch | Name | HEX | RGB | HSL | Usage |
|--------|------|-----|-----|-----|-------|
| ⚪ | White | `#ffffff` | `rgb(255, 255, 255)` | `hsl(0, 0%, 100%)` | Card backgrounds, main content areas, modal surfaces |
| 🟡 | Page Background (Sand) | `#f5f0e8` | `rgb(245, 240, 232)` | `hsl(37, 39%, 94%)` | Main page background, section backgrounds |
| 🔵 | Dark Navy | `#123247` | `rgb(18, 50, 71)` | `hsl(204, 60%, 17%)` | Homepage header, footer bottom bar |
| ⚪ | Light Gray | `#eef1f2` | `rgb(238, 241, 242)` | `hsl(195, 13%, 94%)` | Partner strip background |

### 3.4 Text Colors

| Swatch | Name | HEX | RGB | HSL | Usage |
|--------|------|-----|-----|-----|-------|
| ⚫ | Body Text | `#2c3a47` | `rgb(44, 58, 71)` | `hsl(209, 23%, 23%)` | Primary body copy |
| 🔘 | Secondary Text | `#607080` | `rgb(96, 112, 128)` | `hsl(210, 14%, 44%)` | Captions, metadata, secondary information |
| 🟢 | Brand Text | `#0b7a75` | `rgb(11, 122, 117)` | `hsl(177, 83%, 26%)` | H3 headings, links |
| 🟢 | Dark Brand Text | `#065e5b` | `rgb(6, 94, 91)` | `hsl(178, 88%, 20%)` | H1, H2 headings |
| ⚪ | Light Text | `#ffffff` | `rgb(255, 255, 255)` | `hsl(0, 0%, 100%)` | Text on dark backgrounds |

### 3.5 Border Colors

| Name | HEX | Usage |
|------|-----|-------|
| Nav Border | `#c8d8d7` | Header bottom border |
| Card Border | `rgba(11, 122, 117, 0.12)` | Card, panel, and container borders |
| Input Border | `rgba(44, 58, 71, 0.16)` | Form input borders |

### 3.6 Status Colors

| Swatch | Name | HEX | RGB | Usage |
|--------|------|-----|-----|-------|
| ✅ | Success | `#10b981` / `#d1fae5` | — | Approval, confirmation notifications |
| ⚠️ | Warning | `#f59e0b` / `#fef3c7` | — | Pending status, cautionary messages |
| ❌ | Error / Danger | `#ef4444` / `#fee2e2` | — | Rejection, delete actions, error states |
| ℹ️ | Info | `#3b82f6` / `#dbeafe` | — | Informational notifications, blue badges |

### 3.7 Color Usage Rules
- **Primary Brand (#0b7a75)** should be the dominant brand color, used for links, icons, and section headings.
- **CTA Accent (#e8773a)** should be reserved for primary call-to-action buttons and highlights only.
- **Page Background (#f5f0e8)** creates the warm, sand-like canvas for the site.
- **White (#ffffff)** surfaces create clean card and content areas on the sand background.
- Avoid using the CTA accent for non-interactive elements to preserve its action-signaling power.

---

## 4. Typography

### 4.1 Font Family

| Font Name | Source | CSS Import | Usage |
|-----------|--------|------------|-------|
| **Plus Jakarta Sans** | Google Fonts | `https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700` | Headings (H1-H3), brand wordmark, buttons |
| **Inter** | Google Fonts | `https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600` | Body text, navigation, small text, form labels |
| **Instrument Sans** | Tailwind config (default) | Part of Tailwind v4 theme | Fallback sans-serif |

**Note:** *Plus Jakarta Sans* and *Inter* are loaded via Google Fonts with `preconnect` hints for performance.

### 4.2 Heading Hierarchy

| Level | Font | Size (Desktop) | Size (Mobile) | Weight | Line Height | Color | Usage |
|-------|------|---------------|---------------|--------|-------------|-------|-------|
| **H1** | Plus Jakarta Sans | `clamp(3rem, 5vw, 3.5rem)` → up to 56px | `clamp(1.75rem, 8vw, 2.4rem)` | 700 (Bold) | 1.08 | `#065e5b` | Page titles, hero headings |
| **H2** | Plus Jakarta Sans | `clamp(2rem, 3.2vw, 2.25rem)` → up to 36px | `clamp(1.35rem, 6vw, 1.8rem)` | 700 (Bold) | 1.12 | `#065e5b` | Section headings |
| **H3** | Plus Jakarta Sans | `clamp(1.375rem, 2vw, 1.625rem)` → up to 26px | `clamp(1.1rem, 5vw, 1.3rem)` | 600 (Semi Bold) | 1.2 | `#0b7a75` | Card titles, sub-section headings |

### 4.3 Body Text

| Element | Font | Size | Weight | Line Height | Color |
|---------|------|------|--------|-------------|-------|
| Body Copy | Inter | 16px | 400 (Regular) | 1.65 | `#2c3a47` |
| Lead Paragraph | Inter | 18px | 400 (Regular) | 1.75 | `#2c3a47` |
| Small Text | Inter | 14px | 400 (Regular) | 1.4 | `#607080` |
| Caption | Inter | 12-13px | 400-600 | 1.3 | `#607080` |

### 4.4 Special Text Styles

| Element | Font | Size | Weight | Letter Spacing | Color | Usage |
|---------|------|------|--------|----------------|-------|-------|
| **Eyebrow** | Inter | 14px | 600 | `0.08em` | `#0b7a75` | Section labels above headings |
| **Navigation** | Inter | 15px | 500 | — | Inherits | Nav links |
| **Button** | Plus Jakarta Sans | 16px | 600 | — | `#ffffff` | All buttons |
| **Tag** | Inter | 13px | 600 | — | `#0b7a75` | Blog category tags |

### 4.5 Typography Best Practices
- Maximum line length for body text: **62-64 characters** per line.
- Headings use tight letter spacing (`-0.02em` to `-0.055em` on large hero headings).
- Use `clamp()` for responsive font sizing to ensure fluid typography across breakpoints.
- Always include fallback stacks: `'Plus Jakarta Sans', system-ui, -apple-system, sans-serif`

---

## 5. UI Components

### 5.1 Buttons

**Primary Button (`.button`)**

| Property | Value |
|----------|-------|
| Background | `#e8773a` |
| Text Color | `#ffffff` |
| Font Family | Plus Jakarta Sans |
| Font Size | 16px |
| Font Weight | 600 |
| Border Radius | 8px (`--radius-button`) |
| Min Height | 48px |
| Padding | 0.9rem 1.35rem |
| Box Shadow | `0 2px 12px rgba(0,0,0,0.07)` |
| Hover | `filter: brightness(0.96)` |
| Transition | Hover (filter), Background (linear gradient variants) |

**Button Variants**

| Variant | Class | Background | Border | Text Color | Notes |
|---------|-------|------------|--------|------------|-------|
| Default | `.button` | `#e8773a` | None | White | Primary action |
| Small | `.button--small` | Same | — | White | Min-height: 44px |
| Large | `.button--large` | Same | — | White | Min-height: 56px |
| Outline | `.button--outline` | Transparent | `white 0.86 opacity` | White | For dark backgrounds |
| Outline Accent | `.button--outline-accent` | Transparent | `#e8773a` | `#e8773a` | For light/sand backgrounds |
| Contact | `.button--contact` | `#1AA3A3` | `#1AA3A3` | White | Homepage contact CTA |
| Ghost Light | `.button--ghost-light` | `rgba(20,185,176,0.88)` | Same | White | Homepage hero ghost button |

**Homepage Hero Buttons**
- Min Width: 260px, Min Height: 56px
- Full width on mobile (stacked vertically)

**Disabled State:** Not explicitly styled in CSS. Use standard HTML `disabled` attribute.

### 5.2 Cards

**Standard Card Pattern**

| Property | Value |
|----------|-------|
| Background | `#ffffff` |
| Border | `1px solid rgba(11, 122, 117, 0.12)` |
| Border Radius | 12px (`--radius-card`) |
| Box Shadow | `0 2px 12px rgba(0,0,0,0.07)` |
| Padding | 1.5rem |
| Hover (some) | `transform: translateY(-2px)` |

**Card Variants**

| Card Type | Class | Notes |
|-----------|-------|-------|
| Info Card | `.info-card` | Standard information card |
| Service Card | `.service-card` | Service offering cards |
| Guide Card | `.guide-card` | Guide/article cards |
| Trust Pill | `.trust-pill` | Statistics display with icon + text |
| Blog Card | `.blog-card` | Blog post cards with image (border-radius: 24px) |
| Empathy Card | `.empathy-card` | Split layout with media + body (border-radius: 18px) |

### 5.3 Forms & Inputs

**Text Input**

| Property | Value |
|----------|-------|
| Padding | 0.9rem 1rem |
| Border | `1px solid rgba(44, 58, 71, 0.16)` |
| Border Radius | 6px (`--radius-input`) |
| Background | `#ffffff` |
| Text Color | `#2c3a47` |
| Focus Outline | `2px solid rgba(232, 119, 58, 0.2)` |
| Focus Border | `#e8773a` |

**Lead Strip Input (dark background)**

| Property | Value |
|----------|-------|
| Border | `1px solid rgba(255, 255, 255, 0.15)` |
| Min Height | 56px |
| Focus | Same as standard input |

**Select Dropdown (Custom)**

| Property | Value |
|----------|-------|
| Custom JS-enhanced dropdown | Custom `.pro-select-wrapper` component |
| Border Radius | 6px |
| Dropdown Border Radius | 12px |
| Dropdown Shadow | `0 8px 24px rgba(6, 54, 52, 0.12)` |

**Form Layout**
- Lead forms use grid layout with `gap: 1rem`
- Labels are positioned above inputs with `gap: 0.4rem`
- Error messages shown in `#a44e25` at 13px

### 5.4 Tables

**Bank Table Pattern**

| Property | Value |
|----------|-------|
| Border Radius | 12px |
| Header Background | `rgba(230, 244, 243, 0.7)` |
| Header Text | `#065e5b`, 14px, Plus Jakarta Sans |
| Cell Padding | 1rem |
| Cell Border | `1px solid rgba(11, 122, 117, 0.1)` |
| Row Hover | `rgba(245, 240, 232, 0.6)` |
| Min Width | 760px (scrollable on smaller screens) |

**Admin Table Pattern**

| Property | Value |
|----------|-------|
| Header Background | `#f7fbfd` |
| Header Text | `#12384f`, uppercase, 0.78rem |
| Cell Padding | 0.65rem 0.75rem |
| Border Radius | 22px |
| Box Shadow | `0 18px 40px rgba(22,45,62,0.08)` |

### 5.5 Navigation

**Desktop Header**
- Fixed position, z-index: 40
- Height: 88px (min-height)
- Background: `#ffffff`
- Bottom border: `1px solid #c8d8d7`
- Nav links: 15px, 500 weight
- `gap: 1.2rem` between nav items

**Homepage Header**
- Background: `#123247` (dark navy)
- Nav links: `#ffffff`
- Hover: `#e8773a`

**Mobile Navigation**
- Triggered by hamburger menu (`.menu-toggle`)
- Nav becomes fixed full-screen overlay
- Background: `rgba(18, 50, 71, 0.98)` (homepage) or `rgba(255,255,255,0.98)` (internal)
- Links: 18px, 600 weight
- Transition: opacity + transform

**Mobile Nav Drawer**
- Full-screen drawer overlay
- z-index: 11000
- Backdrop: `rgba(5, 16, 24, 0.54)`
- Panel: `rgba(18, 50, 71, 0.98)`
- Links with bottom border separators

**Active Page Indicator**
- Active nav link color: `#f27d2d` (orange)
- Font weight: 700

### 5.6 Footer

| Property | Value |
|----------|-------|
| Background | `#065e5b` |
| Text Color | `rgba(255, 255, 255, 0.92)` |
| Grid | 4-column layout |
| Padding | 48px top, 32px bottom |
| Bottom Bar | `rgba(255, 255, 255, 0.18)` top border |
| Social Icons | Circular, 36px, white border |

### 5.7 Modals

**Lead Capture Modal**

| Property | Value |
|----------|-------|
| Overlay | `rgba(6, 33, 42, 0.6)` |
| Dialog Width | min(100%, 560px) |
| Dialog Background | `#ffffff` |
| Dialog Border Radius | 12px |
| Dialog Shadow | `0 16px 50px rgba(0,0,0,0.22)` |
| Close Button | Circular, 40px, `rgba(11, 122, 117, 0.08)` |

**Form Success/Error Modal**

| Property | Value |
|----------|-------|
| Overlay | `rgba(0, 0, 0, 0.6)` backdrop-filter blur(4px) |
| Dialog | 400px max-width, border-radius 20px |
| Success Icon | Teal gradient circle |
| Error Icon | Red gradient circle |

**Admin Modal**

| Property | Value |
|----------|-------|
| Overlay | `rgba(0, 0, 0, 0.55)` backdrop-filter blur(3px) |
| Dialog | 500px max-width, border-radius 16px |
| Animation | Scale + slide up spring |

### 5.8 Alerts / Notifications

**Toast Notification Pattern** (Admin Panel)

| Type | Background | Icon | Text Color |
|------|-----------|------|------------|
| Success | `#d1fae5 → #a7f3d0` gradient | `#10b981` | `#065f46` |
| Error | `#fee2e2 → #fecaca` gradient | `#ef4444` | `#7f1d1d` |
| Warning | `#fef3c7 → #fde68a` gradient | `#f59e0b` | `#92400e` |
| Info | `#dbeafe → #bfdbfe` gradient | `#3b82f6` | `#0c4a6e` |

**Flash Banner**
- Background: `#0b7a75`
- Text: `#ffffff`
- Padding: 0.85rem 0

### 5.9 Badges

| Badge | Background | Color | Border Radius |
|-------|-----------|-------|---------------|
| Hero Panel Label | `rgba(11, 122, 117, 0.1)` | `#065e5b` | 999px |
| Tag | `rgba(11, 122, 117, 0.08)` | `#0b7a75` | 999px |
| Winner Badge | `#e8773a` | `#ffffff` | 999px |
| Admin Badge | `#e8f5f4` | `#0b7a75` | 999px |

---

## 6. Spacing System

### 6.1 Base Spacing
The CSS uses a rem-based spacing system with `1rem = 16px` as the base.

| Spacing Name | Value | PX Equivalent |
|-------------|-------|--------------|
| XS | 0.5rem | 8px |
| SM | 0.75rem | 12px |
| MD | 1rem | 16px |
| LG | 1.25rem | 20px |
| XL | 1.5rem | 24px |
| 2XL | 2rem | 32px |
| 3XL | 2.5rem | 40px |
| 4XL | 3rem | 48px |
| 5XL | 5rem | 80px |

### 6.2 Section Padding
- Default section padding: **80px 0** (5rem)
- Mobile section padding: **40px 0** (2.5rem)

### 6.3 Container Width
- Max width: **1180px** (`--max-width`)
- Gutter: `calc(100% - 2rem)` on each side (1rem padding)
- Admin and guide pages may use wider containers (up to 74rem / 1184px)

### 6.4 Grid System
Components use CSS Grid with the following column patterns:

| Pattern | Columns | Gap | Usage |
|---------|---------|-----|-------|
| `.card-grid--three` | 3 | 1.25rem | Feature cards |
| `.card-grid--two` | 2 | 1.25rem | Split layouts |
| `.service-icon-grid` | 4 (desktop), 2 (tablet), 1 (mobile) | 1rem | Service icons |
| `.logo-strip` | 5 (desktop), 3 (tablet), 1 (mobile) | 1rem | Partner logos |
| `.trust-points` | 3 (desktop), 1 (mobile) | 1rem | Trust stats |
| `.hero-grid` | 1.55fr / 0.85fr | 2rem | Hero content + panel |

---

## 7. Responsive Design

### 7.1 Breakpoints

| Breakpoint | Width | Behavior |
|-----------|-------|----------|
| **Large Desktop** | > 1280px | Full multi-column layouts |
| **Desktop** | 1025px – 1279px | Some grids collapse to 1-column, header shrinks to 78px |
| **Tablet** | 768px – 1024px | Sidebars hidden, stacked layouts |
| **Mobile** | 640px – 767px | Single column, stacked navigation, compact spacing |
| **Small Mobile** | 390px – 639px | Further reduced padding and font sizes |
| **Very Small** | < 390px | Single column logo strip, minimal padding |

### 7.2 Responsive Behaviors by Element

| Element | Desktop | Tablet (≤1024px) | Mobile (≤767px) |
|---------|---------|-------------------|------------------|
| **Header** | 88px, white bg | 78px | 72px, dark bg |
| **Navigation** | Horizontal links | Horizontal links | Hamburger drawer |
| **Hero Grid** | Side-by-side | Stacked | Stacked |
| **Card Grids** | 3-column | 2-column | 1-column |
| **Footer Grid** | 4-column | 2-column | 1-column |
| **Buttons** | Auto width | Auto width | Full width |
| **Typography** | Desktop clamp | Mid-range | Mobile clamp |
| **Section Padding** | 80px | 56px | 40px |
| **Lead Form** | Horizontal row | Horizontal row | Vertical stack |

### 7.3 Container Adaptation
- Default container: `width: min(calc(100% - 2rem), var(--max-width))`
- On mobile (≤767px): container uses `padding: 0 1rem` with `width: 100%`

---

## 8. Iconography

### 8.1 Icon Libraries
**No external icon library (Font Awesome, Material Icons, etc.) is used.**

All icons are implemented as:
1. **Inline SVGs** — Direct SVG markup within HTML
2. **CSS Mask Images** — SVG data URIs used as mask images for icons (footer)
3. **Unicode Characters** — Arrow, checkmark, and close symbols
4. **Image Icons** — WebP images for AI assistant and WhatsApp floating buttons

### 8.2 Custom SVG Icons (Footer)
The following icon types are defined as CSS mask images in `resources/css/site/base.css`:
- **Location (Map Pin)** — Footer address icon
- **Phone** — Footer phone icon
- **Email** — Footer email icon

### 8.3 List Icons
- Bullet points: 6px circular dots in `#0b7a75` (before pseudo-elements)
- Checkmarks: Green gradient check icons in empathy cards
- Orange dots: 7px circular dots in `#e8773a` (migration pages)

### 8.4 Icon Sizing
- Standard icon container: **24px × 24px** (service icon cards)
- Footer icons: **1.05rem × 1.05rem** (~17px)
- Social links: **36px × 36px** circular containers
- Avatar ring: **220px × 220px** (founder photo)

### 8.5 Recommendation
The project would benefit from a dedicated SVG sprite system or a consistent inline SVG component approach for all icons. Consider creating a reusable Blade icon component.

---

## 9. Imagery Style

### 9.1 Photography Style

| Aspect | Style |
|--------|-------|
| **Tone** | Warm, natural, authentic |
| **Color Treatment** | Natural/true-to-life. No heavy filters. |
| **Subjects** | Real people in real settings (not stock-looking) |
| **Format** | WebP (preferred), PNG (partner logos) |
| **Orientation** | Both portrait and landscape used |
| **Resolution** | High-quality, optimized for web |

### 9.2 Hero Image
- **File:** `public/media/hero/hero.webp`
- **Video Fallback:** `public/media/home/hero.webm`
- **Style:** Full-bleed background image with dark gradient overlay
- **Overlay:** `linear-gradient(180deg, rgba(4,22,36,0.75), rgba(5,32,50,0.7), rgba(6,50,70,0.75))`

### 9.3 Image Inventory

| Location | Subject | Format |
|----------|---------|--------|
| Homepage Hero | Background scene/destinations | WebP + WebM video |
| Empathy Cards (4) | Audience-specific imagery | WebP |
| Founder Photo | Founder portrait | WebP |
| About Page | About hero scene | WebP |
| Testimonials (4) | Client portraits | WebP |
| Blog Posts | Article featured images | WebP |
| AI Assistant | Chat icon | WebP |
| WhatsApp | WhatsApp icon | WebP |
| Partner Logos (5) | Company logos (Wise, SafetyWing, Booking.com, Cigna, OFX) | PNG |
| Contact Page | Contact illustration | PNG |

### 9.4 Partner Logo Treatment
- Displayed in a marquee animation
- Grayscale filter applied by default (`filter: grayscale(1)`, `opacity: 0.72`)
- Color on hover (`filter: grayscale(0)`, `opacity: 1`)
- Max height: 38px (standard), 45.6px (large)

### 9.5 Illustration Style
No custom illustrations identified. The project uses photography and gradient-based graphic elements instead. Consider commissioning a set of custom illustrations for the service cards and hero sections.

---

## 10. Accessibility

### 10.1 Color Contrast Analysis

| Combination | Ratio | WCAG AA (4.5:1) | WCAG AAA (7:1) |
|-------------|-------|-----------------|-----------------|
| `#0b7a75` on `#ffffff` | ~3.5:1 | ❌ (Large text ✓) | ❌ |
| `#065e5b` on `#ffffff` | ~4.6:1 | ✅ | ❌ |
| `#e8773a` on `#ffffff` | ~3.2:1 | ❌ (Large text ✓) | ❌ |
| `#2c3a47` on `#ffffff` | ~12:1 | ✅ | ✅ |
| `#ffffff` on `#065e5b` | ~4.6:1 | ✅ | ❌ |
| `#ffffff` on `#0b7a75` | ~3.5:1 | ❌ (Large text ✓) | ❌ |
| `#607080` on `#ffffff` | ~4.8:1 | ✅ | ❌ |

**Recommendations:**
- Text smaller than 18px (or 14px bold) on `#0b7a75` or `#e8773a` backgrounds should use darker variants.
- Consider `#065e5b` instead of `#0b7a75` for body-size text on white.
- The CTA accent `#e8773a` on white may fail AA for small text — use for larger buttons only.

### 10.2 Typography Accessibility
- Minimum body text size: **16px** (within WCAG recommendations)
- Line height: **1.65** for body (good readability)
- Button minimum height: **48px** (meets touch target recommendation of 44px)
- `prefers-reduced-motion` media queries are implemented for scroll animations and marquee.

### 10.3 Responsive Accessibility
- All viewport `meta` tag is set with `width=device-width, initial-scale=1`
- Touch targets are at minimum 44px (buttons, menu toggle)
- Mobile navigation has `aria-expanded` and `aria-controls` attributes
- Skip-to-content links recommended for future improvement

### 10.4 WCAG Compliance Recommendations

| Priority | Issue | Recommendation |
|----------|-------|----------------|
| High | Color contrast on CTA buttons | Ensure body text on orange/teal backgrounds meets 4.5:1 |
| High | Missing alt text on some images | Audit and add descriptive alt attributes to all images |
| Medium | Form labels association | Ensure all inputs have explicit `<label for="">` associations |
| Medium | Focus indicators | Custom focus styles are present for inputs, verify for all interactive elements |
| Low | Skip navigation | Implement a "Skip to content" link |
| Low | ARIA landmarks | Ensure all sections have appropriate ARIA roles |

---

## 11. Technical Design System

### 11.1 CSS Custom Properties (Design Tokens)

```css
:root {
  /* Brand Colors */
  --primary-brand: #0b7a75;
  --primary-dark: #065e5b;
  --light-brand-fill: #e6f4f3;
  
  /* Backgrounds */
  --page-background: #f5f0e8;
  --white: #ffffff;
  
  /* Accent */
  --cta-accent: #e8773a;
  
  /* Text */
  --body-text: #2c3a47;
  --secondary-text: #607080;
  
  /* Borders */
  --nav-border: #c8d8d7;
  
  /* Shadows */
  --shadow-soft: 0 2px 12px rgba(0, 0, 0, 0.07);
  
  /* Border Radius */
  --radius-card: 12px;
  --radius-button: 8px;
  --radius-input: 6px;
  
  /* Layout */
  --max-width: 1180px;
}
```

### 11.2 Tailwind Configuration
**Status:** Tailwind CSS v4 is installed and configured via `@tailwindcss/vite`.

Configuration (`resources/css/app.css`):
```css
@import 'tailwindcss';
@import './site/base.css';

@theme {
  --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
}
```

**Note:** Tailwind usage in the frontend appears minimal. The site relies primarily on custom CSS with CSS custom properties. Tailwind is used mainly as a build tool via Vite.

### 11.3 Technology Stack

| Technology | Version | Purpose |
|-----------|---------|---------|
| Laravel | 11.x (inferred) | PHP framework |
| PHP | 8.x (inferred) | Backend language |
| Tailwind CSS | 4.x | Utility CSS framework (Vite plugin) |
| Vite | 8.x | Build tool and asset bundler |
| CSS Custom Properties | Native | Design tokens and theming |
| Google Fonts | API | Web font delivery |
| WebP | Image format | Modern image delivery |

### 11.4 Reusable Component Patterns

| Component | CSS Pattern | Usage |
|-----------|------------|-------|
| `.container` | `width: min(calc(100% - 2rem), var(--max-width)); margin: 0 auto` | Page wrapper |
| `.section` | `padding: 80px 0` | Section container |
| `.button` | `inline-flex; min-height: 48px; border-radius: var(--radius-button)` | All buttons |
| `.card-grid--three` | `grid-template-columns: repeat(3, 1fr); gap: 1.25rem` | 3-column card layouts |
| `.hero-grid` | `grid-template-columns: minmax(0, 1.55fr) minmax(320px, 0.85fr)` | Hero split layouts |
| `.lead-form` | `display: grid; gap: 1rem; padding: 1.5rem` | Form wrapper |
| `.eyebrow` | `font-size: 14px; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase` | Section labels |
| `.reveal-on-scroll` | Scroll-triggered opacity + translate animation | Entrance animations |

---

## 12. Design Principles

### 12.1 Core Principles
1. **Clarity First** — Every element serves a purpose. No decorative excess.
2. **Human Warmth** — Colors, imagery, and tone should feel welcoming, not institutional.
3. **Consistency** — Reusable components, predictable patterns, systematic spacing.
4. **Mobile-First Responsiveness** — Every page works beautifully on any device.
5. **Accessibility** — Contrast ratios, font sizes, and interactions meet WCAG standards.

### 12.2 Voice & Tone Guidelines

| Context | Tone | Example |
|---------|------|---------|
| Hero Headlines | Bold, aspirational | "Landing in a New Country Shouldn't Feel Like Guessing Every Step!" |
| Body Copy | Warm, conversational | "I made every mistake a new immigrant can make." |
| CTAs | Direct, action-oriented | "Get SettleANZ Guide", "Start My Family Settlement Plan" |
| Form Labels | Clean, minimal | "First Name", "Email Address" |
| Footer | Informative, professional | Standard copyright + links |

### 12.3 Animation Guidelines

| Element | Animation | Duration | Easing |
|---------|-----------|----------|--------|
| Scroll Reveal | Opacity + translateY | 1800ms | `cubic-bezier(0.22, 1, 0.36, 1)` |
| Card Hover | TranslateY(-2px to -4px) | 200-240ms | ease |
| Button Hover | Brightness filter | 150ms | ease |
| Modal Enter | Scale + opacity | 300ms | `cubic-bezier(0.34, 1.56, 0.64, 1)` |
| Dropdown | TranslateY + opacity | 200ms | ease |
| Partner Marquee | TranslateX infinite | 24s | linear |

---

## Appendix A: Missing Assets & Recommendations

### A.1 Missing Assets

| Asset | Status | Recommendation |
|-------|--------|---------------|
| SVG Logo | ❌ Not found | Create an SVG wordmark logo file for brand consistency |
| Favicon | ⚠️ Present (`favicon.ico`) | Consider updating with a branded teal/orange design |
| OG Default Image | ⚠️ Referenced (`media/og-default.jpg`) | Ensure this file exists with 1200×630px dimensions |
| Icon Library | ❌ Not used | Consider Font Awesome or Lucide for consistent iconography |
| Custom Illustrations | ❌ Not found | Commission a set of brand illustrations for service sections |
| Brand Font Files (local) | ❌ Not found | Consider self-hosting fonts for performance and privacy |

### A.2 Recommendations for Brand Consistency

1. **Create a proper SVG logo** — A dedicated logo file (both full and icon-only variants) should be placed in `public/media/` and used consistently.

2. **Standardize icon system** — Move footer icons, list markers, and decorative icons to a consistent SVG sprite or icon component system.

3. **Establish a component library** — Extract repeated patterns (cards, buttons, forms) into reusable Blade components for consistency.

4. **Improve color contrast** — Audit all foreground/background color combinations to ensure WCAG AA compliance. Consider slightly darkening the CTA accent (`#d46a2e`) or using it only for large elements.

5. **Self-host fonts** — Download and self-host Plus Jakarta Sans and Inter to reduce external dependencies and improve page load performance.

6. **Create a Figma design system** — Migrate these brand guidelines into a Figma component library for visual consistency across all future design work.

7. **Audit image alt text** — Ensure all images have descriptive, meaningful alt text for accessibility.

8. **Add a favicon set** — Generate favicon files in multiple sizes (16px, 32px, 192px, 512px) with the brand teal color.

---

*This brand guidelines document was generated through comprehensive analysis of the SettleANZ Laravel codebase, including CSS files, Blade templates, JavaScript assets, and media assets. All information reflects the live implementation as of June 2026.*
