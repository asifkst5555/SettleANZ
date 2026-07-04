@extends('layouts.app')

@section('page_styles')
    <style>
        .arrival-guide {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at top left, rgba(232, 119, 58, 0.12), transparent 24%),
                linear-gradient(180deg, #f4eee6 0%, #fcf8f2 16%, #f8fbfb 52%, #ffffff 100%);
        }

        .arrival-guide::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 10% 14%, rgba(10, 106, 103, 0.07) 0 1px, transparent 1px),
                radial-gradient(circle at 86% 18%, rgba(232, 119, 58, 0.08) 0 1px, transparent 1px);
            background-size: 18px 18px, 26px 26px;
            opacity: 0.45;
            pointer-events: none;
        }

        .arrival-guide > * {
            position: relative;
            z-index: 1;
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
            background: var(--page-background, #ffffff);
            padding: 3.5rem 0 1.5rem;
        }

        .arrival-intro__inner {
            display: grid;
            gap: 1.4rem;
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

        .arrival-hero__visual {
            position: relative;
            box-sizing: border-box;
            width: 100%;
            max-width: none;
            padding: 14px;
            border: 6px solid #f27d2d;
            border-radius: 32px;
            line-height: 0;
            background: #ffffff;
        }

        .arrival-hero__image-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 520px;
            border-radius: 18px;
            background: linear-gradient(135deg, #0a4f51 0%, #063638 100%);
            color: #ffffff;
            text-align: center;
            padding: 2rem;
            box-shadow: 0 36px 90px rgba(3, 25, 32, 0.28);
        }

        .arrival-hero__image-placeholder svg {
            width: 64px;
            height: 64px;
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        .arrival-hero__image {
            width: 100%;
            height: clamp(300px, 50vw, 520px) !important;
            object-fit: cover;
            border-radius: 18px;
            box-shadow: 0 36px 90px rgba(3, 25, 32, 0.28);
        }

        .arrival-card__image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .arrival-photo-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .arrival-shell {
            padding: 1.2rem 0 4rem;
        }

        .arrival-layout {
            display: block;
        }

        .arrival-content {
            display: grid;
            gap: 1.35rem;
        }

        .arrival-section {
            padding: 3rem;
            border: 1px solid rgba(10, 100, 97, 0.11);
            border-radius: 32px;
            background: rgba(255, 255, 255, 0.94);
            box-shadow: 0 24px 60px rgba(12, 55, 66, 0.08);
        }

        .arrival-section .eyebrow {
            color: var(--primary-brand);
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

        .arrival-section__intro {
            max-width: 74ch;
            margin-top: 0.85rem;
            margin-inline: auto;
            color: #556975;
            line-height: 1.75;
            text-align: center;
        }

        #before-you-land, #faq {
            padding: 0;
            border: 0;
            border-radius: 0;
            background: transparent;
            box-shadow: none;
        }

        .arrival-grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .arrival-card {
            background: #ffffff;
            border-radius: var(--radius-card, 18px);
            padding: 32px;
            border: var(--border-card, 1px solid rgba(16, 88, 98, 0.08));
            box-shadow: var(--shadow-card, 0 10px 30px rgba(10, 35, 45, 0.04));
            display: flex;
            flex-direction: column;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
        }

        .arrival-card:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 20px 40px rgba(10, 35, 45, 0.08);
        }

        .arrival-card__media {
            width: 100%;
            height: 240px;
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 1.5rem;
            background: #f1f5f9;
        }

        .arrival-card__media-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: #64748b;
            font-weight: 600;
            font-size: 0.95rem;
            gap: 0.5rem;
        }

        .arrival-card__head {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .arrival-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(242, 125, 45, 0.12);
            color: #f27d2d;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .arrival-icon svg { width: 22px; height: 22px; fill: currentColor; }

        .arrival-icon--teal {
            background: rgba(10, 122, 117, 0.12);
            color: #0a7a75;
        }

        .arrival-card__head h3 {
            font-size: 1.3rem;
            font-weight: 800;
            color: #0a4f51;
            margin-bottom: 0.25rem;
        }

        .arrival-card__head p {
            font-size: 0.92rem;
            color: #64748b;
            margin: 0;
        }

        .arrival-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .arrival-list li {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            font-size: 0.95rem;
            color: #334155;
            line-height: 1.5;
        }

        .arrival-list__mark {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #e6f4f3;
            color: #0a7a75;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .arrival-list__mark svg { width: 12px; height: 12px; stroke: currentColor; stroke-width: 3; fill: none; }

        .arrival-pro-tip {
            margin-top: 1.5rem;
            padding: 1.25rem 1.5rem;
            background: #fffcf6;
            border-left: 4px solid #e8773a;
            border-radius: 12px;
            color: #451a03;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .arrival-photo-card {
            width: 100%;
            height: 380px;
            border-radius: 24px;
            overflow: hidden;
            margin-top: 2rem;
            background: #f1f5f9;
        }

        .arrival-photo-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0a4f51 0%, #0e6e70 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 1.1rem;
            gap: 0.75rem;
        }

        .arrival-cta {
            background: linear-gradient(135deg, #0a4f51 0%, #063638 100%);
            border-radius: 28px;
            padding: 3.5rem 2.5rem;
            color: #ffffff;
            text-align: center;
            margin-top: 2rem;
        }

        .arrival-cta h3 { font-size: 2rem; font-weight: 800; color: #ffffff; margin-bottom: 1rem; }
        .arrival-cta p { font-size: 1.05rem; color: rgba(255,255,255,0.9); max-width: 36rem; margin: 0 auto 2rem auto; }
        .arrival-cta__actions { display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap; }

        .arrival-dd-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .arrival-dd-box {
            background: #ffffff;
            border-radius: 20px;
            padding: 1.75rem;
            border: 1px solid #e2e8f0;
        }

        .arrival-dd-box--dos { border-top: 4px solid #10b981; }
        .arrival-dd-box--donts { border-top: 4px solid #ef4444; }

        .arrival-dd-box h3 {
            font-size: 1.2rem;
            font-weight: 800;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .arrival-dd-box--dos h3 { color: #047857; }
        .arrival-dd-box--donts h3 { color: #b91c1c; }

        .arrival-dd-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .arrival-dd-list li strong {
            display: block;
            font-size: 1rem;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .arrival-dd-list li p {
            font-size: 0.92rem;
            line-height: 1.6;
            color: #475569;
            margin: 0;
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
            padding: 0.35rem 0;
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
            border: 7px solid #ffffff;
            border-radius: 999px;
            background: #ffffff;
            color: #e8773a;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 1.45rem;
            font-weight: 800;
            line-height: 1;
            box-shadow: 0 10px 24px rgba(35, 52, 62, 0.12);
            z-index: 2;
        }

        .arrival-step__label {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            color: #0a7a75;
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .arrival-step__label svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }

        .arrival-step h3 {
            margin: 0.2rem 0 0 0;
            font-size: 1.35rem;
            font-weight: 800;
            color: #0a4f51;
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
            text-align: left;
        }

        .arrival-step__body p {
            text-align: left;
            margin: 0;
            font-size: 0.95rem;
            line-height: 1.65;
            color: #475569;
        }

        .arrival-step__body strong {
            display: inline-block;
            margin-bottom: 0.3rem;
            color: #0a4f51;
            font-size: 0.8rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            text-align: left;
        }

        .arrival-faq-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.08fr) minmax(300px, 0.92fr);
            gap: 1.5rem;
            align-items: start;
            margin-top: 1.4rem;
        }

        .arrival-faqs {
            display: grid;
            gap: 0.85rem;
        }

        .arrival-faq {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.1rem 1.25rem;
            transition: all 0.2s ease;
        }

        .arrival-faq[open] {
            border-color: #0a7a75;
            box-shadow: 0 20px 46px rgba(12, 55, 66, 0.1);
        }

        .arrival-faq summary {
            font-size: 1rem;
            font-weight: 700;
            color: #0a4f51;
            cursor: pointer;
            outline: none;
            list-style: none;
            display: flex;
            align-items: center;
            text-align: left;
        }

        .arrival-faq summary::-webkit-details-marker { display: none; }
        
        .arrival-faq summary::before {
            content: '+';
            display: inline-grid;
            place-items: center;
            width: 30px;
            height: 30px;
            border-radius: 999px;
            background: #eff8f7;
            color: #0a7a75;
            font-size: 1.4rem;
            line-height: 1;
            margin-right: 0.85rem;
            flex-shrink: 0;
        }

        .arrival-faq[open] summary::before { content: '−'; }
        .arrival-faq summary::after { content: none; }
        .arrival-faq p { margin-top: 0.85rem; padding-left: 2.75rem; font-size: 0.95rem; line-height: 1.65; color: #475569; text-align: left; }

        .arrival-grid-cta {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .arrival-cta-card {
            border-radius: 16px;
            padding: 2.25rem 2rem;
            display: flex;
            flex-direction: column;
            text-align: left;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
        }

        .arrival-cta-card--mint {
            background: #e6f4f3;
            border-left: 6px solid #0a7a75;
        }

        .arrival-cta-card--cream {
            background: #fff7ed;
            border-left: 6px solid #e8773a;
        }

        .arrival-cta-card h3 {
            font-size: 1.35rem;
            font-weight: 800;
            color: #0a4f51;
            margin: 0 0 0.75rem 0;
            text-align: left;
        }

        .arrival-cta-card p {
            font-size: 0.98rem;
            line-height: 1.65;
            color: #334155;
            margin: 0 0 1.5rem 0;
            text-align: left;
        }

        .arrival-cta-card__action {
            margin-top: auto;
        }

        .arrival-section--dark {
            color: #ffffff;
            background: linear-gradient(152deg, #083f47 0%, #0b5b5a 46%, #106766 100%);
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
            text-align: left;
        }

        .arrival-guidance__content p {
            margin: 0;
            color: rgba(255, 255, 255, 0.78);
            font-size: 0.94rem;
            line-height: 1.65;
            text-align: left;
        }

        @media (max-width: 1024px) {
            .arrival-hero__grid,
            .arrival-faq-grid,
            .arrival-grid-2,
            .arrival-grid-3,
            .arrival-grid-cta,
            .arrival-day-grid,
            .arrival-dd-grid,
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
                width: auto !important;
                height: 200px;
                margin: -1.15rem -1.15rem 1.15rem;
                border-radius: 24px 24px 0 0;
            }

            .arrival-card__media img {
                height: 100%;
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

            .arrival-faq p {
                padding-left: 0;
            }
        }
    </style>
@endsection

@section('content')
    <div class="arrival-guide">
        <!-- HERO SECTION -->
        <section id="top" class="arrival-hero">
            <div class="container arrival-hero__grid">
                <div class="arrival-hero__content">
                    <p class="eyebrow">New to New Zealand guide 2026</p>
                    <h1><span class="arrival-hero__accent">Just</span><br>Arrived in New Zealand?</h1>
                    <p class="arrival-hero__subhead">Here's Exactly What to Do First and in the Right Order.</p>
                </div>

                <div class="arrival-hero__visual">
                    <!-- HERO IMAGE -->
                    <img src="{{ asset('media/New Zealand/New Zealand Hero.webp') }}" alt="New Zealand Hero" class="arrival-hero__image" style="display: block;">
                </div>
            </div>
        </section>

        <!-- INTRO SECTION -->
        <section class="arrival-intro">
            <div class="container arrival-intro__inner">
                <p class="arrival-intro__lead">New Zealand rewards the people who arrive prepared. Yes, the cost of living is high. The rental market in Auckland and Wellington is competitive. The healthcare system has its pressures. These are real — not reasons to reconsider, but things to plan for. Every country has its challenges alongside its advantages. New Zealand's advantages are significant: a safe, multicultural society, strong employment in the right sectors, a world-class natural environment, and a clear pathway to permanent residency and citizenship.</p>
                <p class="arrival-intro__lead" style="margin-top: 1rem;">The difference between people who settle well and people who struggle is almost never ability or effort. It is preparation and sequence. Knowing what to do, when to do it, and in what order. This guide gives you that — clearly, honestly, and in the right order.</p>

                <div class="arrival-intro__quote" style="margin-top: 1.5rem;">
                    <p>“Knowing what to do, when to do it, and in what order is what makes your settlement stress-free.”</p>
                    <span>SettleANZ Aotearoa Team</span>
                </div>
            </div>
        </section>

        <!-- MAIN CONTENT SHELL -->
        <section class="arrival-shell">
            <div class="container arrival-layout">
                <div class="arrival-content">
                    
                    <!-- SECTION 1: BEFORE YOU LAND -->
                    <section id="before-you-land" class="arrival-section">
                        <p class="eyebrow">Before You Land</p>
                        <h2>Moving to New Zealand in 2026? Sort These Before Your Flight Lands</h2>
                        <p class="arrival-section__intro">Use this section as your reference before you leave — not after. The most expensive mistakes happen when people arrive with the wrong paperwork, no short-term plan, or no idea what to do in the first 48 hours.</p>

                        <div class="arrival-grid-2" style="margin-top: 1.5rem;">
                            <!-- CARD 1 -->
                            <article class="arrival-card arrival-card--cool">
                                <div class="arrival-card__media">
                                    <img src="{{ asset('media/New Zealand/Documents to carry physically.webp') }}" alt="Documents to carry physically" class="arrival-card__image" loading="lazy">
                                </div>

                                <div class="arrival-card__head">
                                    <span class="arrival-icon arrival-icon--teal" aria-hidden="true">
                                        <svg viewBox="0 0 24 24"><path d="M6 2h9l5 5v13.5A1.5 1.5 0 0 1 18.5 22h-11A1.5 1.5 0 0 1 6 20.5V2Zm8 1.8V8h4.2L14 3.8ZM9 11h6v1.8H9V11Zm0 3.6h6v1.8H9v-1.8Zm0-7.2h2.8v1.8H9V7.4Z"/></svg>
                                    </span>
                                    <div>
                                        <h3>Documents to carry physically</h3>
                                        <p>Print these — don't rely on your phone.</p>
                                    </div>
                                </div>

                                <ul class="arrival-list">
                                    <li><span class="arrival-list__mark"><svg viewBox="0 0 24 24"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span><span><strong>Valid passport:</strong> Must be valid for at least 3 months beyond your planned departure date from New Zealand. Check this before booking your flight.</span></li>
                                    <li><span class="arrival-list__mark"><svg viewBox="0 0 24 24"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span><span><strong>NZeTA (New Zealand Electronic Travel Authority):</strong> Required before travel for most visa-waiver country passport holders. Apply via the Immigration NZ app ($17 NZD) or online ($23 NZD). Allow up to 72 hours for processing.</span></li>
                                    <li><span class="arrival-list__mark"><svg viewBox="0 0 24 24"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span><span><strong>Visa grant letter:</strong> Your visa approval document. Print your eVisa letter. Banks, employers, and landlords will ask for it.</span></li>
                                    <li><span class="arrival-list__mark"><svg viewBox="0 0 24 24"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span><span><strong>New Zealand Traveller Declaration (NZTD):</strong> Completed before arrival — mandatory for biosecurity and customs. Free. Complete online or via the NZTD app up to 24 hours before departure.</span></li>
                                    <li><span class="arrival-list__mark"><svg viewBox="0 0 24 24"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span><span><strong>Proof of funds:</strong> Bank statements showing sufficient funds for your stay, as required by your visa conditions.</span></li>
                                    <li><span class="arrival-list__mark"><svg viewBox="0 0 24 24"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span><span><strong>Health insurance documents:</strong> Critical for covering medical costs before you are eligible for public healthcare. Carry your policy number and emergency contact.</span></li>
                                    <li><span class="arrival-list__mark"><svg viewBox="0 0 24 24"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span><span><strong>Qualification documents and NZQA assessment:</strong> Original certificates and your New Zealand Qualifications Authority (NZQA) assessment if your profession requires it. Get this done before you leave — it takes weeks.</span></li>
                                    <li><span class="arrival-list__mark"><svg viewBox="0 0 24 24"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span><span><strong>Police clearance certificate:</strong> Required for character assessment on certain visa types. Check your visa conditions.</span></li>
                                    <li><span class="arrival-list__mark"><svg viewBox="0 0 24 24"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span><span><strong>Passport photos (6 copies):</strong> Needed for driver licence application, some bank accounts, and other applications. Bring printed copies.</span></li>
                                    <li><span class="arrival-list__mark"><svg viewBox="0 0 24 24"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span><span><strong>Medical records:</strong> Especially important for chronic conditions, ongoing medications, or if you have children.</span></li>
                                    <li><span class="arrival-list__mark"><svg viewBox="0 0 24 24"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span><span><strong>Birth and marriage certificates:</strong> Original or certified copies for family members or relationship proof. Get certified translations if not in English — translation in New Zealand costs 3–5x more than at home.</span></li>
                                    <li><span class="arrival-list__mark"><svg viewBox="0 0 24 24"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span><span><strong>Employment contract:</strong> If you have a job offer before arrival, carry the signed offer letter. You will need it for your IRD number application and bank account.</span></li>
                                </ul>
                            </article>

                            <!-- CARD 2 -->
                            <article class="arrival-card arrival-card--warm">
                                <div class="arrival-card__media">
                                    <img src="{{ asset('media/New Zealand/Book before you land.webp') }}" alt="Book before you land" class="arrival-card__image" loading="lazy">
                                </div>

                                <div class="arrival-card__head">
                                    <span class="arrival-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24"><path d="M20 7h-3.2a2.8 2.8 0 0 0-5.6 0H8a3 3 0 0 0-3 3v6.5A2.5 2.5 0 0 0 7.5 19h9a2.5 2.5 0 0 0 2.5-2.5V10a3 3 0 0 0-3-3ZM14 6a1.2 1.2 0 1 1-2.4 0A1.2 1.2 0 0 1 14 6Zm-4.6 5.5 2.4 2.4 4.8-4.8 1.4 1.4-6.2 6.2-3.8-3.8 1.4-1.4Z"/></svg>
                                    </span>
                                    <div>
                                        <h3>Book before you land</h3>
                                        <p>These reduce pressure in your first week.</p>
                                    </div>
                                </div>

                                <ul class="arrival-list">
                                    <li><span class="arrival-list__mark"><svg viewBox="0 0 24 24"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span><span><strong>Short-term accommodation (minimum 4–6 weeks):</strong> New Zealand's rental market is competitive, especially in Auckland and Wellington. Booking temporary accommodation gives you time to explore suburbs without pressure. Do not sign a 12-month lease before you have spent time in the area.</span></li>
                                    <li><span class="arrival-list__mark"><svg viewBox="0 0 24 24"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span><span><strong>Airport transfer:</strong> Pre-book a transfer. Do not rely on finding an Uber or taxi easily at the airport with heavy luggage, jet lag, and no working local SIM card.</span></li>
                                    <li><span class="arrival-list__mark"><svg viewBox="0 0 24 24"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span><span><strong>Travel insurance starting from your arrival date:</strong> This covers any gap before you are eligible for New Zealand's public health services. Sort this before you leave home — it is significantly cheaper.</span></li>
                                    <li><span class="arrival-list__mark"><svg viewBox="0 0 24 24"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span><span><strong>NZeTA if required:</strong> Apply at least 72 hours before departure via the Immigration NZ app or website. Do not leave this to the airport.</span></li>
                                    <li><span class="arrival-list__mark"><svg viewBox="0 0 24 24"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span><span><strong>New Zealand Dollars:</strong> Have NZD in cash for your first 24 hours. ATMs are widely available but you want funds accessible immediately on arrival.</span></li>
                                    <li><span class="arrival-list__mark"><svg viewBox="0 0 24 24"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span><span><strong>Rental car if needed:</strong> If you plan to drive immediately, book in advance. You can drive on your valid overseas licence for up to 12 months in New Zealand.</span></li>
                                </ul>
                            </article>
                        </div>

                        <div class="arrival-pro-tip">
                            <p><strong>PRO TIP:</strong> New Zealand homes — especially older builds — are often poorly insulated and cold inside even in summer. When inspecting rentals, check insulation, heating, and whether the property has a Healthy Homes compliance certificate. Landlords are legally required to meet Healthy Homes standards.</p>
                        </div>

                        <div class="arrival-pro-tip">
                            <p><strong>PRO TIP:</strong> Pre-open a New Zealand bank account before you land. ANZ, ASB, and Westpac all offer pre-arrival account opening for migrants with eligible visas. ASB's process via their app is the most straightforward. Note: you'll receive a limited account — you can deposit money but cannot withdraw until you verify your identity in person at a branch on arrival.</p>
                        </div>
                    </section>

                    <!-- SECTION 2: DOS AND DONTS -->
                    <section id="dos-and-donts" class="arrival-section arrival-section--dark" style="margin-top: 2rem;">
                        <p class="eyebrow">Dos &amp; Don'ts</p>
                        <h2>New Zealand Immigration Dos and Don'ts</h2>
                        <p class="arrival-section__intro" style="color: rgba(255, 255, 255, 0.85);">Things Most New Arrivals Get Wrong — These are not opinions. They are the patterns that come up consistently among new arrivals.</p>

                        <div class="arrival-guidance">
                            <div class="arrival-guidance__panel">
                                <article class="arrival-guidance__column arrival-guidance__column--do">
                                    <header class="arrival-guidance__head">
                                        <div class="arrival-guidance__label-row">
                                            <span class="arrival-guidance__mark" aria-hidden="true">
                                                <svg viewBox="0 0 24 24"><path d="M5.5 12.5 10 17l8.5-9"/></svg>
                                            </span>
                                            <h3>DOs</h3>
                                        </div>
                                        <p class="arrival-guidance__tagline">Habits that help you settle faster</p>
                                    </header>

                                    <ol class="arrival-guidance__items">
                                        <li class="arrival-guidance__row">
                                            <span class="arrival-guidance__index" aria-hidden="true">01</span>
                                            <div class="arrival-guidance__content">
                                                <h4>Apply for your IRD number as a new arrival — immediately</h4>
                                                <p>Your IRD number is your tax identification number — you need it to work legally, receive salary, and access government services. As a new arrival you can give Immigration New Zealand permission to share your identity documents with Inland Revenue, saving you from sending them twice. Apply online the day you arrive. Processing takes approximately 10 working days. Without it, your employer must deduct tax at the higher non-declaration rate.</p>
                                            </div>
                                        </li>
                                        <li class="arrival-guidance__row">
                                            <span class="arrival-guidance__index" aria-hidden="true">02</span>
                                            <div class="arrival-guidance__content">
                                                <h4>Open your bank account on arrival day — or before you land</h4>
                                                <p>Your bank account is the foundation everything else is built on. You need it before you can receive salary, pay rent, or complete your IRD application. ANZ, ASB, and Westpac all allow you to start the process before arrival. Kiwibank requires an in-branch visit. If you did not pre-open, go to a branch on day one or two with your passport and visa grant letter.</p>
                                            </div>
                                        </li>
                                        <li class="arrival-guidance__row">
                                            <span class="arrival-guidance__index" aria-hidden="true">03</span>
                                            <div class="arrival-guidance__content">
                                                <h4>Start building your New Zealand credit history from week one</h4>
                                                <p>Your overseas credit history does not transfer to New Zealand. Landlords and lenders check your local credit score. Get a New Zealand bank account and apply for a secured credit card as soon as possible. Use it for small regular purchases and pay it off each month. Starting early means you have a usable credit file within 3–6 months.</p>
                                            </div>
                                        </li>
                                        <li class="arrival-guidance__row">
                                            <span class="arrival-guidance__index" aria-hidden="true">04</span>
                                            <div class="arrival-guidance__content">
                                                <h4>Register with a GP within your first two weeks</h4>
                                                <p>The New Zealand healthcare system can be strained and GP appointment wait times can be significant. Register with a local general practitioner before you need one — not on the day you get sick. Use the HealthPoint website to find a GP in your area that is accepting new patients.</p>
                                            </div>
                                        </li>
                                        <li class="arrival-guidance__row">
                                            <span class="arrival-guidance__index" aria-hidden="true">05</span>
                                            <div class="arrival-guidance__content">
                                                <h4>Use Trade Me Property and Seek.co.nz — these are New Zealand's dominant platforms</h4>
                                                <p>Trade Me Property is where the majority of New Zealand rentals are listed. Seek.co.nz is the dominant job search platform — not LinkedIn. Familiarise yourself with both in the weeks before arrival so you understand market prices and realistic timelines.</p>
                                            </div>
                                        </li>
                                        <li class="arrival-guidance__row">
                                            <span class="arrival-guidance__index" aria-hidden="true">06</span>
                                            <div class="arrival-guidance__content">
                                                <h4>Join KiwiSaver and understand your contributions from day one</h4>
                                                <p>KiwiSaver is New Zealand's retirement savings scheme. If you are employed, you are automatically enrolled. As of April 2026, the minimum contribution rate is 3.5% from both you and your employer, on top of your salary. You can opt to contribute more. If you leave New Zealand permanently after 12 months, you can withdraw your savings under the KiwiSaver withdrawal scheme. Do not ignore this from day one — it compounds significantly over time.</p>
                                            </div>
                                        </li>
                                    </ol>
                                </article>

                                <article class="arrival-guidance__column arrival-guidance__column--dont">
                                    <header class="arrival-guidance__head">
                                        <div class="arrival-guidance__label-row">
                                            <span class="arrival-guidance__mark" aria-hidden="true">
                                                <svg viewBox="0 0 24 24"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm1 14h-2v-2h2Zm0-4h-2V7h2Z"/></svg>
                                            </span>
                                            <h3>DON'Ts</h3>
                                        </div>
                                        <p class="arrival-guidance__tagline">Mistakes most new arrivals regret</p>
                                    </header>

                                    <ol class="arrival-guidance__items">
                                        <li class="arrival-guidance__row">
                                            <span class="arrival-guidance__index" aria-hidden="true">01</span>
                                            <div class="arrival-guidance__content">
                                                <h4>Don't sign a long-term lease before you've spent time in the suburb</h4>
                                                <p>New Zealand's rental market moves fast, especially in Auckland and Wellington, and the temptation is to lock something in quickly. Resist it. Spend your first 4–6 weeks in short-term accommodation while you explore. The wrong long-term rental in the wrong suburb is an expensive and stressful mistake.</p>
                                            </div>
                                        </li>
                                        <li class="arrival-guidance__row">
                                            <span class="arrival-guidance__index" aria-hidden="true">02</span>
                                            <div class="arrival-guidance__content">
                                                <h4>Don't sign a long-term phone plan immediately</h4>
                                                <p>Start with a prepaid SIM. Coverage and pricing vary across providers, and you will not know which suits you until you have been in your area for a few weeks. Prepaid gives you flexibility. Lock into a 24-month plan later, once you know where you live and which network has the best coverage there.</p>
                                            </div>
                                        </li>
                                        <li class="arrival-guidance__row">
                                            <span class="arrival-guidance__index" aria-hidden="true">03</span>
                                            <div class="arrival-guidance__content">
                                                <h4>Don't ignore the cold inside New Zealand homes</h4>
                                                <p>Many older New Zealand properties are poorly insulated. A home can feel warmer outside than inside in winter. When inspecting rentals, check for insulation, heating systems, and Healthy Homes compliance. Landlords are legally required to meet Healthy Homes standards — ask for the compliance certificate before signing.</p>
                                            </div>
                                        </li>
                                        <li class="arrival-guidance__row">
                                            <span class="arrival-guidance__index" aria-hidden="true">04</span>
                                            <div class="arrival-guidance__content">
                                                <h4>Don't assume your overseas qualifications are automatically recognised</h4>
                                                <p>Many professions in New Zealand — healthcare, engineering, teaching, law — require formal recognition of overseas qualifications through the New Zealand Qualifications Authority (NZQA) or a relevant professional body. This process takes weeks or months. Start it before you leave home, not after you arrive.</p>
                                            </div>
                                        </li>
                                        <li class="arrival-guidance__row">
                                            <span class="arrival-guidance__index" aria-hidden="true">05</span>
                                            <div class="arrival-guidance__content">
                                                <h4>Don't use unlicensed immigration advisers</h4>
                                                <p>Only licensed immigration advisers or New Zealand lawyers can legally provide personalised immigration advice. Verify credentials through the Immigration Advisers Authority (IAA) website before paying anyone for advice. Unlicensed advisers operate illegally and leave you with no legal protection if things go wrong.</p>
                                            </div>
                                        </li>
                                    </ol>
                                </article>
                            </div>
                        </div>
                    </section>

                    <!-- SECTION 3: ARRIVAL SEQUENCE -->
                    <section id="first-7-days" class="arrival-section" style="margin-top: 2rem;">
                        <p class="eyebrow">Arrival Sequence</p>
                        <h2>What to Do When You Arrive in New Zealand — The Order That Actually Matters</h2>
                        <p class="arrival-section__intro">Here is what no government website tells you: these steps depend on each other. Get the sequence wrong and you will find yourself trying to apply for an IRD number without a verified address, or a rental application without a bank account. This is the order that works.</p>

                        <div class="arrival-timeline" data-arrival-timeline>
                            <article class="arrival-step">
                                <div class="arrival-step__head">
                                    <span class="arrival-step__number">1</span>
                                    <div class="arrival-step__copy">
                                        <span class="arrival-step__label"><svg viewBox="0 0 24 24"><path d="M7 2h10a2 2 0 0 1 2 2v16l-4-2.2L12 20l-3-2.2L5 20V4a2 2 0 0 1 2-2Zm1 4v10.6l1-.8 3 2.2 3-2.2 1 .8V6H8Z"/></svg>Step 1</span>
                                        <h3>Get a prepaid SIM card at the airport, before you leave the terminal</h3>
                                    </div>
                                </div>
                                <div class="arrival-step__body">
                                    <strong>Why the order matters</strong>
                                    <p>Landlords, employers, and banks all need a local contact number. Every application you fill in from day one requires it. Get this done before anything else.</p>
                                </div>
                            </article>

                            <article class="arrival-step">
                                <div class="arrival-step__head">
                                    <span class="arrival-step__number">2</span>
                                    <div class="arrival-step__copy">
                                        <span class="arrival-step__label"><svg viewBox="0 0 24 24"><path d="M6 2h9l5 5v13.5A1.5 1.5 0 0 1 18.5 22h-11A1.5 1.5 0 0 1 6 20.5V2Zm8 1.8V8h4.2L14 3.8ZM9 11h6v1.8H9V11Zm0 3.6h6v1.8H9v-1.8Zm0-7.2h2.8v1.8H9V7.4Z"/></svg>Step 2</span>
                                        <h3>Apply for your IRD number — Day 1 or 2</h3>
                                    </div>
                                </div>
                                <div class="arrival-step__body">
                                    <strong>Why the order matters</strong>
                                    <p>As a new arrival you can give IRD permission to verify your identity through Immigration NZ — saving you from providing the same documents twice. Apply online immediately. Processing takes approximately 10 working days. Without it, your employer deducts tax at the higher non-declaration rate.</p>
                                </div>
                            </article>

                            <article class="arrival-step">
                                <div class="arrival-step__head">
                                    <span class="arrival-step__number">3</span>
                                    <div class="arrival-step__copy">
                                        <span class="arrival-step__label"><svg viewBox="0 0 24 24"><path d="M3 7.5 12 3l9 4.5V9H3V7.5Zm2 3h2v7H5v-7Zm6 0h2v7h-2v-7Zm6 0h2v7h-2v-7ZM3 19h18v2H3v-2Z"/></svg>Step 3</span>
                                        <h3>Open or activate your bank account — Day 1 or 2</h3>
                                    </div>
                                </div>
                                <div class="arrival-step__body">
                                    <strong>Why the order matters</strong>
                                    <p>Everything flows from this. You need a bank account to receive your salary, pay rent, and start building your credit history. If you pre-opened from overseas, visit a branch to activate with your passport and visa grant. If not, open one immediately.</p>
                                </div>
                            </article>

                            <article class="arrival-step">
                                <div class="arrival-step__head">
                                    <span class="arrival-step__number">4</span>
                                    <div class="arrival-step__copy">
                                        <span class="arrival-step__label"><svg viewBox="0 0 24 24"><path d="M12 2 4 5v6c0 5 3.4 9.5 8 10.8C16.6 20.5 20 16 20 11V5l-8-3Zm-1.1 13.6-3.3-3.3 1.4-1.4 1.9 1.9 4.2-4.2 1.4 1.4-5.6 5.6Z"/></svg>Step 4</span>
                                        <h3>Enrol in ACC and register health — Week 1</h3>
                                    </div>
                                </div>
                                <div class="arrival-step__body">
                                    <strong>Why the order matters</strong>
                                    <p>New Zealand's Accident Compensation Corporation (ACC) covers accident injuries for everyone in New Zealand regardless of visa status. Register with a GP as soon as possible — appointment wait times can be long and you need a registered doctor before an emergency arises.</p>
                                </div>
                            </article>

                            <article class="arrival-step">
                                <div class="arrival-step__head">
                                    <span class="arrival-step__number">5</span>
                                    <div class="arrival-step__copy">
                                        <span class="arrival-step__label"><svg viewBox="0 0 24 24"><path d="M12 3 3 9v11h6v-6h6v6h6V9l-9-6Zm0 2.4 6.5 4.3V18h-2.5v-6h-8v6H5.5V9.7L12 5.4Z"/></svg>Step 5</span>
                                        <h3>Start your Trade Me Property search — Day 1</h3>
                                    </div>
                                </div>
                                <div class="arrival-step__body">
                                    <strong>Why the order matters</strong>
                                    <p>Your temporary accommodation has a fixed end date. The permanent rental market is competitive. Start researching suburbs and properties immediately — not when your short-term stay is about to expire.</p>
                                </div>
                            </article>

                            <article class="arrival-step">
                                <div class="arrival-step__head">
                                    <span class="arrival-step__number">6</span>
                                    <div class="arrival-step__copy">
                                        <span class="arrival-step__label"><svg viewBox="0 0 24 24"><path d="M12 2.5a7 7 0 0 0-7 7c0 5.2 7 12 7 12s7-6.8 7-12a7 7 0 0 0-7-7Zm0 9.3a2.3 2.3 0 1 1 0-4.6 2.3 2.3 0 0 1 0 4.6Z"/></svg>Step 6</span>
                                        <h3>Apply for a credit card — Week 1 to 2</h3>
                                    </div>
                                </div>
                                <div class="arrival-step__body">
                                    <strong>Why the order matters</strong>
                                    <p>Your New Zealand credit history starts at zero on arrival. Apply for a secured credit card through your bank as early as possible. Use it regularly and pay it off monthly. This credit file is what landlords and lenders will check in months 3, 6, and 12.</p>
                                </div>
                            </article>

                            <article class="arrival-step">
                                <div class="arrival-step__head">
                                    <span class="arrival-step__number">7</span>
                                    <div class="arrival-step__copy">
                                        <span class="arrival-step__label"><svg viewBox="0 0 24 24"><path d="M20 6h-4V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2H4a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2ZM10 4h4v2h-4V4Z"/></svg>Step 7</span>
                                        <h3>Begin work — Week 2</h3>
                                    </div>
                                </div>
                                <div class="arrival-step__body">
                                    <strong>Why the order matters</strong>
                                    <p>You are now set up legally (IRD), financially (bank account), and communicatively (SIM). You can receive salary, pay tax correctly, and sign a rental agreement. This is the sequence that makes everything else possible.</p>
                                </div>
                            </article>
                        </div>

                        <div class="arrival-pro-tip" style="margin-top: 1.5rem;">
                            <p><strong>PRO TIP:</strong> Apply for your IRD number as a new arrival — not as 'living in New Zealand.' The new arrival pathway lets Immigration NZ share your identity verification with Inland Revenue, so you only provide documents once. This pathway is only available within a few weeks of arrival or before your visa arrival date expires.</p>
                        </div>
                    </section>

                    <!-- SECTION 4: FAQ & CONTENT MEDIA -->
                    <section id="faq" class="arrival-section" style="margin-top: 2rem;">
                        <p class="eyebrow">Common Questions</p>
                        <h2>New to New Zealand — Common Questions Answered</h2>
                        
                        <div class="arrival-faq-grid">
                            <div class="arrival-faqs">
                                <details class="arrival-faq" open>
                                    <summary>Do I need an IRD number before I start working in New Zealand?</summary>
                                    <p>Yes. Your IRD number is essential for legal employment in New Zealand. Apply immediately on arrival using the new arrival pathway on the Inland Revenue website. It takes around 10 minutes and processing takes approximately 10 working days. Without it, your employer must deduct tax at the non-declaration rate, which is higher than your actual rate. You do not need a bank account open first to apply as a new arrival.</p>
                                </details>

                                <details class="arrival-faq">
                                    <summary>Can I open a New Zealand bank account before I arrive?</summary>
                                    <p>Yes, and you should. ANZ, ASB, and Westpac all offer pre-arrival account opening for migrants with eligible visas. ASB's process via their app is the most straightforward. You will receive a limited-access account, where you can deposit funds but cannot withdraw until you verify your identity in person at a New Zealand branch on arrival. Kiwibank requires an in-branch visit to open. Do this as early as possible, because having an account number on arrival accelerates your IRD application and your first salary payment.</p>
                                </details>

                                <details class="arrival-faq">
                                    <summary>Does New Zealand's public health system cover dental care?</summary>
                                    <p>For children under 18, dental care through the Community Oral Health Service is free. For adults, the public health system does not cover routine or emergency dental care. Adults pay for private dental services. Budget for this as dental costs in New Zealand can be significant. Consider including dental coverage in your private health insurance before arrival.</p>
                                </details>

                                <details class="arrival-faq">
                                    <summary>Do I need travel insurance before I arrive in New Zealand?</summary>
                                    <p>Yes, this is one of the most important steps to take before you leave home. New Zealand's Accident Compensation Corporation (ACC) covers accident injuries for everyone in the country regardless of visa status. However, ACC does not cover illness, pre-existing conditions, or medical costs from non-accident causes. You need private health insurance to cover these gaps, especially while you are waiting to understand your eligibility for public health services. Sort insurance before you depart, it is significantly cheaper to buy at home.</p>
                                </details>

                                <details class="arrival-faq">
                                    <summary>How long does it take to get permanent residency in New Zealand?</summary>
                                    <p>Processing times vary significantly depending on your visa category and individual circumstances. Skilled migrant and employer-sponsored pathways can range from several months to over a year. The Accredited Employer Work Visa (AEWV) is currently the most common skilled worker pathway. Check the Immigration New Zealand website for current processing time estimates; these change regularly.</p>
                                </details>

                                <details class="arrival-faq">
                                    <summary>Can I rent a property in New Zealand without a local rental history?</summary>
                                    <p>It is challenging but not impossible. Landlords prefer applicants with a proven local track record. To strengthen your application: provide strong references from previous landlords (if applicable), show proof of employment or income, and offer 2–3 weeks' additional rent in advance. Being transparent about your situation and presenting a complete application pack including your visa grant, bank statements, and employment contract, significantly improves your chances.</p>
                                </details>

                                <details class="arrival-faq">
                                    <summary>Can I drive on my overseas licence in New Zealand?</summary>
                                    <p>Yes. You can drive on a valid overseas licence for up to 12 months from the date you arrive in New Zealand. After 12 months, you must convert to a New Zealand driver's licence. If your overseas licence is not in English, you will need a certified translation or an international driving permit. Book your licence conversion test early; the wait times in major cities can be 4–8 weeks.</p>
                                </details>

                                <details class="arrival-faq">
                                    <summary>What happens to my KiwiSaver if I leave New Zealand permanently?</summary>
                                    <p>If you leave New Zealand permanently, you may be able to withdraw your KiwiSaver savings. You generally need to have been living outside New Zealand for at least 12 months and meet other criteria. As of April 2026, the minimum employer and employee contribution rate is 3.5% each, rising to 4% from April 2028. Keep your KiwiSaver fund details and member number on file from day one. You will need them if you ever make a withdrawal claim.</p>
                                </details>
                            </div>

                            <div class="arrival-photo-card" style="height: 100%; min-height: 520px; margin-top: 0;">
                                <img src="{{ asset('media/New Zealand/New Zealand FAQ.webp') }}" alt="New Zealand FAQ" class="arrival-photo-image" loading="lazy">
                            </div>
                        </div>
                    </section>

                    <!-- FINAL CTA -->
                    <section class="arrival-cta-section" style="margin-top: 3rem;" aria-label="Next steps">
                        <h2 style="font-size: 2.2rem; font-weight: 800; color: #0a4f51; text-align: center; margin-bottom: 2rem;">Ready to Settle in New Zealand With Confidence?</h2>
                        
                        <div class="arrival-grid-cta">
                            <div class="arrival-cta-card arrival-cta-card--mint">
                                <h3>Get the Free 90-Day Arrival Checklist</h3>
                                <p>The same steps from this guide in a format you can keep open during your first week in New Zealand. Download it free — no strings attached.</p>
                                <div class="arrival-cta-card__action">
                                    <button class="button button--small" type="button" data-open-lead-modal style="background: #e8773a; border-color: #e8773a; color: #ffffff;">Send me the checklist</button>
                                </div>
                            </div>

                            <div class="arrival-cta-card arrival-cta-card--cream">
                                <h3>Need help beyond the guide?</h3>
                                <p>The guide gives you the roadmap. If you want someone to walk it with you, SettleANZ agents are always ready to help you</p>
                                <div class="arrival-cta-card__action">
                                    <a class="button button--small" href="/settlement-services" style="background: #0a7a75; border-color: #0a7a75; color: #ffffff;">View settlement services</a>
                                </div>
                            </div>
                        </div>
                    </section>

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
