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
            padding: 5rem 0;
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
            width: min(calc(100% - 2rem), var(--max-width));
            margin: 0 auto;
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
            font-family: 'Inter', system-ui, sans-serif;
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

        #before-you-land {
            padding: 0;
            border: 0;
            border-radius: 0;
            background: transparent;
            box-shadow: none;
        }

        #faq {
            padding: 0;
            border: 0;
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

        .arrival-photo-card {
            overflow: hidden;
            border-radius: 30px;
            background: #dfeceb;
            box-shadow: 0 20px 52px rgba(12, 55, 66, 0.12);
        }

        .arrival-photo-card img {
            display: block;
            width: 100%;
            height: 100%;
            min-height: 340px;
            object-fit: cover;
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
            border: 1px solid rgba(10, 100, 97, 0.11);
            border-radius: 26px;
            background: #ffffff;
            box-shadow: 0 16px 40px rgba(12, 55, 66, 0.07);
        }

        .arrival-card {
            height: 100%;
            padding: 1.35rem;
        }

        .arrival-card__media {
            overflow: hidden;
            margin: -1.35rem -1.35rem 1.25rem;
            border-radius: 26px 26px 0 0;
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

        #before-you-land .arrival-card__head h3,
        #before-you-land .arrival-card__head p,
        #before-you-land .arrival-list li,
        #before-you-land .arrival-list li span {
            text-align: left;
        }

        #faq .arrival-photo-card img {
            height: 580px;
            min-height: 580px;
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

        .arrival-faq-grid {
            grid-template-columns: minmax(0, 1.08fr) minmax(300px, 0.92fr);
            align-items: start;
            margin-top: 1.4rem;
        }

        .arrival-faqs {
            display: grid;
            gap: 0.85rem;
        }

        .arrival-faq {
            padding: 1rem 1.15rem;
        }

        .arrival-faq[open] {
            box-shadow: 0 20px 46px rgba(12, 55, 66, 0.1);
        }

        .arrival-faq summary {
            list-style: none;
            cursor: pointer;
            display: grid;
            grid-template-columns: auto minmax(0, 1fr);
            gap: 0.9rem;
            align-items: center;
            color: var(--primary-dark);
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 1rem;
            line-height: 1.4;
            text-align: left;
        }

        .arrival-faq summary::before {
            content: '+';
            display: inline-grid;
            place-items: center;
            width: 30px;
            height: 30px;
            border-radius: 999px;
            background: #eff8f7;
            color: var(--primary-brand);
            font-size: 1.5rem;
            line-height: 1;
        }

        .arrival-faq[open] summary::before {
            content: '−';
        }

        .arrival-faq summary::-webkit-details-marker {
            display: none;
        }

        .arrival-faq p {
            margin-top: 0.85rem;
            padding-left: 2.95rem;
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

        @media (max-width: 1180px) {
            .arrival-hero__grid,
            .arrival-faq-grid {
                grid-template-columns: 1fr;
            }

            .arrival-hero__visual {
                justify-self: stretch;
                max-width: none;
            }

            .arrival-hero::before {
                top: 0;
                bottom: 30%;
                left: -8%;
                width: 116%;
                border-radius: 0 0 50% 50% / 0 0 18% 18%;
            }
        }

        @media (max-width: 720px) {
            .arrival-grid-2,
            .arrival-grid-3,
            .arrival-grid-cta,
            .arrival-day-grid,
            .arrival-guidance__panel {
                grid-template-columns: 1fr;
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

            .arrival-hero__image {
                height: auto;
                aspect-ratio: 0.88;
                border-radius: 24px;
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
        <section id="top" class="arrival-hero">
            <div class="container arrival-hero__grid">
                <div class="arrival-hero__content">
                    <p class="eyebrow">New to Australia guide 2026</p>
                    <h1><span class="arrival-hero__accent">Just</span><br>Arrived in Australia?</h1>
                    <p class="arrival-hero__subhead">Here's Exactly What to Do First and in the Right Order.</p>
                </div>

                <div class="arrival-hero__visual">
                    <img class="arrival-hero__image" src="{{ str_replace(' ', '%20', asset('media/new to australlia/New to Australia hero.webp')) }}" alt="New arrivals settling into life in Australia" width="800" height="655">
                </div>
            </div>
        </section>

        <section class="arrival-intro">
            <div class="container arrival-intro__inner">
                <p class="arrival-intro__lead">Most new arrivals do not fail because information is missing; they struggle because the steps are out of order. This guide gives you a practical sequence for your first days, weeks, and months in Australia.</p>

                <div class="arrival-intro__quote">
                    <p>“By the time most people realise they've taken the wrong steps in the wrong order, weeks have passed — and some of those mistakes are expensive to undo.”</p>
                    <span>Entel Dajsmaili, arrived January 2001. Australian citizen by 2004.</span>
                </div>
            </div>
        </section>

        <section class="arrival-shell">
            <div class="container arrival-layout">
                <div class="arrival-content">
                    <section id="before-you-land" class="arrival-section">
                        <p class="eyebrow">Before You Land</p>
                        <h2>Moving to Australia in 2026? Sort These Before Your Flight Lands</h2>
                        <p class="arrival-section__intro">Use this section before you leave, not after. The most expensive mistakes happen when people arrive with the wrong paperwork, no short-term setup, or no plan for the first few days.</p>

                        <div class="arrival-grid-2" style="margin-top: 1.5rem;">
                                <article class="arrival-card arrival-card--cool">
                                    <div class="arrival-card__media">
                                        <img src="{{ asset('media/services/Pre-arrival.webp') }}" alt="Pre-arrival planning for moving to Australia" loading="lazy" width="700" height="528">
                                    </div>

                                    <div class="arrival-card__head">
                                        <span class="arrival-icon arrival-icon--teal" aria-hidden="true">
                                            <svg viewBox="0 0 24 24"><path d="M6 2h9l5 5v13.5A1.5 1.5 0 0 1 18.5 22h-11A1.5 1.5 0 0 1 6 20.5V2Zm8 1.8V8h4.2L14 3.8ZM9 11h6v1.8H9V11Zm0 3.6h6v1.8H9v-1.8Zm0-7.2h2.8v1.8H9V7.4Z"/></svg>
                                        </span>
                                        <div>
                                            <h3>Documents to Carry Physically</h3>
                                            <p>Carry these in print, not just on your phone.</p>
                                        </div>
                                    </div>

                                    <ul class="arrival-list">
                                        <li><span class="arrival-list__mark" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span><span>Passport + visa grant letter — printed, not just on your phone</span></li>
                                        <li><span class="arrival-list__mark" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span><span>University offer letter or employment contract</span></li>
                                        <li><span class="arrival-list__mark" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span><span>3 months of bank statements from your home country</span></li>
                                        <li><span class="arrival-list__mark" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span><span>International health insurance certificate</span></li>
                                        <li><span class="arrival-list__mark" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span><span>6 passport photos, you'll need them more than you expect</span></li>
                                    </ul>
                                </article>

                                <article class="arrival-card arrival-card--warm">
                                    <div class="arrival-card__media">
                                        <img src="{{ asset('storage/blog/Moving Checklist What to Sort Before You Fly.webp') }}" alt="Checklist and booking steps before arriving in Australia" loading="lazy" width="1500" height="1000">
                                    </div>

                                    <div class="arrival-card__head">
                                        <span class="arrival-icon" aria-hidden="true">
                                            <svg viewBox="0 0 24 24"><path d="M20 7h-3.2a2.8 2.8 0 0 0-5.6 0H8a3 3 0 0 0-3 3v6.5A2.5 2.5 0 0 0 7.5 19h9a2.5 2.5 0 0 0 2.5-2.5V10a3 3 0 0 0-3-3ZM14 6a1.2 1.2 0 1 1-2.4 0A1.2 1.2 0 0 1 14 6Zm-4.6 5.5 2.4 2.4 4.8-4.8 1.4 1.4-6.2 6.2-3.8-3.8 1.4-1.4Z"/></svg>
                                        </span>
                                        <div>
                                            <h3>Book Before You Land</h3>
                                            <p>These bookings reduce pressure in your first week.</p>
                                        </div>
                                    </div>

                                    <ul class="arrival-list">
                                        <li><span class="arrival-list__mark" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span><span>Short-term accommodation — minimum 4 weeks. You need time to find a suburb that actually fits your life before you sign a 12-month lease. <a href="https://www.booking.com" target="_blank" rel="noreferrer">Browse short-term accommodation in Australia</a></span></li>
                                        <li><span class="arrival-list__mark" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span><span>Airport transfer — don't rely on Uber on arrival if you have heavy luggage and no Australian SIM yet</span></li>
                                        <li><span class="arrival-list__mark" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5.5 12.5 10 17l8.5-9"/></svg></span><span>Travel insurance for the gap before Medicare activates → <a href="https://www.cignaglobal.com" target="_blank" rel="noreferrer">Cigna Global</a> or <a href="https://safetywing.com" target="_blank" rel="noreferrer">SafetyWing</a></span></li>
                                    </ul>
                                </article>
                        </div>

                        <div class="arrival-pro-tip">
                            <p><strong>Pro tip:</strong> Need short-term accommodation while you find your feet? I always recommend booking at least 3–4 weeks before committing to a suburb. Search by distance to your workplace first not by price or city centre. For a full walkthrough of renting in Australia, read the <a class="text-link" href="/housing">Housing Guide</a>.</p>
                        </div>
                    </section>

                    <section id="dos-and-donts" class="arrival-section arrival-section--dark">
                        <p class="eyebrow">Dos and Don'ts</p>
                        <h2>Australia Immigration Dos and Don'ts</h2>
                        <p class="arrival-section__lede">Things That Most New Arrivals Get Wrong</p>

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
                                        <p class="arrival-guidance__tagline">Practices that help you settle faster</p>
                                    </header>

                                    <ol class="arrival-guidance__items">
                                        <li class="arrival-guidance__row">
                                            <span class="arrival-guidance__index" aria-hidden="true">01</span>
                                            <div class="arrival-guidance__content">
                                                <h4>Use your employer as a rental reference</h4>
                                                <p>You have no Australian rental history that's the reality for every new arrival. An employment contract plus a letter from your manager replaces rental references with most private landlords. Target private landlords over large real estate agencies. They have more flexibility and fewer box-ticking requirements.</p>
                                            </div>
                                        </li>
                                        <li class="arrival-guidance__row">
                                            <span class="arrival-guidance__index" aria-hidden="true">02</span>
                                            <div class="arrival-guidance__content">
                                                <h4>Track your superannuation from week one</h4>
                                                <p>Every Australian employer is legally required to contribute 11% of your salary into a superannuation fund on top of your salary, not from it. Most migrants only find out years later that a previous employer didn't pay it. The ATO has a free tool to check. Use it from the start.</p>
                                            </div>
                                        </li>
                                        <li class="arrival-guidance__row">
                                            <span class="arrival-guidance__index" aria-hidden="true">03</span>
                                            <div class="arrival-guidance__content">
                                                <h4>Build your Australian credit score deliberately from month one</h4>
                                                <p>Your overseas credit history means nothing here. You start at zero and that affects post-paid phone plans, car financing, and eventually a mortgage. A secured credit card linked to your bank account, used for small purchases and paid off in full every month, is the fastest way to build it.</p>
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
                                                <h4>Don't sign a 24-month phone plan before you know your suburb</h4>
                                                <p>Go prepaid first. Coverage and pricing vary significantly depending on where you end up living. Lock yourself into a plan on day one and you may be paying for coverage you don't get where you actually live.</p>
                                            </div>
                                        </li>
                                        <li class="arrival-guidance__row">
                                            <span class="arrival-guidance__index" aria-hidden="true">02</span>
                                            <div class="arrival-guidance__content">
                                                <h4>Don't ignore your superannuation even if you plan to leave</h4>
                                                <p>If you leave Australia permanently, you are legally entitled to claim your super back. It's called the Departing Australia Superannuation Payment (DASP). Most migrants walk away, leaving thousands behind simply because they didn't know this existed. It does. Claim it.</p>
                                            </div>
                                        </li>
                                        <li class="arrival-guidance__row">
                                            <span class="arrival-guidance__index" aria-hidden="true">03</span>
                                            <div class="arrival-guidance__content">
                                                <h4>Don't arrive without medical insurance when Medicare isn't active yet</h4>
                                                <p>If your visa doesn't qualify you for Medicare immediately, you need private cover before you land. One unplanned emergency visit costs $800–$2,000 out of pocket without it. Don't arrive uncovered.</p>
                                            </div>
                                        </li>
                                    </ol>
                                </article>
                            </div>
                        </div>
                    </section>

                    <section id="first-7-days" class="arrival-section">
                        <p class="eyebrow">Your First 7 Days</p>
                        <h2>What to Do When You Arrive in Australia — The Order That Actually Matters</h2>
                        <p class="arrival-section__intro">Here's what no government website tells you: these steps depend on each other. Get the sequence wrong and you'll find yourself trying to rent a flat without a bank account, or applying for Medicare without a registered address. This is the order that works.</p>

                        <div class="arrival-timeline" data-arrival-timeline>
                            <article class="arrival-step">
                                <div class="arrival-step__head">
                                    <span class="arrival-step__number">1</span>
                                    <div class="arrival-step__copy">
                                        <span class="arrival-step__label"><svg viewBox="0 0 24 24"><path d="M7 2h10a2 2 0 0 1 2 2v16l-4-2.2L12 20l-3-2.2L5 20V4a2 2 0 0 1 2-2Zm1 4v10.6l1-.8 3 2.2 3-2.2 1 .8V6H8Z"/></svg>Step 1</span>
                                        <h3>Get an Australian SIM card</h3>
                                    </div>
                                </div>
                                <div class="arrival-step__body">
                                    <strong>Why the order matters</strong>
                                    <p>You need an Australian number for every form, callback, and verification</p>
                                </div>
                            </article>

                            <article class="arrival-step">
                                <div class="arrival-step__head">
                                    <span class="arrival-step__number">2</span>
                                    <div class="arrival-step__copy">
                                        <span class="arrival-step__label"><svg viewBox="0 0 24 24"><path d="M6 2h9l5 5v13.5A1.5 1.5 0 0 1 18.5 22h-11A1.5 1.5 0 0 1 6 20.5V2Zm8 1.8V8h4.2L14 3.8ZM9 11h6v1.8H9V11Zm0 3.6h6v1.8H9v-1.8Zm0-7.2h2.8v1.8H9V7.4Z"/></svg>Step 2</span>
                                        <h3>Apply for your TFN (Tax File Number)</h3>
                                    </div>
                                </div>
                                <div class="arrival-step__body">
                                    <strong>Why the order matters</strong>
                                    <p>Takes up to 28 days. Start this before anything else. Your employer legally needs it.</p>
                                </div>
                            </article>

                            <article class="arrival-step">
                                <div class="arrival-step__head">
                                    <span class="arrival-step__number">3</span>
                                    <div class="arrival-step__copy">
                                        <span class="arrival-step__label"><svg viewBox="0 0 24 24"><path d="M3 7.5 12 3l9 4.5V9H3V7.5Zm2 3h2v7H5v-7Zm6 0h2v7h-2v-7Zm6 0h2v7h-2v-7ZM3 19h18v2H3v-2Z"/></svg>Step 3</span>
                                        <h3>Open a bank account</h3>
                                    </div>
                                </div>
                                <div class="arrival-step__body">
                                    <strong>Why the order matters</strong>
                                    <p>Most banks require your TFN, or at least proof you've applied. Compare your options in the <a class="text-link" href="/banking">Banking Guide</a>.</p>
                                </div>
                            </article>

                            <article class="arrival-step">
                                <div class="arrival-step__head">
                                    <span class="arrival-step__number">4</span>
                                    <div class="arrival-step__copy">
                                        <span class="arrival-step__label"><svg viewBox="0 0 24 24"><path d="M12 2 4 5v6c0 5 3.4 9.5 8 10.8C16.6 20.5 20 16 20 11V5l-8-3Zm-1.1 13.6-3.3-3.3 1.4-1.4 1.9 1.9 4.2-4.2 1.4 1.4-5.6 5.6Z"/></svg>Step 4</span>
                                        <h3>Set up Medicare (if eligible)</h3>
                                    </div>
                                </div>
                                <div class="arrival-step__body">
                                    <strong>Why the order matters</strong>
                                    <p>Requires your bank account for rebate deposits</p>
                                </div>
                            </article>

                            <article class="arrival-step">
                                <div class="arrival-step__head">
                                    <span class="arrival-step__number">5</span>
                                    <div class="arrival-step__copy">
                                        <span class="arrival-step__label"><svg viewBox="0 0 24 24"><path d="M12 3 3 9v11h6v-6h6v6h6V9l-9-6Zm0 2.4 6.5 4.3V18h-2.5v-6h-8v6H5.5V9.7L12 5.4Z"/></svg>Step 5</span>
                                        <h3>Secure rental accommodation</h3>
                                    </div>
                                </div>
                                <div class="arrival-step__body">
                                    <strong>Why the order matters</strong>
                                    <p>Requires bank statements which require an active bank account</p>
                                </div>
                            </article>

                            <article class="arrival-step">
                                <div class="arrival-step__head">
                                    <span class="arrival-step__number">6</span>
                                    <div class="arrival-step__copy">
                                        <span class="arrival-step__label"><svg viewBox="0 0 24 24"><path d="M12 2.5a7 7 0 0 0-7 7c0 5.2 7 12 7 12s7-6.8 7-12a7 7 0 0 0-7-7Zm0 9.3a2.3 2.3 0 1 1 0-4.6 2.3 2.3 0 0 1 0 4.6Z"/></svg>Step 6</span>
                                        <h3>Register your address with the ATO</h3>
                                    </div>
                                </div>
                                <div class="arrival-step__body">
                                    <strong>Why the order matters</strong>
                                    <p>Requires a permanent address</p>
                                </div>
                            </article>

                            <article class="arrival-step">
                                <div class="arrival-step__head">
                                    <span class="arrival-step__number">7</span>
                                    <div class="arrival-step__copy">
                                        <span class="arrival-step__label"><svg viewBox="0 0 24 24"><path d="M12 3a9 9 0 1 0 9 9 9 9 0 0 0-9-9Zm1 5v1.1a3.5 3.5 0 0 1 2.8 2.2l-1.8.7a1.7 1.7 0 0 0-1.6-1.1c-.8 0-1.4.4-1.4 1s.5.9 1.8 1.2c2 .4 3.4 1.2 3.4 3 0 1.6-1.2 2.8-3.2 3.1V20h-2v-1.1a3.8 3.8 0 0 1-3.1-2.7l1.9-.7a2.1 2.1 0 0 0 2 1.5c1 0 1.6-.4 1.6-1.1 0-.6-.5-.9-1.9-1.2-1.9-.5-3.2-1.2-3.2-3 0-1.5 1.1-2.7 3-3V8h2Z"/></svg>Step 7</span>
                                        <h3>Transfer your international funds</h3>
                                    </div>
                                </div>
                                <div class="arrival-step__body">
                                    <strong>Why the order matters</strong>
                                    <p>Do this after your Australian account is active not before</p>
                                </div>
                            </article>
                        </div>

                        <div class="arrival-note">
                            <p>"Most people try to rent first and open a bank account second. That's backwards. Without an Australian bank account you have no transaction history and agents will reject your application. Get the bank account first, even if you're still in temporary accommodation." Entel Dajsmaili</p>
                        </div>

                        <div class="arrival-day-grid">
                            <article class="arrival-day-card">
                                <div class="arrival-day-card__head">
                                    <span class="arrival-icon arrival-icon--teal" aria-hidden="true">
                                        <svg viewBox="0 0 24 24"><path d="M7 2h10a2 2 0 0 1 2 2v16l-4-2.2L12 20l-3-2.2L5 20V4a2 2 0 0 1 2-2Zm1 4v10.6l1-.8 3 2.2 3-2.2 1 .8V6H8Z"/></svg>
                                    </span>
                                    <h3>Day 1–2 — Get a SIM Card</h3>
                                </div>
                                <p>Your Australian mobile number is the key that unlocks everything else. Every form, every callback, every bank verification uses it. Get one before you do anything else.</p>
                                <p>Go prepaid — Woolworths Mobile runs on Telstra towers at a fraction of the price. Don't sign a 24-month plan before you know your suburb and what coverage you actually get there.</p>
                            </article>

                            <article class="arrival-day-card">
                                <div class="arrival-day-card__head">
                                    <span class="arrival-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24"><path d="M6 2h9l5 5v13.5A1.5 1.5 0 0 1 18.5 22h-11A1.5 1.5 0 0 1 6 20.5V2Zm8 1.8V8h4.2L14 3.8ZM9 11h6v1.8H9V11Zm0 3.6h6v1.8H9v-1.8Zm0-7.2h2.8v1.8H9V7.4Z"/></svg>
                                    </span>
                                    <h3>Day 2–3 — Apply for Your TFN</h3>
                                </div>
                                <p>Go to <a href="https://www.ato.gov.au" target="_blank" rel="noreferrer">ato.gov.au</a> right now. The application takes 20 minutes. Your TFN card arrives by post within 28 days however, you get a receipt number immediately, and most employers accept that.</p>
                                <p>Don't wait for the physical card before telling your employer you've applied. Without a TFN on file, you'll be taxed at 45% on everything you earn until you provide one.</p>
                            </article>

                            <article class="arrival-day-card">
                                <div class="arrival-day-card__head">
                                    <span class="arrival-icon arrival-icon--slate" aria-hidden="true">
                                        <svg viewBox="0 0 24 24"><path d="M3 7.5 12 3l9 4.5V9H3V7.5Zm2 3h2v7H5v-7Zm6 0h2v7h-2v-7Zm6 0h2v7h-2v-7ZM3 19h18v2H3v-2Z"/></svg>
                                    </span>
                                    <h3>Day 3–4 — Open Your Australian Bank Account</h3>
                                </div>
                                <p>Two options that work for new arrivals with no Australian credit history:</p>
                                <p>Commonwealth Bank — you can open an account before you land, using just your passport. Walk into any branch on arrival to activate it. Your card is waiting.</p>
                                <p>Airwallex — no monthly fees, built for people moving money internationally. Good option if you're receiving payments from overseas or need multi-currency access.</p>
                            </article>

                            <article class="arrival-day-card">
                                <div class="arrival-day-card__head">
                                    <span class="arrival-icon arrival-icon--teal" aria-hidden="true">
                                        <svg viewBox="0 0 24 24"><path d="M12 2 4 5v6c0 5 3.4 9.5 8 10.8C16.6 20.5 20 16 20 11V5l-8-3Zm-1.1 13.6-3.3-3.3 1.4-1.4 1.9 1.9 4.2-4.2 1.4 1.4-5.6 5.6Z"/></svg>
                                    </span>
                                    <h3>Day 5 — Enrol in Medicare</h3>
                                </div>
                                <p>Not every visa qualifies, and some have a waiting period. Check your visa conditions at <a href="https://immi.homeaffairs.gov.au" target="_blank" rel="noreferrer">homeaffairs.gov.au</a> before you go. Take your passport and visa grant letter; you will need both. Show up without one, and they'll turn you away.</p>
                            </article>

                            <article class="arrival-day-card arrival-day-card--full">
                                <div class="arrival-day-card__head">
                                    <span class="arrival-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24"><path d="M12 3a9 9 0 1 0 9 9 9 9 0 0 0-9-9Zm1 5v1.1a3.5 3.5 0 0 1 2.8 2.2l-1.8.7a1.7 1.7 0 0 0-1.6-1.1c-.8 0-1.4.4-1.4 1s.5.9 1.8 1.2c2 .4 3.4 1.2 3.4 3 0 1.6-1.2 2.8-3.2 3.1V20h-2v-1.1a3.8 3.8 0 0 1-3.1-2.7l1.9-.7a2.1 2.1 0 0 0 2 1.5c1 0 1.6-.4 1.6-1.1 0-.6-.5-.9-1.9-1.2-1.9-.5-3.2-1.2-3.2-3 0-1.5 1.1-2.7 3-3V8h2Z"/></svg>
                                    </span>
                                    <h3>Day 6–7 — Set Up International Transfers</h3>
                                </div>
                                <p>Your Australian bank charges 3–5% on every international transfer, buried inside the exchange rate. You won't see it on the confirmation screen it's hidden in the rate they give you.</p>
                                <p>Wise shows you the exact fee before you confirm the transfer. I've used it for years. On a $5,000 transfer, the difference between using your bank and using Wise can be $150–$300.</p>
                            </article>
                        </div>
                    </section>

                    <section id="faq" class="arrival-section">
                        <p class="eyebrow">FAQ</p>
                        <h2>New to Australia — Common Questions Answered</h2>

                        <div class="arrival-faq-grid">
                            <div class="arrival-faqs">
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
                                    <p>It depends on your visa pathway. Employer-sponsored (482 → 186): typically 2–4 years. Skilled independent (189 points-tested): 6 months to 2 years from invitation. Family/partner visa: 1–3 years. State nomination can speed up a skilled application significantly. For personalised guidance, visit our <a class="text-link" href="/migration-services">Migration Services</a> page.</p>
                                </details>

                                <details class="arrival-faq">
                                    <summary>Can I rent a property in Australia without a local rental history?</summary>
                                    <p>Yes. Offer to pay 4 weeks' rent in advance instead of 2. Use your employer as a character reference. Target private landlords over large real estate agencies they have more flexibility. Get a rental reference letter from your previous landlord overseas, translated into English if needed.</p>
                                </details>

                                <details class="arrival-faq">
                                    <summary>What happens to my superannuation if I leave Australia permanently?</summary>
                                    <p>You can claim it back. It's called the Departing Australia Superannuation Payment (DASP). You apply online through the ATO after your visa expires and you've left the country. Tax is withheld on the payment, but you receive the balance. Most migrants leave thousands behind simply because they don't know this process exists.</p>
                                </details>
                            </div>

                            <div class="arrival-grid-3" style="grid-template-columns: 1fr; margin-top: 0;">
                                <div class="arrival-photo-card">
                                    <img src="{{ str_replace(' ', '%20', asset('media/new to australlia/New to Australia FAQ.webp')) }}" alt="Frequently asked questions for new arrivals in Australia" loading="lazy" width="600" height="650">
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="arrival-grid-cta" aria-label="Next steps">
                        <div class="arrival-cta">
                            <h3>Get the Free 90-Day Arrival Checklist</h3>
                            <p>Use the same checklist from this page in a format you can keep open during your first week in Australia.</p>
                            <div class="arrival-cta__actions">
                                <button class="button button--small" type="button" data-open-lead-modal>Send me the checklist</button>
                                <a class="button button--small button--outline" href="/settlement-services">Explore settlement support</a>
                            </div>
                        </div>

                        <div class="arrival-cta">
                            <h3>Need help beyond the guide?</h3>
                            <p>If you want support with pre-arrival planning, arrival day, housing, banking order, or your first 90 days, explore the settlement services page for the structured support path.</p>
                            <div class="arrival-cta__actions">
                                <a class="button button--small" href="/settlement-services">View settlement services</a>
                                <a class="button button--small button--outline" href="/contact">Ask a question first</a>
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
