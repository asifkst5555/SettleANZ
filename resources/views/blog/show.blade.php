@extends('layouts.app')

@php
    $hasHeroImage = !empty($post->image);
    $authorInitials = collect(explode(' ', trim($post->author_name ?? 'SettleANZ')))
        ->filter()
        ->take(2)
        ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
        ->implode('');
    $shareUrl = url()->current();
    $shareTitle = $post->title;
@endphp

@section('page_styles')
    <style>
        /* ===== Reading progress bar ===== */
        .article-progress {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: transparent;
            z-index: 80;
            pointer-events: none;
        }
        .article-progress__bar {
            height: 100%;
            width: 0;
            background: linear-gradient(90deg, #ff9a4f 0%, #f27d2d 100%);
            transition: width 0.08s linear;
        }

        /* ===== Hero ===== */
        .article-hero-v2 {
            position: relative;
            padding: 56px 0 0;
            background: linear-gradient(180deg, #f3faf9 0%, #ffffff 100%);
            isolation: isolate;
        }
        .article-hero-v2::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(800px 320px at 88% 0%, rgba(10, 111, 108, 0.08), transparent 60%),
                radial-gradient(620px 280px at 0% 0%, rgba(242, 125, 45, 0.05), transparent 65%);
            z-index: -1;
            pointer-events: none;
        }
        .article-hero-v2__inner {
            max-width: 820px;
            margin: 0 auto;
            text-align: center;
            padding: 0 1rem;
        }
        .article-hero-v2__crumb {
            display: inline-flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            margin: 0 0 1.2rem;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.78);
            border: 1px solid rgba(215, 228, 234, 0.9);
            font-size: 0.82rem;
            color: #61727d;
            backdrop-filter: blur(8px);
        }
        .article-hero-v2__crumb a {
            color: #0a6f6c;
            font-weight: 700;
            text-decoration: none;
        }
        .article-hero-v2__crumb a:hover {
            text-decoration: underline;
        }
        .article-hero-v2__crumb span[aria-hidden] { color: #b8c5cb; }
        .article-hero-v2__crumb {
            margin-bottom: 1.4rem;
        }
        .article-hero-v2__pill {
            display: inline-flex;
            align-items: center;
            padding: 0.45rem 0.95rem;
            border-radius: 999px;
            background: rgba(10, 111, 108, 0.1);
            color: #0a6f6c;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 1.1rem;
        }
        .article-hero-v2 h1 {
            margin: 0 0 1.1rem;
            font-size: clamp(2rem, 4.5vw, 3.4rem);
            line-height: 1.08;
            letter-spacing: -0.025em;
            color: #0e2a31;
            text-wrap: balance;
        }
        .article-hero-v2__excerpt {
            margin: 0 auto 1.6rem;
            max-width: 640px;
            font-size: clamp(1.02rem, 1.4vw, 1.18rem);
            line-height: 1.65;
            color: #4a5a64;
        }
        .article-hero-v2__meta {
            display: inline-flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 0.9rem;
            padding: 0.65rem 1.1rem;
            border-radius: 999px;
            background: #fff;
            border: 1px solid #e5edf0;
            box-shadow: 0 6px 18px rgba(10, 60, 70, 0.05);
            font-size: 0.92rem;
            color: #4a5a64;
        }
        .article-hero-v2__avatar {
            display: inline-grid;
            place-items: center;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0a6f6c 0%, #117f79 100%);
            color: #fff;
            font-weight: 700;
            font-size: 0.78rem;
            letter-spacing: 0.04em;
        }
        .article-hero-v2__author {
            font-weight: 600;
            color: #0e2a31;
        }
        .article-hero-v2__dot {
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: #c9d4d9;
        }
        .article-hero-v2__disclosure {
            margin: 1.6rem auto 0;
            max-width: 640px;
            font-size: 0.78rem;
            color: #7a8891;
        }
        .article-hero-v2__cover {
            margin-top: 2.6rem;
            margin-bottom: -120px;
        }
        .article-hero-v2__cover-inner {
            position: relative;
            border-radius: 0;
            overflow: hidden;
            aspect-ratio: 16 / 8.2;
            box-shadow: 0 30px 60px rgba(10, 60, 70, 0.18);
            background: linear-gradient(135deg, #0a6f6c 0%, #117f79 100%);
        }
        .article-hero-v2__cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* ===== Body layout ===== */
        .article-body-v2 {
            padding: 160px 0 80px;
            background: #fff;
        }
        .article-body-v2__layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 340px;
            gap: 64px;
            align-items: start;
        }

        /* ===== Right sidebar (related posts list) ===== */
        .article-sidebar-v2 {
            position: sticky;
            top: 96px;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }
        .article-sidebar-v2__title {
            margin: 0 0 0.85rem;
            font-size: 0.74rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #7a8891;
            font-weight: 700;
        }
        .article-sidebar-v2__heading {
            margin: 0 0 0.4rem;
            font-size: 1.15rem;
            color: #0e2a31;
            letter-spacing: -0.01em;
        }
        .article-related-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .article-related-card {
            display: grid;
            grid-template-columns: 96px 1fr;
            gap: 0.85rem;
            padding: 0.65rem;
            border-radius: 14px;
            background: #fff;
            border: 1px solid #e8eef0;
            text-decoration: none;
            color: inherit;
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        }
        .article-related-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(10, 60, 70, 0.08);
            border-color: rgba(10, 111, 108, 0.25);
        }
        .article-related-card__media {
            width: 96px;
            height: 96px;
            border-radius: 10px;
            overflow: hidden;
            background: linear-gradient(135deg, #0a6f6c 0%, #117f79 100%);
            flex-shrink: 0;
        }
        .article-related-card__media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }
        .article-related-card:hover .article-related-card__media img { transform: scale(1.06); }
        .article-related-card__body {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
            min-width: 0;
        }
        .article-related-card__title {
            margin: 0;
            font-size: 0.98rem;
            line-height: 1.3;
            color: #0e2a31;
            font-weight: 700;
            letter-spacing: -0.005em;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .article-related-card__excerpt {
            margin: 0;
            font-size: 0.82rem;
            line-height: 1.45;
            color: #5a6a73;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .article-related-card__foot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            margin-top: 0.25rem;
        }
        .article-related-card__tag {
            display: inline-flex;
            align-items: center;
            padding: 0;
            background: transparent;
            color: #0a6f6c;
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            border: none;
        }
        .article-related-card__more {
            color: #f27d2d;
            font-size: 0.78rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
            white-space: nowrap;
        }
        .article-related-card__more::after {
            content: '\2192';
            transition: transform 0.2s ease;
        }
        .article-related-card:hover .article-related-card__more::after { transform: translateX(3px); }

        .article-sidebar-v2__viewall {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            margin-top: 0.4rem;
            color: #0a6f6c;
            font-weight: 600;
            font-size: 0.92rem;
            text-decoration: none;
        }
        .article-sidebar-v2__viewall:hover { text-decoration: underline; }

        /* ===== Featured (first) recent card ===== */
        .article-feature-card {
            display: flex;
            flex-direction: column;
            border-radius: 16px;
            overflow: hidden;
            background: #fff;
            border: 1px solid #e8eef0;
            text-decoration: none;
            color: inherit;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }
        .article-feature-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 36px rgba(10, 60, 70, 0.1);
            border-color: rgba(10, 111, 108, 0.25);
        }
        .article-feature-card__media {
            position: relative;
            aspect-ratio: 16 / 9;
            background: linear-gradient(135deg, #0a6f6c 0%, #117f79 100%);
            overflow: hidden;
        }
        .article-feature-card__media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.45s ease;
        }
        .article-feature-card:hover .article-feature-card__media img { transform: scale(1.05); }
        .article-feature-card__badge {
            position: absolute;
            top: 0.75rem;
            left: 0.75rem;
            display: inline-flex;
            align-items: center;
            padding: 0.3rem 0.7rem;
            border-radius: 999px;
            background: rgba(242, 125, 45, 0.95);
            color: #fff;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            box-shadow: 0 6px 14px rgba(242, 125, 45, 0.3);
        }
        .article-feature-card__body {
            display: flex;
            flex-direction: column;
            gap: 0.55rem;
            padding: 1rem 1rem 1.1rem;
        }
        .article-feature-card__title {
            margin: 0;
            font-size: 1.08rem;
            line-height: 1.3;
            color: #0e2a31;
            font-weight: 700;
            letter-spacing: -0.01em;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .article-feature-card__excerpt {
            margin: 0;
            font-size: 0.88rem;
            line-height: 1.5;
            color: #5a6a73;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* ===== Article content ===== */
        .article-content-v2 {
            max-width: 720px;
            color: #2c3a47;
            font-size: 1.075rem;
            line-height: 1.78;
        }
        .article-content-v2 section + section { margin-top: 3rem; }
        .article-content-v2 h2 {
            margin: 0 0 1rem;
            font-size: clamp(1.55rem, 2.4vw, 1.95rem);
            line-height: 1.2;
            letter-spacing: -0.015em;
            color: #0e2a31;
            scroll-margin-top: 110px;
        }
        .article-content-v2 h2::before {
            content: '';
            display: block;
            width: 44px;
            height: 3px;
            border-radius: 2px;
            background: linear-gradient(90deg, #ff9a4f 0%, #f27d2d 100%);
            margin-bottom: 0.85rem;
        }
        .article-content-v2 p {
            margin: 0 0 1.1rem;
            color: #3b4a55;
        }
        .article-content-v2 section:first-child > p:first-of-type::first-letter {
            float: left;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 3.4rem;
            line-height: 0.95;
            font-weight: 700;
            color: #0a6f6c;
            padding: 0.35rem 0.65rem 0 0;
        }

        /* Body HTML (rich content from CMS / seeders) */
        .article-body-html h2 {
            margin: 2.6rem 0 1rem;
            font-size: clamp(1.55rem, 2.4vw, 1.95rem);
            line-height: 1.2;
            letter-spacing: -0.015em;
            color: #0e2a31;
            scroll-margin-top: 110px;
        }
        .article-body-html h2::before {
            content: '';
            display: block;
            width: 44px;
            height: 3px;
            border-radius: 2px;
            background: linear-gradient(90deg, #ff9a4f 0%, #f27d2d 100%);
            margin-bottom: 0.85rem;
        }
        .article-body-html > h2:first-child { margin-top: 0; }
        .article-body-html h3 {
            margin: 2rem 0 0.65rem;
            font-size: 1.2rem;
            color: #0e2a31;
            letter-spacing: -0.01em;
        }
        .article-body-html h4 {
            margin: 1.5rem 0 0.5rem;
            font-size: 1.05rem;
            color: #0e2a31;
        }
        .article-body-html p { margin: 0 0 1.1rem; color: #3b4a55; }
        .article-body-html > p:first-child::first-letter {
            float: left;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 3.4rem;
            line-height: 0.95;
            font-weight: 700;
            color: #0a6f6c;
            padding: 0.35rem 0.65rem 0 0;
        }
        .article-body-html ul,
        .article-body-html ol {
            margin: 0.5rem 0 1.4rem;
            padding: 0;
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }
        .article-body-html li {
            position: relative;
            padding-left: 1.85rem;
            color: #3b4a55;
        }
        .article-body-html ul > li::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0.55rem;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: rgba(10, 111, 108, 0.12);
        }
        .article-body-html ul > li::after {
            content: '';
            position: absolute;
            left: 5px;
            top: 0.85rem;
            width: 8px;
            height: 4px;
            border-left: 2px solid #0a6f6c;
            border-bottom: 2px solid #0a6f6c;
            transform: rotate(-45deg);
        }
        .article-body-html ol { counter-reset: olc; }
        .article-body-html ol > li { counter-increment: olc; }
        .article-body-html ol > li::before {
            content: counter(olc);
            position: absolute;
            left: 0;
            top: 0.15rem;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #0a6f6c;
            color: #fff;
            font-size: 0.78rem;
            font-weight: 700;
            display: grid;
            place-items: center;
        }
        .article-body-html blockquote {
            margin: 1.6rem 0;
            padding: 1.1rem 1.4rem;
            border-radius: 14px;
            border-left: 4px solid #f27d2d;
            background: #fff8f1;
            color: #3b2a18;
            font-style: italic;
        }
        .article-body-html blockquote p { margin: 0 0 0.4rem; color: inherit; }
        .article-body-html blockquote cite {
            display: block;
            margin-top: 0.4rem;
            font-style: normal;
            font-size: 0.88rem;
            color: #7a5a30;
            font-weight: 600;
        }
        .article-body-html blockquote p::first-letter { all: unset; }
        .article-body-html .article-callout,
        .article-body-html .article-cta-card { margin-top: 1.6rem; }
        .article-body-html a:not(.button) {
            color: #0a6f6c;
            text-decoration: underline;
            text-decoration-thickness: 1.5px;
            text-underline-offset: 3px;
        }
        .article-body-html strong { color: #0e2a31; }
        .article-list-v2 {
            margin: 0.5rem 0 1.4rem;
            padding: 0;
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }
        .article-list-v2 li {
            position: relative;
            padding-left: 1.85rem;
            color: #3b4a55;
        }
        .article-list-v2 li::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0.55rem;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: rgba(10, 111, 108, 0.12);
        }
        .article-list-v2 li::after {
            content: '';
            position: absolute;
            left: 5px;
            top: 0.85rem;
            width: 8px;
            height: 4px;
            border-left: 2px solid #0a6f6c;
            border-bottom: 2px solid #0a6f6c;
            transform: rotate(-45deg);
        }

        .article-callout {
            margin: 1.6rem 0;
            padding: 1.4rem 1.5rem;
            border-radius: 16px;
            background: linear-gradient(135deg, #f4f9f8 0%, #ecf5f3 100%);
            border-left: 4px solid #0a6f6c;
        }
        .article-callout h3 {
            margin: 0 0 0.4rem;
            font-size: 1.1rem;
            color: #0e2a31;
        }
        .article-callout p { margin: 0 0 0.65rem; color: #3b4a55; }
        .article-callout .text-link { font-weight: 600; }

        .article-cta-card {
            margin-top: 1.8rem;
            padding: 1.75rem;
            border-radius: 18px;
            background: linear-gradient(135deg, #0a6f6c 0%, #0d8682 100%);
            color: #fff;
            box-shadow: 0 20px 40px rgba(10, 60, 70, 0.18);
            display: grid;
            gap: 0.8rem;
        }
        .article-cta-card h3 { margin: 0; color: #fff; font-size: 1.2rem; }
        .article-cta-card p { margin: 0; color: rgba(255,255,255,0.86); }
        .article-cta-card .button {
            justify-self: start;
            background: #fff;
            color: #0a6f6c;
            border: none;
        }
        .article-cta-card .button:hover { background: #f3f7f8; }

        .article-faq-v2 {
            margin-top: 3.6rem;
            padding: 2rem;
            border-radius: 24px;
            background: linear-gradient(180deg, #fbfefd 0%, #f5faf9 100%);
            border: 1px solid #dfecee;
        }
        .article-faq-v2__eyebrow {
            margin: 0 0 0.35rem;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #0a6f6c;
        }
        .article-faq-v2 h2 {
            margin: 0 0 0.5rem;
        }
        .article-faq-v2 h2::before {
            display: none;
        }
        .article-faq-v2__intro {
            margin: 0 0 1.25rem;
            color: #667788;
            font-size: 0.96rem;
            line-height: 1.65;
        }
        .article-faq-v2__list {
            display: grid;
            gap: 0.8rem;
        }
        .article-faq-v2 details {
            border: 1px solid #dbe7ea;
            border-radius: 16px;
            background: #fff;
            overflow: hidden;
        }
        .article-faq-v2 summary {
            list-style: none;
            cursor: pointer;
            padding: 1rem 1.15rem;
            font-weight: 700;
            color: #12384f;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        .article-faq-v2 summary::-webkit-details-marker { display: none; }
        .article-faq-v2 summary::after {
            content: '+';
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: inline-grid;
            place-items: center;
            background: #eef6f4;
            color: #0a6f6c;
            flex-shrink: 0;
        }
        .article-faq-v2 details[open] summary::after {
            content: '–';
        }
        .article-faq-v2__answer {
            padding: 0 1.15rem 1.1rem;
            color: #495965;
            line-height: 1.7;
        }
        .article-faq-v2__answer p:last-child {
            margin-bottom: 0;
        }

        /* ===== Author card ===== */
        .article-author-v2 {
            margin-top: 4rem;
            padding: 2rem;
            border-radius: 20px;
            background: #f8fbfb;
            border: 1px solid #e5edf0;
            display: grid;
            grid-template-columns: 88px 1fr;
            gap: 1.5rem;
            align-items: start;
        }
        .article-author-v2__photo {
            display: grid;
            place-items: center;
            width: 88px;
            height: 88px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0a6f6c 0%, #117f79 100%);
            color: #fff;
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: 0.04em;
            box-shadow: 0 12px 24px rgba(10, 60, 70, 0.18);
        }
        .article-author-v2__title {
            margin: 0 0 0.15rem;
            font-size: 0.78rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #7a8891;
            font-weight: 700;
        }
        .article-author-v2__name {
            margin: 0 0 0.5rem;
            font-size: 1.25rem;
            color: #0e2a31;
        }
        .article-author-v2__bio {
            margin: 0 0 0.9rem;
            color: #4a5a64;
            line-height: 1.65;
        }
        .article-author-v2__social {
            display: flex;
            gap: 0.5rem;
        }
        .article-author-v2__social a {
            display: inline-grid;
            place-items: center;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #fff;
            border: 1px solid #e5edf0;
            color: #0a6f6c;
            transition: background 0.15s ease, color 0.15s ease, transform 0.15s ease;
            text-decoration: none;
        }
        .article-author-v2__social a:hover {
            background: #0a6f6c;
            color: #fff;
            transform: translateY(-2px);
        }
        .article-author-v2__social svg { width: 14px; height: 14px; }

        /* ===== Bottom CTA ===== */
        .article-end-cta {
            background: linear-gradient(135deg, #0a6f6c 0%, #117f79 100%);
            padding: 64px 0;
            color: #fff;
        }
        .article-end-cta__inner {
            max-width: 760px;
            margin: 0 auto;
            text-align: center;
            padding: 0 1rem;
        }
        .article-end-cta h2 {
            margin: 0 0 0.85rem;
            color: #fff;
            font-size: clamp(1.7rem, 3vw, 2.4rem);
            letter-spacing: -0.02em;
        }
        .article-end-cta p {
            margin: 0 0 1.6rem;
            color: rgba(255,255,255,0.86);
            font-size: 1.05rem;
            line-height: 1.65;
        }
        .article-end-cta .button {
            background: #ff9a4f;
            background: linear-gradient(180deg, #ff9a4f 0%, #f27d2d 100%);
            color: #fff;
            border: none;
            box-shadow: 0 14px 28px rgba(242, 125, 45, 0.32);
        }

        /* ===== Related posts ===== */
        .related-posts-v2 {
            padding: 72px 0;
            background: #f8fbfb;
        }
        .related-posts-v2__head {
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        .related-posts-v2__head h2 {
            margin: 0;
            font-size: clamp(1.6rem, 2.6vw, 2.1rem);
            color: #0e2a31;
            letter-spacing: -0.02em;
        }
        .related-grid-v2 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }
        .related-card-v2 {
            display: flex;
            flex-direction: column;
            background: #fff;
            border-radius: 18px;
            border: 1px solid #e5edf0;
            overflow: hidden;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }
        .related-card-v2:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(10, 60, 70, 0.1);
            border-color: rgba(10, 111, 108, 0.2);
        }
        .related-card-v2__media {
            aspect-ratio: 16 / 10;
            background: linear-gradient(135deg, #0a6f6c 0%, #117f79 100%);
            overflow: hidden;
        }
        .related-card-v2__media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }
        .related-card-v2:hover .related-card-v2__media img { transform: scale(1.05); }
        .related-card-v2__body {
            padding: 1.4rem 1.4rem 1.6rem;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }
        .related-card-v2__tag {
            margin: 0;
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #f27d2d;
        }
        .related-card-v2__title {
            margin: 0;
            color: #0e2a31;
            font-size: 1.15rem;
            line-height: 1.35;
            letter-spacing: -0.01em;
        }
        .related-card-v2__excerpt {
            margin: 0;
            color: #5a6a73;
            font-size: 0.95rem;
            line-height: 1.6;
        }
        .related-card-v2__more {
            margin-top: auto;
            padding-top: 0.5rem;
            color: #0a6f6c;
            font-weight: 600;
            font-size: 0.92rem;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }
        .related-card-v2__more::after {
            content: '\2192';
            transition: transform 0.2s ease;
        }
        .related-card-v2:hover .related-card-v2__more::after { transform: translateX(4px); }

        /* ===== Responsive ===== */
        @media (max-width: 960px) {
            .article-body-v2__layout {
                grid-template-columns: 1fr;
                gap: 40px;
            }
            .article-sidebar-v2 {
                position: static;
                top: auto;
            }
        }
        @media (max-width: 720px) {
            .article-body-v2 { padding: 130px 0 56px; }
            .article-hero-v2 { padding: 36px 0 0; }
            .article-hero-v2__cover { margin-top: 2rem; margin-bottom: -90px; }
            .article-hero-v2__cover-inner { aspect-ratio: 16 / 10; border-radius: 0; }
            .article-author-v2 {
                grid-template-columns: 1fr;
                text-align: center;
                padding: 1.5rem;
            }
            .article-author-v2__photo { margin: 0 auto; }
            .article-author-v2__social { justify-content: center; }
            .article-content-v2 section:first-child > p:first-of-type::first-letter {
                font-size: 2.6rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="article-progress" aria-hidden="true"><div class="article-progress__bar" data-article-progress></div></div>

    <section id="top" class="article-hero-v2">
        <div class="container">
            <div class="article-hero-v2__inner">
                @if (!empty($breadcrumbItems))
                    <nav class="article-hero-v2__crumb" aria-label="Breadcrumb">
                        @foreach ($breadcrumbItems as $index => $crumb)
                            @if ($index < count($breadcrumbItems) - 1)
                                <a href="{{ $crumb['url'] }}">{{ $crumb['name'] }}</a>
                                <span aria-hidden="true">/</span>
                            @else
                                <span>{{ $crumb['name'] }}</span>
                            @endif
                        @endforeach
                    </nav>
                @endif
                <span class="article-hero-v2__pill">{{ $post->category }}</span>
                <h1>{{ $post->title }}</h1>
                @if (!empty($post->excerpt))
                    <p class="article-hero-v2__excerpt">{{ $post->excerpt }}</p>
                @endif
                <div class="article-hero-v2__meta">
                    <span class="article-hero-v2__avatar">{{ $authorInitials }}</span>
                    <span class="article-hero-v2__author">{{ $post->author_name ?? 'SettleANZ Team' }}</span>
                    @if (!empty($post->published_at))
                        <span class="article-hero-v2__dot" aria-hidden="true"></span>
                        <span>{{ optional($post->published_at)->format('F j, Y') }}</span>
                    @endif
                    @if (!empty($post->reading_time))
                        <span class="article-hero-v2__dot" aria-hidden="true"></span>
                        <span>{{ $post->reading_time }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="container article-hero-v2__cover">
            <div class="article-hero-v2__cover-inner">
                @if ($hasHeroImage)
                    <img src="{{ $post->image_url }}" alt="{{ $post->title }}">
                @else
                    <div class="{{ $post->image_class }}" style="width:100%;height:100%;" aria-hidden="true"></div>
                @endif
            </div>
        </div>
    </section>

    <section class="article-body-v2">
        <div class="container">
            <div class="article-body-v2__layout">
                <article class="article-content-v2">
                    @if (!empty($post->body_html))
                        <div class="article-body-html">
                            {!! $post->body_html !!}
                        </div>
                    @else
                        <section id="why-it-matters">
                            <h2>Why It Matters</h2>
                            <p>{{ $post->intro_content ?: $post->excerpt }}</p>
                            <p>{{ $post->excerpt }} This article is written to be practical first, helping a newcomer understand the decision, the common risks, and the fastest next step.</p>
                        </section>

                        <section id="what-to-check">
                            <h2>What to Check</h2>
                            <p>{{ $post->checks_content ?: 'Start by looking at timing, cost, setup friction, and whether the option still makes sense after your first few weeks on the ground.' }}</p>
                            <ul class="article-list-v2">
                                <li>Check the practical steps that must be handled before you land.</li>
                                <li>Separate urgent setup tasks from nice-to-have optimisations.</li>
                                <li>Use trusted providers or internal guides when the next decision has money or compliance implications.</li>
                            </ul>
                            <div class="article-callout">
                                <h3>Want the shorter version?</h3>
                                <p>Use the main guide pages when you need a faster summary with the key actions and decisions already prioritised.</p>
                                <a class="text-link" href="{{ route('guides.new-to-australia') }}">Go to the newcomer guide &rarr;</a>
                            </div>
                        </section>

                        <section id="best-next-step">
                            <h2>Best Next Step</h2>
                            <p>{{ $post->next_steps_content ?: 'The strongest next step is usually one of three things: choose a trusted tool, move to a deeper guide, or submit your details when the decision is complex enough to need support.' }}</p>
                            <div class="article-cta-card">
                                <h3>Need help with the next move?</h3>
                                <p>Get the SettleANZ starter guide or move directly into Housing, Banking, or Migration support depending on your situation.</p>
                                <button class="button button--small" type="button" data-open-lead-modal>Get free help</button>
                            </div>
                        </section>
                    @endif

                    @if (!empty($faqItems))
                        <section class="article-faq-v2" aria-labelledby="article-faq-title">
                            <p class="article-faq-v2__eyebrow">Common Questions</p>
                            <h2 id="article-faq-title">Frequently Asked Questions</h2>
                            <p class="article-faq-v2__intro">Quick answers related to this article’s main topic.</p>
                            <div class="article-faq-v2__list">
                                @foreach ($faqItems as $faq)
                                    <details>
                                        <summary>{{ $faq['question'] }}</summary>
                                        <div class="article-faq-v2__answer">
                                            <p>{!! nl2br(e($faq['answer'])) !!}</p>
                                        </div>
                                    </details>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    <section class="article-author-v2" aria-label="About the author">
                        <div class="article-author-v2__photo"><span>{{ $authorInitials }}</span></div>
                        <div>
                            <p class="article-author-v2__title">Written by</p>
                            <h3 class="article-author-v2__name">{{ $post->author_name ?? 'SettleANZ Team' }}</h3>
                            <p class="article-author-v2__bio">SettleANZ content is shaped by real relocation experience, practical research, and the day-to-day decisions new arrivals actually need help making.</p>
                            <div class="article-author-v2__social">
                                <a href="#" aria-label="Facebook"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 21v-7.5h2.6l.4-3h-3V8.6c0-.86.24-1.45 1.48-1.45H17V4.45c-.27-.04-1.2-.12-2.27-.12-2.25 0-3.79 1.37-3.79 3.9V10.5H8.5v3h2.44V21h2.56z"/></svg></a>
                                <a href="#" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg></a>
                                <a href="#" aria-label="LinkedIn"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5C4.98 4.88 3.87 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5zM.22 8h4.56v14H.22zM8 8h4.37v1.92h.06c.61-1.16 2.1-2.39 4.32-2.39 4.62 0 5.47 3.04 5.47 6.99V22h-4.56v-6.05c0-1.44-.03-3.3-2.01-3.3-2.01 0-2.32 1.57-2.32 3.19V22H8z"/></svg></a>
                            </div>
                        </div>
                    </section>
                </article>

                @if ($recentPosts->count())
                    <aside class="article-sidebar-v2" aria-label="Recent articles">
                        <div>
                            <p class="article-sidebar-v2__title">Recent articles</p>
                            <h3 class="article-sidebar-v2__heading">Latest from the blog</h3>
                        </div>

                        @php($featuredRecent = $recentPosts->first())
                        <a class="article-feature-card" href="{{ route('blog.show', $featuredRecent->slug) }}">
                            <div class="article-feature-card__media">
                                @if (!empty($featuredRecent->image))
                                    <img src="{{ $featuredRecent->image_url }}" alt="{{ $featuredRecent->title }}" loading="lazy">
                                @else
                                    <div class="{{ $featuredRecent->image_class }}" style="width:100%;height:100%;" aria-hidden="true"></div>
                                @endif
                                <span class="article-feature-card__badge">Featured</span>
                            </div>
                            <div class="article-feature-card__body">
                                <span class="article-related-card__tag">{{ $featuredRecent->category }}</span>
                                <h4 class="article-feature-card__title">{{ $featuredRecent->title }}</h4>
                                <p class="article-feature-card__excerpt">{{ $featuredRecent->excerpt }}</p>
                                <span class="article-related-card__more">Read more</span>
                            </div>
                        </a>

                        @if ($recentPosts->count() > 1)
                            <div class="article-related-list">
                                @foreach ($recentPosts->slice(1) as $recentPost)
                                    <a class="article-related-card" href="{{ route('blog.show', $recentPost->slug) }}">
                                        <div class="article-related-card__media">
                                            @if (!empty($recentPost->image))
                                                <img src="{{ $recentPost->image_url }}" alt="{{ $recentPost->title }}" loading="lazy">
                                            @else
                                                <div class="{{ $recentPost->image_class }}" style="width:100%;height:100%;" aria-hidden="true"></div>
                                            @endif
                                        </div>
                                        <div class="article-related-card__body">
                                            <h4 class="article-related-card__title">{{ $recentPost->title }}</h4>
                                            <p class="article-related-card__excerpt">{{ $recentPost->excerpt }}</p>
                                            <div class="article-related-card__foot">
                                                <span class="article-related-card__more">Read more</span>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        <a class="article-sidebar-v2__viewall" href="{{ route('blog.index') }}">View all articles &rarr;</a>
                    </aside>
                @endif
            </div>
        </div>
    </section>

    <section class="article-end-cta">
        <div class="container">
            <div class="article-end-cta__inner">
                <h2>Make your move easier with SettleANZ</h2>
                <p>Get tailored help with housing, banking, and migration so you can settle in faster and skip the guesswork.</p>
                <button class="button button--large" type="button" data-open-lead-modal>Get free relocation help</button>
            </div>
        </div>
    </section>

    <script>
        (function () {
            const progressBar = document.querySelector('[data-article-progress]');
            const article = document.querySelector('.article-content-v2');

            function updateProgress() {
                if (!progressBar || !article) return;
                const rect = article.getBoundingClientRect();
                const total = rect.height - window.innerHeight + 200;
                const scrolled = Math.min(Math.max(-rect.top + 100, 0), total);
                const pct = total > 0 ? (scrolled / total) * 100 : 0;
                progressBar.style.width = Math.min(Math.max(pct, 0), 100) + '%';
            }

            window.addEventListener('scroll', updateProgress, { passive: true });
            window.addEventListener('resize', updateProgress);
            updateProgress();

        })();
    </script>
@endsection
