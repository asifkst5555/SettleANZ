@extends('layouts.app')

@section('page_styles')
    <style>
        .settlement-page {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at top right, rgba(232, 119, 58, 0.12), transparent 26%),
                linear-gradient(180deg, #f4eee5 0%, #ffffff 20%, #f7fbfa 100%);
        }

        .settlement-page::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 12% 12%, rgba(11, 122, 117, 0.06) 0 1px, transparent 1px),
                radial-gradient(circle at 88% 18%, rgba(242, 125, 45, 0.08) 0 1px, transparent 1px);
            background-size: 18px 18px, 22px 22px;
            opacity: 0.35;
            pointer-events: none;
        }

        .settlement-page > * {
            position: relative;
            z-index: 1;
        }

        .settlement-hero {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            min-height: calc(100svh - 88px);
            padding: 2.5rem 0;
            background:
                linear-gradient(140deg, #0a524f 0%, #0c6a67 36%, #11807a 100%);
            color: #ffffff;
        }

        .settlement-hero .container {
            width: min(calc(100% - 2rem), var(--max-width));
            margin: 0 auto;
        }

        .settlement-hero::before,
        .settlement-hero::after {
            content: '';
            position: absolute;
            border-radius: 999px;
            pointer-events: none;
        }

        .settlement-hero::before {
            top: -160px;
            right: -120px;
            width: 380px;
            height: 380px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.14) 0%, transparent 70%);
        }

        .settlement-hero::after {
            bottom: -190px;
            left: -140px;
            width: 460px;
            height: 460px;
            background: radial-gradient(circle, rgba(232, 119, 58, 0.2) 0%, transparent 72%);
        }

        .settlement-hero__grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: clamp(1.5rem, 3vw, 2.75rem);
            align-items: center;
            width: 100%;
        }

        .settlement-hero__content,
        .settlement-hero__visual {
            position: relative;
            z-index: 1;
        }

        .settlement-hero__content {
            max-width: 560px;
            padding-right: clamp(0.5rem, 1.8vw, 1.5rem);
        }

        .settlement-hero .eyebrow,
        .settlement-hero h1,
        .settlement-hero p {
            color: #ffffff;
        }

        .settlement-hero .eyebrow {
            color: rgba(255, 255, 255, 0.76);
        }

        .settlement-hero h1 {
            max-width: 9.5ch;
            margin-top: 0.75rem;
            font-size: clamp(2.8rem, 5.2vw, 4.75rem);
            line-height: 0.93;
            letter-spacing: -0.02em;
        }

        .settlement-hero__subhead {
            max-width: 52ch;
            margin-top: 1.05rem;
            color: rgba(255, 255, 255, 0.88);
            font-size: 1rem;
            line-height: 1.72;
        }

        .settlement-hero__actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.95rem;
            margin-top: 1.55rem;
        }

        .settlement-hero__actions .button--ghost-light {
            border: 1px solid rgba(255, 255, 255, 0.22);
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            box-shadow: none;
        }

        .settlement-hero__actions .button--ghost-light:hover,
        .settlement-hero__actions .button--ghost-light:focus-visible {
            background: rgba(255, 255, 255, 0.14);
        }

        .settlement-hero__visual-stack {
            position: relative;
            width: 100%;
            max-width: 560px;
            justify-self: end;
        }

        .settlement-hero__image {
            display: block;
            width: 100%;
            height: min(72vh, 720px);
            object-fit: cover;
            object-position: center;
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 34px;
            box-shadow: 0 36px 90px rgba(4, 24, 30, 0.26);
        }

        .settlement-overview {
            padding: 1.25rem 0 1rem;
        }

        .settlement-overview__card {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.85rem;
            align-items: stretch;
        }

        .settlement-overview__item {
            display: grid;
            grid-template-columns: 52px minmax(0, 1fr);
            gap: 1rem;
            align-items: start;
            padding: 1.25rem 1.2rem;
            border: 1px solid rgba(11, 122, 117, 0.1);
            border-radius: 24px;
            background: #ffffff;
            box-shadow: 0 20px 45px rgba(12, 55, 66, 0.08);
        }

        .settlement-overview__icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            color: var(--primary-brand);
        }

        .settlement-overview__icon svg {
            width: 38px;
            height: 38px;
            fill: currentColor;
        }

        .settlement-overview__content {
            min-width: 0;
        }

        .settlement-overview__number {
            display: inline-block;
            margin-bottom: 0.35rem;
            color: #6e838d;
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .settlement-overview__item strong {
            display: block;
            color: var(--primary-dark);
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 1.18rem;
            font-weight: 700;
            line-height: 1.25;
        }

        .settlement-overview__content > span:not(.settlement-overview__number) {
            display: block;
            margin-top: 0.45rem;
            color: #59707b;
            line-height: 1.65;
            font-size: 0.95rem;
        }

        .settlement-packages {
            padding: 1rem 0 3.5rem;
        }

        .settlement-packages__intro {
            max-width: 780px;
            margin: 0 auto 1.45rem;
            text-align: center;
        }

        .settlement-packages__intro p {
            margin-top: 0.75rem;
            color: #5f727c;
        }

        .settlement-package-grid {
            display: grid;
            gap: 1.2rem;
        }

        .settlement-package {
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(320px, 0.9fr);
            gap: 4rem;
            align-items: start;
            padding: 3rem;
            border: 1px solid rgba(11, 122, 117, 0.12);
            border-radius: 32px;
            background: rgba(255, 255, 255, 0.94);
            box-shadow: 0 26px 62px rgba(12, 55, 66, 0.08);
        }

        .settlement-package--reverse {
            grid-template-columns: minmax(320px, 0.9fr) minmax(0, 1.1fr);
        }

        .settlement-package--reverse .settlement-package__aside {
            order: -1;
        }

        .settlement-package__stage {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            color: #657983;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .settlement-package__number {
            display: inline-grid;
            place-items: center;
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: linear-gradient(180deg, #ff9a4f 0%, #f27d2d 100%);
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 1rem;
            font-weight: 700;
        }

        .settlement-package__headline {
            max-width: 20ch;
            color: var(--primary-dark);
            font-size: clamp(1.72rem, 2.5vw, 2.28rem);
            line-height: 1.02;
            letter-spacing: -0.04em;
        }

        .settlement-package__tagline {
            margin-top: 0.55rem;
            color: var(--primary-brand);
            font-size: 0.96rem;
            font-weight: 700;
        }

        .settlement-package__summary {
            max-width: 49ch;
            margin-top: 0.7rem;
            color: #556a75;
            line-height: 1.6;
            font-size: 0.95rem;
        }

        .settlement-package__includes-title {
            margin-top: 1.15rem;
            color: var(--primary-dark);
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 1rem;
            font-weight: 700;
            line-height: 1.25;
        }

        .settlement-package__timeline {
            position: relative;
            display: grid;
            gap: 1.5rem;
            margin-top: 1.15rem;
            padding-left: 0;
        }

        .settlement-package__timeline::before {
            content: '';
            position: absolute;
            left: 28px;
            top: 8px;
            bottom: 8px;
            width: 2px;
            background: linear-gradient(180deg, rgba(11, 122, 117, 0.22) 0%, rgba(11, 122, 117, 0.08) 100%);
        }

        .settlement-package__timeline-item {
            position: relative;
            display: grid;
            grid-template-columns: 56px minmax(0, 1fr);
            gap: 1rem;
            align-items: start;
        }

        .settlement-package__timeline-icon {
            position: relative;
            z-index: 1;
            display: grid;
            place-items: center;
            width: 56px;
            height: 56px;
            border-radius: 999px;
            background: #ffffff;
            color: var(--primary-brand);
            box-shadow:
                0 0 0 8px rgba(225, 243, 241, 0.95),
                inset 0 0 0 1px rgba(11, 122, 117, 0.1);
        }

        .settlement-package__timeline-icon svg {
            width: 28px;
            height: 28px;
            fill: currentColor;
        }

        .settlement-package__timeline-content {
            padding-top: 0.15rem;
        }

        .settlement-package__timeline-content strong {
            display: block;
            color: #12233c;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 1.32rem;
            font-weight: 700;
            line-height: 1.18;
            letter-spacing: -0.03em;
        }

        .settlement-package__timeline-content span {
            display: block;
            margin-top: 0.45rem;
            color: #5d6d78;
            font-size: 0.98rem;
            line-height: 1.6;
            max-width: 42ch;
        }

        .settlement-package__list {
            display: grid;
            gap: 0.68rem;
            margin-top: 1rem;
        }

        .settlement-package__list li {
            display: grid;
            grid-template-columns: auto minmax(0, 1fr);
            gap: 0.7rem;
            align-items: start;
            color: #38505b;
            line-height: 1.48;
            font-size: 0.93rem;
        }

        .settlement-package__list-icon {
            display: inline-grid;
            place-items: center;
            width: 34px;
            height: 34px;
            margin-top: 0.05rem;
            border-radius: 11px;
            background: #eff7f5;
            color: var(--primary-brand);
            box-shadow: inset 0 0 0 1px rgba(11, 122, 117, 0.08);
        }

        .settlement-package__list-icon svg {
            width: 16px;
            height: 16px;
        }

        .settlement-package__aside {
            display: grid;
            align-content: start;
            gap: 0.8rem;
        }

        .settlement-package__media {
            position: relative;
            overflow: hidden;
            min-height: 210px;
            border-radius: 26px;
            background: #dfeceb;
        }

        .settlement-package--pre-arrival .settlement-package__media {
            min-height: 400px;
            height: 400px;
        }

        .settlement-package--pre-arrival .settlement-package__media img {
            object-position: center 50%;
        }

        .settlement-package--month-support .settlement-package__media {
            min-height: 450px;
            height: 450px;
        }

        .settlement-package--month-support .settlement-package__media img {
            object-position: center 50%;
        }

        .settlement-package__media::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(8, 48, 57, 0.02), rgba(8, 48, 57, 0.22));
            pointer-events: none;
        }

        .settlement-package__media img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .settlement-package__badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            z-index: 1;
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            min-height: 42px;
            padding: 0.7rem 0.9rem;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 999px;
            background: rgba(8, 43, 51, 0.72);
            color: #ffffff;
            backdrop-filter: blur(10px);
        }

        .settlement-package__badge svg {
            width: 16px;
            height: 16px;
            color: #ffb16d;
        }

        .settlement-package__card {
            display: grid;
            gap: 0.75rem;
            padding: 2.5rem;
            border-radius: 26px;
            background:
                linear-gradient(180deg, rgba(11, 122, 117, 0.08) 0%, rgba(255, 255, 255, 0.98) 100%);
            box-shadow: inset 0 0 0 1px rgba(11, 122, 117, 0.08);
        }

        .settlement-package__card-label {
            color: #6d8088;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .settlement-package__price {
            color: var(--primary-dark);
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 1.7rem;
            font-weight: 700;
            line-height: 1.06;
        }

        .settlement-package__price-meta {
            margin: 0.2rem 0 0.55rem;
            color: #5e707b;
            font-size: 0.85rem;
            font-weight: 500;
            line-height: 1.4;
        }

        .settlement-package__price-note {
            color: #5e707b;
            font-size: 0.88rem;
            line-height: 1.5;
        }

        .settlement-package__pricing {
            display: grid;
            gap: 0.7rem;
        }

        .settlement-package__pricing li {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.7rem 0.85rem;
            border: 1px solid rgba(11, 122, 117, 0.1);
            border-radius: 14px;
            background: #ffffff;
            color: #37525c;
            font-size: 0.9rem;
        }

        .settlement-package__pricing li svg {
            width: 14px;
            height: 14px;
            flex: 0 0 auto;
            color: var(--primary-brand);
        }

        .settlement-package__cta {
            margin-top: 0.1rem;
        }

        .settlement-eligibility {
            padding: 0 0 3.25rem;
        }

        .settlement-eligibility__panel {
            position: relative;
            overflow: hidden;
            display: grid;
            place-items: center;
            min-height: 420px;
            padding: 3.5rem 2rem;
            border-radius: 32px;
            background:
                linear-gradient(rgba(11, 122, 117, 0.2), rgba(11, 122, 117, 0.2)),
                linear-gradient(rgba(9, 25, 31, 0.72), rgba(9, 25, 31, 0.72)),
                url('{{ asset('media/services/Eligibility check.webp') }}') center/cover no-repeat;
            box-shadow: 0 28px 70px rgba(10, 41, 47, 0.2);
        }

        .settlement-eligibility__copy h2,
        .settlement-eligibility__copy p,
        .settlement-eligibility__copy .eyebrow {
            color: #ffffff;
        }

        .settlement-eligibility__copy {
            position: relative;
            z-index: 1;
            max-width: 1120px;
            margin: 0 auto;
            text-align: center;
        }

        .settlement-eligibility__copy .settlement-hero__actions {
            justify-content: center;
        }

        .settlement-eligibility__copy p {
            max-width: 72ch;
            margin-top: 0.7rem;
            color: rgba(255, 255, 255, 0.86);
            font-size: 0.96rem;
            line-height: 1.65;
            margin-inline: auto;
        }

        .settlement-eligibility__copy h2 {
            max-width: 18ch;
            margin-top: 1rem;
            margin-inline: auto;
            font-size: clamp(2.45rem, 4.8vw, 4.1rem);
            line-height: 0.98;
            letter-spacing: -0.05em;
        }

        .settlement-faqs {
            padding: 0 0 3.8rem;
        }

        .settlement-faqs__intro {
            max-width: 780px;
            margin: 0 auto 1.45rem;
            text-align: center;
        }

        .settlement-faqs__intro p {
            margin-top: 0.75rem;
            color: #5f727c;
        }

        .settlement-faqs__layout {
            display: grid;
            grid-template-columns: minmax(0, 1.08fr) minmax(300px, 0.92fr);
            gap: 2rem;
            align-items: start;
        }

        .settlement-faqs__media {
            overflow: hidden;
            border: 1px solid rgba(11, 122, 117, 0.12);
            border-radius: 30px;
            background: #e6efed;
            box-shadow: 0 22px 56px rgba(12, 55, 66, 0.08);
        }

        .settlement-faqs__media img {
            display: block;
            width: 100%;
            aspect-ratio: 1.12;
            object-fit: cover;
        }

        .settlement-faqs__content {
            display: grid;
            gap: 0.85rem;
        }

        .settlement-faq-list {
            display: grid;
            gap: 0.85rem;
        }

        .settlement-faq {
            padding: 1rem 1.15rem;
            border: 1px solid rgba(11, 122, 117, 0.12);
            border-radius: 999px;
            background: #ffffff;
            box-shadow: 0 14px 36px rgba(12, 55, 66, 0.06);
            transition: border-color 0.2s ease, box-shadow 0.2s ease, border-radius 0.2s ease;
        }

        .settlement-faq[open] {
            border-color: rgba(11, 122, 117, 0.22);
            border-radius: 26px;
            box-shadow: 0 18px 42px rgba(12, 55, 66, 0.08);
        }

        .settlement-faq summary {
            list-style: none;
            cursor: pointer;
            display: grid;
            grid-template-columns: auto minmax(0, 1fr);
            align-items: center;
            gap: 1rem;
            color: var(--primary-dark);
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 0.98rem;
            font-weight: 700;
            line-height: 1.4;
        }

        .settlement-faq summary::before {
            content: '+';
            display: inline-grid;
            place-items: center;
            width: 28px;
            height: 28px;
            border-radius: 999px;
            color: var(--primary-dark);
            font-size: 1.75rem;
            font-weight: 500;
            line-height: 1;
        }

        .settlement-faq[open] summary::before {
            content: '−';
        }

        .settlement-faq summary::-webkit-details-marker {
            display: none;
        }

        .settlement-faq p {
            margin-top: 0.85rem;
            padding-left: 2.95rem;
            color: #566975;
            line-height: 1.58;
            font-size: 0.94rem;
        }

        @media (max-width: 1180px) {
            .settlement-hero__grid {
                grid-template-columns: 1fr;
            }

            .settlement-hero__content {
                max-width: none;
                padding-right: 0;
            }

            .settlement-hero__visual-stack {
                margin-left: 0;
                width: 100%;
                justify-self: stretch;
            }

            .settlement-hero__image {
                height: min(58vh, 680px);
            }
        }

        @media (max-width: 1024px) {
            .settlement-overview__card,
            .settlement-package,
            .settlement-package--reverse,
            .settlement-faqs__layout {
                grid-template-columns: 1fr;
            }

            .settlement-package--reverse .settlement-package__aside {
                order: initial;
            }

            .settlement-package__headline,
            .settlement-hero h1 {
                max-width: none;
            }
        }

        @media (max-width: 767px) {
            .settlement-hero {
                min-height: calc(100svh - 72px);
                padding: 1.75rem 0;
            }

            .settlement-hero .container {
                width: min(calc(100% - 2rem), var(--max-width));
            }

            .settlement-hero__content {
                text-align: center;
            }

            .settlement-hero__content .eyebrow,
            .settlement-hero__content h1,
            .settlement-hero__subhead {
                text-align: center !important;
            }

            .settlement-hero h1 {
                font-size: clamp(2.55rem, 11vw, 3.8rem);
                margin-left: auto;
                margin-right: auto;
            }

            .settlement-hero__subhead {
                font-size: 1rem;
                margin-left: auto;
                margin-right: auto;
            }

            .settlement-hero__actions {
                justify-content: center;
            }

            .settlement-hero__actions {
                display: grid;
            }

            .settlement-hero__actions .button,
            .settlement-package__cta .button {
                width: 100%;
            }

            .settlement-hero__visual-stack {
                max-width: none;
            }

            .settlement-hero__image,
            .settlement-package,
            .settlement-eligibility__panel,
            .settlement-overview__card {
                border-radius: 24px;
            }

            .settlement-hero__image {
                height: auto;
                aspect-ratio: 0.88;
            }

            .settlement-package,
            .settlement-eligibility__panel,
            .settlement-overview__card {
                padding: 1.15rem;
            }

            .settlement-package__card,
            .settlement-faq,
            .settlement-overview__item,
            .settlement-eligibility__aside {
                padding: 1.1rem;
            }

            .settlement-package__timeline::before {
                left: 22px;
            }

            .settlement-package__timeline-item {
                grid-template-columns: 44px minmax(0, 1fr);
                gap: 0.9rem;
            }

            .settlement-package__timeline-icon {
                width: 44px;
                height: 44px;
                box-shadow:
                    0 0 0 6px rgba(225, 243, 241, 0.95),
                    inset 0 0 0 1px rgba(11, 122, 117, 0.1);
            }

            .settlement-package__timeline-icon svg {
                width: 22px;
                height: 22px;
            }

            .settlement-package__timeline-content strong {
                font-size: 1.08rem;
            }

            .settlement-package__timeline-content span {
                font-size: 0.94rem;
            }

            .settlement-overview__item {
                grid-template-columns: 44px minmax(0, 1fr);
                gap: 0.85rem;
            }

            .settlement-overview__icon {
                width: 44px;
                height: 44px;
            }

            .settlement-overview__icon svg {
                width: 32px;
                height: 32px;
            }

            .settlement-faq {
                border-radius: 24px;
            }

            .settlement-faq p {
                padding-left: 0;
            }
        }
    </style>
@endsection

@section('content')
    <div class="settlement-page">
        <section id="top" class="settlement-hero">
            <div class="container settlement-hero__grid">
                <div class="settlement-hero__content">
                    <p class="eyebrow">Settlement services</p>
                    <h1>The only independent concierge for new arrivals in Australia</h1>
                    <p class="settlement-hero__subhead">We help you at every step of your journey. We find the right person for your specific situation — personally vetted by Entel</p>

                    <div class="settlement-hero__actions">
                        <a class="button button--large" href="#settlement-packages">Explore Packages</a>
                        <button class="button button--large button--ghost-light" type="button" data-open-lead-modal>Download SettleANZ Guide</button>
                    </div>

                </div>

                <div class="settlement-hero__visual">
                    <div class="settlement-hero__visual-stack">
                        <img class="settlement-hero__image" src="{{ asset('media/services/service_her0.webp') }}" alt="Settlement services support for new arrivals in Australia">
                    </div>
                </div>
            </div>
        </section>

        <section class="settlement-overview">
            <div class="container">
                <div class="settlement-overview__card">
                    <article class="settlement-overview__item">
                        <span class="settlement-overview__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.5 3 6.7v5.9c0 5.2 3.6 9.9 9 11 5.4-1.1 9-5.8 9-11V6.7L12 2.5Zm4.2 7.2-4.9 5a1 1 0 0 1-1.4 0l-2.2-2.2 1.4-1.4 1.5 1.5 4.2-4.3 1.4 1.4Z"/></svg>
                        </span>
                        <div class="settlement-overview__content">
                            <span class="settlement-overview__number">01</span>
                            <strong>Plan before you land</strong>
                            <span>Reduce mistakes early with document review, a first-90-days action plan, and personally matched referrals.</span>
                        </div>
                    </article>
                    <article class="settlement-overview__item">
                        <span class="settlement-overview__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2.5 19h19v2h-19v-2Zm18.6-7.5a1.5 1.5 0 0 0-1.84-1.06l-4.74 1.27-6.16-5.74-1.93.52 3.69 6.39-4.43 1.19-1.76-1.38-1.45.39 2.31 4 14.42-3.86A1.5 1.5 0 0 0 21.1 11.5Z"/></svg>
                        </span>
                        <div class="settlement-overview__content">
                            <span class="settlement-overview__number">02</span>
                            <strong>Arrive with less stress</strong>
                            <span>Get airport support, transport help, local orientation, and fast answers in those first confusing days.</span>
                        </div>
                    </article>
                    <article class="settlement-overview__item">
                        <span class="settlement-overview__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 2.5 11.2l1.3 1.5L5 11.7V21h5v-6h4v6h5v-9.3l1.2 1 1.3-1.5L12 3Zm0 5.6a1.6 1.6 0 1 1 0 3.2 1.6 1.6 0 0 1 0-3.2Z"/></svg>
                        </span>
                        <div class="settlement-overview__content">
                            <span class="settlement-overview__number">03</span>
                            <strong>Settle into daily life</strong>
                            <span>Get support for rental search, school fit, banking order, healthcare, and the community connections that make life work.</span>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section id="settlement-packages" class="settlement-packages">
            <div class="container">
                <div class="settlement-packages__intro">
                    <p class="eyebrow">Packages</p>
                    <h2>A premium service path with clear stages, pricing, and outcomes</h2>
                    <p>The direction from the PDF is kept intact here, but presented with stronger hierarchy and easier scanning so people can quickly find the package that matches where they are in the move.</p>
                </div>

                <div class="settlement-package-grid">
                    @foreach ($packages as $package)
                        <article class="settlement-package{{ $loop->first ? ' settlement-package--pre-arrival' : '' }}{{ $loop->index === 1 ? ' settlement-package--reverse' : '' }}{{ $loop->last ? ' settlement-package--month-support' : '' }}">
                            <div class="settlement-package__content">
                                <div class="settlement-package__stage">
                                    <span class="settlement-package__number">{{ $package['number'] }}</span>
                                    <span>{{ $package['stage'] }}</span>
                                </div>

                                <h3 class="settlement-package__headline">{{ $package['headline'] }}</h3>
                                <p class="settlement-package__tagline">{{ $package['tagline'] }}</p>
                                <p class="settlement-package__summary">{{ $package['summary'] }}</p>

                                @if (!empty($package['timeline']))
                                    <p class="settlement-package__includes-title">{{ $package['includes_heading'] ?? "What's included" }}</p>
                                    <div class="settlement-package__timeline" aria-label="{{ $package['stage'] }} inclusions">
                                        @foreach ($package['timeline'] as $timelineItem)
                                            <article class="settlement-package__timeline-item">
                                                <span class="settlement-package__timeline-icon" aria-hidden="true">
                                                    @php
                                                        $timelineTitle = strtolower($timelineItem['title']);
                                                    @endphp
                                                    @if (str_contains($timelineTitle, 'document'))
                                                        <svg viewBox="0 0 24 24"><path d="M6 2h9l5 5v13.5A1.5 1.5 0 0 1 18.5 22h-11A1.5 1.5 0 0 1 6 20.5V2Zm8 1.8V8h4.2L14 3.8ZM9 11h6v1.8H9V11Zm0 3.6h6v1.8H9v-1.8Zm0-7.2h2.8v1.8H9V7.4Z"/></svg>
                                                    @elseif (str_contains($timelineTitle, 'airport') || str_contains($timelineTitle, 'arrival'))
                                                        <svg viewBox="0 0 24 24" style="fill:none;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round;"><path d="m22 2-7 20-4-9-9-4 20-7Z"></path><path d="m22 2-11 11"></path></svg>
                                                    @elseif (str_contains($timelineTitle, 'sim') || str_contains($timelineTitle, 'cash') || str_contains($timelineTitle, 'bank'))
                                                        <svg viewBox="0 0 24 24"><path d="M7 2h8l5 5v13a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Zm7 1.5V8h4.5L14 3.5ZM9 11h6v2H9v-2Zm0 4h6v2H9v-2Z"/></svg>
                                                    @elseif (str_contains($timelineTitle, 'rental') || str_contains($timelineTitle, 'rent') || str_contains($timelineTitle, 'housing'))
                                                        <svg viewBox="0 0 24 24"><path d="M3 10.5 12 3l9 7.5V21h-6v-6h-6v6H3v-10.5Zm2 1V19h2v-6h10v6h2v-7.5L12 5.6 5 11.5Z"/></svg>
                                                    @elseif (str_contains($timelineTitle, 'school') || str_contains($timelineTitle, 'education') || str_contains($timelineTitle, 'placement'))
                                                        <svg viewBox="0 0 24 24"><path d="M12 3 2 8l10 5 8-4v6h2V8L12 3Zm-6 9.5V16c0 2 3 3.5 6 3.5s6-1.5 6-3.5v-3.5l-6 3-6-3Z"/></svg>
                                                    @elseif (str_contains($timelineTitle, 'banking') || str_contains($timelineTitle, 'financial') || str_contains($timelineTitle, 'superannuation'))
                                                        <svg viewBox="0 0 24 24"><path d="M12 3 2 8v2h20V8L12 3Zm-7 9h2v6H5v-6Zm4 0h2v6H9v-6Zm4 0h2v6h-2v-6Zm4 0h2v6h-2v-6ZM2 20h20v2H2v-2Z"/></svg>
                                                    @elseif (str_contains($timelineTitle, 'transport') || str_contains($timelineTitle, 'accommodation') || str_contains($timelineTitle, 'travel'))
                                                        <svg viewBox="0 0 24 24"><path d="M6 3h12l3 8v8h-2v-2H5v2H3v-8l3-8Zm1.4 2-2 5h13.2l-2-5H7.4ZM6 15a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm12 0a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/></svg>
                                                    @elseif (str_contains($timelineTitle, 'community') || str_contains($timelineTitle, 'cultural') || str_contains($timelineTitle, 'connection'))
                                                        <svg viewBox="0 0 24 24"><path d="M16 11a3 3 0 1 0-2.999-3A3 3 0 0 0 16 11Zm-8 0a3 3 0 1 0-3-3 3 3 0 0 0 3 3Zm0 2c-2.67 0-8 1.34-8 4v2h10v-2c0-1.22.94-2.27 2.31-3.05A10.1 10.1 0 0 0 8 13Zm8 0c-.29 0-.62.02-.97.05 1.2.87 1.97 1.99 1.97 3.45v2H24v-2c0-2.66-5.33-4-8-4Z"/></svg>
                                                    @elseif (str_contains($timelineTitle, 'support call') || str_contains($timelineTitle, 'calls') || str_contains($timelineTitle, 'check-in'))
                                                        <svg viewBox="0 0 24 24"><path d="M6.6 10.8a15.4 15.4 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.24 11.4 11.4 0 0 0 3.56.57 1 1 0 0 1 1 1V21a1 1 0 0 1-1 1A18 18 0 0 1 2 4a1 1 0 0 1 1-1h4.26a1 1 0 0 1 1 1 11.4 11.4 0 0 0 .57 3.56 1 1 0 0 1-.24 1l-2 2.24Z"/></svg>
                                                    @elseif (str_contains($timelineTitle, 'concierge') || str_contains($timelineTitle, 'whatsapp') || str_contains($timelineTitle, 'access'))
                                                        <svg viewBox="0 0 24 24"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3h11A2.5 2.5 0 0 1 20 5.5v8a2.5 2.5 0 0 1-2.5 2.5h-3.2L10 20.3V16H6.5A2.5 2.5 0 0 1 4 13.5v-8Zm4 2.3h8v2H8v-2Zm0 3.8h5.6v2H8v-2Z"/></svg>
                                                    @elseif (str_contains($timelineTitle, 'neighbourhood') || str_contains($timelineTitle, 'orientation') || str_contains($timelineTitle, 'local'))
                                                        <svg viewBox="0 0 24 24"><path d="M12 2.5c4.1 0 7.5 3.4 7.5 7.5 0 5.3-7.5 11.5-7.5 11.5S4.5 15.3 4.5 10c0-4.1 3.4-7.5 7.5-7.5Zm0 10.2a2.7 2.7 0 1 0 0-5.4 2.7 2.7 0 0 0 0 5.4Z"/></svg>
                                                    @elseif (str_contains($timelineTitle, 'plan'))
                                                        <svg viewBox="0 0 24 24"><path d="M17 2v2h3a1 1 0 0 1 1 1v14a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V5a1 1 0 0 1 1-1h3V2h2v2h6V2h2ZM5 9v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V9H5Zm3 3h3v3H8v-3Zm5 0h3v1.8h-3V12Zm0 3.2h3V17h-3v-1.8Z"/></svg>
                                                    @elseif (str_contains($timelineTitle, 'referral'))
                                                        <svg viewBox="0 0 24 24"><path d="M12 3a4 4 0 1 1 0 8 4 4 0 0 1 0-8Zm-6 14.5c0-2.5 3.1-4.5 6-4.5s6 2 6 4.5V19H6v-1.5ZM18 7h4v2h-4V7Zm0 4h4v2h-4v-2Z"/></svg>
                                                    @elseif (str_contains($timelineTitle, 'video') || str_contains($timelineTitle, 'call'))
                                                        <svg viewBox="0 0 24 24"><path d="M3 7.5A2.5 2.5 0 0 1 5.5 5h9A2.5 2.5 0 0 1 17 7.5v1.8l3.8-2A1 1 0 0 1 22 8.2v7.6a1 1 0 0 1-1.2.9L17 14.7v1.8A2.5 2.5 0 0 1 14.5 19h-9A2.5 2.5 0 0 1 3 16.5v-9Z"/></svg>
                                                    @elseif (str_contains($timelineTitle, 'written') || str_contains($timelineTitle, 'summary'))
                                                        <svg viewBox="0 0 24 24"><path d="M7 2h10a2 2 0 0 1 2 2v16l-4-2.2L12 20l-3-2.2L5 20V4a2 2 0 0 1 2-2Zm2 5v2h6V7H9Zm0 4v2h6v-2H9Z"/></svg>
                                                    @else
                                                        <svg viewBox="0 0 24 24"><path d="M11 2.5a7 7 0 0 0-7 7c0 5.2 7 12 7 12s7-6.8 7-12a7 7 0 0 0-7-7Zm0 9.3a2.3 2.3 0 1 1 0-4.6 2.3 2.3 0 0 1 0 4.6Z"/></svg>
                                                    @endif
                                                </span>
                                                <div class="settlement-package__timeline-content">
                                                    <strong>{{ $timelineItem['title'] }}</strong>
                                                    <span>{{ $timelineItem['description'] }}</span>
                                                </div>
                                            </article>
                                        @endforeach
                                    </div>
                                @else
                                    <ul class="settlement-package__list">
                                        @foreach ($package['items'] as $item)
                                            <li>
                                                <span class="settlement-package__list-icon" aria-hidden="true">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 4 4L19 6"></path></svg>
                                                </span>
                                                <span>{{ $item }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>

                            <aside class="settlement-package__aside" aria-label="{{ $package['stage'] }} details">
                                <div class="settlement-package__media">
                                    <span class="settlement-package__badge">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 4v5c0 4.5-2.8 7.7-7 9-4.2-1.3-7-4.5-7-9V7l7-4z"></path></svg>
                                        Stage {{ $package['number'] }} support
                                    </span>
                                    <img src="{{ asset($package['image']) }}" alt="{{ $package['stage'] }} support">
                                </div>

                                <div class="settlement-package__card">
                                    <p class="settlement-package__card-label">Starting from</p>
                                    <p class="settlement-package__price">{{ $package['starting_from'] }}</p>
                                    @if (!empty($package['starting_from_meta']))
                                        <p class="settlement-package__price-meta">{{ $package['starting_from_meta'] }}</p>
                                    @endif
                                    <p class="settlement-package__price-note">Choose the level of support that matches where you are now, then keep the next stage open for later if needed.</p>

                                    <ul class="settlement-package__pricing">
                                        @foreach ($package['pricing'] as $price)
                                            <li>
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><path d="m9 12 2 2 4-4"></path></svg>
                                                <span>{{ $price }}</span>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <div class="settlement-package__cta">
                                        <button class="button button--large button--full" type="button"
                                            data-open-package-modal
                                            data-package-number="{{ $package['number'] }}"
                                            data-package-stage="{{ $package['stage'] }}"
                                            data-package-headline="{{ $package['headline'] }}"
                                            data-package-price="{{ $package['starting_from'] }}">
                                            Book this Service Now
                                        </button>
                                    </div>
                                </div>
                            </aside>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="eligibility-check" class="settlement-eligibility">
            <div class="container">
                <div class="settlement-eligibility__panel">
                    <div class="settlement-eligibility__copy">
                        <p class="eyebrow">Eligibility check</p>
                        <h2>Not  sure  which  stage  you're  at  or  which  package  fits?</h2>
                        <p>Tell Entel where you are in your journey — pre-arrival, just landed, or a month in — and he'll point you to the right support.</p>

                        <div class="settlement-hero__actions">
                            <a class="button button--large" href="/contact">Ask a Question First</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="settlement-faqs">
            <div class="container">
                <div class="settlement-faqs__intro">
                    <p class="eyebrow">Frequently asked questions</p>
                    <h2>Questions people usually ask before choosing settlement support</h2>
                    <p>Clear answers to the practical questions people usually ask before choosing a settlement support package.</p>
                </div>

                <div class="settlement-faqs__layout">
                    <div class="settlement-faqs__content">
                        <div class="settlement-faq-list">
                            @foreach ($faqs as $faq)
                                <details class="settlement-faq"@if ($loop->first) open @endif>
                                    <summary>{{ $faq['question'] }}</summary>
                                    <p>{{ $faq['answer'] }}</p>
                                </details>
                            @endforeach
                        </div>
                    </div>

                    <div class="settlement-faqs__media">
                        <img src="{{ asset('media/services/faq.webp') }}" alt="Settlement support frequently asked questions">
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
