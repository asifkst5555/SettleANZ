@extends('layouts.app')

@section('page_styles')
    <style>
        .settlement-page {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at top right, rgba(232, 119, 58, 0.08), transparent 35%),
                linear-gradient(180deg, #fdfbfa 0%, #ffffff 20%, #f7fbfa 100%);
        }

        .settlement-hero {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            min-height: 580px;
            padding: 80px 0;
            background: linear-gradient(140deg, #0a524f 0%, #0c6a67 36%, #11807a 100%);
            color: #ffffff;
        }

        .settlement-hero::before {
            content: '';
            position: absolute;
            top: -120px;
            right: -100px;
            width: 400px;
            height: 400px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            pointer-events: none;
            z-index: 1;
        }

        .settlement-hero__row {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 4rem;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .settlement-hero__content {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            text-align: left;
        }

        .settlement-hero__label {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: #9FE1CB;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 1.25rem;
            display: block;
        }

        .settlement-hero h1 {
            font-size: clamp(2.2rem, 3.8vw, 3.2rem);
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.02em;
            margin-top: 0;
            margin-bottom: 1.25rem;
            color: #ffffff;
        }

        .settlement-hero__subhead {
            font-size: clamp(1.05rem, 2vw, 1.2rem);
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2.25rem;
            font-weight: 500;
        }

        .settlement-hero__ctas {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            width: 100%;
        }

        .settlement-hero__btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 28px;
            font-size: 15px;
            font-weight: 700;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            white-space: nowrap;
            cursor: pointer;
        }

        .settlement-hero__btn--primary {
            background: #E8773A;
            color: #ffffff !important;
            border: none;
            box-shadow: 0 4px 14px rgba(232, 119, 58, 0.25);
        }

        .settlement-hero__btn--primary:hover {
            background: #d36528;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(232, 119, 58, 0.35);
        }

        .settlement-hero__btn--secondary {
            background: transparent;
            color: #ffffff !important;
            border: 1.5px solid rgba(255, 255, 255, 0.45);
        }

        .settlement-hero__btn--secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.85);
            transform: translateY(-2px);
        }

        .settlement-hero__visual-container {
            display: flex;
            justify-content: flex-end;
            width: 100%;
        }

        .settlement-hero__visual {
            position: relative;
            width: 100%;
            max-width: 440px;
            aspect-ratio: 1 / 1;
            border-radius: 32px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .settlement-hero__visual img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        @media (max-width: 991px) {
            .settlement-hero {
                padding: 60px 0;
            }
            .settlement-hero__row {
                grid-template-columns: 1fr;
                gap: 3rem;
                text-align: center;
            }
            .settlement-hero__content {
                align-items: center;
                text-align: center;
            }
            .settlement-hero__ctas {
                justify-content: center;
            }
            .settlement-hero__visual-container {
                justify-content: center;
            }
            .settlement-hero__visual {
                max-width: 380px;
            }
        }

        @media (max-width: 575px) {
            .settlement-hero__ctas {
                flex-direction: column;
                gap: 0.75rem;
            }
            .settlement-hero__btn {
                width: 100%;
            }
        }

        .intro-quote-section {
            padding: 5rem 0 3rem;
            text-align: center;
        }

        .intro-quote-card {
            width: 100%;
            margin: 0 auto;
            background: #ffffff;
            border: var(--border-card, 1px solid rgba(16, 88, 98, 0.08));
            border-radius: var(--radius-card, 18px);
            padding: 32px;
            box-shadow: var(--shadow-card, 0 10px 30px rgba(10, 35, 45, 0.04));
            position: relative;
        }

        .intro-quote-text {
            font-size: clamp(1.15rem, 2vw, 1.45rem);
            color: var(--body-text);
            line-height: 1.6;
            font-weight: 600;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .intro-quote-author {
            color: var(--secondary-text);
            font-size: 1rem;
            font-weight: 500;
        }

        .stages-overview-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2.5rem;
            padding-bottom: 5rem;
        }

        @media (max-width: 991px) {
            .stages-overview-grid {
                grid-template-columns: 1fr;
            }
        }

        .stage-card {
            background: #ffffff;
            border: var(--border-card, 1px solid rgba(16, 88, 98, 0.08));
            border-radius: var(--radius-card, 18px);
            padding: 32px;
            box-shadow: var(--shadow-card, 0 10px 30px rgba(10, 35, 45, 0.04));
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
        }

        .stage-card:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 20px 40px rgba(10, 35, 45, 0.08);
        }

        .stage-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--light-brand-fill);
            color: var(--primary-brand);
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            align-self: flex-start;
            margin-bottom: 1.25rem;
        }

        .stage-title {
            font-size: 1.6rem;
            color: var(--body-text);
            font-weight: 800;
            margin-bottom: 1rem;
        }

        .stage-description {
            font-size: 1.05rem;
            line-height: 1.6;
            color: var(--secondary-text);
            margin-bottom: 2rem;
        }

        .stage-bullets-list {
            list-style: none;
            padding: 0;
            margin: 0 0 2.5rem 0;
            display: grid;
            gap: 1.25rem;
        }

        .stage-bullet-item {
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
        }

        .stage-bullet-icon {
            flex-shrink: 0;
            width: 20px;
            height: 20px;
            color: var(--primary-brand);
            margin-top: 0.2rem;
        }

        .stage-bullet-text {
            display: flex;
            flex-direction: column;
        }

        .stage-bullet-text strong {
            display: block;
            font-size: 1rem;
            color: var(--body-text);
            font-weight: 700;
            margin-bottom: 0.15rem;
        }

        .stage-bullet-text span {
            font-size: 0.92rem;
            color: var(--secondary-text);
            line-height: 1.5;
            display: block;
        }

        .stage-cta-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 0.9rem 1.5rem;
            background: var(--primary-brand);
            color: #ffffff;
            font-weight: 700;
            font-size: 1rem;
            border-radius: var(--radius-button);
            text-decoration: none;
            transition: background 0.25s, transform 0.2s;
            box-shadow: 0 4px 10px rgba(11, 122, 117, 0.15);
        }

        .stage-cta-btn:hover {
            background: var(--primary-dark);
            color: #ffffff;
            transform: translateY(-2px);
        }

        .stage-cta-btn::after {
            content: ' →';
            margin-left: 0.25rem;
            transition: transform 0.2s;
        }

        .stage-cta-btn:hover::after {
            transform: translateX(3px);
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

        .stage-card__image-container {
            width: 100%;
            aspect-ratio: 16 / 10;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(10, 35, 45, 0.04);
            border: 1px solid rgba(16, 88, 98, 0.06);
        }

        .stage-card__image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            display: block;
        }

        .stage-card:hover .stage-card__image {
            transform: scale(1.04);
        }

        /* ============================================
           PREMIUM JOURNEY WORKFLOW — Stripe/Apple Style
           ============================================ */
        .journey-workflow {
            position: relative;
            padding: 7rem 0 6rem;
            overflow: hidden;
            background:
                radial-gradient(ellipse 80% 60% at 20% 40%, rgba(11,122,117,0.05) 0%, transparent 70%),
                radial-gradient(ellipse 60% 50% at 80% 30%, rgba(232,119,58,0.04) 0%, transparent 70%),
                radial-gradient(ellipse 50% 40% at 50% 80%, rgba(11,122,117,0.03) 0%, transparent 70%),
                #fafbfc;
        }

        /* Abstract blur blobs */
        .journey-workflow::before,
        .journey-workflow::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(80px);
            opacity: 0.4;
        }
        .journey-workflow::before {
            width: 500px; height: 500px;
            top: -120px; left: -100px;
            background: radial-gradient(circle, rgba(11,122,117,0.14) 0%, transparent 70%);
        }
        .journey-workflow::after {
            width: 400px; height: 400px;
            bottom: -80px; right: -60px;
            background: radial-gradient(circle, rgba(232,119,58,0.1) 0%, transparent 70%);
        }

        /* Dot pattern overlay */
        .journey-workflow__dots {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(15,23,42,0.035) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
            z-index: 0;
        }

        .journey-workflow__inner {
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* Header */
        .journey-workflow__eyebrow {
            text-align: center;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: #0b7a75;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }
        .journey-workflow__eyebrow::before,
        .journey-workflow__eyebrow::after {
            content: '';
            width: 32px; height: 1px;
            background: linear-gradient(90deg, transparent, #0b7a75, transparent);
        }

        .journey-workflow__heading {
            text-align: center;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            color: #0f172a;
            line-height: 1.15;
            letter-spacing: -0.03em;
            margin-bottom: 1rem;
        }

        .journey-workflow__subtitle {
            text-align: center;
            font-size: 1.125rem;
            color: #64748b;
            max-width: 640px;
            margin: 0 auto 4rem;
            line-height: 1.7;
        }

        /* Connection Path — hidden */
        .journey-workflow__path-wrap {
            position: relative;
        }

        .journey-workflow__path {
            display: none;
        }

        /* Cards Grid */
        .journey-workflow__grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.75rem;
            position: relative;
            z-index: 1;
        }

        /* Individual Card */
        .jw-card {
            position: relative;
            background: rgba(255,255,255,0.7);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid rgba(255,255,255,0.6);
            border-radius: 28px;
            padding: 2.25rem 1.75rem 2rem;
            text-align: center;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
            transition: transform 0.4s cubic-bezier(0.16,1,0.3,1),
                        box-shadow 0.4s cubic-bezier(0.16,1,0.3,1),
                        border-color 0.4s ease;
            box-shadow: 0 4px 24px rgba(15,23,42,0.04), 0 1px 3px rgba(15,23,42,0.06);
            /* Scroll animation initial state */
            opacity: 0;
            transform: translateY(40px);
        }

        .jw-card.jw-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Gradient border glow on hover */
        .jw-card::before {
            content: '';
            position: absolute;
            inset: -1px;
            border-radius: 29px;
            padding: 2px;
            background: var(--jw-gradient);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.4s ease;
            pointer-events: none;
        }

        .jw-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 50px rgba(15,23,42,0.1), 0 8px 24px rgba(15,23,42,0.06);
        }
        .jw-card:hover::before {
            opacity: 1;
        }

        .jw-card:focus-visible {
            outline: 3px solid var(--jw-color);
            outline-offset: 4px;
        }

        /* Stage-specific color tokens — Teal & Orange brand palette */
        .jw-card[data-stage="1"] { --jw-color: #065e5b; --jw-bg: rgba(6,94,91,0.08); --jw-gradient: linear-gradient(135deg, #065e5b, #0b7a75); transition-delay: 0s; }
        .jw-card[data-stage="2"] { --jw-color: #0b7a75; --jw-bg: rgba(11,122,117,0.08); --jw-gradient: linear-gradient(135deg, #0b7a75, #14a89f); transition-delay: 0.12s; }
        .jw-card[data-stage="3"] { --jw-color: #c96a30; --jw-bg: rgba(232,119,58,0.08); --jw-gradient: linear-gradient(135deg, #c96a30, #e8773a); transition-delay: 0.24s; }
        .jw-card[data-stage="4"] { --jw-color: #e8773a; --jw-bg: rgba(232,119,58,0.08); --jw-gradient: linear-gradient(135deg, #e8773a, #f0935e); transition-delay: 0.36s; }

        /* Icon container */
        .jw-card__icon-wrap {
            width: 72px; height: 72px;
            border-radius: 22px;
            background: var(--jw-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
            transition: transform 0.4s cubic-bezier(0.16,1,0.3,1),
                        background 0.4s ease;
            /* Scroll animation initial */
            transform: scale(0);
        }
        .jw-card.jw-visible .jw-card__icon-wrap {
            transform: scale(1);
        }
        .jw-card__icon-wrap svg {
            width: 32px; height: 32px;
            color: var(--jw-color);
            transition: transform 0.4s cubic-bezier(0.16,1,0.3,1);
        }
        .jw-card:hover .jw-card__icon-wrap {
            background: var(--jw-color);
        }
        .jw-card:hover .jw-card__icon-wrap svg {
            color: #ffffff;
            transform: rotate(-8deg) scale(1.08);
        }

        /* Stage badge */
        .jw-card__badge {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--jw-color);
            background: var(--jw-bg);
            padding: 0.3rem 0.8rem;
            border-radius: 999px;
            margin-bottom: 0.75rem;
        }

        /* Card title */
        .jw-card__title {
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.6rem;
            letter-spacing: -0.02em;
            line-height: 1.25;
        }

        /* Card description */
        .jw-card__desc {
            font-size: 0.9rem;
            color: #64748b;
            line-height: 1.65;
            margin: 0;
            flex-grow: 1;
        }

        /* Connector dots — hidden */
        .jw-card__dot {
            display: none;
        }

        /* ---- Responsive ---- */
        @media (max-width: 1024px) {
            .journey-workflow { padding: 5rem 0 4rem; }
            .journey-workflow__grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5rem;
                max-width: 600px;
                margin: 0 auto;
            }
            .journey-workflow__path { display: none; }
            .jw-card__dot { display: none; }
            .jw-card {
                transition-delay: 0s !important;
            }
            .jw-card[data-stage="1"] { transition-delay: 0s !important; }
            .jw-card[data-stage="2"] { transition-delay: 0.08s !important; }
            .jw-card[data-stage="3"] { transition-delay: 0.16s !important; }
            .jw-card[data-stage="4"] { transition-delay: 0.24s !important; }
        }

        @media (max-width: 640px) {
            .journey-workflow { padding: 4rem 0 3rem; }
            .journey-workflow__subtitle { font-size: 1rem; margin-bottom: 2.5rem; }
            .journey-workflow__grid {
                grid-template-columns: 1fr;
                max-width: 400px;
                gap: 1.25rem;
            }
            .jw-card { border-radius: 22px; padding: 1.75rem 1.5rem 1.5rem; }
            .jw-card__icon-wrap { width: 60px; height: 60px; border-radius: 18px; }
            .jw-card__icon-wrap svg { width: 28px; height: 28px; }
            .jw-card__title { font-size: 1.2rem; }
        }

        @media (max-width: 767px) {
            .disclaimer-card {
                text-align: center;
            }
        }
    </style>
@endsection

@section('content')
    <div class="settlement-page">
        <!-- Hero section -->
        <section class="settlement-hero">
            <div class="container">
                <div class="settlement-hero__row">
                    <div class="settlement-hero__content">
                        <span class="settlement-hero__label">Settlement Services</span>
                        <h1>SettleANZ — Personal Concierge Services</h1>
                        <p class="settlement-hero__subhead">Your Journey to a Thriving Life in Australia & New Zealand</p>
                        <div class="settlement-hero__ctas">
                            <a href="#stages" class="settlement-hero__btn settlement-hero__btn--primary">Explore Packages</a>
                            <a href="#lead-strip" data-open-lead-modal class="settlement-hero__btn settlement-hero__btn--secondary">Check Your Eligibility Free</a>
                        </div>
                    </div>
                    <div class="settlement-hero__visual-container">
                        <div class="settlement-hero__visual">
                            <img src="{{ asset('media/services/service_hero.webp') }}" alt="SettleANZ Personal Concierge Services" />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Intro Quote section -->
        <section class="intro-quote-section">
            <div class="container">
                <div class="intro-quote-card">
                    <p class="intro-quote-text">
                        Moving to a new country is a significant life event; you should not plan it according to random advice from strangers on the internet. Seek guidance from someone who has done this before you.
                    </p>
                    <p class="intro-quote-author">
                        SettleANZ offers comprehensive support, broken down into four clear stages, ensuring you navigate your new beginning with confidence and ease.
                    </p>
                    <h3 style="margin-top: 1.5rem; font-size: 1.25rem; color: var(--primary-brand); font-weight: 700;">Guiding You Every Step of the Way</h3>
                </div>
            </div>
        </section>

        <!-- Premium Journey Workflow Section -->
        <section class="journey-workflow" aria-labelledby="jw-heading">
            <div class="journey-workflow__dots" aria-hidden="true"></div>
            <div class="journey-workflow__inner">
                <p class="journey-workflow__eyebrow">Simplifying Your Move, Every Step of the Way</p>
                <h2 class="journey-workflow__heading" id="jw-heading">Your Relocation Journey</h2>
                <p class="journey-workflow__subtitle">A guided 4-stage roadmap designed to help migrants confidently move, settle, build their future, and truly belong in Australia or New&nbsp;Zealand.</p>

                <div class="journey-workflow__path-wrap">
                    <!-- Gradient connection path -->
                    <div class="journey-workflow__path" aria-hidden="true"></div>

                    <div class="journey-workflow__grid" role="list">
                        <!-- Stage 01 — Arrive -->
                        <a href="#stage-arrive" class="jw-card" data-stage="1" role="listitem" aria-label="Stage 01: Arrive — Prepare everything before and immediately after arrival">
                            <span class="jw-card__dot" aria-hidden="true"></span>
                            <div class="jw-card__icon-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                </svg>
                            </div>
                            <span class="jw-card__badge">Stage 01</span>
                            <h3 class="jw-card__title">Arrive</h3>
                            <p class="jw-card__desc">Prepare everything before and immediately after arrival — documents, housing, SIM, transport and your first-week action plan.</p>
                        </a>

                        <!-- Stage 02 — Settle -->
                        <a href="#stage-settle" class="jw-card" data-stage="2" role="listitem" aria-label="Stage 02: Settle — Find housing, schools, banking and essential services">
                            <span class="jw-card__dot" aria-hidden="true"></span>
                            <div class="jw-card__icon-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                </svg>
                            </div>
                            <span class="jw-card__badge">Stage 02</span>
                            <h3 class="jw-card__title">Settle</h3>
                            <p class="jw-card__desc">Find housing, schools, banking and essential services — feel confident and grounded in your new environment.</p>
                        </a>

                        <!-- Stage 03 — Work & Invest -->
                        <a href="#stage-work-invest" class="jw-card" data-stage="3" role="listitem" aria-label="Stage 03: Work and Invest — Career growth, financial planning and investment">
                            <span class="jw-card__dot" aria-hidden="true"></span>
                            <div class="jw-card__icon-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </div>
                            <span class="jw-card__badge">Stage 03</span>
                            <h3 class="jw-card__title">Work & Invest</h3>
                            <p class="jw-card__desc">Build your career and financial future with smart planning, credential recognition and the right opportunities.</p>
                        </a>

                        <!-- Stage 04 — Enjoy -->
                        <a href="#stage-enjoy" class="jw-card" data-stage="4" role="listitem" aria-label="Stage 04: Enjoy — Lifestyle, community, citizenship and long-term future">
                            <span class="jw-card__dot" aria-hidden="true"></span>
                            <div class="jw-card__icon-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
                                </svg>
                            </div>
                            <span class="jw-card__badge">Stage 04</span>
                            <h3 class="jw-card__title">Enjoy</h3>
                            <p class="jw-card__desc">Experience a balanced and fulfilling life while creating lasting memories — citizenship, community and belonging.</p>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <script>
        (function() {
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('jw-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15 });

            document.querySelectorAll('.jw-card, .journey-workflow__path').forEach(function(el) {
                observer.observe(el);
            });
        })();
        </script>

        <!-- Stages Grid Section -->
        <section class="stages-section" id="stages">
            <div class="container">
                <div class="stages-overview-grid">
                    <!-- Stage 1 -->
                    <div class="stage-card" id="stage-arrive">
                        <div>
                            <div class="stage-card__image-container">
                                <img src="{{ asset('media/services/services_new/stage-1.webp') }}" class="stage-card__image" alt="Stage 1: Arrive" />
                            </div>
                            <span class="stage-badge">Stage 01</span>
                            <h2 class="stage-title">Arrive: Your Smooth Start</h2>
                            <p class="stage-description">
                                The first stage covers everything before and immediately after you land. Most people underestimate how much preparation matters — the wrong paperwork, the wrong suburb, the wrong sequence of steps in the first week can cost you months. This stage makes sure your arrival is the beginning of something good, not the start of a stressful scramble.
                            </p>
                            
                            <ul class="stage-bullets-list">
                                <li class="stage-bullet-item">
                                    <svg class="stage-bullet-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.2 4.8 12l-1.4 1.4 5.6 5.6L20 7.8l-1.4-1.4z"/></svg>
                                    <div class="stage-bullet-text">
                                        <strong>Pre-arrival document review</strong>
                                        <span>We review your visa, qualifications, references and flag exactly what's missing before you land.</span>
                                    </div>
                                </li>
                                <li class="stage-bullet-item">
                                    <svg class="stage-bullet-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.2 4.8 12l-1.4 1.4 5.6 5.6L20 7.8l-1.4-1.4z"/></svg>
                                    <div class="stage-bullet-text">
                                        <strong>Personalised 90-day action plan</strong>
                                        <span>Your specific steps, in the right order — matched to your visa type, city, family, and profession.</span>
                                    </div>
                                </li>
                                <li class="stage-bullet-item">
                                    <svg class="stage-bullet-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.2 4.8 12l-1.4 1.4 5.6 5.6L20 7.8l-1.4-1.4z"/></svg>
                                    <div class="stage-bullet-text">
                                        <strong>Airport meet & greet</strong>
                                        <span>Someone who knows Australia or New Zealand meets you at arrivals. SIM sorted, transport handled, neighbourhood briefing done.</span>
                                    </div>
                                </li>
                                <li class="stage-bullet-item">
                                    <svg class="stage-bullet-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.2 4.8 12l-1.4 1.4 5.6 5.6L20 7.8l-1.4-1.4z"/></svg>
                                    <div class="stage-bullet-text">
                                        <strong>Vetted referrals — culturally matched</strong>
                                        <span>GP who speaks your language. Accountant who knows your visa. Migration lawyer we personally trust. Not Google results.</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <a href="/settlement-services/arrive" class="stage-cta-btn">Explore Arrival Service</a>
                    </div>

                    <!-- Stage 2 -->
                    <div class="stage-card" id="stage-settle">
                        <div>
                            <div class="stage-card__image-container">
                                <img src="{{ asset('media/services/services_new/stage-2.webp') }}" class="stage-card__image" alt="Stage 2: Settle" />
                            </div>
                            <span class="stage-badge">Stage 02</span>
                            <h2 class="stage-title">Settle: Establish Your Foundation</h2>
                            <p class="stage-description">
                                You have found your feet. The adrenaline of arrival is fading and the real work begins finding a permanent home, choosing schools for your children, understanding how banking and healthcare work, and starting to feel like you belong somewhere. This stage handles the practical foundations that make everything else possible.
                            </p>
                            
                            <ul class="stage-bullets-list">
                                <li class="stage-bullet-item">
                                    <svg class="stage-bullet-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.2 4.8 12l-1.4 1.4 5.6 5.6L20 7.8l-1.4-1.4z"/></svg>
                                    <div class="stage-bullet-text">
                                        <strong>Rental finding on the ground</strong>
                                        <span>We attend open homes, send video walkthroughs, and submit applications on your behalf. No rental history? We know how to handle that.</span>
                                    </div>
                                </li>
                                <li class="stage-bullet-item">
                                    <svg class="stage-bullet-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.2 4.8 12l-1.4 1.4 5.6 5.6L20 7.8l-1.4-1.4z"/></svg>
                                    <div class="stage-bullet-text">
                                        <strong>School placement culturally matched</strong>
                                        <span>Not just school ratings. Which school has your community, has ESL support, and fits your children's specific needs.</span>
                                    </div>
                                </li>
                                <li class="stage-bullet-item">
                                    <svg class="stage-bullet-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.2 4.8 12l-1.4 1.4 5.6 5.6L20 7.8l-1.4-1.4z"/></svg>
                                    <div class="stage-bullet-text">
                                        <strong>Banking and financial setup</strong>
                                        <span>TFN or IRD, bank account, superannuation or KiwiSaver — in the right order, with the right providers for your situation.</span>
                                    </div>
                                </li>
                                <li class="stage-bullet-item">
                                    <svg class="stage-bullet-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.2 4.8 12l-1.4 1.4 5.6 5.6L20 7.8l-1.4-1.4z"/></svg>
                                    <div class="stage-bullet-text">
                                        <strong>Community & cultural connections</strong>
                                        <span>Introductions to your cultural community, mosque, temple, church, or professional network — wherever you belong.</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <a href="/settlement-services/settle" class="stage-cta-btn">Explore Settlement Services</a>
                    </div>

                    <!-- Stage 3 -->
                    <div class="stage-card" id="stage-work-invest">
                        <div>
                            <div class="stage-card__image-container">
                                <img src="{{ asset('media/services/services_new/stage-3.webp') }}" class="stage-card__image" alt="Stage 3: Work & Invest" />
                            </div>
                            <span class="stage-badge">Stage 03</span>
                            <h2 class="stage-title">Work & Invest: Build Your Future</h2>
                            <p class="stage-description">
                                You are settled enough to think beyond survival. Now comes the question every migrant eventually asks: how do I get the most out of this country professionally and financially? This stage helps you navigate the job market, understand your earning potential, and start making smart decisions with what you earn, so your future here is as secure as your present.
                            </p>
                            
                            <ul class="stage-bullets-list">
                                <li class="stage-bullet-item">
                                    <svg class="stage-bullet-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.2 4.8 12l-1.4 1.4 5.6 5.6L20 7.8l-1.4-1.4z"/></svg>
                                    <div class="stage-bullet-text">
                                        <strong>Job pathways and recruitment referrals</strong>
                                        <span>Referred to vetted recruitment agencies that know how to place skilled migrants, not just any recruiter.</span>
                                    </div>
                                </li>
                                <li class="stage-bullet-item">
                                    <svg class="stage-bullet-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.2 4.8 12l-1.4 1.4 5.6 5.6L20 7.8l-1.4-1.4z"/></svg>
                                    <div class="stage-bullet-text">
                                        <strong>Credential recognition support</strong>
                                        <span>Guidance on NZQA, skills assessment bodies, and professional registration for regulated occupations.</span>
                                    </div>
                                </li>
                                <li class="stage-bullet-item">
                                    <svg class="stage-bullet-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.2 4.8 12l-1.4 1.4 5.6 5.6L20 7.8l-1.4-1.4z"/></svg>
                                    <div class="stage-bullet-text">
                                        <strong>Tax, super and financial planning</strong>
                                        <span>Referral to accountants and financial planners who specialise in migrants — your tax situation is not the same as a local's.</span>
                                    </div>
                                </li>
                                <li class="stage-bullet-item">
                                    <svg class="stage-bullet-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.2 4.8 12l-1.4 1.4 5.6 5.6L20 7.8l-1.4-1.4z"/></svg>
                                    <div class="stage-bullet-text">
                                        <strong>Property and business investment</strong>
                                        <span>Introduction to property investment, mortgage brokers, buyers agents, and business setup — when you are ready to go beyond earning to building.</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <a href="/settlement-services/work-invest" class="stage-cta-btn">Explore Work & Invest Services</a>
                    </div>

                    <!-- Stage 4 -->
                    <div class="stage-card" id="stage-enjoy">
                        <div>
                            <div class="stage-card__image-container">
                                <img src="{{ asset('media/services/services_new/stage-4.webp') }}" class="stage-card__image" alt="Stage 4: Enjoy" />
                            </div>
                            <span class="stage-badge">Stage 04</span>
                            <h2 class="stage-title">Enjoy: Embrace Your New Lifestyle</h2>
                            <p class="stage-description">
                                The hardest part is behind you. You have a home, an income, a routine. The stress of arrival has given way to something quieter and more meaningful — a sense that this place is actually yours. This final stage is about deepening that feeling. Exploring your new country. Building real belonging. And for some, giving back to the people arriving after you, the way you wish someone had done for you.
                            </p>
                            
                            <ul class="stage-bullets-list">
                                <li class="stage-bullet-item">
                                    <svg class="stage-bullet-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.2 4.8 12l-1.4 1.4 5.6 5.6L20 7.8l-1.4-1.4z"/></svg>
                                    <div class="stage-bullet-text">
                                        <strong>Citizenship pathway guidance</strong>
                                        <span>When you are ready to make it official — understanding the citizenship timeline, eligibility, and application process for Australia or New Zealand.</span>
                                    </div>
                                </li>
                                <li class="stage-bullet-item">
                                    <svg class="stage-bullet-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.2 4.8 12l-1.4 1.4 5.6 5.6L20 7.8l-1.4-1.4z"/></svg>
                                    <div class="stage-bullet-text">
                                        <strong>Long-term property and wealth planning</strong>
                                        <span>Referrals to buyers agents, financial planners, and property investment specialists for building long-term wealth in your new country.</span>
                                    </div>
                                </li>
                                <li class="stage-bullet-item">
                                    <svg class="stage-bullet-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.2 4.8 12l-1.4 1.4 5.6 5.6L20 7.8l-1.4-1.4z"/></svg>
                                    <div class="stage-bullet-text">
                                        <strong>Community and cultural deepening</strong>
                                        <span>Going beyond survival connections — festivals, cultural organisations, professional networks, and spaces where you feel genuinely at home.</span>
                                    </div>
                                </li>
                                <li class="stage-bullet-item">
                                    <svg class="stage-bullet-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.2 4.8 12l-1.4 1.4 5.6 5.6L20 7.8l-1.4-1.4z"/></svg>
                                    <div class="stage-bullet-text">
                                        <strong>Giving back: Mentoring new arrivals</strong>
                                        <span>If you want to support the people coming after you, we will connect you with opportunities to mentor, guide, and welcome the next generation of new arrivals.</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <a href="/settlement-services/enjoy" class="stage-cta-btn">Explore Enjoy Services</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Disclaimer box -->
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
