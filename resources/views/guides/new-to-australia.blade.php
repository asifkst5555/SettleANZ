@extends('layouts.app')

@section('page_styles')

    <style>
        /* ======= HERO — ARRIVE STYLE ======= */
        .guide-hero {
            position: relative;
            overflow: hidden;
            padding: 90px 0 50px;
            background:
                radial-gradient(circle at 80% 18%, rgba(159, 225, 203, 0.14), transparent 30%),
                linear-gradient(135deg, #0a4a45 0%, #0f6c6b 55%, #0a4a45 100%);
            color: #ffffff;
        }

        .guide-hero__grid {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(360px, 520px);
            gap: clamp(2rem, 5vw, 4rem);
            align-items: center;
        }

        .guide-hero__meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 24px;
            color: #9FE1CB;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.24em;
            text-transform: uppercase;
        }

        .guide-hero__number {
            display: grid;
            place-items: center;
            width: 48px;
            height: 48px;
            border-radius: 999px;
            background: #e8773a;
            color: #ffffff;
            letter-spacing: 0;
        }

        .guide-hero h1 {
            max-width: 14ch;
            margin: 0 0 24px;
            color: #ffffff;
            font-size: clamp(2.55rem, 5vw, 4.35rem);
            font-weight: 800;
            line-height: 1.04;
            letter-spacing: -1.2px;
        }

        .guide-hero__accent {
            color: #e8773a;
        }

        .guide-hero__copy {
            max-width: 58ch;
            margin: 0 0 24px;
            color: rgba(255, 255, 255, 0.84);
            font-size: clamp(1rem, 1.4vw, 1.18rem);
            line-height: 1.75;
        }

        .guide-hero__chips {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
        }

        .guide-hero__chip {
            display: inline-flex;
            align-items: center;
            gap: 16px;
            min-height: 50px;
            padding: 0 24px;
            border: 1px solid rgba(255, 255, 255, 0.24);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.09);
            color: #ffffff;
            font-weight: 800;
            font-size: 0.9rem;
            line-height: 1.2;
            text-decoration: none;
            transition: background 0.2s ease, border-color 0.2s ease;
        }

        .guide-hero__chip:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.35);
            color: #ffffff;
        }

        .guide-hero__chip--primary {
            background: #e8773a;
            border-color: #e8773a;
        }

        .guide-hero__chip--primary:hover {
            background: #d86424;
            border-color: #d86424;
        }

        .guide-hero__chip svg {
            width: 17px;
            height: 17px;
            flex: 0 0 auto;
        }

        .guide-hero__image {
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 16px;
            aspect-ratio: 4 / 3;
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 28px 70px rgba(0, 0, 0, 0.26);
        }

        .guide-hero__image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        @media (max-width: 991px) {
            .guide-hero {
                padding: 70px 0 40px;
            }

            .guide-hero__grid {
                grid-template-columns: 1fr;
                gap: 32px;
            }

            .guide-hero__content {
                text-align: center;
            }

            .guide-hero h1,
            .guide-hero__copy {
                margin-left: auto;
                margin-right: auto;
            }

            .guide-hero h1 {
                max-width: 100%;
            }

            .guide-hero__meta,
            .guide-hero__chips {
                justify-content: center;
            }

            .guide-hero__image {
                max-width: 560px;
                margin: 0 auto;
            }
        }

        @media (max-width: 767px) {
            .guide-hero {
                padding: 60px 0 32px;

            .guide-hero__meta {
                flex-direction: column;
                gap: 0.75rem;
                letter-spacing: 0.16em;
                text-align: center;
            }

            .guide-hero h1 {
                font-size: clamp(1.8rem, 8vw, 2.55rem);
                max-width: 100%;
            }

            .guide-hero__copy {
                font-size: 0.95rem;
            }

            .guide-hero__chip {
                width: 100%;
                justify-content: center;
            }
            }
        }

        /* ======= CTA SECTION ======= */
        .guide-cta {
            padding: 80px 0;
            background: #f8f7f4;
        }

        .guide-cta__heading {
            max-width: 700px;
            margin: 0 auto 48px;
            color: #0f172a;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: clamp(1.8rem, 3.5vw, 2.8rem);
            font-weight: 800;
            line-height: 1.15;
            text-align: center;
        }

        .guide-cta__grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 32px;
        }

        .guide-cta__card {
            background: #ffffff;
            border-radius: 20px;
            padding: 32px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .guide-cta__card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.08);
        }

        .guide-cta__card--checklist {
            border-top: 4px solid #0F766E;
        }

        .guide-cta__card--services {
            border-top: 4px solid #e8773a;
        }

        .guide-cta__icon {
            display: inline-grid;
            place-items: center;
            width: 48px;
            height: 48px;
            border-radius: 14px;
            margin-bottom: 16px;
        }

        .guide-cta__icon--checklist {
            background: rgba(15, 118, 110, 0.1);
            color: #0F766E;
        }

        .guide-cta__icon--services {
            background: rgba(232, 119, 58, 0.1);
            color: #e8773a;
        }

        .guide-cta__icon svg {
            width: 24px;
            height: 24px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.6;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .guide-cta__card h3 {
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 1.35rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 8px;
            line-height: 1.3;
        }

        .guide-cta__card p {
            font-size: 0.95rem;
            color: #64748b;
            line-height: 1.65;
            margin: 0 0 auto;
        }

        .guide-cta__action {
            margin-top: 24px;
        }

        .guide-cta__action .button {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            min-height: 50px;
            padding: 0 1.35rem;
            border-radius: 8px;
            font-family: 'Inter', system-ui, sans-serif;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: transform 0.2s ease, background 0.2s ease;
            border: none;
        }

        .guide-cta__action .button:hover {
            transform: translateY(-2px);
        }

        .guide-cta__action .button--primary {
            background: #e8773a;
            color: #ffffff;
            box-shadow: 0 12px 24px rgba(232, 119, 58, 0.22);
        }

        .guide-cta__action .button--primary:hover {
            background: #d86424;
        }

        .guide-cta__action .button--secondary {
            background: #0F766E;
            color: #ffffff;
            box-shadow: 0 12px 24px rgba(15, 118, 110, 0.2);
        }

        .guide-cta__action .button--secondary:hover {
            background: #0b5e57;
        }

        @media (max-width: 900px) {
            .guide-cta__grid {
                grid-template-columns: 1fr;
                gap: 24px;
                padding: 0 24px;
            }

            .guide-cta__heading {
                margin-bottom: 32px;
                padding: 0 24px;
            }
        }

        @media (max-width: 767px) {
            .guide-cta {
                padding: 64px 0;
            }

            .guide-cta__card {
                padding: 24px;
            }
        }

        .arrival-guide {
            background: #ffffff;
        }

         .arrival-hero {
            position: relative;
            overflow: hidden;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 120px 0; /* Hero top/bottom 120px */
            background: #ffffff;
            color: #ffffff;
        }

        .arrival-hero > .container {
            width: 100%;
        }

        .arrival-hero::before {
            content: '';
            position: absolute;
            top: -10%;
            bottom: -10%;
            left: -8%;
            width: 78%;
            background: linear-gradient(155deg, #0a4f51 0%, #0e6e70 60%, #117675 100%);
            border-radius: 0 65% 60% 0 / 0 50% 55% 0;
            pointer-events: none;
            z-index: 0;
        }

        .arrival-hero::after {
            content: '';
            position: absolute;
            bottom: 6%;
            left: -120px;
            width: 360px;
            height: 360px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(242, 125, 45, 0.22) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .arrival-hero .container,
        /* ======= SECTION & CARD STYLING (HOME PAGE MATCH) ======= */
        .guide-section {
            padding: 80px 0;
            border-bottom: 1px solid #f2f2f2;
        }

        @media (max-width: 1024px) {
            .guide-section { padding: 64px 0; }
            .guide-section--hero-adjacent { padding: 64px 0; }
        }

        @media (max-width: 767px) {
            .guide-section { padding: 48px 0; }
            .guide-section--hero-adjacent { padding: 48px 0; }
        }

        .guide-section--white {
            background: #ffffff;
        }

        .guide-section--sand {
            background: #F5F0E8;
        }

        .guide-section--cream {
            background: #F8F4EC;
        }

        .guide-section--teal {
            background: #065E5B;
            color: #ffffff;
        }

        .guide-section--hero-adjacent {
            background: #F8F4EC;
            padding: 80px 0;
            border-bottom: 1px solid #f2f2f2;
        }

        .guide-container {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            padding-left: 48px;
            padding-right: 48px;
        }

        @media (max-width: 767px) {
            .guide-container {
                padding-left: 24px;
                padding-right: 24px;
            }
        }

        .guide-card {
            border: 1px solid rgba(16, 88, 98, 0.08);
            border-radius: 18px;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .guide-card:hover {
            transform: translateY(-4px) scale(1.01);
            box-shadow: 0 20px 40px rgba(10, 35, 45, 0.08);
        }

        .arrival-intro {
            padding: 3.5rem 0 1.5rem;
        }

        .arrival-shell .container {
            /* Inherited from site.css */
        }

        .arrival-hero__grid {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 3rem;
            align-items: center;
        }

        .arrival-hero__content {
            max-width: 640px;
            padding: 0;
            border: 0;
            background: transparent;
            box-shadow: none;
            backdrop-filter: none;
        }

        .arrival-hero__accent {
            display: inline-block;
            color: #f27d2d;
            font-size: 2em;
            line-height: 0.9;
            letter-spacing: -0.04em;
        }

        .arrival-hero .eyebrow,
        .arrival-hero h1,
        .arrival-hero p {
            color: #ffffff;
        }

        .arrival-hero .eyebrow {
            color: rgba(255, 255, 255, 0.76);
            font-size: 1.2rem;
            letter-spacing: 0.18em;
        }

        .arrival-hero h1 {
            margin-top: 0.75rem;
            font-size: clamp(2.8rem, 6vw, 5.4rem);
            line-height: 1.02;
            letter-spacing: -0.01em;
            max-width: 15ch;
            text-wrap: balance;
        }

        .arrival-hero__subhead {
            max-width: 46ch;
            margin-top: 1.1rem;
            color: rgba(255, 255, 255, 0.88);
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.65;
            letter-spacing: 0;
        }

        .arrival-intro {
            background: #F5F0E8;
            padding: 3.5rem 0 1.5rem;
        }

        .arrival-intro__inner {
            display: grid;
            gap: 24px;
            max-width: 820px;
            margin-inline: auto;
            text-align: center;
        }

        .arrival-intro__lead {
            margin: 0;
            color: var(--body-text, #2a3640);
            font-size: 1.05rem;
            line-height: 1.7;
        }

        .arrival-intro__quote {
            padding: 1.1rem 1.3rem;
            border-radius: 6px;
            background: rgba(15, 139, 141, 0.06);
            text-align: center;
        }

        .arrival-intro__quote p {
            margin: 0;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 1rem;
            line-height: 1.65;
            color: var(--primary-dark);
        }

        .arrival-intro__quote span {
            display: block;
            margin-top: 0.7rem;
            color: rgba(15, 23, 42, 0.65);
            font-size: 0.78rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        @media (max-width: 767px) {
            .arrival-intro {
                padding: 2rem 0 0.5rem;
            }
        }

        .arrival-hero__lead {
            max-width: 56ch;
            margin-top: 0.85rem;
            color: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
            line-height: 1.65;
        }

        .arrival-hero__quote {
            margin-top: 1rem;
            padding: 0.95rem 1.05rem;
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 18px 42px rgba(5, 28, 36, 0.18);
            backdrop-filter: blur(10px);
        }

        .arrival-hero__quote p,
        .arrival-hero__quote span {
            margin: 0;
            color: #ffffff;
        }

        .arrival-hero__quote p {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 1rem;
            line-height: 1.7;
        }

        .arrival-hero__quote span {
            display: block;
            margin-top: 0.75rem;
            color: rgba(255, 255, 255, 0.78);
            font-size: 0.88rem;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }


        .arrival-hero__visual {
            position: relative;
            box-sizing: border-box;
            width: 100%;
            max-width: none;
            padding: 14px;
            border: 6px solid #f27d2d;
            border-radius: 32px;
            line-height: 0;
        }

        .arrival-hero__image {
            display: block;
            width: 100%;
            height: 520px;
            object-fit: cover;
            object-position: center;
            border-radius: 18px;
            border: 0;
            box-shadow: 0 36px 90px rgba(3, 25, 32, 0.28);
        }

        .arrival-shell {
            padding: 0;
        }

        .arrival-layout {
            display: block;
        }

        .arrival-content {
            display: flex;
            flex-direction: column;
        }

        .arrival-section {
            padding: 0;
            border: none;
            border-radius: 0;
            background: transparent;
            box-shadow: none;
        }

        .arrival-section--dark {
            color: #ffffff;
            background:
                linear-gradient(152deg, #083f47 0%, #0b5b5a 46%, #106766 100%);
        }

        .arrival-section--dark h2,
        .arrival-section--dark h3,
        .arrival-section--dark h4,
        .arrival-section--dark p,
        .arrival-section--dark li,
        .arrival-section--dark strong,
        .arrival-section--dark .eyebrow {
            color: #ffffff;
        }

        .arrival-section--dark a {
            color: #ffd5b4;
        }

        .arrival-section .eyebrow {
            color: var(--primary-brand);
            text-align: center;
        }

        .arrival-section__lede {
            margin: 0.6rem auto 0;
            max-width: 56ch;
            color: rgba(255, 255, 255, 0.85);
            font-size: 1.05rem;
            line-height: 1.55;
            text-align: center;
        }

        .arrival-section h2 {
            margin-top: 0.75rem;
            max-width: 27ch;
            margin-inline: auto;
            color: var(--primary-dark);
            font-size: clamp(2rem, 3.6vw, 3rem);
            line-height: 1.2;
            letter-spacing: -0.03em;
            text-align: center;
        }

        .arrival-section--dark h2,
        .arrival-section--dark .eyebrow {
            color: #ffffff;
        }

        .arrival-section__intro {
            max-width: 74ch;
            margin-top: 0.85rem;
            margin-inline: auto;
            color: #556975;
            line-height: 1.75;
            text-align: center;
        }



        .arrival-grid-2,
        .arrival-grid-3,
        .arrival-grid-cta,
        .arrival-faq-grid,
        .arrival-timeline,
        .arrival-day-grid {
            display: grid;
            gap: 1rem;
        }

        .arrival-grid-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .arrival-grid-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            margin-top: 1.35rem;
        }

        .arrival-grid-cta {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            margin-top: 1.35rem;
        }

        .arrival-card,
        .arrival-step,
        .arrival-day-card,
        .arrival-faq,
        .arrival-cta,
        .arrival-note {
            border: var(--border-card, 1px solid rgba(16, 88, 98, 0.08));
            border-radius: var(--radius-card, 18px);
            background: #ffffff;
            box-shadow: var(--shadow-card, 0 10px 30px rgba(10, 35, 45, 0.04));
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
        }

        .arrival-card:hover,
        .arrival-day-card:hover,
        .arrival-faq:hover,
        .arrival-cta:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 20px 40px rgba(10, 35, 45, 0.08);
        }

        .arrival-card {
            height: 100%;
            padding: 32px;
        }

        .arrival-card__media {
            overflow: hidden;
            margin: -32px -32px 24px;
            border-radius: var(--radius-card, 18px) var(--radius-card, 18px) 0 0;
        }

        .arrival-card__media img {
            display: block;
            width: 100%;
            height: 350px;
            object-fit: cover;
        }

        .arrival-card--warm {
            background: linear-gradient(180deg, #fff7ee 0%, #ffffff 100%);
        }

        .arrival-card--cool {
            background: linear-gradient(180deg, #f2fbfa 0%, #ffffff 100%);
        }

        .arrival-card__head {
            display: flex;
            gap: 0.9rem;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .arrival-icon {
            display: inline-grid;
            place-items: center;
            width: 54px;
            height: 54px;
            flex: 0 0 auto;
            border-radius: 18px;
            background: linear-gradient(180deg, #f18a42 0%, #e8773a 100%);
            color: #ffffff;
            box-shadow: 0 18px 34px rgba(232, 119, 58, 0.24);
        }

        .arrival-icon--teal {
            background: linear-gradient(180deg, #14827b 0%, #0b6d69 100%);
            box-shadow: 0 18px 34px rgba(11, 122, 117, 0.18);
        }

        .arrival-icon--slate {
            background: linear-gradient(180deg, #365866 0%, #173746 100%);
            box-shadow: 0 18px 34px rgba(17, 44, 57, 0.18);
        }

        .arrival-icon svg {
            width: 26px;
            height: 26px;
            fill: currentColor;
        }

        .arrival-card h3,
        .arrival-step h3,
        .arrival-day-card h3,
        .arrival-cta h3 {
            color: var(--primary-dark);
            font-size: 1.34rem;
            line-height: 1.2;
            letter-spacing: -0.03em;
            text-align: center;
        }

        .arrival-card p,
        .arrival-card li,
        .arrival-day-card p,
        .arrival-step p,
        .arrival-faq p,
        .arrival-note p,
        .arrival-cta p {
            color: #556975;
            line-height: 1.72;
            text-align: center;
        }

        .arrival-list {
            display: grid;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .arrival-list li {
            display: grid;
            grid-template-columns: 38px minmax(0, 1fr);
            gap: 0.75rem;
            align-items: start;
        }

        .arrival-list__mark {
            display: inline-grid;
            place-items: center;
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: #eff8f7;
            color: var(--primary-brand);
        }

        .arrival-list__mark svg {
            width: 20px;
            height: 20px;
            fill: none;
            stroke: currentColor;
            stroke-width: 3.25;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .arrival-pro-tip {
            margin-top: 1rem;
            padding: 1.2rem 1.25rem;
            border-radius: 24px;
            background: linear-gradient(180deg, rgba(11, 122, 117, 0.08) 0%, rgba(255, 255, 255, 0.98) 100%);
            box-shadow: inset 0 0 0 1px rgba(11, 122, 117, 0.1);
        }

        .arrival-pro-tip p {
            font-size: 1.3rem;
        }

        .arrival-pro-tip strong {
            color: var(--primary-dark);
        }

        .arrival-pro-tip a {
            color: var(--primary-brand);
        }



        .arrival-guidance {
            margin-top: 2rem;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: rgba(0, 0, 0, 0.12);
            box-shadow: 0 28px 64px rgba(0, 0, 0, 0.18);
        }

        .arrival-guidance__panel {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .arrival-guidance__column {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .arrival-guidance__column--do {
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .arrival-guidance__head {
            display: grid;
            gap: 0.45rem;
            padding: 1.5rem 1.65rem 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .arrival-guidance__column--do .arrival-guidance__head {
            background: linear-gradient(180deg, rgba(72, 196, 181, 0.14) 0%, rgba(72, 196, 181, 0.04) 100%);
        }

        .arrival-guidance__column--dont .arrival-guidance__head {
            background: linear-gradient(180deg, rgba(242, 125, 45, 0.14) 0%, rgba(242, 125, 45, 0.04) 100%);
        }

        .arrival-guidance__label-row {
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }

        .arrival-guidance__mark {
            display: inline-grid;
            place-items: center;
            width: 36px;
            height: 36px;
            flex: 0 0 auto;
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            line-height: 1;
        }

        .arrival-guidance__column--do .arrival-guidance__mark {
            background: rgba(72, 196, 181, 0.22);
            color: #9ef0e4;
            box-shadow: inset 0 0 0 1px rgba(158, 240, 228, 0.35);
        }

        .arrival-guidance__column--dont .arrival-guidance__mark {
            background: rgba(242, 125, 45, 0.22);
            color: #ffd5b4;
            box-shadow: inset 0 0 0 1px rgba(255, 213, 180, 0.35);
        }

        .arrival-guidance__mark svg {
            width: 18px;
            height: 18px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2.75;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .arrival-guidance__column--dont .arrival-guidance__mark svg {
            fill: currentColor;
            stroke: none;
        }

        .arrival-guidance__head h3 {
            margin: 0;
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .arrival-guidance__tagline {
            margin: 0;
            color: rgba(255, 255, 255, 0.72);
            font-size: 0.92rem;
            line-height: 1.45;
        }

        .arrival-guidance__items {
            display: grid;
            grid-template-columns: 1fr;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .arrival-guidance__row {
            display: flex;
            flex-direction: column;
            gap: 0.55rem;
            padding: 1.35rem 1.65rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .arrival-guidance__row:last-child {
            border-bottom: 0;
        }

        .arrival-guidance__column--do .arrival-guidance__row {
            border-left: 3px solid rgba(72, 196, 181, 0.55);
        }

        .arrival-guidance__column--dont .arrival-guidance__row {
            border-left: 3px solid rgba(242, 125, 45, 0.65);
        }

        .arrival-guidance__index {
            display: block;
            color: rgba(255, 255, 255, 0.42);
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.1em;
        }

        .arrival-guidance__content h4 {
            margin: 0 0 0.4rem;
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 1.05rem;
            font-weight: 600;
            line-height: 1.35;
            letter-spacing: -0.02em;
        }

        .arrival-guidance__content p {
            margin: 0;
            color: rgba(255, 255, 255, 0.78);
            font-size: 0.94rem;
            line-height: 1.65;
            text-align: left;
        }

        .arrival-timeline {
            position: relative;
            display: grid;
            gap: 2.4rem;
            margin-top: 1.45rem;
            padding-left: 0;
            --timeline-progress: 0px;
            --timeline-start: 55px;
            --timeline-end-offset: 55px;
        }

        .arrival-timeline::before {
            content: '';
            position: absolute;
            left: 34px;
            top: var(--timeline-start);
            bottom: var(--timeline-end-offset);
            width: 2px;
            border-radius: 999px;
            background: rgba(62, 79, 90, 0.28);
            z-index: 0;
        }

        .arrival-timeline::after {
            content: '';
            position: absolute;
            left: 34px;
            top: var(--timeline-start);
            width: 2px;
            height: var(--timeline-progress);
            max-height: calc(100% - var(--timeline-start) - var(--timeline-end-offset));
            border-radius: 999px;
            background: linear-gradient(180deg, #f18a42 0%, #e8773a 100%);
            transition: height 0.18s ease-out;
            z-index: 0;
        }

        .arrival-step {
            position: relative;
            min-height: 88px;
            padding: 0.35rem 0 0.35rem 0;
            border: 0;
            border-radius: 0;
            background: transparent;
            box-shadow: none;
        }

        .arrival-step::before {
            content: '';
            position: absolute;
            left: 24px;
            top: 24px;
            width: 20px;
            height: 20px;
            border-radius: 999px;
            background: #e8773a;
            z-index: 1;
        }

        .arrival-step__head {
            display: block;
            min-height: 68px;
        }

        .arrival-step__number {
            position: absolute;
            left: 0;
            top: 0;
            display: grid;
            place-items: center;
            width: 68px;
            height: 68px;
            border: 7px solid currentColor;
            border-radius: 999px;
            background: #ffffff;
            color: #e8773a;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 1.45rem;
            font-weight: 600;
            line-height: 1;
            box-shadow: 0 10px 24px rgba(35, 52, 62, 0.08);
            z-index: 2;
        }

        .arrival-step__label {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            width: fit-content;
            min-height: auto;
            padding: 0;
            border-radius: 0;
            background: transparent;
            color: #5f7380;
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            box-shadow: none;
        }

        .arrival-step__label svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }

        .arrival-step h3 {
            margin: 0;
            max-width: none;
            font-size: 1.42rem;
            line-height: 1.08;
            text-align: left;
        }

        .arrival-step__copy {
            margin-left: 96px;
            padding-top: 0.18rem;
            text-align: left;
        }

        .arrival-step__body {
            margin-top: 0.45rem;
            margin-left: 96px;
            padding-top: 0;
            border-top: 0;
            text-align: left;
        }

        .arrival-step__body p {
            text-align: left;
        }

        .arrival-step__body strong {
            display: inline-block;
            margin-bottom: 0.3rem;
            color: var(--primary-dark);
            font-size: 0.8rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            text-align: left;
        }

        .arrival-step__copy {
            padding-top: 0.2rem;
        }


        .arrival-note {
            margin-top: 1.3rem;
            padding: 1.35rem 1.45rem;
            background: linear-gradient(180deg, #fff8ef 0%, #ffffff 100%);
        }

        .arrival-note p {
            margin: 0;
            max-width: 62ch;
            margin-inline: auto;
            color: #314d58;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 1.3rem;
            line-height: 1.7;
            text-align: center;
        }

        .arrival-day-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            margin-top: 1.25rem;
        }

        .arrival-day-card {
            padding: 2.5rem;
        }

        .arrival-day-card--full {
            grid-column: 1 / -1;
        }

        .arrival-day-card__head {
            display: flex;
            gap: 0.8rem;
            align-items: center;
            margin-bottom: 0.85rem;
        }

        .arrival-day-card__head .arrival-icon {
            width: 46px;
            height: 46px;
            border-radius: 15px;
        }

        .arrival-day-card__head h3 {
            margin: 0;
            font-size: 1.18rem;
            text-align: left;
        }

        .arrival-day-card a {
            color: var(--primary-brand);
        }

        .arrival-day-card,
        .arrival-day-card p {
            text-align: left;
        }

        .arrival-cta {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2.5rem;
            background:
                linear-gradient(180deg, rgba(11, 122, 117, 0.08) 0%, rgba(255, 255, 255, 0.98) 100%);
        }

        .arrival-cta__actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.8rem;
            justify-content: center;
            margin-top: auto;
            padding-top: 1rem;
        }

        .arrival-cta .button--outline {
            background: transparent;
            border: 1px solid rgba(11, 122, 117, 0.12);
            color: var(--primary-brand);
            box-shadow: none;
        }

        @media (max-width: 1024px) {
            .arrival-hero__grid,
            .arrival-grid-2,
            .arrival-grid-3,
            .arrival-grid-cta,
            .arrival-day-grid,
            .arrival-guidance__panel {
                grid-template-columns: 1fr;
            }

            .arrival-hero::before {
                top: 0;
                bottom: 30%;
                left: -8%;
                width: 116%;
                border-radius: 0 0 50% 50% / 0 0 18% 18%;
            }

            .arrival-guidance__column--do {
                border-right: 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .arrival-guidance__head,
            .arrival-guidance__row {
                padding-left: 1.15rem;
                padding-right: 1.15rem;
            }
        }

        @media (max-width: 767px) {
            .arrival-hero {
                min-height: 0;
                padding: 1.8rem 0 1.6rem;
            }

            .arrival-hero__content {
                padding: 0;
                border-radius: 0;
                text-align: center;
            }

            .arrival-hero__content .eyebrow,
            .arrival-hero__content h1,
            .arrival-hero__content .arrival-hero__lead,
            .arrival-hero__content .arrival-hero__quote,
            .arrival-hero__content .arrival-hero__quote p,
            .arrival-hero__content .arrival-hero__quote span {
                text-align: center !important;
            }

            .arrival-hero__lead {
                margin-left: auto !important;
                margin-right: auto !important;
            }

            .arrival-hero h1 {
                max-width: none;
                font-size: clamp(2.55rem, 11vw, 3.95rem);
            }

            .arrival-cta__actions {
                display: grid;
            }

            .arrival-cta__actions .button {
                width: 100%;
            }

            .arrival-hero__visual {
                max-width: 100%;
            }

            .arrival-hero__image {
                height: auto;
                aspect-ratio: 0.88;
                border-radius: 24px;
                max-width: 100%;
            }

            .arrival-section,
            .arrival-photo-card,
            .arrival-card,
            .arrival-step,
            .arrival-day-card,
            .arrival-faq,
            .arrival-cta,
            .arrival-note {
                border-radius: 24px;
            }

            .arrival-section,
            .arrival-card,
            .arrival-step,
            .arrival-day-card,
            .arrival-faq,
            .arrival-cta {
                padding: 1.15rem;
            }

            .arrival-card__media {
                margin: -1.15rem -1.15rem 1.15rem;
            }

            .arrival-card__media img {
                height: 200px;
            }

            .arrival-timeline {
                gap: 1.5rem;
            }

            .arrival-timeline::before {
                left: 29px;
            }

            .arrival-timeline::after {
                left: 29px;
            }

            .arrival-step {
                min-height: 78px;
                padding: 0.15rem 0 0.15rem 0;
            }

            .arrival-step::before {
                left: 19px;
                top: 19px;
                width: 18px;
                height: 18px;
            }

            .arrival-step__head {
                min-height: 58px;
            }

            .arrival-step__number {
                left: 0;
                top: 0;
                width: 58px;
                height: 58px;
                border-width: 6px;
                font-size: 1.1rem;
            }

            .arrival-step h3 {
                font-size: 1.05rem;
            }

            .arrival-step__body strong {
                font-size: 0.74rem;
            }

            .arrival-step__copy,
            .arrival-step__body {
                margin-left: 76px;
            }

            .arrival-note p {
                font-size: 1.04rem;
            }

            .arrival-pro-tip p {
                font-size: 1.105rem;
            }
        }

        /* ======= BEFORE YOU LAND — REDESIGN ======= */
        #before-you-land {
            padding: 100px 0 !important;
            background: #FAFBFC !important;
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }

        .byl-header {
            text-align: center;
            margin-bottom: 48px;
            padding: 0 24px;
        }

        .byl-title {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: clamp(1.875rem, 3.6vw, 3rem);
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 4px;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .byl-subtitle {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: clamp(1.875rem, 3.6vw, 3rem);
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 16px;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .byl-description {
            max-width: 660px;
            margin: 0 auto;
            font-size: 1.0625rem;
            color: #64748b;
            line-height: 1.7;
            font-weight: 400;
        }

        .byl-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 32px;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .byl-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 32px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .byl-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.08);
        }

        .byl-card--green { border-left: 4px solid #0F766E; }
        .byl-card--orange { border-left: 4px solid #EA580C; }
        .byl-card--blue { border-left: 4px solid #2563EB; }
        .byl-card--red { border-left: 4px solid #DC2626; }

        .byl-card--green:hover { border-left-color: #0F766E; box-shadow: 0 16px 40px rgba(15, 118, 110, 0.1); }
        .byl-card--orange:hover { border-left-color: #EA580C; box-shadow: 0 16px 40px rgba(234, 88, 12, 0.1); }
        .byl-card--blue:hover { border-left-color: #2563EB; box-shadow: 0 16px 40px rgba(37, 99, 235, 0.1); }
        .byl-card--red:hover { border-left-color: #DC2626; box-shadow: 0 16px 40px rgba(220, 38, 38, 0.1); }

        .byl-card__top {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
        }

        .byl-card__badge {
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 999px;
            line-height: 1.4;
        }

        .byl-card__badge--green { background: rgba(15, 118, 110, 0.08); color: #0F766E; }
        .byl-card__badge--orange { background: rgba(234, 88, 12, 0.08); color: #EA580C; }
        .byl-card__badge--blue { background: rgba(37, 99, 235, 0.08); color: #2563EB; }
        .byl-card__badge--red { background: rgba(220, 38, 38, 0.08); color: #DC2626; }

        .byl-card__iconwrap {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-left: auto;
            transition: transform 0.3s ease;
        }

        .byl-card:hover .byl-card__iconwrap {
            transform: scale(1.08);
        }

        .byl-card__iconwrap--green { background: rgba(15, 118, 110, 0.1); color: #0F766E; }
        .byl-card__iconwrap--orange { background: rgba(234, 88, 12, 0.1); color: #EA580C; }
        .byl-card__iconwrap--blue { background: rgba(37, 99, 235, 0.1); color: #2563EB; }
        .byl-card__iconwrap--red { background: rgba(220, 38, 38, 0.1); color: #DC2626; }

        .byl-card__iconwrap svg {
            width: 22px;
            height: 22px;
        }

        .byl-card__title {
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 4px;
            line-height: 1.3;
        }

        .byl-card__subtitle {
            font-size: 0.9375rem;
            color: #64748b;
            margin: 0 0 16px;
            line-height: 1.5;
        }

        .byl-checklist {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: auto;
        }

        .byl-card--green .byl-checklist {
            gap: 8px;
        }

        .byl-check-row {
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .byl-check-icon {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .byl-check-icon svg {
            width: 12px;
            height: 12px;
        }

        .byl-check-icon--green { background: rgba(15, 118, 110, 0.1); color: #0F766E; }
        .byl-check-icon--orange { background: rgba(234, 88, 12, 0.1); color: #EA580C; }
        .byl-check-icon--blue { background: rgba(37, 99, 235, 0.1); color: #2563EB; }
        .byl-check-icon--red { background: rgba(220, 38, 38, 0.1); color: #DC2626; }

        .byl-check-content {
            flex: 1;
            min-width: 0;
        }

        .byl-check-title {
            display: block;
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 0.9375rem;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 2px;
            line-height: 1.4;
        }

        .byl-check-desc {
            margin: 0;
            font-size: 0.875rem;
            color: #64748b;
            line-height: 1.55;
        }

        .byl-card__text {
            font-size: 0.9375rem;
            color: #475569;
            line-height: 1.7;
            margin: 0;
        }

        @media (max-width: 1024px) {
            .byl-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 24px;
                padding: 0 20px;
            }

            .byl-title,
            .byl-subtitle {
                font-size: clamp(1.7rem, 3.2vw, 2.375rem);
            }
        }

        @media (max-width: 767px) {
            #before-you-land {
                padding: 64px 0 !important;
            }

            .byl-grid {
                grid-template-columns: 1fr;
                gap: 24px;
                padding: 0 16px;
            }

            .byl-header {
                margin-bottom: 40px;
                padding: 0 16px;
            }

            .byl-card {
                padding: 24px;
            }

            .byl-title,
            .byl-subtitle {
                font-size: 1.875rem;
            }

            .byl-checklist {
                gap: 14px;
            }
        }

        /* ======= DOS AND DONTS — REDESIGN ======= */
        #dos-and-donts.arrival-section {
            padding: 100px 0 60px !important;
            background: #FAFBFC !important;
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            margin-top: 0 !important;
        }

        .dd-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 32px;
        }

        .dd-header {
            text-align: center;
            margin-bottom: 48px;
            padding: 0 24px;
        }

        .dd-header .eyebrow {
            color: var(--primary-brand, #0a7a75);
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .dd-heading {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: clamp(2rem, 3.8vw, 3.2rem);
            font-weight: 800;
            color: #0f172a;
            margin: 0 auto 6px;
            line-height: 1.15;
        }

        .dd-subtitle {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: clamp(1.4rem, 2.6vw, 2rem);
            font-weight: 700;
            color: #334155;
            margin: 0 auto 16px;
            line-height: 1.2;
        }

        .dd-description {
            max-width: 660px;
            margin: 0 auto;
            font-size: 1.0625rem;
            color: #64748b;
            line-height: 1.7;
        }

        .dd-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
            align-items: start;
        }

        .dd-column__header {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 24px;
            border-radius: 16px;
            margin-bottom: 24px;
        }

        .dd-column__header--do {
            background: #F0FDF4;
            border-left: 4px solid #16A34A;
        }

        .dd-column__header--dont {
            background: #FEF2F2;
            border-left: 4px solid #DC2626;
        }

        .dd-column__icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .dd-column__icon--do {
            background: #16A34A;
            color: #ffffff;
        }

        .dd-column__icon--dont {
            background: #DC2626;
            color: #ffffff;
        }

        .dd-column__icon svg {
            width: 20px;
            height: 20px;
        }

        .dd-column__header-text h3 {
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 2px;
        }

        .dd-column__header-text p {
            font-size: 0.9rem;
            color: #64748b;
            margin: 0;
        }

        .dd-cards {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .dd-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 32px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            position: relative;
        }

        .dd-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.08);
        }

        .dd-card--do {
            border-left: 4px solid #16A34A;
        }

        .dd-card--dont {
            border-left: 4px solid #DC2626;
        }

        .dd-card--do:hover {
            border-left-color: #15803d;
            box-shadow: 0 12px 32px rgba(22, 163, 74, 0.08);
        }

        .dd-card--dont:hover {
            border-left-color: #b91c1c;
            box-shadow: 0 12px 32px rgba(220, 38, 38, 0.08);
        }

        .dd-card__badge {
            position: absolute;
            top: 16px;
            right: 16px;
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 3px 10px;
            border-radius: 999px;
            line-height: 1.4;
        }

        .dd-card__badge--do {
            background: #F0FDF4;
            color: #16A34A;
            border: 1px solid rgba(22, 163, 74, 0.2);
        }

        .dd-card__badge--dont {
            background: #FEF2F2;
            color: #DC2626;
            border: 1px solid rgba(220, 38, 38, 0.2);
        }

        .dd-card__top {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 12px;
            padding-right: 80px;
        }

        .dd-card__iconwrap {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: transform 0.25s ease;
        }

        .dd-card:hover .dd-card__iconwrap {
            transform: scale(1.1);
        }

        .dd-card__iconwrap--do {
            background: #F0FDF4;
            color: #16A34A;
        }

        .dd-card__iconwrap--dont {
            background: #FEF2F2;
            color: #DC2626;
        }

        .dd-card__iconwrap svg {
            width: 22px;
            height: 22px;
        }

        .dd-card__title {
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            line-height: 1.35;
            flex: 1;
        }

        .dd-card__desc {
            font-size: 0.9375rem;
            color: #64748b;
            line-height: 1.65;
            margin: 0;
        }

        .dd-card__index {
            display: inline-block;
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 0.7rem;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .dd-card--do .dd-card__index { color: #16A34A; }
        .dd-card--dont .dd-card__index { color: #DC2626; }

        @media (max-width: 900px) {
            .dd-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .dd-card__top {
                padding-right: 0;
            }

            .dd-card__badge {
                position: static;
                display: inline-block;
                margin-bottom: 8px;
            }
        }

        @media (max-width: 767px) {
            #dos-and-donts.arrival-section {
                padding: 64px 0 !important;
            }

            .dd-container {
                padding: 0 20px;
            }

            .dd-header {
                margin-bottom: 36px;
            }

            .dd-grid {
                gap: 32px;
            }

            .dd-card {
                padding: 22px;
            }

            .dd-card__title {
                font-size: 1rem;
            }

            .dd-card__desc {
                font-size: 0.9rem;
            }
        }

        /* ======= FIRST WEEK — NEW SECTION ======= */
        #first-week {
            padding: 100px 0;
            background: #FAFBFC;
        }

        .fw-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 32px;
        }

        .fw-header {
            text-align: center;
            margin-bottom: 48px;
        }

        .fw-header .eyebrow {
            color: var(--primary-brand, #0a7a75);
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .fw-heading {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: clamp(2rem, 3.8vw, 3.2rem);
            font-weight: 800;
            color: #0f172a;
            margin: 0 auto 12px;
            line-height: 1.15;
        }

        .fw-subtitle {
            max-width: 680px;
            margin: 0 auto;
            font-size: 1.1rem;
            color: #64748b;
            line-height: 1.7;
            font-weight: 400;
        }

        .fw-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
            align-items: start;
        }

        .fw-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 32px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            display: flex;
            flex-direction: column;
        }

        .fw-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.08);
        }

        .fw-card--left {
            background: #ECFEF9;
        }

        .fw-card--right {
            background: #FFF7ED;
        }

        .fw-card__title {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 24px;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .fw-list {
            display: flex;
            flex-direction: column;
            gap: 0;
            margin: 0 0 auto;
        }

        .fw-item {
            display: flex;
            gap: 16px;
            align-items: flex-start;
            padding: 14px 8px;
            margin: 0 -8px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            transition: background 0.2s ease;
            border-radius: 8px;
            cursor: default;
        }

        .fw-item:last-of-type {
            border-bottom: none;
        }

        .fw-item:hover {
            background: rgba(255, 255, 255, 0.6);
        }

        .fw-number {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #0F766E;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 1rem;
            font-weight: 700;
            flex-shrink: 0;
            box-shadow: 0 4px 8px rgba(15, 118, 110, 0.2);
        }

        .fw-check {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(15, 118, 110, 0.1);
            color: #0F766E;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: transform 0.25s ease;
        }

        .fw-item:hover .fw-check {
            transform: scale(1.1);
        }

        .fw-check svg {
            width: 20px;
            height: 20px;
        }

        .fw-item__content {
            flex: 1;
            min-width: 0;
            padding-top: 2px;
        }

        .fw-item__title {
            display: block;
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 1.125rem;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 2px;
            line-height: 1.4;
        }

        .fw-item__desc {
            margin: 0;
            font-size: 0.9375rem;
            color: #64748b;
            line-height: 1.55;
        }

        .fw-tip {
            margin-top: 28px;
            padding: 18px 20px;
            border-radius: 14px;
            background: rgba(15, 118, 110, 0.08);
            border: 1px solid rgba(15, 118, 110, 0.12);
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .fw-tip svg {
            width: 22px;
            height: 22px;
            color: #0F766E;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .fw-tip__content {
            flex: 1;
        }

        .fw-tip__label {
            display: block;
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 0.8rem;
            font-weight: 700;
            color: #0F766E;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 2px;
        }

        .fw-tip__text {
            margin: 0;
            font-size: 0.9375rem;
            color: #334155;
            line-height: 1.55;
        }

        .fw-info {
            margin-top: 28px;
            padding: 18px 20px;
            border-radius: 14px;
            background: rgba(234, 88, 12, 0.08);
            border: 1px solid rgba(234, 88, 12, 0.12);
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .fw-info svg {
            width: 22px;
            height: 22px;
            color: #EA580C;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .fw-info__content {
            flex: 1;
        }

        .fw-info__label {
            display: block;
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 0.8rem;
            font-weight: 700;
            color: #EA580C;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 2px;
        }

        .fw-info__text {
            margin: 0;
            font-size: 0.9375rem;
            color: #334155;
            line-height: 1.55;
        }

        @media (max-width: 900px) {
            .fw-grid {
                grid-template-columns: 1fr;
                gap: 24px;
            }
        }

        @media (max-width: 767px) {
            #first-week {
                padding: 64px 0;
            }

            .fw-container {
                padding: 0 20px;
            }

            .fw-header {
                margin-bottom: 40px;
            }

            .fw-card {
                padding: 24px;
            }

            .fw-card__title {
                font-size: 1.5rem;
                margin-bottom: 20px;
            }

            .fw-item {
                padding: 12px 8px;
                gap: 14px;
            }

            .fw-number,
            .fw-check {
                width: 38px;
                height: 38px;
                font-size: 0.9rem;
            }

            .fw-check svg {
                width: 18px;
                height: 18px;
            }

            .fw-item__title {
                font-size: 1rem;
            }

            .fw-item__desc {
                font-size: 0.875rem;
            }

            .fw-tip,
            .fw-info {
                padding: 16px;
                margin-top: 24px;
            }
        }

        /* ======= ARRIVAL SEQUENCE — PRO CORPORATE ======= */
        .at-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 32px;
        }

        .at-header {
            text-align: center;
            margin-bottom: 64px;
        }

        .at-header .eyebrow {
            color: var(--primary-brand, #0a7a75);
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .at-heading {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: clamp(2.2rem, 4vw, 3.4rem);
            font-weight: 800;
            color: #0f172a;
            margin: 0 auto 16px;
            max-width: 700px;
            line-height: 1.15;
        }

        .at-heading-desc {
            max-width: 660px;
            margin: 0 auto;
            font-size: 1.125rem;
            color: #64748b;
            line-height: 1.7;
        }

        .at-timeline {
            position: relative;
            padding: 0 0 0 64px;
            margin-top: 0;
        }

        .at-timeline::before {
            content: '';
            position: absolute;
            left: 31px;
            top: 28px;
            bottom: 28px;
            width: 2px;
            background: linear-gradient(180deg, #0F766E 0%, rgba(15, 118, 110, 0.15) 100%);
            border-radius: 999px;
        }

        .at-step {
            position: relative;
            margin-bottom: 32px;
        }

        .at-step:last-child {
            margin-bottom: 0;
        }

        .at-step__marker {
            position: absolute;
            left: -72px;
            top: 28px;
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #0F766E;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            box-shadow: 0 6px 16px rgba(15, 118, 110, 0.25);
            z-index: 2;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            border: 3px solid #ffffff;
        }

        .at-step:hover .at-step__marker {
            transform: scale(1.08);
            box-shadow: 0 10px 28px rgba(15, 118, 110, 0.35);
        }

        .at-step__card {
            background: #ffffff;
            border-radius: 20px;
            padding: 32px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .at-step:hover .at-step__card {
            transform: translateX(4px);
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.08);
        }

        .at-step__top {
            display: flex;
            gap: 16px;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .at-step__iconwrap {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: #F0FDF9;
            color: #0F766E;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: transform 0.25s ease;
        }

        .at-step:hover .at-step__iconwrap {
            transform: scale(1.08);
        }

        .at-step__iconwrap svg {
            width: 24px;
            height: 24px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.6;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .at-step__title {
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: #0f172a;
            margin: 8px 0 0;
            line-height: 1.4;
            flex: 1;
        }

        .at-step__body {
            font-size: 0.9375rem;
            color: #64748b;
            line-height: 1.65;
            margin: 0 0 0 64px;
        }

        .at-step__reason {
            margin-top: 14px;
            padding: 14px 18px;
            background: #F0FDF9;
            border-radius: 12px;
            border-left: 3px solid #0F766E;
            margin-left: 64px;
        }

        .at-step__reason-label {
            display: block;
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 0.72rem;
            font-weight: 700;
            color: #0F766E;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 3px;
        }

        .at-step__reason p {
            margin: 0;
            font-size: 0.875rem;
            color: #334155;
            line-height: 1.55;
        }

        .at-pro-tip {
            margin-top: 60px;
            max-width: 820px;
            margin-left: auto;
            margin-right: auto;
            padding: 24px 28px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #0F766E;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
            display: flex;
            gap: 16px;
            align-items: flex-start;
        }

        .at-pro-tip svg {
            width: 24px;
            height: 24px;
            color: #0F766E;
            flex-shrink: 0;
            margin-top: 2px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .at-pro-tip-content {
            flex: 1;
        }

        .at-pro-tip-label {
            display: block;
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 0.8rem;
            font-weight: 700;
            color: #0F766E;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 4px;
        }

        .at-pro-tip-content p {
            margin: 0;
            font-size: 0.9375rem;
            color: #334155;
            line-height: 1.65;
        }

        @media (max-width: 900px) {
            .at-timeline {
                padding-left: 60px;
            }

            .at-step__marker {
                left: -60px;
                width: 52px;
                height: 52px;
                font-size: 1.15rem;
            }

            .at-timeline::before {
                left: 26px;
            }

            .at-step__body,
            .at-step__reason {
                margin-left: 0;
            }

            .at-step__card {
                padding: 24px;
            }
        }

        @media (max-width: 767px) {
            #first-7-days.arrival-section {
                padding: 64px 0 !important;
            }

            .at-container {
                padding: 0 20px;
            }

            .at-header {
                margin-bottom: 40px;
            }

            .at-heading {
                font-size: clamp(1.8rem, 6vw, 2.2rem);
            }

            .at-timeline {
                padding: 0 0 0 48px;
            }

            .at-timeline::before {
                left: 20px;
            }

            .at-step {
                margin-bottom: 24px;
            }

            .at-step__marker {
                left: -48px;
                top: 20px;
                width: 42px;
                height: 42px;
                font-size: 0.95rem;
                border-width: 2px;
            }

            .at-step__card {
                padding: 20px;
                border-radius: 16px;
            }

            .at-step__top {
                gap: 12px;
            }

            .at-step__iconwrap {
                width: 40px;
                height: 40px;
                border-radius: 12px;
            }

            .at-step__iconwrap svg {
                width: 20px;
                height: 20px;
            }

            .at-step__title {
                font-size: 1rem;
                margin-top: 6px;
            }

            .at-step__body {
                font-size: 0.875rem;
            }

            .at-step__reason {
                padding: 12px 14px;
            }

            .at-pro-tip {
                margin-top: 40px;
                padding: 18px 20px;
                flex-direction: column;
                gap: 10px;
            }
        }

    </style>
@endsection

@section('content')
    <div class="arrival-guide">
        <!-- HERO SECTION — ARRIVE STYLE -->
        <section id="top" class="guide-hero">
            <div class="container">
                <div class="guide-hero__grid">
                    <div class="guide-hero__content">
                        <div class="guide-hero__meta">
                            <span class="guide-hero__number">G</span>
                            <span>New to Australia guide 2026</span>
                        </div>
                        <h1><span class="guide-hero__accent">Just</span> Arrived in Australia?</h1>
                        <p class="guide-hero__copy">Here's Exactly What to Do First and in the Right Order — a practical step-by-step guide to settling in with confidence.</p>
                        <div class="guide-hero__chips">
                            <a class="guide-hero__chip guide-hero__chip--primary" href="#before-you-land">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                Start the guide
                            </a>
                            <a class="guide-hero__chip" href="/settlement-services">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                                Settlement services
                            </a>
                        </div>
                    </div>
                    <div class="guide-hero__image">
                        <img src="{{ str_replace(' ', '%20', asset('media/new to australlia/New to Australia hero.webp')) }}" alt="New arrivals settling into life in Australia" loading="eager">
                    </div>
                </div>
            </div>
        </section>

        <section class="guide-section guide-section--hero-adjacent">
            <div class="guide-container">
                <div class="arrival-intro__inner">
                <p class="arrival-intro__lead">Most new arrivals do not fail because information is missing; they struggle because the steps are out of order. This guide gives you a practical sequence for your first days, weeks, and months in Australia.</p>

                <div class="arrival-intro__quote">
                    <p>“By the time most people realise they've taken the wrong steps in the wrong order, weeks have passed — and some of those mistakes are expensive to undo.”</p>
                    <span>Entel Dajsmaili, arrived January 2001. Australian citizen by 2004.</span>
                </div>
                </div>
            </div>
        </section>

                    <!-- SECTION 1: BEFORE YOU LAND — REDESIGNED -->
                    <section id="before-you-land" class="guide-section guide-section--white">
                        <div class="guide-container">
                        <div class="byl-header">
                            <h2 class="byl-title">Moving to Australia in 2026?</h2>
                            <p class="byl-subtitle">Sort These Before Your Flight Lands</p>
                            <p class="byl-description">Completing these tasks before you arrive helps you avoid the most common settlement mistakes and start your new life with confidence.</p>
                        </div>

                        <div class="byl-grid">
                            <!-- CARD 1: Documents -->
                            <article class="byl-card byl-card--green">
                                <div class="byl-card__top">
                                    <span class="byl-card__badge byl-card__badge--green">Documents</span>
                                    <span class="byl-card__iconwrap byl-card__iconwrap--green" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                            <polyline points="14 2 14 8 20 8"/>
                                            <line x1="16" y1="13" x2="8" y2="13"/>
                                            <line x1="16" y1="17" x2="8" y2="17"/>
                                        </svg>
                                    </span>
                                </div>
                                <h3 class="byl-card__title">Documents to Carry Physically</h3>
                                <p class="byl-card__subtitle">Carry these in print, not just on your phone.</p>

                                <div class="byl-checklist">
                                    <div class="byl-check-row">
                                        <span class="byl-check-icon byl-check-icon--green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span>
                                        <div class="byl-check-content">
                                            <strong class="byl-check-title">Passport + visa grant letter</strong>
                                            <p class="byl-check-desc">Printed, not just on your phone.</p>
                                        </div>
                                    </div>
                                    <div class="byl-check-row">
                                        <span class="byl-check-icon byl-check-icon--green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span>
                                        <div class="byl-check-content">
                                            <strong class="byl-check-title">University offer letter or employment contract</strong>
                                            <p class="byl-check-desc"></p>
                                        </div>
                                    </div>
                                    <div class="byl-check-row">
                                        <span class="byl-check-icon byl-check-icon--green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span>
                                        <div class="byl-check-content">
                                            <strong class="byl-check-title">3 months of bank statements from your home country</strong>
                                            <p class="byl-check-desc"></p>
                                        </div>
                                    </div>
                                    <div class="byl-check-row">
                                        <span class="byl-check-icon byl-check-icon--green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span>
                                        <div class="byl-check-content">
                                            <strong class="byl-check-title">International health insurance certificate</strong>
                                            <p class="byl-check-desc"></p>
                                        </div>
                                    </div>
                                    <div class="byl-check-row">
                                        <span class="byl-check-icon byl-check-icon--green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span>
                                        <div class="byl-check-content">
                                            <strong class="byl-check-title">6 passport photos</strong>
                                            <p class="byl-check-desc">You'll need them more than you expect.</p>
                                        </div>
                                    </div>
                                </div>
                            </article>

                            <!-- CARD 2: Book Before -->
                            <article class="byl-card byl-card--orange">
                                <div class="byl-card__top">
                                    <span class="byl-card__badge byl-card__badge--orange">Bookings</span>
                                    <span class="byl-card__iconwrap byl-card__iconwrap--orange" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                            <line x1="16" y1="2" x2="16" y2="6"/>
                                            <line x1="8" y1="2" x2="8" y2="6"/>
                                            <line x1="3" y1="10" x2="21" y2="10"/>
                                        </svg>
                                    </span>
                                </div>
                                <h3 class="byl-card__title">Book Before You Land</h3>
                                <p class="byl-card__subtitle">These bookings reduce pressure in your first week.</p>

                                <div class="byl-checklist">
                                    <div class="byl-check-row">
                                        <span class="byl-check-icon byl-check-icon--orange"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span>
                                        <div class="byl-check-content">
                                            <strong class="byl-check-title">Short-term accommodation — minimum 4 weeks</strong>
                                            <p class="byl-check-desc">You need time to find a suburb that actually fits your life before you sign a 12-month lease. <a href="https://www.booking.com" target="_blank" rel="noreferrer">Browse short-term accommodation in Australia</a></p>
                                        </div>
                                    </div>
                                    <div class="byl-check-row">
                                        <span class="byl-check-icon byl-check-icon--orange"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span>
                                        <div class="byl-check-content">
                                            <strong class="byl-check-title">Airport transfer</strong>
                                            <p class="byl-check-desc">Don't rely on Uber on arrival if you have heavy luggage and no Australian SIM yet.</p>
                                        </div>
                                    </div>
                                    <div class="byl-check-row">
                                        <span class="byl-check-icon byl-check-icon--orange"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span>
                                        <div class="byl-check-content">
                                            <strong class="byl-check-title">Travel insurance for the gap before Medicare activates</strong>
                                            <p class="byl-check-desc"><a href="https://www.cignaglobal.com" target="_blank" rel="noreferrer">Cigna Global</a> or <a href="https://safetywing.com" target="_blank" rel="noreferrer">SafetyWing</a></p>
                                        </div>
                                    </div>
                                </div>
                            </article>

                            <!-- CARD 3: Financial -->
                            <article class="byl-card byl-card--blue">
                                <div class="byl-card__top">
                                    <span class="byl-card__badge byl-card__badge--blue">Finance</span>
                                    <span class="byl-card__iconwrap byl-card__iconwrap--blue" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/>
                                            <path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/>
                                            <path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/>
                                        </svg>
                                    </span>
                                </div>
                                <h3 class="byl-card__title">Financial Preparation</h3>
                                <p class="byl-card__subtitle">Sort your money before you arrive.</p>

                                <div class="byl-checklist">
                                    <div class="byl-check-row">
                                        <span class="byl-check-icon byl-check-icon--blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span>
                                        <div class="byl-check-content">
                                            <strong class="byl-check-title">Set up your finances before you land</strong>
                                            <p class="byl-check-desc">Open an Australian bank account before you arrive, arrange international fund transfers, and notify your home bank of your move.</p>
                                        </div>
                                    </div>
                                </div>
                            </article>

                            <!-- CARD 4: Health -->
                            <article class="byl-card byl-card--red">
                                <div class="byl-card__top">
                                    <span class="byl-card__badge byl-card__badge--red">Health</span>
                                    <span class="byl-card__iconwrap byl-card__iconwrap--red" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                            <line x1="12" y1="9" x2="12" y2="15"/>
                                            <line x1="9" y1="12" x2="15" y2="12"/>
                                        </svg>
                                    </span>
                                </div>
                                <h3 class="byl-card__title">Health &amp; Insurance</h3>
                                <p class="byl-card__subtitle">Protect your health from day one.</p>

                                <div class="byl-checklist">
                                    <div class="byl-check-row">
                                        <span class="byl-check-icon byl-check-icon--red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span>
                                        <div class="byl-check-content">
                                            <strong class="byl-check-title">Need short-term accommodation while you find your feet?</strong>
                                            <p class="byl-check-desc">I always recommend booking at least 3–4 weeks before committing to a suburb. Search by distance to your workplace first not by price or city centre. For a full walkthrough of renting in Australia, read the <a class="text-link" href="/housing">Housing Guide</a>.</p>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                        </div>
                    </section>

                    <!-- SECTION: FIRST WEEK — NEW -->
<section id="first-week" class="guide-section guide-section--sand">
                        <div class="guide-container">
                            <div class="fw-header">
                                <p class="eyebrow">First Week</p>
                                <h2 class="fw-heading">You've Landed. Here's Your First Week</h2>
                                <p class="fw-subtitle">The first seven days are the most important for settling into Australia. Follow these essential steps to get organised, avoid common mistakes, and start your new life with confidence.</p>
                            </div>

                            <div class="fw-grid">
                                <!-- LEFT CARD: Day 1-2 Essentials -->
                                <article class="fw-card fw-card--left">
                                    <h3 class="fw-card__title">Day 1–2 Essentials</h3>

                                    <div class="fw-list">
                                        <!-- Item 1 -->
                                        <div class="fw-item">
                                            <span class="fw-number" aria-hidden="true">1</span>
                                            <div class="fw-item__content">
                                                <strong class="fw-item__title">Buy a Local SIM Card</strong>
                                                <p class="fw-item__desc">Get a prepaid SIM from Telstra, Optus, or Vodafone at the airport or a local store. You'll need an Australian number for every form, bank verification, and employer contact.</p>
                                            </div>
                                        </div>

                                        <!-- Item 2 -->
                                        <div class="fw-item">
                                            <span class="fw-number" aria-hidden="true">2</span>
                                            <div class="fw-item__content">
                                                <strong class="fw-item__title">Activate Your Australian Bank Account</strong>
                                                <p class="fw-item__desc">Visit your chosen bank's branch with your passport, visa grant letter, and proof of address. Commonwealth Bank, ANZ, and Westpac have streamlined processes for new arrivals.</p>
                                            </div>
                                        </div>

                                        <!-- Item 3 -->
                                        <div class="fw-item">
                                            <span class="fw-number" aria-hidden="true">3</span>
                                            <div class="fw-item__content">
                                                <strong class="fw-item__title">Apply for Your TFN</strong>
                                                <p class="fw-item__desc">Your Tax File Number is essential for legal employment. Apply online through the ATO. Processing takes up to 28 days but your employer can accept proof of application.</p>
                                            </div>
                                        </div>

                                        <!-- Item 4 -->
                                        <div class="fw-item">
                                            <span class="fw-number" aria-hidden="true">4</span>
                                            <div class="fw-item__content">
                                                <strong class="fw-item__title">Register Your Address</strong>
                                                <p class="fw-item__desc">Update your address with the ATO, your bank, and the Department of Home Affairs. This ensures official correspondence and tax documents reach you without delay.</p>
                                            </div>
                                        </div>

                                        <!-- Item 5 -->
                                        <div class="fw-item">
                                            <span class="fw-number" aria-hidden="true">5</span>
                                            <div class="fw-item__content">
                                                <strong class="fw-item__title">Set Up Utilities</strong>
                                                <p class="fw-item__desc">Arrange electricity, gas, internet, and water connections for your rental. Compare providers on Energy Made Easy or Finder to find the best rates for your suburb.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pro Tip -->
                                    <div class="fw-tip">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M9 18h6"/>
                                            <path d="M10 22h4"/>
                                            <path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14"/>
                                        </svg>
                                        <div class="fw-tip__content">
                                            <span class="fw-tip__label">Pro Tip</span>
                                            <p class="fw-tip__text">Completing these first five tasks within 48 hours of landing will save you days of administrative stress later. Do them in this order for the smoothest experience.</p>
                                        </div>
                                    </div>
                                </article>

                                <!-- RIGHT CARD: Week 1 Setup -->
                                <article class="fw-card fw-card--right">
                                    <h3 class="fw-card__title">Week 1 Setup</h3>

                                    <div class="fw-list">
                                        <!-- Item 1 -->
                                        <div class="fw-item">
                                            <span class="fw-check" aria-hidden="true">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M5.5 12.5 10 17l8.5-9"/>
                                                </svg>
                                            </span>
                                            <div class="fw-item__content">
                                                <strong class="fw-item__title">Enrol in Medicare</strong>
                                                <p class="fw-item__desc">Check your visa eligibility for Medicare. If eligible, visit a Service Centre with your passport and visa grant letter. Medicare covers GP visits and public hospital care.</p>
                                            </div>
                                        </div>

                                        <!-- Item 2 -->
                                        <div class="fw-item">
                                            <span class="fw-check" aria-hidden="true">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M5.5 12.5 10 17l8.5-9"/>
                                                </svg>
                                            </span>
                                            <div class="fw-item__content">
                                                <strong class="fw-item__title">Register with a GP</strong>
                                                <p class="fw-item__desc">Find a general practice accepting new patients near your home. Register before you need one — not on the day you get sick. Bulk billing practices offer no-gap fees.</p>
                                            </div>
                                        </div>

                                        <!-- Item 3 -->
                                        <div class="fw-item">
                                            <span class="fw-check" aria-hidden="true">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M5.5 12.5 10 17l8.5-9"/>
                                                </svg>
                                            </span>
                                            <div class="fw-item__content">
                                                <strong class="fw-item__title">Explore Your Neighbourhood</strong>
                                                <p class="fw-item__desc">Walk your local area to find supermarkets, pharmacies, ATMs, and public transport stops. Knowing your immediate surroundings reduces anxiety and helps you feel at home faster.</p>
                                            </div>
                                        </div>

                                        <!-- Item 4 -->
                                        <div class="fw-item">
                                            <span class="fw-check" aria-hidden="true">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M5.5 12.5 10 17l8.5-9"/>
                                                </svg>
                                            </span>
                                            <div class="fw-item__content">
                                                <strong class="fw-item__title">Join Community Groups</strong>
                                                <p class="fw-item__desc">Connect with local expat and migrant communities on Facebook, Meetup, or local council events. These groups share practical advice and help you build a support network.</p>
                                            </div>
                                        </div>

                                        <!-- Item 5 -->
                                        <div class="fw-item">
                                            <span class="fw-check" aria-hidden="true">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M5.5 12.5 10 17l8.5-9"/>
                                                </svg>
                                            </span>
                                            <div class="fw-item__content">
                                                <strong class="fw-item__title">Learn Public Transport</strong>
                                                <p class="fw-item__desc">Get an Opal card (Sydney), Myki (Melbourne), or Go card (Brisbane). Download the local transport app and plan your commute routes before your first day of work or study.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Estimated Time -->
                                    <div class="fw-info">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"/>
                                            <polyline points="12 6 12 12 16 14"/>
                                        </svg>
                                        <div class="fw-info__content">
                                            <span class="fw-info__label">Estimated Time</span>
                                            <p class="fw-info__text">Approximately 6–8 hours spread across your first week. Tackle two items per day to make steady progress without feeling overwhelmed.</p>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        </div>
                    </section>

                    <!-- SECTION: DOS AND DONTS — REDESIGNED -->
                    <section id="dos-and-donts" class="guide-section guide-section--white">
                        <div class="guide-container">
                            <div class="dd-header">
                                <p class="eyebrow">Dos &amp; Don'ts</p>
                                <h2 class="dd-heading">Australia Immigration Mistakes to Avoid</h2>
                                <p class="dd-description">Following these best practices helps you avoid common settlement mistakes and settle into Australian life with confidence.</p>
                            </div>

                            <div class="dd-grid">
                                <!-- DO'S COLUMN -->
                                <div class="dd-column">
                                    <div class="dd-column__header dd-column__header--do">
                                        <span class="dd-column__icon dd-column__icon--do" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5.5 12.5 10 17l8.5-9"/>
                                            </svg>
                                        </span>
                                        <div class="dd-column__header-text">
                                            <h3>Do's — Best Practices</h3>
                                            <p>Habits that help you settle faster</p>
                                        </div>
                                    </div>

                                    <div class="dd-cards">
                                        <!-- Do Card 1 -->
                                        <article class="dd-card dd-card--do">
                                            <span class="dd-card__badge dd-card__badge--do">Recommended</span>
                                            <div class="dd-card__top">
                                                <span class="dd-card__iconwrap dd-card__iconwrap--do" aria-hidden="true">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9z"/>
                                                        <polyline points="9 22 9 12 15 12 15 22"/>
                                                    </svg>
                                                </span>
                                                <h4 class="dd-card__title">Use your employer as a rental reference</h4>
                                            </div>
                                            <p class="dd-card__desc">You have no Australian rental history — that's the reality for every new arrival. An employment contract plus a letter from your manager replaces rental references with most private landlords. Target private landlords over large real estate agencies. They have more flexibility and fewer box-ticking requirements.</p>
                                        </article>

                                        <!-- Do Card 2 -->
                                        <article class="dd-card dd-card--do">
                                            <span class="dd-card__badge dd-card__badge--do">Recommended</span>
                                            <div class="dd-card__top">
                                                <span class="dd-card__iconwrap dd-card__iconwrap--do" aria-hidden="true">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                        <line x1="12" y1="1" x2="12" y2="23"/>
                                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                                                    </svg>
                                                </span>
                                                <h4 class="dd-card__title">Track your superannuation from week one</h4>
                                            </div>
                                            <p class="dd-card__desc">Every Australian employer is legally required to contribute 11% of your salary into a superannuation fund on top of your salary, not from it. Most migrants only find out years later that a previous employer didn't pay it. The ATO has a free tool to check. Use it from the start.</p>
                                        </article>

                                        <!-- Do Card 3 -->
                                        <article class="dd-card dd-card--do">
                                            <span class="dd-card__badge dd-card__badge--do">Recommended</span>
                                            <div class="dd-card__top">
                                                <span class="dd-card__iconwrap dd-card__iconwrap--do" aria-hidden="true">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                                                        <line x1="1" y1="10" x2="23" y2="10"/>
                                                    </svg>
                                                </span>
                                                <h4 class="dd-card__title">Build your Australian credit score deliberately from month one</h4>
                                            </div>
                                            <p class="dd-card__desc">Your overseas credit history means nothing here. You start at zero — and that affects post-paid phone plans, car financing, and eventually a mortgage. A secured credit card linked to your bank account, used for small purchases and paid off in full every month, is the fastest way to build it.</p>
                                        </article>
                                    </div>
                                </div>

                                <!-- DON'TS COLUMN -->
                                <div class="dd-column">
                                    <div class="dd-column__header dd-column__header--dont">
                                        <span class="dd-column__icon dd-column__icon--dont" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"/>
                                                <line x1="15" y1="9" x2="9" y2="15"/>
                                                <line x1="9" y1="9" x2="15" y2="15"/>
                                            </svg>
                                        </span>
                                        <div class="dd-column__header-text">
                                            <h3>Don'ts — Common Mistakes</h3>
                                            <p>Mistakes most new arrivals regret</p>
                                        </div>
                                    </div>

                                    <div class="dd-cards">
                                        <!-- Don't Card 1 -->
                                        <article class="dd-card dd-card--dont">
                                            <span class="dd-card__badge dd-card__badge--dont">Avoid</span>
                                            <div class="dd-card__top">
                                                <span class="dd-card__iconwrap dd-card__iconwrap--dont" aria-hidden="true">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                        <rect x="5" y="2" width="14" height="20" rx="2"/>
                                                        <line x1="12" y1="18" x2="12.01" y2="18"/>
                                                    </svg>
                                                </span>
                                                <h4 class="dd-card__title">Don't sign a 24-month phone plan before you know your suburb</h4>
                                            </div>
                                            <p class="dd-card__desc">Go prepaid first. Coverage and pricing vary significantly depending on where you end up living. Lock yourself into a plan on day one and you may be paying for coverage you don't get where you actually live.</p>
                                        </article>

                                        <!-- Don't Card 2 -->
                                        <article class="dd-card dd-card--dont">
                                            <span class="dd-card__badge dd-card__badge--dont">Avoid</span>
                                            <div class="dd-card__top">
                                                <span class="dd-card__iconwrap dd-card__iconwrap--dont" aria-hidden="true">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Z"/>
                                                        <path d="M12 6v6l4 2"/>
                                                    </svg>
                                                </span>
                                                <h4 class="dd-card__title">Don't ignore your superannuation even if you plan to leave</h4>
                                            </div>
                                            <p class="dd-card__desc">If you leave Australia permanently, you are legally entitled to claim your super back. It's called the Departing Australia Superannuation Payment (DASP). Most migrants walk away leaving thousands behind simply because they didn't know this existed. It does. Claim it.</p>
                                        </article>

                                        <!-- Don't Card 3 -->
                                        <article class="dd-card dd-card--dont">
                                            <span class="dd-card__badge dd-card__badge--dont">Avoid</span>
                                            <div class="dd-card__top">
                                                <span class="dd-card__iconwrap dd-card__iconwrap--dont" aria-hidden="true">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                                        <line x1="12" y1="9" x2="12" y2="15"/>
                                                        <line x1="9" y1="12" x2="15" y2="12"/>
                                                    </svg>
                                                </span>
                                                <h4 class="dd-card__title">Don't arrive without medical insurance when Medicare isn't active yet</h4>
                                            </div>
                                            <p class="dd-card__desc">If your visa doesn't qualify you for Medicare immediately, you need private cover before you land. One unplanned emergency visit costs $800–$2,000 out of pocket without it. Don't arrive uncovered.</p>
                                        </article>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- SECTION 3: ARRIVAL SEQUENCE — PRO CORPORATE -->
                    <section id="first-7-days" class="guide-section guide-section--sand">
                        <div class="guide-container">
                            <div class="at-header">
                                <p class="eyebrow">Arrival Sequence</p>
                                <h2 class="at-heading">The Order That Actually Matters</h2>
                                <p class="at-heading-desc">Complete these important tasks in order after arriving in Australia.</p>
                            </div>

                            <div class="at-timeline">
                                <!-- Step 1 -->
                                <article class="at-step">
                                    <span class="at-step__marker" aria-hidden="true">1</span>
                                    <div class="at-step__card">
                                        <div class="at-step__top">
                                            <span class="at-step__iconwrap" aria-hidden="true">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M7 2h10a2 2 0 0 1 2 2v16l-4-2.2L12 20l-3-2.2L5 20V4a2 2 0 0 1 2-2Zm1 4v10.6l1-.8 3 2.2 3-2.2 1 .8V6H8Z"/>
                                                </svg>
                                            </span>
                                            <h3 class="at-step__title">Get a prepaid SIM card at the airport, before you leave the terminal</h3>
                                        </div>
                                        <div class="at-step__body">Your Australian mobile number is the key that unlocks everything else. Landlords, employers, and banks all need a local contact number. Every application you fill in from day one requires it. Get this done before anything else.</div>
                                        <div class="at-step__reason">
                                            <span class="at-step__reason-label">Why the order matters</span>
                                            <p>Without a local number you cannot complete bank verification, receive employer callbacks, or fill in rental applications. This single step unlocks everything else.</p>
                                        </div>
                                    </div>
                                </article>

                                <!-- Step 2 -->
                                <article class="at-step">
                                    <span class="at-step__marker" aria-hidden="true">2</span>
                                    <div class="at-step__card">
                                        <div class="at-step__top">
                                            <span class="at-step__iconwrap" aria-hidden="true">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M6 2h9l5 5v13.5A1.5 1.5 0 0 1 18.5 22h-11A1.5 1.5 0 0 1 6 20.5V2Zm8 1.8V8h4.2L14 3.8ZM9 11h6v1.8H9V11Zm0 3.6h6v1.8H9v-1.8Zm0-7.2h2.8v1.8H9V7.4Z"/>
                                                </svg>
                                            </span>
                                            <h3 class="at-step__title">Apply for your TFN (Tax File Number) — Day 1 or 2</h3>
                                        </div>
                                        <div class="at-step__body">Go to ato.gov.au right now. The application takes 20 minutes. Your TFN card arrives by post within 28 days but you get a receipt number immediately, and most employers accept that.</div>
                                        <div class="at-step__reason">
                                            <span class="at-step__reason-label">Why the order matters</span>
                                            <p>Without a TFN on file, your employer must deduct tax at the highest marginal rate (up to 45%) on everything you earn until you provide one. Apply on day one even if you haven't started work yet.</p>
                                        </div>
                                    </div>
                                </article>

                                <!-- Step 3 -->
                                <article class="at-step">
                                    <span class="at-step__marker" aria-hidden="true">3</span>
                                    <div class="at-step__card">
                                        <div class="at-step__top">
                                            <span class="at-step__iconwrap" aria-hidden="true">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3 7.5 12 3l9 4.5V9H3V7.5Zm2 3h2v7H5v-7Zm6 0h2v7h-2v-7Zm6 0h2v7h-2v-7ZM3 19h18v2H3v-2Z"/>
                                                </svg>
                                            </span>
                                            <h3 class="at-step__title">Open or activate your bank account — Day 2 or 3</h3>
                                        </div>
                                        <div class="at-step__body">If you pre-opened from overseas (Commonwealth Bank lets you do this), visit a branch to activate it with your passport. If not, open one immediately. Most banks require your TFN or proof you've applied.</div>
                                        <div class="at-step__reason">
                                            <span class="at-step__reason-label">Why the order matters</span>
                                            <p>Without a bank account you cannot receive salary, pay rent, or provide the bank statements that agents require for rental applications. This account is the financial hub everything else depends on.</p>
                                        </div>
                                    </div>
                                </article>

                                <!-- Step 4 -->
                                <article class="at-step">
                                    <span class="at-step__marker" aria-hidden="true">4</span>
                                    <div class="at-step__card">
                                        <div class="at-step__top">
                                            <span class="at-step__iconwrap" aria-hidden="true">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M12 2 4 5v6c0 5 3.4 9.5 8 10.8C16.6 20.5 20 16 20 11V5l-8-3Zm-1.1 13.6-3.3-3.3 1.4-1.4 1.9 1.9 4.2-4.2 1.4 1.4-5.6 5.6Z"/>
                                                </svg>
                                            </span>
                                            <h3 class="at-step__title">Enrol in Medicare (if eligible) — Day 3 to 5</h3>
                                        </div>
                                        <div class="at-step__body">Not every visa qualifies for Medicare, and some have a waiting period. Check your visa conditions at homeaffairs.gov.au before you go. Take your passport and visa grant letter to a Medicare Service Centre — you will need both.</div>
                                        <div class="at-step__reason">
                                            <span class="at-step__reason-label">Why the order matters</span>
                                            <p>Medicare rebates require a bank account for deposits. By completing step 3 first, you walk in ready to enrol fully. Without Medicare, one GP visit costs $80–$150 out of pocket. Don't delay this step.</p>
                                        </div>
                                    </div>
                                </article>

                                <!-- Step 5 -->
                                <article class="at-step">
                                    <span class="at-step__marker" aria-hidden="true">5</span>
                                    <div class="at-step__card">
                                        <div class="at-step__top">
                                            <span class="at-step__iconwrap" aria-hidden="true">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9z"/>
                                                    <polyline points="9 22 9 12 15 12 15 22"/>
                                                </svg>
                                            </span>
                                            <h3 class="at-step__title">Start your rental property search — Day 1 (ongoing)</h3>
                                        </div>
                                        <div class="at-step__body">Your temporary accommodation has a fixed end date. The rental market is competitive. Start researching suburbs and inspecting properties immediately — but don't apply until your bank account is active.</div>
                                        <div class="at-step__reason">
                                            <span class="at-step__reason-label">Why the order matters</span>
                                            <p>Real estate agents won't process your application without Australian bank statements showing transaction history. By researching early and applying after your account is active, you move fast when the right property appears.</p>
                                        </div>
                                    </div>
                                </article>

                                <!-- Step 6 -->
                                <article class="at-step">
                                    <span class="at-step__marker" aria-hidden="true">6</span>
                                    <div class="at-step__card">
                                        <div class="at-step__top">
                                            <span class="at-step__iconwrap" aria-hidden="true">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="12" cy="10" r="3"/>
                                                    <path d="M12 21.7C17.3 17 20 13 20 10a8 8 0 1 0-16 0c0 3 2.7 6.9 8 11.7z"/>
                                                </svg>
                                            </span>
                                            <h3 class="at-step__title">Set up international money transfers — Week 1</h3>
                                        </div>
                                        <div class="at-step__body">Your Australian bank charges 3–5% on every international transfer, hidden inside the exchange rate. Use Wise or Airwallex instead — they show you the exact fee before you confirm. On a $5,000 transfer, the difference can be $150–$300.</div>
                                        <div class="at-step__reason">
                                            <span class="at-step__reason-label">Why the order matters</span>
                                            <p>Transferring funds before your Australian account is active means paying international wire fees twice (sending bank + receiving bank). Wait until step 3 is complete, then move your money in one efficient transfer.</p>
                                        </div>
                                    </div>
                                </article>

                                <!-- Step 7 -->
                                <article class="at-step">
                                    <span class="at-step__marker" aria-hidden="true">7</span>
                                    <div class="at-step__card">
                                        <div class="at-step__top">
                                            <span class="at-step__iconwrap" aria-hidden="true">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                                                </svg>
                                            </span>
                                            <h3 class="at-step__title">Begin work — Week 2</h3>
                                        </div>
                                        <div class="at-step__body">By this point you are set up legally (TFN applied), financially (bank account active), and communicatively (SIM working). Your employer can pay you, the ATO can tax you correctly, and you can sign a lease without stress.</div>
                                        <div class="at-step__reason">
                                            <span class="at-step__reason-label">Why the order matters</span>
                                            <p>Starting work before securing the steps above creates administrative chaos. This sequence ensures your employer can pay you, the ATO can tax you correctly, and you can rent a home without scrambling.</p>
                                        </div>
                                    </div>
                                </article>
                            </div>

                            <div class="at-pro-tip">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 18h6"/>
                                    <path d="M10 22h4"/>
                                    <path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14"/>
                                </svg>
                                <div class="at-pro-tip-content">
                                    <span class="at-pro-tip-label">Pro Tip</span>
                                    <p>Apply for your TFN as a <strong>new arrival</strong> before visiting a bank. Most Australian banks ask for your TFN when opening an account, and having the receipt number ready means you complete the full setup in one branch visit instead of two.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <x-faq-section
                        heading="New to Australia — Common Questions Answered"
                        image="{{ asset('media/new to australlia/new_to_australia_faq.webp') }}"
                        alt="Frequently asked questions for new arrivals in Australia"
                    >
                        <details class="arrival-faq" open>
                            <summary>Do I need a TFN before I start working in Australia?</summary>
                            <p>No, you can legally start work without one. But apply the day you arrive. Without a TFN on file, your employer will tax you at 45% on everything you earn until you provide it. The online application takes 20 minutes at ato.gov.au.</p>
                        </details>
                        <details class="arrival-faq">
                            <summary>Can I open an Australian bank account before I arrive?</summary>
                            <p>Yes. Commonwealth Bank allows you to open an account online up to 12 months before you arrive, using your passport. Your card will be waiting at a branch when you land. This is one of the most useful things you can do before you fly.</p>
                        </details>
                        <details class="arrival-faq">
                            <summary>Does Medicare cover dental?</summary>
                            <p>No, and this surprises almost everyone. Medicare covers GP visits, public hospital stays, and partial specialist costs. Dental, optical, and physiotherapy are not covered. Budget for these separately, or look at private health insurance that includes extras cover.</p>
                        </details>
                        <details class="arrival-faq">
                            <summary>How long does it take to get permanent residency in Australia?</summary>
                            <p>It depends on your visa pathway. Employer-sponsored (482 → 186): typically 2–4 years. Skilled independent (189 points-tested): 6 months to 2 years from invitation. Family/partner visa: 1–3 years. State nomination can speed up a skilled application significantly. For personalised guidance, visit our <a class="text-link" href="/contact">contact page</a>.</p>
                        </details>
                        <details class="arrival-faq">
                            <summary>Can I rent a property in Australia without a local rental history?</summary>
                            <p>Yes. Offer to pay 4 weeks' rent in advance instead of 2. Use your employer as a character reference. Target private landlords over large real estate agencies they have more flexibility. Get a rental reference letter from your previous landlord overseas, translated into English if needed.</p>
                        </details>
                        <details class="arrival-faq">
                            <summary>What happens to my superannuation if I leave Australia permanently?</summary>
                            <p>You can claim it back. It's called the Departing Australia Superannuation Payment (DASP). You apply online through the ATO after your visa expires and you've left the country. Tax is withheld on the payment, but you receive the balance. Most migrants leave thousands behind simply because they don't know this process exists.</p>
                        </details>
                    </x-faq-section>
                    <section class="guide-cta" aria-label="Next steps">
                        <h2 class="guide-cta__heading">Ready to Settle in Australia With Confidence?</h2>
                        <div class="guide-cta__grid">
                            <div class="guide-cta__card guide-cta__card--checklist">
                                <span class="guide-cta__icon guide-cta__icon--checklist" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                                        <path d="m9 14 2 2 4-4"/>
                                    </svg>
                                </span>
                                <h3>Get the Free 90-Day Checklist</h3>
                                <p>The same steps from this guide in a downloadable format you can keep open during your first week in Australia. No strings attached.</p>
                                <div class="guide-cta__action">
                                    <button class="button button--primary" type="button" data-open-lead-modal>
                                        Send me the checklist
                                        <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </div>

                            <div class="guide-cta__card guide-cta__card--services">
                                <span class="guide-cta__icon guide-cta__icon--services" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                        <path d="M12 16v-4"/>
                                        <path d="M12 8h.01"/>
                                    </svg>
                                </span>
                                <h3>Need Personal Settlement Support?</h3>
                                <p>The guide gives you the roadmap. If you want someone to walk it with you, our SettleANZ agents provide one-on-one support tailored to your situation.</p>
                                <div class="guide-cta__action">
                                    <a class="button button--secondary" href="/settlement-services">
                                        View settlement services
                                        <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </section>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const timeline = document.querySelector('[data-arrival-timeline]');

                if (!timeline) {
                    return;
                }

                const getMarkerMetrics = () => {
                    const markers = timeline.querySelectorAll('.arrival-step__number');

                    if (!markers.length) {
                        return null;
                    }

                    const timelineRect = timeline.getBoundingClientRect();
                    const firstRect = markers[0].getBoundingClientRect();
                    const lastRect = markers[markers.length - 1].getBoundingClientRect();

                    const start = firstRect.top - timelineRect.top + (firstRect.height / 2);
                    const end = lastRect.top - timelineRect.top + (lastRect.height / 2);

                    return {
                        start,
                        maxProgress: Math.max(end - start, 0),
                        endOffset: Math.max(timeline.offsetHeight - end, 0),
                    };
                };

                const updateTimelineProgress = () => {
                    const metrics = getMarkerMetrics();

                    if (!metrics) {
                        return;
                    }

                    const rect = timeline.getBoundingClientRect();
                    const viewportTrigger = window.innerHeight * 0.72;
                    const progress = Math.min(Math.max(viewportTrigger - rect.top - metrics.start, 0), metrics.maxProgress);

                    timeline.style.setProperty('--timeline-start', `${metrics.start}px`);
                    timeline.style.setProperty('--timeline-end-offset', `${metrics.endOffset}px`);
                    timeline.style.setProperty('--timeline-progress', `${progress}px`);
                };

                updateTimelineProgress();
                window.addEventListener('scroll', updateTimelineProgress, { passive: true });
                window.addEventListener('resize', updateTimelineProgress);
            });
        </script>
    </div>
@endsection
