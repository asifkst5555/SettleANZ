@extends('layouts.app')

@section('page_styles')
    <style>
        /* ============================================
           SETTLEMENT PAGE — Manus-Inspired Layout
           ============================================ */
        .settlement-page {
            position: relative;
            overflow: hidden;
            background: linear-gradient(180deg, #f8fbfa 0%, #ffffff 30%, #f2f7f6 100%);
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        /* ---- HERO SECTION ---- */
        .settlement-hero {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            min-height: 100vh;
            padding: 80px 0 100px;
            background: linear-gradient(140deg, #0a524f 0%, #0c6a67 36%, #11807a 100%);
            color: #ffffff;
        }

        .settlement-hero::before {
            content: '';
            position: absolute;
            top: -120px;
            right: -100px;
            width: 500px;
            height: 500px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            pointer-events: none;
            z-index: 1;
        }

        .settlement-hero::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -60px;
            width: 400px;
            height: 400px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(232, 119, 58, 0.06) 0%, transparent 70%);
            pointer-events: none;
            z-index: 1;
        }

        .settlement-hero__row {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 3rem;
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
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: #E8773A;
            text-transform: uppercase;
            letter-spacing: 2.5px;
            margin-bottom: 1.5rem;
        }

        .settlement-hero__label svg {
            width: 20px;
            height: 20px;
            color: #E8773A;
        }

        .settlement-hero h1 {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            font-size: clamp(2.45rem, 4.2vw, 3.5rem);
            font-weight: 800;
            line-height: 1.16;
            letter-spacing: -1.2px;
            margin-top: 0;
            margin-bottom: 1.5rem;
            color: #ffffff;
        }

        .settlement-hero h1 .text-highlight {
            color: #E8773A;
            font-style: normal;
        }

        .settlement-hero__subhead {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            font-size: clamp(1rem, 1.45vw, 1.1rem);
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 1rem;
            font-weight: 400;
        }

        .settlement-hero__subhead-secondary {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            font-size: clamp(0.98rem, 1.35vw, 1.05rem);
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.75);
            margin-bottom: 2.25rem;
            font-weight: 400;
        }

        .settlement-hero__btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 16px 32px;
            font-size: 15px;
            font-weight: 700;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            white-space: nowrap;
            cursor: pointer;
            background: #E8773A;
            color: #ffffff !important;
            border: none;
            box-shadow: 0 4px 14px rgba(232, 119, 58, 0.35), 0 1px 3px rgba(0,0,0,0.1);
        }

        .settlement-hero__btn:hover {
            background: #d36528;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(232, 119, 58, 0.45);
        }

        .settlement-hero__btn:active {
            transform: scale(0.98);
        }

        .settlement-hero__btn svg {
            width: 16px;
            height: 16px;
            transition: transform 0.2s;
        }

        .settlement-hero__btn:hover svg {
            transform: translateX(3px);
        }

        /* ---- PATH-TO-SETTLED CARD ---- */
        .path-card {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 24px;
            padding: 2rem 1.75rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15), 0 4px 16px rgba(0,0,0,0.06);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .path-card__title {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: #0b7a75;
            margin-bottom: 1.5rem;
        }

        .path-card__list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .path-card__item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.85rem 1rem;
            border-radius: 14px;
            text-decoration: none;
            color: inherit;
            transition: background 0.25s ease, transform 0.2s ease;
            position: relative;
        }

        .path-card__item:hover {
            background: rgba(11, 122, 117, 0.06);
            transform: translateX(4px);
        }

        .path-card__connector {
            display: flex;
            justify-content: center;
            padding-left: calc(1rem + 18px);
            height: 20px;
        }

        .path-card__connector-line {
            width: 2px;
            height: 100%;
            background: linear-gradient(180deg, #0b7a75, rgba(11, 122, 117, 0.3));
            border-radius: 2px;
        }

        .path-card__number {
            flex-shrink: 0;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #0b7a75;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        }

        .path-card__info {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .path-card__stage-title {
            font-size: 1rem;
            font-weight: 800;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .path-card__stage-title svg {
            width: 16px;
            height: 16px;
            color: #0b7a75;
        }

        .path-card__stage-sub {
            font-size: 0.85rem;
            color: #64748b;
            margin-top: 0.15rem;
            line-height: 1.4;
        }

        .path-card__arrow {
            flex-shrink: 0;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(11, 122, 117, 0.06);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.25s;
        }

        .path-card__item:hover .path-card__arrow {
            background: rgba(11, 122, 117, 0.12);
        }

        .path-card__arrow svg {
            width: 14px;
            height: 14px;
            color: #0b7a75;
        }

        .path-card__footer {
            margin-top: 1.25rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(11, 122, 117, 0.08);
            text-align: center;
            font-size: 0.85rem;
            font-style: italic;
            color: #64748b;
        }

        /* ---- TRANSITION TEXT ---- */
        .transition-text {
            text-align: center;
            padding: 3.5rem 0 1rem;
        }

        .transition-text p {
            font-size: 0.95rem;
            color: #64748b;
            font-style: italic;
            letter-spacing: 0.02em;
        }

        /* ---- STAGES SECTION ---- */
        .stages-section {
            padding: 4rem 0 5rem;
        }

        .stage-block {
            margin-bottom: 5rem;
        }

        .stage-block:last-child {
            margin-bottom: 0;
        }

        .stage-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            align-items: stretch;
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(10, 35, 45, 0.04), 0 1px 4px rgba(10, 35, 45, 0.02);
            border: 1px solid rgba(16, 88, 98, 0.06);
            margin-bottom: 0;
            min-height: 420px;
        }

        .stage-row:last-child {
            margin-bottom: 0;
        }

        /* Stage Header (above the card) */
        .stage-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.25rem;
            padding-left: 0.5rem;
        }

        .stage-header__number {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #0b7a75;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 800;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            flex-shrink: 0;
        }

        .stage-header__accent {
            width: 8px;
            height: 8px;
            background: #E8773A;
            border-radius: 2px;
            transform: rotate(45deg);
            position: relative;
            top: -12px;
            left: -8px;
        }

        .stage-header__meta {
            display: flex;
            flex-direction: column;
        }

        .stage-header__label {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #0b7a75;
        }

        .stage-header__title {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            font-size: clamp(1.45rem, 2vw, 1.7rem);
            font-weight: 800;
            color: #0A4A45;
            line-height: 1.22;
            letter-spacing: -0.3px;
        }

        .stage-header__line {
            flex: 1;
            height: 2px;
            background: linear-gradient(90deg, #0b7a75, rgba(11, 122, 117, 0.1));
            border-radius: 2px;
            margin-left: 0.75rem;
        }

        /* Stage Image Column */
        .stage-image-col {
            position: relative;
            overflow: hidden;
            min-height: 380px;
        }

        .stage-image-col img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .stage-row:hover .stage-image-col img {
            transform: scale(1.03);
        }

        /* Floating Icon Badge */
        .stage-image-badge {
            position: absolute;
            top: 1.25rem;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
            z-index: 2;
        }

        .stage-image-col--left .stage-image-badge {
            left: 1.25rem;
        }

        .stage-image-col--right .stage-image-badge {
            right: 1.25rem;
        }

        .stage-image-badge svg {
            width: 22px;
            height: 22px;
            color: #0b7a75;
        }

        /* Stage Content Column */
        .stage-content-col {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2.5rem 3rem;
        }

        .stage-content__subtitle {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            font-size: 1.05rem;
            font-weight: 800;
            color: #E8773A;
            margin-bottom: 1.25rem;
            line-height: 1.3;
            letter-spacing: -0.1px;
        }

        .stage-content__description {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            font-size: 1.02rem;
            line-height: 1.7;
            color: #475569;
            margin-bottom: 2rem;
            font-weight: 400;
        }

        .stage-content__btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 1rem 1.5rem;
            background: #E8773A;
            color: #ffffff !important;
            font-weight: 700;
            font-size: 1rem;
            border-radius: 12px;
            text-decoration: none;
            transition: background 0.25s, transform 0.2s, box-shadow 0.25s;
            box-shadow: 0 4px 14px rgba(232, 119, 58, 0.2);
            border: none;
            cursor: pointer;
        }

        .stage-content__btn:hover {
            background: #d36528;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(232, 119, 58, 0.3);
        }

        .stage-content__btn svg {
            width: 16px;
            height: 16px;
            transition: transform 0.2s;
        }

        .stage-content__btn:hover svg {
            transform: translateX(3px);
        }

        /* ---- DISCLAIMER ---- */
        .disclaimer-section {
            padding: 0 0 5rem;
        }

        .disclaimer-card {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 16px;
            padding: 2rem;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            font-size: 0.9rem;
            line-height: 1.6;
            color: var(--secondary-text, #64748b);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.02);
        }

        .disclaimer-card h3 {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            font-size: 1.05rem;
            color: var(--body-text, #0f172a);
            font-weight: 800;
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* ---- SCROLL ANIMATIONS ---- */
        .stage-block {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            scroll-margin-top: 2rem;
        }

        .stage-block.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ---- RESPONSIVE ---- */
        @media (max-width: 991px) {
            .settlement-hero {
                padding: 60px 0 80px;
                min-height: auto;
            }

            .settlement-hero__row {
                grid-template-columns: 1fr;
                gap: 2.5rem;
                text-align: center;
            }

            .settlement-hero__content {
                align-items: center;
                text-align: center;
            }

            .path-card {
                max-width: 440px;
                margin: 0 auto;
            }

            .stage-header {
                padding-left: 0;
                justify-content: center;
            }

            .stage-row {
                grid-template-columns: 1fr;
                min-height: auto;
            }

            .stage-image-col {
                min-height: 260px;
                max-height: 320px;
            }

            /* On mobile, image always comes first */
            .stage-row--img-right .stage-image-col {
                order: -1;
            }

            .stage-content-col {
                padding: 2rem 1.5rem;
            }

            .stage-image-badge {
                left: 1.25rem !important;
                right: auto !important;
            }
        }

        @media (max-width: 575px) {
            .settlement-hero h1 {
                font-size: 2rem;
            }

            .settlement-hero__btn {
                width: 100%;
            }

            .stage-header__title {
                font-size: 1.35rem;
            }

            .stage-content-col {
                padding: 1.5rem 1.25rem;
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
    <div class="settlement-page">
        <!-- Hero section -->
        <section class="settlement-hero">
            <div class="container">
                <div class="settlement-hero__row">
                    <div class="settlement-hero__content">
                        <span class="settlement-hero__label">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21" /></svg>
                            SettleANZ Personal Concierge
                        </span>
                        <h1>Your Journey to a<br><span class="text-highlight">Thriving Life</span><br>in Australia & New Zealand</h1>
                        <p class="settlement-hero__subhead">
                            Moving to a new country is a significant life event; you should not plan it according to random advice from strangers on the internet.
                        </p>
                        <p class="settlement-hero__subhead-secondary">
                            SettleANZ offers comprehensive support, broken down into four clear stages, ensuring you navigate your new beginning with confidence and ease.
                        </p>
                        <a href="#lead-strip" data-open-lead-modal class="settlement-hero__btn">
                            Get Your Free 90-Day Roadmap
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                        </a>
                    </div>

                    <!-- Path to Settled Navigation Card -->
                    <div class="path-card">
                        <p class="path-card__title">Your Path to Settled</p>
                        <ul class="path-card__list">
                            <li>
                                <a href="#stage-arrive" class="path-card__item">
                                    <span class="path-card__number">1</span>
                                    <span class="path-card__info">
                                        <span class="path-card__stage-title">
                                            Arrive
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" /></svg>
                                        </span>
                                        <span class="path-card__stage-sub">Land with confidence, not confusion</span>
                                    </span>
                                    <span class="path-card__arrow">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <div class="path-card__connector"><div class="path-card__connector-line"></div></div>
                            </li>
                            <li>
                                <a href="#stage-settle" class="path-card__item">
                                    <span class="path-card__number">2</span>
                                    <span class="path-card__info">
                                        <span class="path-card__stage-title">
                                            Settle
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                                        </span>
                                        <span class="path-card__stage-sub">Build your foundations, find your home</span>
                                    </span>
                                    <span class="path-card__arrow">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <div class="path-card__connector"><div class="path-card__connector-line"></div></div>
                            </li>
                            <li>
                                <a href="#stage-work-invest" class="path-card__item">
                                    <span class="path-card__number">3</span>
                                    <span class="path-card__info">
                                        <span class="path-card__stage-title">
                                            Work & Invest
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                        </span>
                                        <span class="path-card__stage-sub">Grow your career, secure your future</span>
                                    </span>
                                    <span class="path-card__arrow">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <div class="path-card__connector"><div class="path-card__connector-line"></div></div>
                            </li>
                            <li>
                                <a href="#stage-enjoy" class="path-card__item">
                                    <span class="path-card__number">4</span>
                                    <span class="path-card__info">
                                        <span class="path-card__stage-title">
                                            Enjoy
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
                                        </span>
                                        <span class="path-card__stage-sub">This place is yours now — live it fully</span>
                                    </span>
                                    <span class="path-card__arrow">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                                    </span>
                                </a>
                            </li>
                        </ul>
                        <p class="path-card__footer">Each stage builds on the last — start where you are</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stages Section -->
        <section class="stages-section" id="stages">
            <div class="container">

                <!-- Stage 1: Arrive — Image LEFT, Content RIGHT -->
                <div class="stage-block" id="stage-arrive">
                    <div class="stage-header">
                        <div style="position: relative;">
                            <span class="stage-header__number">01</span>
                            <span class="stage-header__accent"></span>
                        </div>
                        <div class="stage-header__meta">
                            <span class="stage-header__label">Stage 1</span>
                            <span class="stage-header__title">Arrive</span>
                        </div>
                        <div class="stage-header__line"></div>
                    </div>
                    <div class="stage-row">
                        <div class="stage-image-col stage-image-col--left">
                            <img src="{{ \App\Support\AssetVersion::url('media/services/services_new/stage-1.webp') }}" alt="Stage 1: Arrive — Your Smooth Start in a New Land" loading="lazy" />
                            <div class="stage-image-badge">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" /></svg>
                            </div>
                        </div>
                        <div class="stage-content-col">
                            <p class="stage-content__subtitle">Land with confidence, not confusion</p>
                            <p class="stage-content__description">
                                The first stage covers everything before and immediately after you land. Most people underestimate how much preparation matters — the wrong paperwork, the wrong suburb, the wrong sequence of steps in the first week can cost you months. This stage makes sure your arrival is the beginning of something good, not the start of a stressful scramble.
                            </p>
                            <a href="/settlement-services/arrive" class="stage-content__btn">
                                Explore Arrival Services
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Stage 2: Settle — Content LEFT, Image RIGHT -->
                <div class="stage-block" id="stage-settle">
                    <div class="stage-header">
                        <div style="position: relative;">
                            <span class="stage-header__number">02</span>
                            <span class="stage-header__accent"></span>
                        </div>
                        <div class="stage-header__meta">
                            <span class="stage-header__label">Stage 2</span>
                            <span class="stage-header__title">Settle</span>
                        </div>
                        <div class="stage-header__line"></div>
                    </div>
                    <div class="stage-row stage-row--img-right">
                        <div class="stage-content-col">
                            <p class="stage-content__subtitle">Build your foundations, find your home</p>
                            <p class="stage-content__description">
                                You have found your feet. The adrenaline of arrival is fading and the real work begins — finding a permanent home, choosing schools for your children, understanding how banking and healthcare work, and starting to feel like you belong somewhere. This stage handles the practical foundations that make everything else possible.
                            </p>
                            <a href="/settlement-services/settle" class="stage-content__btn">
                                Explore Settlement Services
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                            </a>
                        </div>
                        <div class="stage-image-col stage-image-col--right">
                            <img src="{{ \App\Support\AssetVersion::url('media/services/services_new/stage-2.webp') }}" alt="Stage 2: Settle — Establish Your Foundation" loading="lazy" />
                            <div class="stage-image-badge">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stage 3: Work & Invest — Image LEFT, Content RIGHT -->
                <div class="stage-block" id="stage-work-invest">
                    <div class="stage-header">
                        <div style="position: relative;">
                            <span class="stage-header__number">03</span>
                            <span class="stage-header__accent"></span>
                        </div>
                        <div class="stage-header__meta">
                            <span class="stage-header__label">Stage 3</span>
                            <span class="stage-header__title">Work & Invest</span>
                        </div>
                        <div class="stage-header__line"></div>
                    </div>
                    <div class="stage-row">
                        <div class="stage-image-col stage-image-col--left">
                            <img src="{{ \App\Support\AssetVersion::url('media/services/services_new/stage-3.webp') }}" alt="Stage 3: Work & Invest — Build Your Future" loading="lazy" />
                            <div class="stage-image-badge">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                            </div>
                        </div>
                        <div class="stage-content-col">
                            <p class="stage-content__subtitle">Grow your career, secure your future</p>
                            <p class="stage-content__description">
                                You are settled enough to think beyond survival. Now comes the question every migrant eventually asks: how do I get the most out of this country professionally and financially? This stage helps you navigate the job market, understand your earning potential, and start making smart decisions with what you earn, so your future here is as secure as your present.
                            </p>
                            <a href="/settlement-services/work-invest" class="stage-content__btn">
                                Explore Work & Invest Services
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Stage 4: Enjoy — Content LEFT, Image RIGHT -->
                <div class="stage-block" id="stage-enjoy">
                    <div class="stage-header">
                        <div style="position: relative;">
                            <span class="stage-header__number">04</span>
                            <span class="stage-header__accent"></span>
                        </div>
                        <div class="stage-header__meta">
                            <span class="stage-header__label">Stage 4</span>
                            <span class="stage-header__title">Enjoy</span>
                        </div>
                        <div class="stage-header__line"></div>
                    </div>
                    <div class="stage-row stage-row--img-right">
                        <div class="stage-content-col">
                            <p class="stage-content__subtitle">This place is yours now — live it fully</p>
                            <p class="stage-content__description">
                                The hardest part is behind you. You have a home, an income, a routine. The stress of arrival has given way to something quieter and more meaningful — a sense that this place is actually yours. This final stage is about deepening that feeling. Exploring your new country. Building real belonging. And for some, giving back to the people arriving after you, the way you wish someone had done for you.
                            </p>
                            <a href="/settlement-services/enjoy" class="stage-content__btn">
                                Explore Enjoy Services
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                            </a>
                        </div>
                        <div class="stage-image-col stage-image-col--right">
                            <img src="{{ \App\Support\AssetVersion::url('media/services/services_new/stage-4.webp') }}" alt="Stage 4: Enjoy — Embrace Your New Lifestyle" loading="lazy" />
                            <div class="stage-image-badge">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- Scroll animation script -->
        <script>
        (function() {
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.stage-block').forEach(function(el) {
                observer.observe(el);
            });

            // Smooth scroll for path card links
            document.querySelectorAll('.path-card__item').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    var targetId = this.getAttribute('href');
                    var target = document.querySelector(targetId);
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            });
        })();
        </script>

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
