@extends('layouts.app')

@section('page_styles')
    <style>
        .service-detail-page {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at top right, rgba(11, 122, 117, 0.08), transparent 35%),
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
            display: grid;
            gap: 2.5rem;
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
            line-height: 1.25;
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

        .pricing-section {
            background: var(--page-background);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(16, 88, 98, 0.05);
        }

        .pricing-section h4 {
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--primary-brand);
            margin-bottom: 0.75rem;
            font-weight: 700;
        }

        .pricing-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 0.65rem;
        }

        .pricing-item {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: var(--body-text);
            line-height: 1.4;
        }

        .pricing-bullet {
            flex-shrink: 0;
            width: 14px;
            height: 14px;
            color: var(--cta-accent);
            margin-top: 0.15rem;
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
                gap: 2rem;
            }
            .flagship-inclusions__grid {
                grid-template-columns: 1fr;
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
                        <span class="service-hero__badge">Stage 01. Arrive</span>
                        <h1>Arrive: Your Smooth Start</h1>
                        <p class="service-hero__subhead">
                            From meticulous planning before you leave to a warm welcome upon arrival, our ‘Arrive’ services ensure your first moments in Australia or New Zealand are stress-free, hassle-free, and organized.
                        </p>
                        <div class="service-hero__highlights">
                            <span class="service-hero__highlight-pill">
                                <svg class="service-hero__highlight-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                Pre-Arrival Strategy
                            </span>
                            <span class="service-hero__highlight-pill">
                                <svg class="service-hero__highlight-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                Airport Pickups
                            </span>
                            <span class="service-hero__highlight-pill">
                                <svg class="service-hero__highlight-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                90-Day Action Plan
                            </span>
                        </div>
                    </div>
                    <div class="service-hero__visual-container">
                        <div class="service-hero__visual">
                            <img src="{{ asset('media/services/Pre-arrival.webp') }}" alt="Stage 01: Arrive" />
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
                        <h2 class="flagship-details__title">Personalized Strategy & Ground Support</h2>
                        <p class="flagship-details__intro">
                            From document checklists before you depart to airport pick-ups and local orientation on arrival, we handle the stress of entry.
                        </p>

                        <!-- Timeline / Steps -->
                        <div class="flagship-timeline">
                            <div class="flagship-timeline__item">
                                <div class="flagship-timeline__bullet"></div>
                                <span class="flagship-timeline__phase">Phase 01: Pre-Arrival Strategy</span>
                                <h3 class="flagship-timeline__title">Document Audit & Readiness Call</h3>
                                <p class="flagship-timeline__desc">
                                    A detailed assessment of qualifications, visa requirements, TFN preparedness, and suburb guidelines so you leave with total confidence.
                                </p>
                            </div>
                            <div class="flagship-timeline__item">
                                <div class="flagship-timeline__bullet"></div>
                                <span class="flagship-timeline__phase">Phase 02: On-the-Ground Reception</span>
                                <h3 class="flagship-timeline__title">Airport Meet & Greet</h3>
                                <p class="flagship-timeline__desc">
                                    Met by name at the arrivals gate with airport transfers, local SIM card activation, and first week essentials already organized.
                                </p>
                            </div>
                            <div class="flagship-timeline__item">
                                <div class="flagship-timeline__bullet"></div>
                                <span class="flagship-timeline__phase">Phase 03: First Week Concierge</span>
                                <h3 class="flagship-timeline__title">Neighborhood Briefing & WhatsApp Support</h3>
                                <p class="flagship-timeline__desc">
                                    7 days of direct concierge access to get instant answers about local doctors, shopping options, and transport networks.
                                </p>
                            </div>
                        </div>

                        <!-- Grid Showcase of Inclusions -->
                        <h3 class="flagship-inclusions__title">Everything Included in Your Arrival Support</h3>
                        <div class="flagship-inclusions__grid">
                            <div class="flagship-inclusion__card">
                                <div class="flagship-inclusion__icon-box">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <h4 class="flagship-inclusion__card-title">Document Readiness Review</h4>
                                <p class="flagship-inclusion__card-desc">Passport, qualifications, visa checks, and pre-departure references verified by experts.</p>
                            </div>
                            <div class="flagship-inclusion__card">
                                <div class="flagship-inclusion__icon-box">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                </div>
                                <h4 class="flagship-inclusion__card-title">90-Day Action Plan</h4>
                                <p class="flagship-inclusion__card-desc">Custom step-by-step roadmap tailored specifically to your visa status and destination city.</p>
                            </div>
                            <div class="flagship-inclusion__card">
                                <div class="flagship-inclusion__icon-box">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                                <h4 class="flagship-inclusion__card-title">Airport Meet & Greet</h4>
                                <p class="flagship-inclusion__card-desc">Met at the arrivals gate by name with immediate transfer to your first week accommodation.</p>
                            </div>
                            <div class="flagship-inclusion__card">
                                <div class="flagship-inclusion__icon-box">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                </div>
                                <h4 class="flagship-inclusion__card-title">SIM Card & Cash Setup</h4>
                                <p class="flagship-inclusion__card-desc">Local SIM card activated and immediate currency options sorted right at the airport terminal.</p>
                            </div>
                            <div class="flagship-inclusion__card">
                                <div class="flagship-inclusion__icon-box">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                </div>
                                <h4 class="flagship-inclusion__card-title">Concierge Access</h4>
                                <p class="flagship-inclusion__card-desc">7 days of direct WhatsApp access to our local experts to handle any questions or concerns.</p>
                            </div>
                            <div class="flagship-inclusion__card">
                                <div class="flagship-inclusion__icon-box">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <h4 class="flagship-inclusion__card-title">Local Orientation</h4>
                                <p class="flagship-inclusion__card-desc">Vetted local neighborhood briefings, bulk billing doctor lists, and transport tips.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right column sticky sidebar cards -->
                    <div class="flagship-sidebar">
                        <!-- Package 1 Card -->
                        <div class="flagship-sticky-card">
                            <div class="flagship-sticky-card__image-container">
                                <img src="{{ asset('media/services/stage_01_Package_1.webp') }}" class="flagship-sticky-card__image" alt="Package 1: Pre-Arrival Strategy & Document Review" />
                            </div>
                            <div class="flagship-sticky-card__body">
                                <span class="flagship-sticky-card__badge">Stage 1: Pre-Arrival</span>
                                <h3 class="flagship-sticky-card__title">Package 1: Pre-Arrival Strategy & Document Review</h3>
                                <p class="flagship-sticky-card__tagline">Be prepared for your new homeland before you land.</p>
                                
                                <ul class="flagship-checklist">
                                    <li class="flagship-checklist__item">
                                        <svg class="flagship-checklist__icon" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Document Readiness Audit
                                    </li>
                                    <li class="flagship-checklist__item">
                                        <svg class="flagship-checklist__icon" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Custom First 90-Days Plan
                                    </li>
                                    <li class="flagship-checklist__item">
                                        <svg class="flagship-checklist__icon" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Pre-matched Vetted Referrals
                                    </li>
                                </ul>

                                <div class="pricing-section">
                                    <h4>Pricing Options</h4>
                                    <ul class="pricing-list">
                                        <li class="pricing-item">
                                            <svg class="pricing-bullet" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>
                                            <span>Self-Guided Starter: Forum & Templates - $99 AUD</span>
                                        </li>
                                        <li class="pricing-item">
                                            <svg class="pricing-bullet" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>
                                            <span>Expert Strategy: 1-Hour Call & Summary - $220 AUD</span>
                                        </li>
                                        <li class="pricing-item">
                                            <svg class="pricing-bullet" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>
                                            <span>Review Pack: Plan & Document Checks - $499 AUD</span>
                                        </li>
                                        <li class="pricing-item">
                                            <svg class="pricing-bullet" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>
                                            <span>Comprehensive Pre-Arrival support - $949 AUD</span>
                                        </li>
                                    </ul>
                                </div>

                                <button class="package-cta-btn" type="button"
                                    data-open-package-modal
                                    data-package-number="1"
                                    data-package-stage="Stage 01. Arrive: Your Smooth Start in a New Land"
                                    data-package-headline="Pre-Arrival Strategy & Document Review"
                                    data-package-price="$99 AUD">
                                    Choose Pre-Arrival Support
                                </button>
                            </div>
                        </div>

                        <!-- Package 2 Card -->
                        <div class="flagship-sticky-card">
                            <div class="flagship-sticky-card__image-container">
                                <img src="{{ asset('media/services/stage_01_Package_2.webp') }}" class="flagship-sticky-card__image" alt="Package 2: Airport Meet & Arrival Concierge" />
                            </div>
                            <div class="flagship-sticky-card__body">
                                <span class="flagship-sticky-card__badge">Stage 1: Arrival</span>
                                <h3 class="flagship-sticky-card__title">Package 2: Airport Meet & Arrival Concierge</h3>
                                <p class="flagship-sticky-card__tagline">Step off the plane without feeling lost.</p>
                                
                                <ul class="flagship-checklist">
                                    <li class="flagship-checklist__item">
                                        <svg class="flagship-checklist__icon" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Meet & Greet Gate Reception
                                    </li>
                                    <li class="flagship-checklist__item">
                                        <svg class="flagship-checklist__icon" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        SIM Activation & Airport Pickups
                                    </li>
                                    <li class="flagship-checklist__item">
                                        <svg class="flagship-checklist__icon" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        1st Week WhatsApp Support
                                    </li>
                                </ul>

                                <div class="pricing-section">
                                    <h4>Pricing Options</h4>
                                    <ul class="pricing-list">
                                        <li class="pricing-item">
                                            <svg class="pricing-bullet" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>
                                            <span>Basic Meet & Greet: Pickup, SIM & orientation - $899 AUD</span>
                                        </li>
                                        <li class="pricing-item">
                                            <svg class="pricing-bullet" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>
                                            <span>Full Arrival: Basic + 7-Day WhatsApp & Welcome Pack - $1,299 AUD</span>
                                        </li>
                                    </ul>
                                </div>

                                <button class="package-cta-btn" type="button"
                                    data-open-package-modal
                                    data-package-number="2"
                                    data-package-stage="Stage 01. Arrive: Your Smooth Start in a New Land"
                                    data-package-headline="Airport Meet & Arrival Concierge"
                                    data-package-price="$899 AUD">
                                    Book Your Arrival Concierge
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
                        <div class="trust-strip__value">100%</div>
                        <div class="trust-strip__label">On-Time Greetings</div>
                    </div>
                    <div class="trust-strip__item">
                        <div class="trust-strip__value">500+</div>
                        <div class="trust-strip__label">Welcomed Newcomers</div>
                    </div>
                    <div class="trust-strip__item">
                        <div class="trust-strip__value">24/7</div>
                        <div class="trust-strip__label">Arrivals Support</div>
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
