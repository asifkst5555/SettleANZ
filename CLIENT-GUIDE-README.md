# SettleANZ Client & User Guide — Documentation Package

Professional, marketing-friendly documentation for clients, marketers, and content editors.

## Files in project root

| File | Format | Best for |
|------|--------|----------|
| **SettleANZ-Client-Guide.pdf** | PDF (print & share) | Client handover, presentations, email attachment |
| **SettleANZ-Client-Guide.md** | Markdown (editable) | Updating content, version control, web viewing on GitHub |

## Download from Admin (live site)

After logging in as admin:

- **Full client guide:** `/admin/documentation/client-guide`
- **SEO system guide:** `/admin/documentation/seo-system`

## Regenerate the PDF locally

```bash
cd /home/asifk/projects/SettleANZ
php artisan docs:client-guide
```

Output: `SettleANZ-Client-Guide.pdf` in the project root.

Custom path:

```bash
php artisan docs:client-guide --output=/path/to/My-Guide.pdf
```

## What the guide covers

1. What SettleANZ does (business value)
2. Every public page and visitor feature
3. Lead capture forms and WhatsApp
4. Blog and content marketing
5. Directory and reviews
6. AI assistant (optional)
7. Admin dashboard walkthrough
8. Step-by-step: leads, blog, directory, reviews
9. SEO Manager (plain language)
10. Site settings and integrations
11. User roles and security
12. Quick reference tables
13. Team tips

Written for **non-technical** readers — suitable for client onboarding and training.

## Related technical docs

- `docs/cpanel-deployment.md` — Hosting on Hostinger / cPanel
- `docs/implementation-guide.md` — Developer build notes
- `docs/media-folder-guide.md` — Where to put images
- `SEO-DOCUMENTATION-README.md` — SEO PDF package

---

*SettleANZ — May 2026*
