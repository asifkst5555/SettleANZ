<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $metaTitle ?? 'SettleANZ' }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'Migration, housing, and relocation guidance for new arrivals.' }}">
    @php
        $siteCssVersion = file_exists(public_path('site.css')) ? filemtime(public_path('site.css')) : null;
        $siteJsVersion = file_exists(public_path('site.js')) ? filemtime(public_path('site.js')) : null;
    @endphp
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('site.css') }}{{ $siteCssVersion ? '?v=' . $siteCssVersion : '' }}">
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

        @keyframes site-chat-thinking {
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
    </style>
    <script defer src="{{ asset('site.js') }}{{ $siteJsVersion ? '?v=' . $siteJsVersion : '' }}"></script>
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

                <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="site-nav" data-menu-toggle>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span class="sr-only">Open navigation</span>
                </button>

                <nav id="site-nav" class="site-nav" data-menu>
                    @foreach ($navItems as $item)
                        <a href="{{ $item['href'] }}">{{ $item['label'] }}</a>
                    @endforeach
                    <a class="button button--small site-nav__cta" href="/#lead-strip" data-open-lead-modal>Get Free Help</a>
                </nav>

                <a class="button button--small site-header__cta" href="/#lead-strip" data-open-lead-modal>Get Free Help</a>
            </div>
        </header>

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
                        <li><a href="/housing">Housing</a></li>
                        <li><a href="/banking">Banking</a></li>
                        <li><a href="/migration-services">Migration</a></li>
                        <li><a href="/directory">Directory</a></li>
                        <li><a href="/contact">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3>Top Guides</h3>
                    <ul>
                        @foreach (($guides ?? []) as $guide)
                            <li><a href="{{ $guide['href'] ?? '/#guides' }}">{{ $guide['title'] }}</a></li>
                        @endforeach
                        <li><a href="/migration-services">Migration Services</a></li>
                        <li><a href="/blog">Money Transfer Guide</a></li>
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
                    <a class="footer-whatsapp" href="{{ $sharedSettings['footer_whatsapp'] }}" target="_blank" rel="noreferrer">WhatsApp click-to-chat</a>
                </div>
            </div>
            <div class="site-footer__bottom">
                <div class="container site-footer__bottom-inner">
                    <p>&copy; {{ date('Y') }} SettleANZ. All rights reserved.</p>
                    <div>
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms of Service</a>
                    </div>
                </div>
            </div>
        </footer>

        <div class="lead-modal" data-lead-modal @unless($errors->any()) hidden @endunless>
            <div class="lead-modal__backdrop" data-close-lead-modal></div>
            <div class="lead-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="lead-modal-title">
                <button class="lead-modal__close" type="button" aria-label="Close popup" data-close-lead-modal>&times;</button>
                <p class="eyebrow">Free starter guide</p>
                <h2 id="lead-modal-title">Moving to Australia or New Zealand?</h2>
                <p class="lead-modal__copy">Get the free SettleANZ Starter Guide, everything you need for your first 90 days.</p>
                <form class="lead-form lead-form--modal" method="POST" action="{{ route('lead-capture.store') }}">
                    @csrf
                    <input type="hidden" name="form_type" value="popup">
                    <input type="hidden" name="source_page" value="homepage-popup">
                    <label>
                        <span>First Name</span>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required>
                        @error('first_name')<small>{{ $message }}</small>@enderror
                    </label>
                    <label>
                        <span>Email Address</span>
                        <input type="email" name="email" value="{{ old('email') }}" required>
                        @error('email')<small>{{ $message }}</small>@enderror
                    </label>
                    <button class="button button--large button--full" type="submit">Send Me the Guide</button>
                    <small class="lead-form__hint">No spam. Unsubscribe anytime.</small>
                </form>
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
</body>
</html>








