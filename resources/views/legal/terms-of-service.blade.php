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

        .legal-notice {
            background: #fff8f0;
            border: 1.5px solid #f27d2d;
            border-left: 5px solid #f27d2d;
            border-radius: 10px;
            padding: 1.25rem 1.5rem;
            margin: 1.5rem 0 2rem;
        }

        .legal-notice__title {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: #b85a00;
            margin: 0 0 0.5rem;
        }

        .legal-notice p,
        .legal-notice li {
            color: #5c3a0a !important;
            font-size: 0.97rem !important;
        }

        .legal-notice ul {
            margin: 0.4rem 0 0 1.2rem;
            padding: 0;
        }
    </style>
@endsection

@section('content')
    <div class="legal-page">
        <div class="container">
            <header class="legal-page__header">
                <p class="eyebrow">Legal</p>
                <h1>Terms of Service</h1>
                <p>Last updated: {{ \Carbon\Carbon::parse('2026-01-01')->format('F Y') }}</p>
            </header>

            <article class="legal-page__body">
                <h2>1. Agreement</h2>
                <p>These Terms of Service (“Terms”) govern your use of the SettleANZ website, guides, newsletters, service bookings, and directory (collectively, the “Service”). By accessing or using the Service, you agree to be bound by these Terms. If you do not agree, please do not use the Service.</p>

                <h2>2. Who We Are</h2>
                <p>SettleANZ provides practical housing, banking, healthcare, and relocation guidance to help new arrivals settle into life in Australia and New Zealand. We connect new arrivals with vetted partners and offer settlement service packages tailored to different stages of the relocation journey. We are not a registered migration agent, law firm, financial adviser, or real-estate agency.</p>

                <h2>3. No Migration Advice — Important Disclaimer</h2>

                <div class="legal-notice">
                    <p class="legal-notice__title">⚠ SettleANZ Does Not Provide Migration Advice</p>
                    <p>In Australia, migration advice is a <strong>regulated service</strong> under the <em>Migration Act 1958</em>. It may only be provided by:</p>
                    <ul>
                        <li><strong>Registered Migration Agents</strong> — registered with the <strong>Office of the Migration Agents Registration Authority (OMARA)</strong>; and</li>
                        <li><strong>Australian Legal Practitioners</strong> — admitted solicitors or barristers who hold a current practising certificate.</li>
                    </ul>
                    <p>Providing migration advice without registration is a <strong>criminal offence</strong> in Australia. SettleANZ does <strong>not</strong> give migration advice, assess your visa options, or recommend visa pathways. Any information on this site relating to visas or immigration is general in nature, for awareness only, and must not be relied upon as advice.</p>
                    <p>If you need migration advice, please consult a <strong>registered migration agent</strong> or <strong>immigration lawyer</strong>. You can verify an agent's registration at <strong>mara.gov.au</strong> or search the OMARA public register.</p>
                </div>

                <h2>4. Informational Content — Not Professional Advice</h2>
                <p>All articles, guides, checklists, and general content on this site are provided for <strong>informational purposes only</strong>. They are based on the founder's personal experience and publicly available information at the time of writing.</p>
                <ul>
                    <li>They are <strong>not</strong> legal, migration, tax, medical, or financial advice.</li>
                    <li>Rules, fees, and processes change frequently — always verify with the relevant authority (e.g. Department of Home Affairs, ATO, Medicare, state tenancy authorities).</li>
                    <li>For visa and immigration matters, you <strong>must</strong> consult a registered migration agent (OMARA) or a licensed immigration lawyer. Do not rely on any content from this site as migration advice.</li>
                    <li>For financial matters, consult a licensed financial adviser registered with ASIC.</li>
                </ul>

                <h2>5. Services and Bookings</h2>
                <p>When you book a paid service package (e.g. pre-arrival strategy, arrival concierge, settle-in support), the scope, deliverables, and pricing will be confirmed in writing before work begins.</p>
                <ul>
                    <li>Services are delivered by the founder or vetted providers, with reasonable care and within agreed timelines.</li>
                    <li>Outcomes depend on information you provide. Missing or inaccurate information may affect delivery.</li>
                    <li>All pricing is listed in Australian Dollars (AUD) unless noted otherwise. Payment terms will be confirmed before booking.</li>
                </ul>

                <h3>Cancellations &amp; refunds</h3>
                <p>If you cancel before work begins, we will refund fees minus any non-recoverable third-party costs. Once work has started or deliverables have been provided, refunds are assessed case by case. Contact us if you’re not satisfied — we will work in good faith to resolve issues.</p>

                <h2>6. Third-Party Partners and Referrals</h2>
                <p>Our directory and guides include third-party partners (e.g. relocation specialists, banks, insurance providers, short-term accommodation). We do our best to vet partners, but:</p>
                <ul>
                    <li>Each partner operates independently and sets their own fees, terms, and service standards.</li>
                    <li>Your contract is with the partner, not SettleANZ.</li>
                    <li>Some links may be affiliate or referral links — we disclose this where required and only recommend providers we would use ourselves.</li>
                </ul>

                <h2>7. Directory Listings</h2>
                <p>Businesses applying for a SettleANZ Directory listing must provide accurate information and hold any required licences. We reserve the right to accept, reject, or remove any listing at our discretion, especially where a provider does not meet quality standards or receives verified complaints.</p>

                <h2>8. Intellectual Property</h2>
                <p>All content on SettleANZ — including text, graphics, logos, and downloads — is the property of SettleANZ or its licensors and is protected by copyright. You may read, print, and share our guides for personal use. You may not copy, republish, sell, or repurpose content for commercial use without written permission.</p>

                <h2>9. User Conduct</h2>
                <p>When using the Service, you agree not to:</p>
                <ul>
                    <li>Submit false, misleading, or fraudulent information.</li>
                    <li>Use the site to harass, scam, or harm other users or partners.</li>
                    <li>Attempt to break, overload, or reverse-engineer the platform.</li>
                    <li>Scrape or bulk-download our content without permission.</li>
                </ul>

                <h2>10. Limitation of Liability</h2>
                <p>To the fullest extent permitted by law, SettleANZ is not liable for indirect, incidental, or consequential losses arising from your use of the Service, including decisions you make based on our content or partner referrals. Our total liability for any claim related to a paid service is limited to the amount you paid for that service.</p>
                <p>Nothing in these Terms excludes any rights that cannot be excluded under Australian Consumer Law.</p>

                <h2>11. Privacy</h2>
                <p>Your use of the Service is also governed by our <a href="{{ route('privacy-policy') }}">Privacy Policy</a>, which explains how we collect and handle your information.</p>

                <h2>12. Changes to the Service or Terms</h2>
                <p>We may update the Service, pricing, or these Terms at any time. We will update the “Last updated” date above. Continued use of the Service after changes means you accept the updated Terms.</p>

                <h2>13. Governing Law</h2>
                <p>These Terms are governed by the laws of Australia. Any disputes will be handled in the courts of Australia, unless otherwise required by local consumer protection law.</p>

                <h2>14. Contact</h2>
                <p>Questions about these Terms? Email <a href="mailto:hello@settleanz.com">hello@settleanz.com</a> or use the <a href="{{ route('contact') }}">contact page</a>.</p>
            </article>
        </div>
    </div>
@endsection
