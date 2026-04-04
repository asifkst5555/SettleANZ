<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $metaTitle ?? 'SettleANZ' }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'Migration, housing, and relocation guidance for new arrivals.' }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('site.css') }}">
    <script defer src="{{ asset('site.js') }}"></script>
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
                    <a class="button button--small" href="/#lead-strip" data-open-lead-modal>Get Free Help</a>
                </nav>
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

        <section class="site-chat" aria-label="AI chat assistant">
            <div class="floating-actions" aria-label="Quick actions">
                <a class="floating-action floating-action--whatsapp" href="{{ $sharedSettings['footer_whatsapp'] }}" target="_blank" rel="noreferrer" aria-label="Chat on WhatsApp">
                    <img src="{{ asset('media/icons/whatsapp.webp') }}" alt="" class="floating-action__icon">
                </a>
                @if(($sharedSettings['ai_assistant_enabled'] ?? '1') === '1')
                    <button class="floating-action floating-action--assistant site-chat-toggle" type="button" data-chat-toggle aria-expanded="false" aria-controls="site-chat-panel" aria-label="Open AI chat assistant">
                        <img src="{{ asset('media/icons/ai_assistance.webp') }}" alt="" class="floating-action__icon">
                    </button>
                @endif
            </div>
            @if(($sharedSettings['ai_assistant_enabled'] ?? '1') === '1')
                <div
                    id="site-chat-panel"
                    class="site-chat-panel"
                    data-chat-panel
                    data-chat-greeting="{{ $sharedSettings['ai_assistant_greeting'] }}"
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

