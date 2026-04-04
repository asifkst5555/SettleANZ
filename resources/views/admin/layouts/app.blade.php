<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $metaTitle ?? 'Admin | SettleANZ' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('site.css') }}">
    <link rel="stylesheet" href="{{ asset('admin.css') }}">
</head>
<body class="admin-shell-body">
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <div class="admin-sidebar__brand">
                <p class="admin-sidebar__eyebrow">SettleANZ</p>
                <h1>Admin Panel</h1>

            </div>

            <nav class="admin-sidebar__nav" aria-label="Admin navigation">
                <a @class(['is-active' => request()->routeIs('admin.dashboard')]) href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a @class(['is-active' => request()->routeIs('admin.leads.*') && !request('type')]) href="{{ route('admin.leads.index') }}">All Leads</a>
                <a @class(['is-active' => request()->routeIs('admin.leads.index') && request('type') === 'contact-page']) href="{{ route('admin.leads.index', ['type' => 'contact-page']) }}">Contact Submissions</a>
                <a @class(['is-active' => request()->routeIs('admin.leads.index') && request('type') === 'consultation-booking']) href="{{ route('admin.leads.index', ['type' => 'consultation-booking']) }}">Book Consultations</a>
                <a @class(['is-active' => request()->routeIs('admin.blog-posts.*')]) href="{{ route('admin.blog-posts.index') }}">Blog Posts</a>
                <a @class(['is-active' => request()->routeIs('admin.directory-listings.*')]) href="{{ route('admin.directory-listings.index') }}">Directory Listings</a>
                <a @class(['is-active' => request()->routeIs('admin.settings.*')]) href="{{ route('admin.settings.edit') }}">API Integration Settings</a>
                <a href="/" target="_blank" rel="noreferrer">View Website</a>
            </nav>

            <div class="admin-sidebar__user">
                <div>
                    <strong>{{ auth()->user()?->name }}</strong>
                    <span>{{ auth()->user()?->email }}</span>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="button button--small" type="submit">Sign out</button>
                </form>
            </div>
        </aside>

        <div class="admin-main">
            @yield('content')
        </div>
    </div>
</body>
</html>


