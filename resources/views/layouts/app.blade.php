<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        $seoTitle       = $metaTitle       ?? 'SettleANZ';
        $seoDescription = $metaDescription ?? 'Migration, housing, and relocation guidance for new arrivals in Australia and New Zealand.';
        $seoOgTitle     = $metaOgTitle     ?? $seoTitle;
        $seoOgDesc      = $metaOgDesc      ?? $seoDescription;
        $seoOgImage     = $metaOgImage     ?? asset('media/og-default.jpg');
        $seoCanonical   = $metaCanonical   ?? request()->url();
        $seoNoIndex     = $metaNoIndex     ?? false;
        $seoOgType      = $metaOgType      ?? 'website';
        $seoSchemaType  = $metaSchemaType  ?? 'WebPage';
        $schemaPayload  = $schemaData      ?? null;
        $siteUrl        = rtrim(config('app.url'), '/');
        $siteCssVersion = file_exists(public_path('site.css')) ? filemtime(public_path('site.css')) : time();
        $siteJsVersion  = file_exists(public_path('site.js')) ? filemtime(public_path('site.js')) : time();
    @endphp

    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDescription }}">
    <link rel="canonical" href="{{ $seoCanonical }}">
    @if ($seoNoIndex)
        <meta name="robots" content="noindex, nofollow">
    @else
        <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    @endif

    {{-- Open Graph --}}
    <meta property="og:type" content="{{ $seoOgType }}">
    <meta property="og:site_name" content="SettleANZ">
    <meta property="og:title" content="{{ $seoOgTitle }}">
    <meta property="og:description" content="{{ $seoOgDesc }}">
    <meta property="og:url" content="{{ $seoCanonical }}">
    <meta property="og:image" content="{{ $seoOgImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="en_AU">

    {{-- Twitter / X Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoOgTitle }}">
    <meta name="twitter:description" content="{{ $seoOgDesc }}">
    <meta name="twitter:image" content="{{ $seoOgImage }}">

    {{-- JSON-LD Structured Data --}}
    @if (is_array($schemaPayload))
        <script type="application/ld+json">{!! json_encode($schemaPayload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
    @else
        <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "{{ $seoSchemaType }}",
            "name": "{{ $seoTitle }}",
            "description": "{{ $seoDescription }}",
            "url": "{{ $seoCanonical }}",
            "publisher": {
                "@@type": "Organization",
                "name": "SettleANZ",
                "url": "{{ $siteUrl }}",
                "logo": {
                    "@@type": "ImageObject",
                    "url": "{{ $siteUrl }}/media/logo.svg"
                }
            }
        }
        </script>
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('site.css') }}?v={{ $siteCssVersion }}">
    @stack('styles')
    @yield('page_styles')
    <style>
        .site-chat-panel {
            width: min(420px, calc(100vw - 1.5rem));
            display: none;
            grid-template-rows: auto minmax(260px, 1fr) auto;
            overflow: hidden;
            border: 1px solid #b9cfcb;
            border-radius: 30px;
            background: #ffffff !important;
            box-shadow: 0 30px 80px rgba(10, 35, 45, 0.28), 0 0 0 1px rgba(255, 255, 255, 0.85) inset;
            isolation: isolate;
        }

        .site-chat-panel.is-open {
            display: grid;
        }

        .site-chat-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.15rem 1.15rem 1rem;
            background: linear-gradient(135deg, #f18a42 0%, #e8773a 45%, #d86424 100%) !important;
            color: #ffffff;
        }

        .site-chat-head-main {
            display: grid;
            gap: 0.25rem;
        }

        .site-chat-title {
            margin: 0;
            color: #ffffff !important;
            font-size: 1.15rem;
            line-height: 1.1;
        }

        .site-chat-sub {
            margin: 0;
            color: rgba(255, 255, 255, 0.88) !important;
            font-size: 0.92rem;
            line-height: 1.45;
        }

        .site-chat-head-actions {
            display: flex;
            align-items: center;
            gap: 0.55rem;
        }

        .site-chat-log {
            display: grid;
            gap: 0.85rem;
            min-height: 260px;
            max-height: min(52vh, 430px);
            padding: 1rem;
            overflow-y: auto;
            background: linear-gradient(180deg, #f8fcfb 0%, #eef6f4 100%) !important;
        }

        .site-chat-msg {
            width: fit-content;
            max-width: 88%;
            align-self: start;
            padding: 0.82rem 0.95rem;
            border-radius: 18px;
            font-size: 0.95rem;
            line-height: 1.55;
            box-shadow: 0 8px 20px rgba(16, 35, 58, 0.06);
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        .site-chat-msg.user {
            justify-self: end;
            background: linear-gradient(135deg, #f18a42 0%, #e8773a 45%, #d86424 100%) !important;
            color: #ffffff;
            border-bottom-right-radius: 6px;
        }

        .site-chat-msg.bot {
            justify-self: start;
            background: #fffaf4 !important;
            color: #2c3a47;
            border: 1px solid rgba(232, 119, 58, 0.18);
            border-bottom-left-radius: 6px;
        }

        .site-chat-msg.system {
            justify-self: center;
            max-width: 100%;
            background: #fff1e8 !important;
            color: #8c4d24;
        }

        .site-chat-msg.thinking {
            display: grid;
            gap: 0.5rem;
            min-width: 208px;
            background: #fffaf4 !important;
            border: 1px solid rgba(232, 119, 58, 0.18);
        }

        .site-chat-thinking-title {
            font-weight: 700;
            color: #d86424;
        }

        .site-chat-thinking-copy {
            color: #536171;
            font-size: 0.9rem;
            line-height: 1.45;
        }

        .site-chat-thinking-dots {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .site-chat-thinking-dots span {
            width: 0.45rem;
            height: 0.45rem;
            border-radius: 999px;
            background: #e8773a;
            opacity: 0.35;
            animation: site-chat-thinking 1.15s infinite ease-in-out;
        }

        .site-chat-thinking-dots span:nth-child(2) {
            animation-delay: 0.18s;
        }

        .site-chat-thinking-dots span:nth-child(3) {
            animation-delay: 0.36s;
        }

        @@keyframes site-chat-thinking {
            0%, 80%, 100% {
                opacity: 0.35;
                transform: translateY(0);
            }

            40% {
                opacity: 1;
                transform: translateY(-2px);
            }
        }

        .site-chat-form {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 0.75rem;
            padding: 0.9rem 1rem 1rem;
            border-top: 1px solid rgba(8, 93, 101, 0.12);
            background: #ffffff;
        }

        .site-chat-input {
            min-width: 0;
            padding: 0.92rem 1rem;
            border: 1px solid #b8cbc8;
            border-radius: 14px;
            background: #fefefe !important;
            color: #2c3a47;
        }

        .site-chat-send {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 46px;
            padding: 0.75rem 1rem;
            border: 0;
            border-radius: 14px;
            background: linear-gradient(135deg, #e8773a 0%, #d86424 100%) !important;
            color: #ffffff;
            font-weight: 700;
            letter-spacing: 0.01em;
            cursor: pointer;
        }

        .site-chat-send--secondary {
            min-height: 34px;
            padding: 0.45rem 0.8rem;
            background: rgba(255, 255, 255, 0.18) !important;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.16);
        }

        .site-chat-close {
            width: 36px;
            height: 36px;
            padding: 0;
            border: 0;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14) !important;
            color: #ffffff;
            font-size: 1.6rem;
            line-height: 1;
            cursor: pointer;
        }

        @@media (max-width: 767px) {
            html {
                overflow-x: hidden;
                width: 100%;
            }

            body {
                overflow-x: hidden !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            body.has-mobile-menu-open {
                overflow: hidden !important;
            }

            body.has-mobile-menu-open .site-chat,
            body.has-mobile-menu-open .floating-actions,
            body.has-mobile-menu-open .site-chat-panel {
                opacity: 0 !important;
                visibility: hidden !important;
                pointer-events: none !important;
            }

            .page-shell {
                width: 100% !important;
                overflow-x: hidden !important;
            }

            .container {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 auto !important;
                padding: 0 1rem !important;
                box-sizing: border-box !important;
            }

            .section {
                padding: 2.5rem 0 !important;
            }

            main {
                padding-top: 72px !important;
                width: 100% !important;
                overflow-x: hidden !important;
            }

            body.is-homepage main {
                padding-top: 0 !important;
            }

            .site-header {
                width: 100% !important;
                overflow-x: hidden !important;
                position: fixed !important;
                inset: 0 0 auto 0 !important;
                z-index: 1001 !important;
                background: rgba(18, 50, 71, 0.94) !important;
                box-shadow: none !important;
            }

            .site-header .brand strong {
                color: #ffffff !important;
            }

            .site-header .menu-toggle {
                background: transparent !important;
                border: 0 !important;
                box-shadow: none !important;
            }

            .site-header .menu-toggle span:not(.sr-only) {
                background: #ffffff !important;
            }

            .site-header__inner {
                min-height: 72px !important;
                gap: 1rem;
                width: min(calc(100% - 2rem), 1180px) !important;
                max-width: 1180px !important;
                margin: 0 auto !important;
                padding: 0 !important;
                box-sizing: border-box !important;
                position: relative;
                z-index: 100;
            }

            .brand {
                display: flex !important;
                align-items: center !important;
                min-width: auto !important;
                flex: 0 1 auto !important;
                overflow: visible !important;
            }

            .brand strong {
                font-size: 18px !important;
                white-space: nowrap !important;
                overflow: visible !important;
            }

            .menu-toggle {
                display: inline-flex !important;
                width: 48px;
                height: 48px;
                padding: 0;
                border: 1px solid rgba(15, 23, 42, 0.12);
                border-radius: 14px;
                background: rgba(255, 255, 255, 0.88);
                align-items: center;
                justify-content: center;
                flex-direction: column;
                gap: 4px;
                cursor: pointer;
                z-index: 1000 !important;
                position: relative;
                box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
            }

            .menu-toggle span:not(.sr-only) {
                display: block !important;
                width: 24px;
                height: 3px;
                background: var(--primary-dark);
                border-radius: 2px;
                transition: all 0.3s ease;
            }

            .site-nav {
                position: fixed !important;
                top: calc(72px + 0.75rem) !important;
                left: 1rem !important;
                right: 1rem !important;
                bottom: auto !important;
                display: none !important;
                flex-direction: column !important;
                align-items: stretch !important;
                background: rgba(255, 255, 255, 0.98) !important;
                padding: 1rem 1.15rem 1.15rem !important;
                gap: 0 !important;
                overflow-y: auto !important;
                z-index: 999 !important;
                width: auto !important;
                height: auto !important;
                max-height: calc(100vh - 5.5rem) !important;
                visibility: hidden !important;
                opacity: 0 !important;
                border: 1px solid rgba(15, 23, 42, 0.1);
                border-radius: 24px;
                box-shadow: 0 24px 64px rgba(15, 23, 42, 0.18);
                transform: translateY(-8px);
                transition: visibility 0.25s ease, opacity 0.25s ease, transform 0.25s ease;
            }

            .site-nav.is-open {
                display: flex !important;
                visibility: visible !important;
                opacity: 1 !important;
                transform: translateY(0);
            }

            .site-nav a {
                display: block !important;
                padding: 0.95rem 0 !important;
                font-size: 16px !important;
                font-weight: 600 !important;
                color: var(--primary-dark) !important;
                text-decoration: none !important;
                border-bottom: 1px solid rgba(148, 163, 184, 0.18);
            }

            .site-nav a:hover {
                color: var(--primary-brand) !important;
            }

            .site-nav .button,
            .site-nav__cta {
                margin-top: 0.9rem;
                border-bottom: 0 !important;
            }

            body.is-homepage .site-header--home .site-nav,
            .site-header .site-nav {
                background: rgba(18, 50, 71, 0.98) !important;
                border-color: rgba(255, 255, 255, 0.14);
                box-shadow: 0 24px 64px rgba(2, 12, 19, 0.45);
            }

            body.is-homepage .site-header--home .site-nav a,
            .site-header .site-nav a {
                color: #ffffff !important;
                border-bottom-color: rgba(255, 255, 255, 0.12);
            }

            .site-header__cta {
                display: none !important;
            }

            h1 {
                font-size: clamp(1.75rem, 8vw, 2.4rem) !important;
                line-height: 1.2 !important;
            }

            h2 {
                font-size: clamp(1.35rem, 6vw, 1.8rem) !important;
                line-height: 1.25 !important;
            }

            h3 {
                font-size: clamp(1.1rem, 5vw, 1.3rem) !important;
            }

            .hero-section--reference {
                min-height: 100vh !important;
                padding-top: 72px !important;
                padding-bottom: 48px !important;
                background-position: center !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }

            .hero-reference__inner {
                min-height: calc(100vh - 120px) !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }

            .hero-reference__content {
                text-align: center !important;
            }

            .hero-reference__scroll {
                display: none !important;
            }

            .hero-grid,
            .lead-grid,
            .site-footer__grid,
            .guide-feature-grid,
            .service-icon-grid,
            .card-grid,
            .trust-bar,
            .directory-search-row,
            .directory-filter-meta,
            .value-stack__grid,
            .empathy-grid {
                grid-template-columns: 1fr !important;
            }

            .hero-actions,
            .lead-strip__form {
                flex-direction: column !important;
                align-items: stretch !important;
                width: 100% !important;
            }

            button,
            .button,
            .hero-actions .button,
            .lead-strip__form button,
            .site-nav .button,
            .site-nav__cta,
            .site-header__cta {
                width: 100% !important;
                max-width: none !important;
                min-height: 48px !important;
            }

            /* Directory provider CTA: pill button, content-sized (not full-bleed bar) */
            .directory-sticky-cta .directory-sticky-cta__action.button {
                width: auto !important;
                max-width: min(100%, 21rem) !important;
                min-height: 52px !important;
                margin-inline: auto;
                justify-content: center !important;
                display: inline-flex !important;
                border-radius: 999px !important;
            }

            .hero-reference__actions .button:not(.button--ghost-light) {
                background: linear-gradient(135deg, #f18a42 0%, #e8773a 45%, #d86424 100%) !important;
                border-color: #e8773a !important;
                color: #ffffff !important;
                box-shadow: 0 10px 24px rgba(15, 23, 42, 0.16) !important;
            }

            .hero-reference__actions .button:not(.button--ghost-light):hover,
            .hero-reference__actions .button:not(.button--ghost-light):focus-visible {
                background: linear-gradient(135deg, #f18a42 0%, #d86424 100%) !important;
                border-color: #d86424 !important;
            }

            .hero-reference__actions .button--ghost-light {
                background: rgba(20, 185, 176, 0.88) !important;
                border-color: rgba(20, 185, 176, 0.88) !important;
                color: #ffffff !important;
                box-shadow: 0 10px 24px rgba(15, 23, 42, 0.16) !important;
            }

            .hero-reference__actions .button--ghost-light:hover,
            .hero-reference__actions .button--ghost-light:focus-visible {
                background: rgba(16, 161, 153, 0.96) !important;
                border-color: rgba(16, 161, 153, 0.96) !important;
            }

            .hero-panel,
            .lead-form,
            .guide-card,
            .info-card,
            .service-card,
            .blog-card,
            .directory-card,
            .trust-pill {
                padding: 1.25rem !important;
            }

            .site-chat {
                left: 0.5rem !important;
                right: 0.5rem !important;
                bottom: 0.5rem !important;
            }

            .site-chat-panel {
                width: 100% !important;
            }

            .site-chat-log {
                max-height: 40vh !important;
            }
        }

        @@media (max-width: 767px) {
            .site-header .site-header__inner {
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
                width: min(calc(100% - 2rem), 1180px) !important;
                max-width: 1180px !important;
                margin: 0 auto !important;
            }

            .site-header .brand strong {
                color: #ffffff !important;
            }

            .site-header .menu-toggle {
                margin-left: auto !important;
                flex: 0 0 48px !important;
                align-self: center !important;
                background: transparent !important;
                border: 0 !important;
                box-shadow: none !important;
            }

            .site-header .menu-toggle span {
                background: #ffffff !important;
            }

            .site-header .site-nav {
                left: auto !important;
                right: 1rem !important;
                top: calc(72px + 0.35rem) !important;
                width: min(300px, calc(100vw - 2rem)) !important;
                z-index: 1015 !important;
                display: none !important;
                visibility: hidden !important;
                opacity: 0 !important;
            }

            .site-header .site-nav.is-open {
                display: flex !important;
                visibility: visible !important;
                opacity: 1 !important;
                transform: translateY(0) !important;
            }
        }

        @@media (max-width: 767px) {
            .site-nav {
                display: none !important;
            }

            .mobile-nav-drawer {
                position: fixed;
                inset: 0;
                z-index: 11000;
            }

            .mobile-nav-drawer[hidden] {
                display: none !important;
            }

            .mobile-nav-drawer__backdrop {
                position: absolute;
                inset: 0;
                border: 0;
                background: rgba(5, 16, 24, 0.54);
            }

            .mobile-nav-drawer__panel {
                position: absolute;
                inset: 0;
                width: 100vw;
                min-height: 100vh;
                max-height: 100vh;
                overflow-y: auto;
                padding: 1rem 1rem 1.5rem;
                border: 0;
                border-radius: 0;
                background: rgba(18, 50, 71, 0.98);
                box-shadow: 0 28px 70px rgba(2, 12, 19, 0.5);
            }

            .mobile-nav-drawer__head {
                display: grid;
                grid-template-columns: minmax(0, 1fr) auto;
                align-items: center;
                gap: 1rem;
                margin-bottom: 0.8rem;
                padding-bottom: 0.75rem;
                border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            }

            .mobile-nav-drawer__head p {
                margin: 0;
                color: #ffffff;
                font-size: 0.95rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .mobile-nav-drawer__close {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                justify-self: end;
                width: 2.5rem;
                height: 2.5rem;
                padding: 0;
                border: 0;
                border-radius: 0;
                background: transparent;
                color: #ffffff;
                font-size: 1.7rem;
                line-height: 1;
                text-align: right;
                cursor: pointer;
            }

            .mobile-nav-drawer__links {
                display: grid;
                gap: 0;
            }

            .mobile-nav-drawer__links a {
                display: block;
                padding: 0.9rem 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.12);
                color: #ffffff;
                font-size: 1rem;
                font-weight: 600;
                text-decoration: none;
            }

            .mobile-nav-drawer__links .button {
                margin-top: 1rem;
                border-bottom: 0;
                justify-content: center;
                text-align: center;
            }
        }

        /* Active page indicator (orange) — applies at all breakpoints */
        .site-nav a.is-active,
        .site-header .site-nav a.is-active,
        .mobile-nav-drawer__links a.is-active {
            color: #f27d2d !important;
            font-weight: 700;
        }
    </style>
    <script defer src="{{ asset('site.js') }}?v={{ $siteJsVersion }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const menuToggle = document.querySelector('[data-mobile-menu-toggle]');
            const mobileMenu = document.querySelector('[data-mobile-menu]');
            const closeButtons = document.querySelectorAll('[data-mobile-menu-close]');
            const body = document.body;
            const mobileQuery = window.matchMedia('(max-width: 767px)');

            if (!menuToggle || !mobileMenu) {
                return;
            }

            const syncMenuState = (isOpen) => {
                const shouldOpen = mobileQuery.matches && isOpen;

                mobileMenu.hidden = !shouldOpen;
                menuToggle.setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');
                body.classList.toggle('has-mobile-menu-open', shouldOpen);
            };

            syncMenuState(false);

            menuToggle.addEventListener('click', (event) => {
                if (!mobileQuery.matches) {
                    return;
                }

                event.preventDefault();
                syncMenuState(mobileMenu.hidden);
            });

            closeButtons.forEach((button) => {
                button.addEventListener('click', () => syncMenuState(false));
            });

            mobileMenu.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', () => syncMenuState(false));
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    syncMenuState(false);
                }
            });

            if (mobileQuery.addEventListener) {
                mobileQuery.addEventListener('change', () => syncMenuState(false));
            } else if (mobileQuery.addListener) {
                mobileQuery.addListener(() => syncMenuState(false));
            }
        });
    </script>
</head>
<body @class(['has-modal-open' => $errors->any(), 'is-homepage' => request()->routeIs('home')]) data-lead-submitted="{{ session('lead_submitted') ? 'true' : 'false' }}">
    <div class="page-shell">
        <header @class(['site-header', 'site-header--home' => request()->routeIs('home')]) data-site-header>
            <div class="container site-header__inner">
                <a class="brand" href="/#top" aria-label="SettleANZ home">
                    <span>
                        <strong>SettleANZ</strong>
                    </span>
                </a>

                <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="mobile-nav-drawer" data-mobile-menu-toggle>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span class="sr-only">Open navigation</span>
                </button>

                <nav id="site-nav" class="site-nav" data-menu>
                    @foreach ($navItems as $item)
                        @php
                            $itemPath = parse_url($item['href'], PHP_URL_PATH) ?: '/';
                            $itemPattern = trim($itemPath, '/');
                            $isActive = $itemPattern === '' ? request()->is('/') : (request()->is($itemPattern) || request()->is($itemPattern . '/*'));
                        @endphp
                        <a href="{{ $item['href'] }}" @class(['is-active' => $isActive])>{{ $item['label'] }}</a>
                    @endforeach
                    <a class="button button--small site-nav__cta" href="/#lead-strip" data-open-lead-modal>Get Free Help</a>
                </nav>

                <a class="button button--small site-header__cta" href="/#lead-strip" data-open-lead-modal>Get Free Help</a>
            </div>
        </header>

        <div class="mobile-nav-drawer" id="mobile-nav-drawer" data-mobile-menu hidden>
            <button class="mobile-nav-drawer__backdrop" type="button" aria-label="Close navigation" data-mobile-menu-close></button>
            <div class="mobile-nav-drawer__panel" role="dialog" aria-modal="true" aria-label="Mobile navigation">
                <div class="mobile-nav-drawer__head">
                    <p>Menu</p>
                    <button class="mobile-nav-drawer__close" type="button" aria-label="Close navigation" data-mobile-menu-close>&times;</button>
                </div>
                <nav class="mobile-nav-drawer__links" aria-label="Mobile navigation links">
                    @foreach ($navItems as $item)
                        @php
                            $itemPath = parse_url($item['href'], PHP_URL_PATH) ?: '/';
                            $itemPattern = trim($itemPath, '/');
                            $isActive = $itemPattern === '' ? request()->is('/') : (request()->is($itemPattern) || request()->is($itemPattern . '/*'));
                        @endphp
                        <a href="{{ $item['href'] }}" @class(['is-active' => $isActive])>{{ $item['label'] }}</a>
                    @endforeach
                    <a class="button button--small" href="/#lead-strip" data-open-lead-modal>Get Free Help</a>
                </nav>
            </div>
        </div>

        @if (session('status'))
            <div class="flash-banner">
                <div class="container">{{ session('status') }}</div>
            </div>
        @endif

        <main>
            @yield('content')
        </main>

        <footer class="site-footer">
            <div class="container site-footer__grid">
                <div>
                    <h3>About SettleANZ</h3>
                    <p>Warm, practical migration and relocation guidance for people building a new life in Australia and New Zealand.</p>
                    <div class="social-row" aria-label="Social links">
                        <a href="{{ $sharedSettings['social_facebook'] }}" aria-label="Facebook">Fb</a>
                        <a href="{{ $sharedSettings['social_instagram'] }}" aria-label="Instagram">Ig</a>
                        <a href="{{ $sharedSettings['social_linkedin'] }}" aria-label="LinkedIn">In</a>
                        <a href="{{ $sharedSettings['social_pinterest'] }}" aria-label="Pinterest">Pi</a>
                    </div>
                </div>
                <div>
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="/new-to-australia">New to Australia</a></li>
                        <li><a href="/settlement-services">Settlement Services</a></li>
                        <li><a href="/about">About</a></li>
                        <li><a href="/contact">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3>Contact</h3>
                    <ul class="footer-contact-list">
                        <li><span class="footer-icon" aria-hidden="true">📍</span><span>P.O. Box 446, Gungahlin ACT 2912</span></li>
                        <li><a href="tel:+61416874058"><span class="footer-icon" aria-hidden="true">📞</span><span>+61 416 874 058</span></a></li>
                        <li><a href="mailto:hello@settleanz.com"><span class="footer-icon" aria-hidden="true">📧</span><span>hello@settleanz.com</span></a></li>
                    </ul>
                </div>
                <div>
                    <h3>Stay Connected</h3>
                    <form class="subscribe-form" action="#" method="post">
                        <label>
                            <span class="sr-only">Email address</span>
                            <input type="email" placeholder="Your email address">
                        </label>
                        <button class="button button--small" type="submit">Subscribe</button>
                    </form>
                </div>
            </div>
            <div class="site-footer__bottom" style="background:#123247;padding:1rem 0;border-top:1px solid rgba(255,255,255,0.08);">
                <div class="container site-footer__bottom-inner">
                    <p>&copy; {{ date('Y') }} SettleANZ. All rights reserved.</p>
                    <div>
                        <a href="{{ route('privacy-policy') }}">Privacy Policy</a>
                        <a href="{{ route('terms-of-service') }}">Terms of Service</a>
                    </div>
                </div>
            </div>
        </footer>

        <div class="lead-modal" data-lead-modal @unless($errors->any()) hidden @endunless>
            <div class="lead-modal__backdrop" data-close-lead-modal></div>
            <div class="lead-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="lead-modal-title">
                <button class="lead-modal__close" type="button" aria-label="Close popup" data-close-lead-modal>&times;</button>
                <div class="lead-modal__header">
                    <span class="lead-modal__badge">Free Starter Guide</span>
                    <h2 id="lead-modal-title">Moving to Australia or New Zealand?</h2>
                    <p class="lead-modal__subtitle">Get the free SettleANZ Starter Guide, everything you need for your first 90 days.</p>
                </div>
                <form class="lead-form lead-form--modal" method="POST" action="{{ route('lead-capture.store') }}">
                    @csrf
                    <input type="hidden" name="form_type" value="popup">
                    <input type="hidden" name="source_page" value="homepage-popup">
                    <div class="lead-form__field">
                        <label for="lead-first-name">First Name</label>
                        <input type="text" id="lead-first-name" name="first_name" value="{{ old('first_name') }}" placeholder="Enter your first name" required>
                        @error('first_name')<small class="lead-form__error">{{ $message }}</small>@enderror
                    </div>
                    <div class="lead-form__field">
                        <label for="lead-email">Email Address</label>
                        <input type="email" id="lead-email" name="email" value="{{ old('email') }}" placeholder="Enter your email address" required>
                        @error('email')<small class="lead-form__error">{{ $message }}</small>@enderror
                    </div>
                    <button class="button button--large button--full lead-form__submit" type="submit">Send Me the Guide</button>
                    <p class="lead-form__hint">No spam. Unsubscribe anytime.</p>
                </form>
            </div>
        </div>

        <div class="lead-modal" data-package-modal hidden>
            <div class="lead-modal__backdrop" data-close-package-modal></div>
            <div class="lead-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="package-modal-title">
                <button class="lead-modal__close" type="button" aria-label="Close popup" data-close-package-modal>&times;</button>
                <div class="lead-modal__header">
                    <span class="lead-modal__badge">Book Your Session</span>
                    <h2 id="package-modal-title">SettleANZ Package Booking</h2>
                    <p class="lead-modal__subtitle" id="package-modal-subtitle">Submit your details and we'll be in touch to confirm your booking.</p>
                </div>
                <form class="lead-form lead-form--modal" id="package-lead-form" method="POST" action="{{ route('lead-capture.store') }}" data-async-form data-success-target="package-form-message">
                    @csrf
                    <input type="hidden" name="form_type" value="package_booking">
                    <input type="hidden" name="source_page" value="settlement-services">
                    <input type="hidden" name="subject" id="package-subject">
                    <div class="lead-form__field">
                        <label for="package-lead-name">Your Name</label>
                        <input type="text" id="package-lead-name" name="name" placeholder="Enter your full name" required>
                    </div>
                    <div class="lead-form__field">
                        <label for="package-lead-email">Email Address</label>
                        <input type="email" id="package-lead-email" name="email" placeholder="Enter your email address" required>
                    </div>
                    <div class="lead-form__field">
                        <label for="package-lead-phone">Phone Number (optional)</label>
                        <input type="tel" id="package-lead-phone" name="phone" placeholder="Your phone number">
                    </div>
                    <button class="button button--large button--full lead-form__submit" type="submit">Submit Booking Request</button>
                    <p class="lead-form__hint">We'll contact you within 24 hours to confirm your session.</p>
                    <p id="package-form-message" class="package-form-message async-form-status" hidden></p>
                </form>
            </div>
        </div>

        <div class="form-modal-overlay" id="packageFormModalOverlay" hidden>
            <div class="form-modal">
                <div class="form-modal__loading" id="packageFormModalLoading">
                    <div class="form-modal__spinner"></div>
                    <h3>Submitting your booking...</h3>
                    <p>Please wait while we confirm your request.</p>
                </div>
                <div class="form-modal__success" id="packageFormModalSuccess" hidden>
                    <div class="form-modal__icon form-modal__icon--success">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <h3>Booking Submitted!</h3>
                    <p id="packageFormModalSuccessMessage">Thank you. We’ve received your package request and will contact you within 24 hours.</p>
                    <button class="button button--large" type="button" data-close-package-form-modal>Got it</button>
                </div>
                <div class="form-modal__error" id="packageFormModalError" hidden>
                    <div class="form-modal__icon form-modal__icon--error">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                    </div>
                    <h3>Oops! Something went wrong</h3>
                    <p id="packageFormModalErrorText">Please try again later.</p>
                    <button class="button button--large" type="button" data-close-package-form-modal>Try Again</button>
                </div>
            </div>
        </div>

        <section class="site-chat" aria-label="AI chat assistant" style="position:fixed;right:1rem;bottom:1rem;z-index:9997;">
            <div class="floating-actions" aria-label="Quick actions" style="position:fixed;right:1rem;bottom:1rem;z-index:9997;display:flex;flex-direction:column;align-items:center;gap:0.75rem;">
                @if(($sharedSettings['ai_assistant_enabled'] ?? '1') === '1')
                    <button class="floating-action floating-action--assistant site-chat-toggle" type="button" data-chat-toggle aria-expanded="false" aria-controls="site-chat-panel" aria-label="Open AI chat assistant" style="appearance:none;-webkit-appearance:none;background:transparent;border:0;box-shadow:none;padding:0;margin:0;">
                        <img src="{{ asset('media/icons/ai_assistance.webp') }}" alt="" class="floating-action__icon" width="56" height="56">
                    </button>
                @endif
                <a class="floating-action floating-action--whatsapp" href="{{ $sharedSettings['footer_whatsapp'] }}" target="_blank" rel="noreferrer" aria-label="Chat on WhatsApp">
                    <img src="{{ asset('media/icons/whatsapp.webp') }}" alt="" class="floating-action__icon" width="56" height="56">
                </a>
            </div>
            @if(($sharedSettings['ai_assistant_enabled'] ?? '1') === '1')
                <div
                    id="site-chat-panel"
                    class="site-chat-panel"
                    data-chat-panel
                    data-chat-greeting="{{ $sharedSettings['ai_assistant_greeting'] }}"
                    hidden
                    style="position:fixed;right:1rem;bottom:6.5rem;z-index:9998;width:min(420px, calc(100vw - 1.5rem));"
                >
                    <div class="site-chat-head">
                        <div class="site-chat-head-main">
                            <h2 class="site-chat-title">{{ $sharedSettings['ai_assistant_title'] }}</h2>
                            <p class="site-chat-sub">{{ $sharedSettings['ai_assistant_subtitle'] }}</p>
                        </div>
                        <div class="site-chat-head-actions">
                            <button class="site-chat-send site-chat-send--secondary" type="button" data-chat-reset>Clear</button>
                            <button class="site-chat-close" type="button" data-chat-close aria-label="Close chat">&times;</button>
                        </div>
                    </div>
                    <div class="site-chat-log" data-chat-log></div>
                    <form class="site-chat-form" data-chat-form>
                        <input class="site-chat-input" type="text" name="message" data-chat-input maxlength="1800" placeholder="Type your message..." required>
                        <button class="site-chat-send" type="submit">Send</button>
                    </form>
                </div>
            @endif
        </section>

    </div>
    @yield('page_scripts')

<style>
.pro-select-wrapper { position: relative; width: 100%; }
.pro-select-native { position: absolute; opacity: 0; pointer-events: none; width: 100%; height: 100%; top: 0; left: 0; }
.pro-select-display {
    width: 100%; padding: 0.7rem 2.5rem 0.7rem 1rem;
    border: 1px solid rgba(16, 88, 98, 0.16); border-radius: 6px;
    background: #fff; color: #2c3a47; font-size: 0.9rem;
    cursor: pointer; transition: border-color 0.2s, box-shadow 0.2s;
    position: relative; box-sizing: border-box; min-height: 38px; display: flex; align-items: center;
}
.pro-select-display::after {
    content: ''; position: absolute; right: 0.7rem; top: 50%; transform: translateY(-50%);
    width: 16px; height: 16px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23065e5b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: center; background-size: contain; transition: transform 0.2s;
}
.pro-select-wrapper.is-open .pro-select-display { border-color: #0b7a75; box-shadow: 0 0 0 3px rgba(11, 122, 117, 0.1); }
.pro-select-wrapper.is-open .pro-select-display::after { transform: translateY(-50%) rotate(180deg); }
.pro-select-dropdown {
    position: absolute; top: calc(100% + 6px); left: 0; right: 0;
    background: #fff; border: 1px solid rgba(16, 88, 98, 0.12);
    border-radius: 12px; box-shadow: 0 8px 24px rgba(6, 54, 52, 0.12);
    z-index: 100; opacity: 0; visibility: hidden; transform: translateY(-8px);
    transition: all 0.2s ease; max-height: 280px; overflow-y: auto;
}
.pro-select-wrapper.is-open .pro-select-dropdown { opacity: 1; visibility: visible; transform: translateY(0); }
.pro-select-option { padding: 0.85rem 1.1rem; color: #2c3a47; cursor: pointer; transition: all 0.15s; }
.pro-select-option:first-child { border-radius: 11px 11px 0 0; }
.pro-select-option:last-child { border-radius: 0 0 11px 11px; }
.pro-select-option:hover, .pro-select-option.is-selected { background: #14a394; color: #fff; font-weight: 500; }
.pro-select-option.is-selected { font-weight: 600; background: #0b7a75; }
</style>
<script>
(function() {
    function initProDropdowns() {
        document.querySelectorAll('select.pro-select').forEach(function(select) {
            if (select.dataset.proSelectInitialized) return;
            select.dataset.proSelectInitialized = 'true';

            var wrapper = document.createElement('div');
            wrapper.className = 'pro-select-wrapper';
            select.parentNode.insertBefore(wrapper, select);
            wrapper.appendChild(select);
            select.classList.add('pro-select-native');

            var display = document.createElement('div');
            display.className = 'pro-select-display';
            display.style.color = '#2c3a47';
            var selectedOption = select.options[select.selectedIndex];
            display.textContent = (selectedOption && selectedOption.value) ? selectedOption.text : 'Select option';
            if (selectedOption && !selectedOption.value) {
                display.style.color = '#999';
            }
            wrapper.appendChild(display);

            var dropdown = document.createElement('div');
            dropdown.className = 'pro-select-dropdown';
            wrapper.appendChild(dropdown);

            Array.from(select.options).forEach(function(option) {
                var opt = document.createElement('div');
                opt.className = 'pro-select-option';
                opt.textContent = option.text;
                opt.dataset.value = option.value;
                if (option.selected) opt.classList.add('is-selected');
                dropdown.appendChild(opt);

                opt.addEventListener('click', function() {
                    select.value = this.dataset.value;
                    var selectedOpt = select.options[select.selectedIndex];
                    display.textContent = selectedOpt ? selectedOpt.text : this.textContent;
                    display.style.color = '#2c3a47';
                    dropdown.querySelectorAll('.pro-select-option').forEach(function(o) { o.classList.remove('is-selected'); });
                    this.classList.add('is-selected');
                    wrapper.classList.remove('is-open');
                    select.dispatchEvent(new Event('change'));
                });
            });

            display.addEventListener('click', function(e) {
                e.stopPropagation();
                document.querySelectorAll('.pro-select-wrapper.is-open').forEach(function(w) { w.classList.remove('is-open'); });
                wrapper.classList.toggle('is-open');
            });
        });
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.pro-select-wrapper')) {
            document.querySelectorAll('.pro-select-wrapper.is-open').forEach(function(w) { w.classList.remove('is-open'); });
        }
    });

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initProDropdowns);
    } else {
        initProDropdowns();
    }
})();
</script>
@stack('scripts')
</body>
</html>
























