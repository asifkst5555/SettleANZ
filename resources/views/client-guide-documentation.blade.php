@php
    $faviconVer = file_exists(public_path('favicon.ico')) ? filemtime(public_path('favicon.ico')) : time();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SettleANZ Client & User Guide</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico?v={{ $faviconVer }}">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico?v={{ $faviconVer }}">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png?v={{ $faviconVer }}">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v={{ $faviconVer }}">
    <link rel="icon" type="image/png" sizes="48x48" href="/favicon-48x48.png?v={{ $faviconVer }}">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png?v={{ $faviconVer }}">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v={{ $faviconVer }}">
    <link rel="manifest" href="/site.webmanifest?v={{ $faviconVer }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', 'Segoe UI', sans-serif; color: #2c3e50; line-height: 1.55; font-size: 11px; }
        .cover-page {
            page-break-after: always;
            text-align: center;
            padding: 80px 40px;
            background: #0b7a75;
            color: #fff;
            min-height: 90vh;
        }
        .cover-page h1 { font-size: 36px; margin-bottom: 12px; font-weight: 700; }
        .cover-page .subtitle { font-size: 18px; opacity: 0.95; margin-bottom: 8px; }
        .cover-page .tagline { font-size: 13px; opacity: 0.85; margin-top: 40px; max-width: 420px; margin-left: auto; margin-right: auto; }
        .cover-page .meta { font-size: 11px; opacity: 0.8; margin-top: 60px; }
        .toc { page-break-after: always; padding: 30px 35px; }
        .toc h2 { font-size: 22px; color: #0b7a75; border-bottom: 3px solid #0b7a75; padding-bottom: 8px; margin-bottom: 20px; }
        .toc ol { margin-left: 18px; }
        .toc li { margin: 8px 0; font-size: 11px; }
        .section { padding: 0 35px 25px; page-break-inside: avoid; }
        .section-break { page-break-before: always; }
        h2 { font-size: 20px; color: #0b7a75; border-bottom: 2px solid #0b7a75; padding-bottom: 6px; margin: 22px 0 14px; }
        h3 { font-size: 14px; color: #1a5a56; margin: 16px 0 8px; }
        p { margin-bottom: 10px; text-align: justify; }
        ul, ol { margin: 0 0 12px 18px; }
        li { margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin: 12px 0 16px; font-size: 10px; }
        th { background: #0b7a75; color: #fff; padding: 8px 10px; text-align: left; }
        td { border: 1px solid #d0e8e6; padding: 7px 10px; vertical-align: top; }
        tr:nth-child(even) td { background: #f4faf9; }
        .highlight-box {
            background: #eef8f7;
            border-left: 4px solid #0b7a75;
            padding: 12px 14px;
            margin: 12px 0;
        }
        .tip { background: #fff8e6; border-left: 4px solid #e8a020; padding: 10px 12px; margin: 12px 0; font-size: 10px; }
        .footer-note { text-align: center; font-size: 9px; color: #888; margin-top: 30px; padding-top: 15px; border-top: 1px solid #ddd; }
        .two-col { width: 48%; display: inline-block; vertical-align: top; }
    </style>
</head>
<body>

<div class="cover-page">
    <h1>SettleANZ</h1>
    <p class="subtitle">Complete Client &amp; User Guide</p>
    <p class="tagline">Everything your team needs to understand the public website and admin dashboard — written in plain language for business owners, marketers, and content editors.</p>
    <p class="meta">Version 1.0 &nbsp;|&nbsp; May 2026 &nbsp;|&nbsp; Confidential — Client Handover</p>
</div>

<div class="toc">
    <h2>Table of Contents</h2>
    <ol>
        <li>What SettleANZ Does</li>
        <li>Public Website — Pages &amp; Features</li>
        <li>How Visitors Contact You</li>
        <li>Blog &amp; Content Marketing</li>
        <li>Business Directory &amp; Reviews</li>
        <li>AI Assistant (Optional)</li>
        <li>Admin Dashboard Overview</li>
        <li>Managing Leads &amp; Enquiries</li>
        <li>Managing Blog Posts</li>
        <li>Managing Directory Listings</li>
        <li>Managing Reviews</li>
        <li>SEO Manager</li>
        <li>Site Settings &amp; Integrations</li>
        <li>User Roles &amp; Login</li>
        <li>Quick Reference</li>
        <li>Tips for Your Team</li>
    </ol>
</div>

<div class="section">
    <h2>1. What SettleANZ Does</h2>
    <p>SettleANZ is a professional website platform that helps people who are <strong>new to Australia</strong> plan their arrival, compare essential services, find trusted partners, and contact your team for support.</p>
    <div class="highlight-box">
        <strong>For your business:</strong> More qualified leads in one inbox, full control over content and SEO, a trustworthy mobile-friendly brand, and tools your non-technical team can use every day.
    </div>
    <h3>Who benefits</h3>
    <ul>
        <li><strong>Visitors</strong> — Practical guides, blog articles, directory, and easy contact options</li>
        <li><strong>Your sales team</strong> — Organised leads with status tracking and notes</li>
        <li><strong>Marketing</strong> — Blog, SEO, and social preview control</li>
        <li><strong>Partners</strong> — Featured listings in the directory</li>
    </ul>
</div>

<div class="section section-break">
    <h2>2. Public Website — Pages &amp; Features</h2>
    <table>
        <tr><th>Page</th><th>What visitors see</th></tr>
        <tr><td><strong>Home</strong></td><td>Hero, benefits, founder story, testimonials, featured guides, partners, email capture, latest blog posts</td></tr>
        <tr><td><strong>New to Australia</strong></td><td>Long arrival guide with checklists, first-week tips, and FAQs</td></tr>
        <tr><td><strong>Settlement Services</strong></td><td>Package comparison, pricing, FAQs, and package booking form</td></tr>
        <tr><td><strong>Housing Guide</strong></td><td>Rental advice and featured relocation partners</td></tr>
        <tr><td><strong>Banking Guide</strong></td><td>Bank comparisons and money transfer recommendations</td></tr>
        <tr><td><strong>Migration Services</strong></td><td>Visa information, agent listings, consultation and booking forms</td></tr>
        <tr><td><strong>Blog</strong></td><td>Articles with categories, search, and full article pages</td></tr>
        <tr><td><strong>Directory</strong></td><td>Searchable partner businesses by city and category</td></tr>
        <tr><td><strong>Contact</strong></td><td>Contact details, listing enquiry, and contact form</td></tr>
        <tr><td><strong>About</strong></td><td>Your story, values, and mission</td></tr>
        <tr><td><strong>Privacy &amp; Terms</strong></td><td>Legal pages with admin-managed SEO</td></tr>
    </table>
    <p>Main menu typically shows: Home, New to Australia, Settlement Services, Blog, Directory, About, and Contact. Housing, Banking, and Migration pages are also available for links and search engines.</p>
</div>

<div class="section section-break">
    <h2>3. How Visitors Contact You</h2>
    <p>Every form on the site sends enquiries to your <strong>Admin Dashboard</strong>. You receive in-app notifications — no separate email setup required for basic lead capture.</p>
    <table>
        <tr><th>Visitor action</th><th>Where it appears in Admin</th></tr>
        <tr><td>Homepage email strip</td><td>All Leads</td></tr>
        <tr><td>Popup form</td><td>All Leads</td></tr>
        <tr><td>Settlement package booking</td><td>Package Requests</td></tr>
        <tr><td>Contact page form</td><td>Contact Submissions</td></tr>
        <tr><td>Migration consultation</td><td>Book Consultations</td></tr>
        <tr><td>AI chat (if enabled)</td><td>All Leads</td></tr>
    </table>
    <p><strong>WhatsApp</strong> links appear in the footer and contact areas — URLs are set in Site Settings.</p>
</div>

<div class="section section-break">
    <h2>4. Blog &amp; Content Marketing</h2>
    <p>Your blog drives trust and Google visibility. Visitors see article cards with images, categories, and excerpts. Each article has a full page with related posts.</p>
    <h3>What your team can do</h3>
    <ul>
        <li>Create and publish articles with featured images</li>
        <li>Import drafts from PDF or Word documents</li>
        <li>Use AI assist to draft content and SEO fields (optional)</li>
        <li>Feature posts on the homepage</li>
        <li>Set SEO title, description, social preview, and FAQs per post</li>
    </ul>
    <div class="tip"><strong>Tip:</strong> Publish at least one helpful article per month and always add a featured image for better engagement.</div>
</div>

<div class="section section-break">
    <h2>5. Business Directory &amp; Reviews</h2>
    <h3>Directory listings</h3>
    <p>List partners with name, category, city, descriptions, services, phone, email, website, WhatsApp, and booking links. Mark listings as <strong>Featured</strong> or <strong>Published</strong>.</p>
    <h3>Reviews</h3>
    <p>Visitors submit star ratings and comments. Reviews stay <strong>pending</strong> until you approve them in Admin → Reviews — only quality feedback goes live.</p>
</div>

<div class="section section-break">
    <h2>6. AI Assistant (Optional)</h2>
    <p>A floating chat assistant can answer visitor questions using your site content. Turn it on or off in <strong>API Integration Settings</strong>. Customise the greeting, title, and personality. Connect your OpenAI API key. When visitors share contact details, a lead can be created for follow-up.</p>
</div>

<div class="section section-break">
    <h2>7. Admin Dashboard Overview</h2>
    <p><strong>Login:</strong> your-domain.com/admin/login</p>
    <table>
        <tr><th>Menu</th><th>Purpose</th></tr>
        <tr><td>Dashboard</td><td>Counts and recent activity</td></tr>
        <tr><td>All Leads</td><td>Every enquiry</td></tr>
        <tr><td>Contact / Consultations / Packages</td><td>Filtered lead views</td></tr>
        <tr><td>Blog Posts</td><td>Create and manage articles</td></tr>
        <tr><td>Directory Listings</td><td>Manage partner businesses</td></tr>
        <tr><td>Reviews</td><td>Approve or reject feedback</td></tr>
        <tr><td>API Integration Settings</td><td>Contact, social, WhatsApp, AI</td></tr>
        <tr><td>SEO Manager</td><td>Page titles and descriptions</td></tr>
    </table>
    <p>The <strong>notification bell</strong> alerts you to new leads and pending reviews.</p>
</div>

<div class="section section-break">
    <h2>8. Managing Leads &amp; Enquiries</h2>
    <ol>
        <li>Open All Leads or a filtered view</li>
        <li>Read full details and update status: New → Reviewing → Contacted → Qualified → Closed</li>
        <li>Add internal notes for your team</li>
        <li>Contact the visitor and mark Closed when finished</li>
    </ol>
</div>

<div class="section section-break">
    <h2>9. Managing Blog Posts</h2>
    <ol>
        <li>Blog Posts → Create</li>
        <li>Enter title, category, excerpt, and body</li>
        <li>Upload featured image</li>
        <li>Complete SEO fields</li>
        <li>Publish or save as Draft; optionally feature on homepage</li>
    </ol>
</div>

<div class="section section-break">
    <h2>10. Managing Directory Listings</h2>
    <p>Directory Listings → Create or Edit. Fill business details, set Published to go live, and Featured to prioritise in results and guide pages.</p>
</div>

<div class="section section-break">
    <h2>11. Managing Reviews</h2>
    <p>Reviews → read pending items → Approve to publish, or Reject/Delete if inappropriate.</p>
</div>

<div class="section section-break">
    <h2>12. SEO Manager</h2>
    <p>Update page titles and descriptions for Home, guides, Blog, About, Contact, Directory, and legal pages — no coding needed. Each blog post has its own SEO fields. The site also generates an automatic XML sitemap and robots.txt.</p>
</div>

<div class="section section-break">
    <h2>13. Site Settings &amp; Integrations</h2>
    <p>In API Integration Settings, control contact email, WhatsApp links, response-time text, social media URLs, migration button label, directory apply link, and the AI assistant (on/off, messages, API key).</p>
</div>

<div class="section section-break">
    <h2>14. User Roles &amp; Login</h2>
    <table>
        <tr><th>Role</th><th>Access</th></tr>
        <tr><td>Admin</td><td>Full dashboard</td></tr>
        <tr><td>Regular user</td><td>Cannot access admin</td></tr>
    </table>
    <p>Admin accounts are created by your developer. Change default passwords after launch.</p>
</div>

<div class="section section-break">
    <h2>15. Quick Reference</h2>
    <table>
        <tr><th>I want to…</th><th>Go to…</th></tr>
        <tr><td>See new enquiries</td><td>Dashboard or All Leads</td></tr>
        <tr><td>Publish a blog article</td><td>Blog Posts → Publish</td></tr>
        <tr><td>Add a partner</td><td>Directory Listings → Create</td></tr>
        <tr><td>Approve a review</td><td>Reviews → Approve</td></tr>
        <tr><td>Change WhatsApp</td><td>API Integration Settings</td></tr>
        <tr><td>Update Google title for Home</td><td>SEO Manager → Home</td></tr>
    </table>
</div>

<div class="section section-break">
    <h2>16. Tips for Your Team</h2>
    <ul>
        <li>Respond to leads within 24 hours</li>
        <li>Publish one blog article per month</li>
        <li>Keep directory contact details up to date</li>
        <li>Approve only genuine reviews</li>
        <li>Keep SEO titles under ~60 characters</li>
        <li>Always use featured images on blog posts</li>
        <li>Test forms after any site update</li>
    </ul>
    <p class="footer-note">SettleANZ — Helping newcomers settle with confidence. &nbsp;|&nbsp; For technical hosting steps see docs/cpanel-deployment.md</p>
</div>

</body>
</html>
