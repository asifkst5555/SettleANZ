@extends('layouts.app')

@section('page_styles')
    <style>
        .legal-page {
            background: linear-gradient(180deg, #f6f8fb 0%, #ffffff 40%, #f7fbfa 100%);
            padding: 4rem 0 5rem;
        }

        .legal-page .container {
            width: min(calc(100% - 2rem), 860px);
            margin: 0 auto;
        }

        .legal-page__header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .legal-page__header .eyebrow {
            color: var(--primary-brand);
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .legal-page__header h1 {
            margin: 0.6rem 0 0.75rem;
            color: var(--primary-dark);
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: clamp(2.2rem, 4.2vw, 3.2rem);
            line-height: 1.1;
            letter-spacing: -0.02em;
        }

        .legal-page__header p {
            color: #5e707b;
            font-size: 0.95rem;
        }

        .legal-page__body {
            padding: 2.5rem clamp(1.5rem, 3vw, 3rem);
            border: 1px solid rgba(15, 139, 141, 0.1);
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 16px 42px rgba(12, 43, 63, 0.06);
        }

        .legal-page__body h2 {
            margin: 2rem 0 0.75rem;
            color: var(--primary-dark);
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 1.35rem;
            letter-spacing: -0.01em;
        }

        .legal-page__body h2:first-child {
            margin-top: 0;
        }

        .legal-page__body h3 {
            margin: 1.4rem 0 0.5rem;
            color: var(--primary-dark);
            font-size: 1.08rem;
        }

        .legal-page__body p,
        .legal-page__body li {
            color: #39474f;
            font-size: 1rem;
            line-height: 1.75;
        }

        .legal-page__body ul,
        .legal-page__body ol {
            margin: 0.5rem 0 1rem 1.25rem;
            padding: 0;
        }

        .legal-page__body li + li {
            margin-top: 0.35rem;
        }

        .legal-page__body a {
            color: var(--primary-brand);
            text-decoration: underline;
        }

        .legal-page__body strong {
            color: var(--primary-dark);
        }
    </style>
@endsection

@section('content')
    <div class="legal-page">
        <div class="container">
            <header class="legal-page__header">
                <p class="eyebrow">Legal</p>
                <h1>Privacy Policy</h1>
                <p>Last updated: {{ \Carbon\Carbon::parse('2026-01-01')->format('F Y') }}</p>
            </header>

            <article class="legal-page__body">
                <h2>1. Introduction</h2>
                <p>SettleANZ (“we,” “us,” or “our”) provides migration, housing, banking, and relocation guidance for people moving to Australia and New Zealand. This Privacy Policy explains how we collect, use, store, and share information when you use our website, read our guides, book a service, subscribe to our newsletter, or submit a lead form.</p>
                <p>By using this website, you agree to the practices described below. If you do not agree, please do not use the site or submit any personal information.</p>

                <h2>2. Information We Collect</h2>
                <h3>Information you provide directly</h3>
                <ul>
                    <li><strong>Contact details</strong> — name, email, phone number, country of origin, and preferred destination city when you fill out a lead form, subscribe to our newsletter, or contact us.</li>
                    <li><strong>Journey stage</strong> — pre-arrival, arrival, or settled status, plus any context you share about your situation.</li>
                    <li><strong>Service bookings</strong> — details required to deliver the package you select (e.g. flight details for airport meet &amp; greet, suburb preferences for housing support).</li>
                    <li><strong>Directory applications</strong> — business name, website, contact person, and service category, if you apply to be listed in the SettleANZ Directory.</li>
                </ul>

                <h3>Information collected automatically</h3>
                <ul>
                    <li><strong>Usage data</strong> — pages viewed, links clicked, approximate location (derived from IP), device type, browser, and referral source.</li>
                    <li><strong>Cookies &amp; similar technologies</strong> — small data files that remember your preferences and help us measure site performance.</li>
                </ul>

                <h2>3. How We Use Your Information</h2>
                <ul>
                    <li>To respond to your enquiries and deliver the services you book.</li>
                    <li>To send requested guides, checklists, or newsletter content.</li>
                    <li>To match you with the most relevant resources or vetted partners.</li>
                    <li>To improve our website, content, and service packages.</li>
                    <li>To comply with legal obligations and prevent misuse of the service.</li>
                </ul>

                <h2>4. Sharing Your Information</h2>
                <p>We do not sell your personal information. We only share it in these limited cases:</p>
                <ul>
                    <li><strong>Vetted partners</strong> — when you explicitly request an introduction to a directory partner, relocation specialist, or migration professional.</li>
                    <li><strong>Service providers</strong> — hosting, email delivery, analytics, and payment processing, under data protection agreements.</li>
                    <li><strong>Legal requirements</strong> — if required by law, court order, or to protect our rights, users, or the public.</li>
                </ul>

                <h2>5. Cookies</h2>
                <p>We use cookies to keep your preferences, track basic analytics, and remember form inputs during a session. You can disable cookies in your browser settings, but some features (for example, forms and saved preferences) may not work correctly.</p>

                <h2>6. Data Retention</h2>
                <p>We keep your information only as long as needed to provide the service you requested or as required by law. Newsletter data is retained until you unsubscribe. Lead enquiries are retained for up to 24 months unless you request earlier deletion.</p>

                <h2>7. Your Rights</h2>
                <p>You can contact us at any time to:</p>
                <ul>
                    <li>Access a copy of the personal information we hold about you.</li>
                    <li>Correct inaccurate or outdated details.</li>
                    <li>Request deletion of your information (subject to legal obligations).</li>
                    <li>Unsubscribe from marketing emails using the link in every newsletter.</li>
                </ul>
                <p>Email <a href="mailto:hello@settleanz.com">hello@settleanz.com</a> to exercise any of these rights.</p>

                <h2>8. Security</h2>
                <p>We use industry-standard encryption, access controls, and secure hosting to protect your data. No internet transmission is 100% secure, so please avoid sending highly sensitive documents by email when not explicitly requested.</p>

                <h2>9. Third-Party Links</h2>
                <p>Our guides may link to third-party services (for example, banking providers, insurance, or accommodation platforms). Those providers have their own privacy policies; we are not responsible for their practices.</p>

                <h2>10. Children</h2>
                <p>SettleANZ is intended for adults relocating to Australia or New Zealand. We do not knowingly collect information from anyone under 16.</p>

                <h2>11. Changes to this Policy</h2>
                <p>We may update this policy to reflect changes in our services or legal requirements. We will update the “Last updated” date above and, for significant changes, notify you by email if we have your address.</p>

                <h2>12. Contact</h2>
                <p>Questions about this Privacy Policy or how we handle your data? Email us at <a href="mailto:hello@settleanz.com">hello@settleanz.com</a> or use the <a href="{{ route('contact') }}">contact page</a>.</p>
            </article>
        </div>
    </div>
@endsection
