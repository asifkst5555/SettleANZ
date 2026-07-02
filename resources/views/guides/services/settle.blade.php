@extends('layouts.app')

@section('page_styles')
    <style>
        .service-detail-page {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at top right, rgba(232, 119, 58, 0.08), transparent 35%),
                linear-gradient(180deg, #fdfbfa 0%, #ffffff 20%, #f7fbfa 100%);
        }

        .service-hero {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            min-height: 520px;
            padding: 80px 0;
            background: linear-gradient(140deg, #0a524f 0%, #0c6a67 36%, #11807a 100%);
            color: #ffffff;
        }

        .service-hero::before {
            content: '';
            position: absolute;
            top: -150px;
            right: -100px;
            width: 500px;
            height: 500px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(232, 119, 58, 0.12) 0%, transparent 70%);
            pointer-events: none;
            z-index: 1;
        }

        .service-hero::after {
            content: '';
            position: absolute;
            bottom: -150px;
            left: -100px;
            width: 400px;
            height: 400px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(159, 225, 203, 0.1) 0%, transparent 70%);
            pointer-events: none;
            z-index: 1;
        }

        .service-hero__row {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 4rem;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .service-hero__content {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            text-align: left;
        }

        .service-hero__badge {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 12px;
            font-weight: 700;
            color: #9FE1CB;
            background: rgba(159, 225, 203, 0.15);
            border: 1px solid rgba(159, 225, 203, 0.25);
            padding: 6px 14px;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 1.5rem;
            display: inline-block;
            backdrop-filter: blur(4px);
        }

        .service-hero h1 {
            font-size: clamp(2.2rem, 3.8vw, 3rem);
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.02em;
            margin-top: 0;
            margin-bottom: 1.25rem;
            color: #ffffff;
        }

        .service-hero__subhead {
            font-size: clamp(1.05rem, 2vw, 1.2rem);
            line-height: 1.65;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
            font-weight: 400;
        }

        .service-hero__highlights {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-top: 0.5rem;
        }

        .service-hero__highlight-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.85);
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            padding: 6px 12px;
            border-radius: 50px;
            backdrop-filter: blur(4px);
        }

        .service-hero__highlight-icon {
            width: 16px;
            height: 16px;
            color: #9FE1CB;
            flex-shrink: 0;
        }

        .service-hero__visual-container {
            display: flex;
            justify-content: flex-end;
            width: 100%;
        }

        .service-hero__visual {
            position: relative;
            width: 100%;
            max-width: 420px;
            aspect-ratio: 4 / 3;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .service-hero__visual img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        @media (max-width: 991px) {
            .service-hero {
                padding: 60px 0;
                min-height: auto;
            }
            .service-hero__row {
                grid-template-columns: 1fr;
                gap: 3rem;
                text-align: center;
            }
            .service-hero__content {
                align-items: center;
                text-align: center;
            }
            .service-hero__highlights {
                justify-content: center;
            }
            .service-hero__visual-container {
                justify-content: center;
            }
            .service-hero__visual {
                max-width: 380px;
            }
        }

        .packages-section {
            padding: 5rem 0;
        }

        /* New Flagship Layout styling */
        .flagship-body__row {
            display: grid;
            grid-template-columns: 1.25fr 0.75fr;
            gap: 4rem;
            align-items: start;
        }

        /* Left Column Details */
        .flagship-details__title {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--body-text);
            margin-bottom: 0.5rem;
            line-height: 1.2;
        }

        .flagship-details__intro {
            font-size: 1.1rem;
            color: var(--secondary-text);
            margin-bottom: 3rem;
            line-height: 1.6;
        }

        /* Timeline/Phases */
        .flagship-timeline {
            position: relative;
            margin-bottom: 4rem;
            padding-left: 2rem;
            border-left: 2px solid rgba(16, 88, 98, 0.1);
        }

        .flagship-timeline__item {
            position: relative;
            margin-bottom: 2.5rem;
        }

        .flagship-timeline__item:last-child {
            margin-bottom: 0;
        }

        .flagship-timeline__bullet {
            position: absolute;
            left: calc(-2rem - 9px);
            top: 2px;
            width: 16px;
            height: 16px;
            border-radius: 99px;
            background: var(--primary-brand);
            border: 4px solid #ffffff;
            box-shadow: 0 0 0 4px rgba(16, 88, 98, 0.15);
        }

        .flagship-timeline__phase {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--primary-brand);
            margin-bottom: 0.25rem;
            display: block;
        }

        .flagship-timeline__title {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--body-text);
            margin-bottom: 0.5rem;
        }

        .flagship-timeline__desc {
            font-size: 0.95rem;
            color: var(--secondary-text);
            line-height: 1.5;
        }

        /* Inclusions Grid */
        .flagship-inclusions__title {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--body-text);
            margin-bottom: 1.5rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(16, 88, 98, 0.08);
        }

        .flagship-inclusions__grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .flagship-inclusion__card {
            background: #ffffff;
            border: 1px solid rgba(16, 88, 98, 0.06);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(10, 35, 45, 0.02);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .flagship-inclusion__card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(10, 35, 45, 0.04);
            border-color: rgba(16, 88, 98, 0.12);
        }

        .flagship-inclusion__icon-box {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: rgba(159, 225, 203, 0.15);
            color: var(--primary-brand);
            margin-bottom: 1rem;
        }

        .flagship-inclusion__card-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--body-text);
            margin-bottom: 0.5rem;
        }

        .flagship-inclusion__card-desc {
            font-size: 0.88rem;
            color: var(--secondary-text);
            line-height: 1.5;
        }

        /* Sidebar Sticky Box */
        .flagship-sidebar {
            position: sticky;
            top: 100px;
            z-index: 10;
        }

        .flagship-sticky-card {
            background: #ffffff;
            border: 1px solid rgba(16, 88, 98, 0.08);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 12px 36px rgba(10, 35, 45, 0.06);
        }

        .flagship-sticky-card__image-container {
            width: 100%;
            aspect-ratio: 16 / 10;
            overflow: hidden;
            border-bottom: 1px solid rgba(16, 88, 98, 0.06);
        }

        .flagship-sticky-card__image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .flagship-sticky-card:hover .flagship-sticky-card__image {
            transform: scale(1.03);
        }

        .flagship-sticky-card__body {
            padding: 2rem;
        }

        .flagship-sticky-card__badge {
            font-size: 0.68rem;
            font-weight: 800;
            color: var(--cta-accent);
            background: rgba(232, 119, 58, 0.1);
            padding: 4px 8px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .flagship-sticky-card__title {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--body-text);
            margin-bottom: 0.5rem;
        }

        .flagship-sticky-card__tagline {
            font-size: 0.95rem;
            color: var(--secondary-text);
            margin-bottom: 1.5rem;
            line-height: 1.4;
        }

        .flagship-checklist {
            list-style: none;
            padding: 0;
            margin: 0 0 1.75rem;
            display: grid;
            gap: 0.75rem;
        }

        .flagship-checklist__item {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            font-size: 0.88rem;
            color: var(--body-text);
            font-weight: 600;
        }

        .flagship-checklist__icon {
            width: 16px;
            height: 16px;
            color: #10B981;
            flex-shrink: 0;
        }

        .flagship-sticky-card__price-box {
            border-top: 1px solid rgba(16, 88, 98, 0.06);
            padding-top: 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .flagship-sticky-card__price-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--secondary-text);
            margin-bottom: 0.25rem;
            font-weight: 600;
        }

        .flagship-sticky-card__price-val {
            font-size: 2.2rem;
            font-weight: 850;
            color: var(--body-text);
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            letter-spacing: -0.02em;
        }

        .package-cta-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 1rem 1.5rem;
            background: var(--primary-brand);
            color: #ffffff;
            font-weight: 700;
            font-size: 1.05rem;
            border-radius: var(--radius-button);
            text-decoration: none;
            transition: background 0.25s, transform 0.2s;
            box-shadow: 0 4px 12px rgba(11, 122, 117, 0.2);
            border: 0;
            cursor: pointer;
        }

        .package-cta-btn:hover {
            background: var(--primary-dark);
            color: #ffffff;
            transform: translateY(-2px);
        }

        /* Disclaimer Styling */
        .disclaimer-section {
            padding: 0 0 5rem;
        }

        .disclaimer-card {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 16px;
            padding: 2rem;
            font-size: 0.9rem;
            line-height: 1.6;
            color: var(--secondary-text);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.02);
        }

        .disclaimer-card h3 {
            font-size: 1.05rem;
            color: var(--body-text);
            font-weight: 700;
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Trust Strip styling */
        .trust-strip {
            background: rgba(16, 88, 98, 0.02);
            border-top: 1px solid rgba(16, 88, 98, 0.04);
            border-bottom: 1px solid rgba(16, 88, 98, 0.04);
            padding: 2.5rem 0;
            margin-bottom: 4rem;
        }

        .trust-strip__row {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 2rem;
        }

        .trust-strip__item {
            text-align: center;
            flex: 1;
            min-width: 200px;
        }

        .trust-strip__value {
            font-size: 2.2rem;
            font-weight: 850;
            color: var(--primary-brand);
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            margin-bottom: 0.25rem;
        }

        .trust-strip__label {
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--secondary-text);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        @media (max-width: 991px) {
            .flagship-body__row {
                grid-template-columns: 1fr;
                gap: 3rem;
            }
            .flagship-sidebar {
                position: static;
            }
            .flagship-inclusions__grid {
                grid-template-columns: 1fr;
            }
            .flagship-inclusion__card {
                grid-column: auto !important;
            }
            .trust-strip__row {
                flex-direction: column;
                align-items: center;
            }
            .flagship-details__title,
            .flagship-details__intro {
                text-align: center;
            }
        }

        @media (max-width: 767px) {
            .disclaimer-card {
                text-align: center;
            }
        }
    </style>
@endsection

@section('content')
    <div class="service-detail-page">
        <!-- Hero section -->
        <section class="service-hero">
            <div class="container">
                <div class="service-hero__row">
                    <div class="service-hero__content">
                        <span class="service-hero__badge">Stage 02. Settle</span>
                        <h1>Settle: Establish Your Foundation</h1>
                        <p class="service-hero__subhead">
                            Once you’ve arrived, our ‘Settle’ services focus on helping you establish your new life, from finding a home to setting up essential services.
                        </p>
                        <div class="service-hero__highlights">
                            <span class="service-hero__highlight-pill">
                                <svg class="service-hero__highlight-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                Rental Assistance
                            </span>
                            <span class="service-hero__highlight-pill">
                                <svg class="service-hero__highlight-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                School Placement
                            </span>
                            <span class="service-hero__highlight-pill">
                                <svg class="service-hero__highlight-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                Financial Setup
                            </span>
                        </div>
                    </div>
                    <div class="service-hero__visual-container">
                        <div class="service-hero__visual">
                            <img src="{{ asset('media/services/Month 1–3.webp') }}" alt="Stage 02: Settle" />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Packages Section -->
        <section class="packages-section">
            <div class="container">
                <div class="flagship-body__row">
                    <!-- Left column flagship details -->
                    <div class="flagship-details">
                        <h2 class="flagship-details__title">Guaranteed Placement & Complete Integration</h2>
                        <p class="flagship-details__intro">
                            We hold your hand through the most critical 90 days of your migration journey. Our team handles the search, setups, and integration so you can focus on starting your new life.
                        </p>

                        <!-- Timeline / Steps -->
                        <div class="flagship-timeline">
                            <div class="flagship-timeline__item">
                                <div class="flagship-timeline__bullet"></div>
                                <span class="flagship-timeline__phase">Phase 01: Pre-Arrival Strategy</span>
                                <h3 class="flagship-timeline__title">Tailored Profile & Suburb Matching</h3>
                                <p class="flagship-timeline__desc">
                                    Before you leave, we align on your target suburbs, budget, public transport needs, and preferred schooling profiles so your search is highly focused from day one.
                                </p>
                            </div>
                            <div class="flagship-timeline__item">
                                <div class="flagship-timeline__bullet"></div>
                                <span class="flagship-timeline__phase">Phase 02: On-the-Ground Active Search</span>
                                <h3 class="flagship-timeline__title">Guaranteed Rental & School Finding</h3>
                                <p class="flagship-timeline__desc">
                                    We attend open-home inspections on your behalf, provide virtual video walk-throughs, write and optimize applications, and consult with top schools for enrollment.
                                </p>
                            </div>
                            <div class="flagship-timeline__item">
                                <div class="flagship-timeline__bullet"></div>
                                <span class="flagship-timeline__phase">Phase 03: Settlement & Integration</span>
                                <h3 class="flagship-timeline__title">Administrative Setup & Community Connections</h3>
                                <p class="flagship-timeline__desc">
                                    We assist you with banking setups, tax file numbers (TFN), utility connections, and introduce you to local cultural groups, religious centers, and professional networks.
                                </p>
                            </div>
                        </div>

                        <!-- Grid Showcase of Inclusions -->
                        <h3 class="flagship-inclusions__title">Everything Included in Your 90-Day Concierge Support</h3>
                        <div class="flagship-inclusions__grid">
                            <div class="flagship-inclusion__card">
                                <div class="flagship-inclusion__icon-box">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                </div>
                                <h4 class="flagship-inclusion__card-title">Guaranteed Rental Finding Assistance</h4>
                                <p class="flagship-inclusion__card-desc">Dedicated support to secure your rental, including inspections, walkthroughs, and application submission on your behalf. (Value: $2,800+ AUD in saved time and temporary housing)</p>
                            </div>
                            <div class="flagship-inclusion__card">
                                <div class="flagship-inclusion__icon-box">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                                <h4 class="flagship-inclusion__card-title">School Placement (Culturally Matched)</h4>
                                <p class="flagship-inclusion__card-desc">We find schools that fit your community, language, and children’s needs, beyond just ratings.</p>
                            </div>
                            <div class="flagship-inclusion__card">
                                <div class="flagship-inclusion__icon-box">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                </div>
                                <h4 class="flagship-inclusion__card-title">Banking and Financial Setup</h4>
                                <p class="flagship-inclusion__card-desc">TFN, bank account, superannuation — set up in the right order, with the right providers.</p>
                            </div>
                            <div class="flagship-inclusion__card">
                                <div class="flagship-inclusion__icon-box">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <h4 class="flagship-inclusion__card-title">Community and Cultural Connections</h4>
                                <p class="flagship-inclusion__card-desc">Introductions to your community group, cultural center, mosque, temple, or church — wherever you belong.</p>
                            </div>
                            <div class="flagship-inclusion__card">
                                <div class="flagship-inclusion__icon-box">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                                <h4 class="flagship-inclusion__card-title">Three Support Calls over 90 Days</h4>
                                <p class="flagship-inclusion__card-desc">Week 1 kickoff · Day 30 check-in · Day 90 review and next steps.</p>
                            </div>
                            <div class="flagship-inclusion__card">
                                <div class="flagship-inclusion__icon-box">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                </div>
                                <h4 class="flagship-inclusion__card-title">Full Vetted Referral Network Access</h4>
                                <p class="flagship-inclusion__card-desc">GP, dentist, accountant, lawyer, gynecologist — matched to your language, background, and location.</p>
                            </div>
                            <div class="flagship-inclusion__card" style="grid-column: span 2;">
                                <div class="flagship-inclusion__icon-box">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <h4 class="flagship-inclusion__card-title">Exclusive Member Portal Access</h4>
                                <p class="flagship-inclusion__card-desc">Access to our private SettleANZ member portal with advanced resources and guides.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right column sticky sidebar card -->
                    <div class="flagship-sidebar">
                        <div class="flagship-sticky-card">
                            <div class="flagship-sticky-card__image-container">
                                <img src="{{ asset('media/services/services_new/Package 3.webp') }}" class="flagship-sticky-card__image" alt="Flagship Package" />
                            </div>
                            <div class="flagship-sticky-card__body">
                                <span class="flagship-sticky-card__badge">Flagship Program</span>
                                <h3 class="flagship-sticky-card__title">Package 3: Your First 90 Days, Fully Supported</h3>
                                <p class="flagship-sticky-card__tagline">Your new country sorted for you.</p>
                                
                                <ul class="flagship-checklist">
                                    <li class="flagship-checklist__item">
                                        <svg class="flagship-checklist__icon" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        90 Days Concierge Guidance
                                    </li>
                                    <li class="flagship-checklist__item">
                                        <svg class="flagship-checklist__icon" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Rental Success Guarantee
                                    </li>
                                    <li class="flagship-checklist__item">
                                        <svg class="flagship-checklist__icon" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Dedicated Concierge Team
                                    </li>
                                    <li class="flagship-checklist__item">
                                        <svg class="flagship-checklist__icon" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Private Member Portal Access
                                    </li>
                                </ul>

                                <div class="flagship-sticky-card__price-box">
                                    <span class="flagship-sticky-card__price-label">Program Investment</span>
                                    <span class="flagship-sticky-card__price-val">$2,499 AUD</span>
                                </div>

                                <button class="package-cta-btn" type="button"
                                    data-open-package-modal
                                    data-package-number="3"
                                    data-package-stage="Stage 02. Settle: Establish Your Foundation"
                                    data-package-headline="Your First 90 Days, Fully Supported"
                                    data-package-price="$2,499 AUD">
                                    Start Your 90-Day Settlement
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Trust Strip section -->
        <section class="trust-strip">
            <div class="container">
                <div class="trust-strip__row">
                    <div class="trust-strip__item">
                        <div class="trust-strip__value">98%</div>
                        <div class="trust-strip__label">Rental Finding Success</div>
                    </div>
                    <div class="trust-strip__item">
                        <div class="trust-strip__value">100+</div>
                        <div class="trust-strip__label">Families Settled</div>
                    </div>
                    <div class="trust-strip__item">
                        <div class="trust-strip__value">30 Days</div>
                        <div class="trust-strip__label">Average Placement Time</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Disclaimer Box -->
        <section class="disclaimer-section">
            <div class="container">
                <div class="disclaimer-card">
                    <h3>Disclaimer</h3>
                    <p>
                        We are NOT a registered migration agent and do NOT provide immigration advice, visa assistance, or visa application services. We provide practical settlement guidance for newcomers to Australia (housing, banking, healthcare, employment, community integration). For visa advice, contact a MARA-registered migration agent (search: <a href="https://www.mara.gov.au" target="_blank" rel="noopener">www.mara.gov.au</a>).
                    </p>
                </div>
            </div>
        </section>
    </div>
@endsection
