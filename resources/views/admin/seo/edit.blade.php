@extends('admin.layouts.app')

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">SEO Manager</p>
            <h2>{{ $pageConfig['label'] }}</h2>
            <p>Edit meta tags, Open Graph, and structured data for this page. Changes are saved to the database and override defaults.</p>
        </div>
        <a href="{{ route('admin.seo.index') }}" class="button button--outline button--small">← Back to SEO Manager</a>
    </section>

    @if ($errors->any())
        <div class="admin-alert admin-alert--error">
            <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <style>
        .seo-edit-grid {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 1.5rem;
            align-items: start;
        }
        @media (max-width: 900px) {
            .seo-edit-grid { grid-template-columns: 1fr; }
        }
        .seo-section {
            background: #f7fbfd;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .seo-section h3 {
            font-size: 1rem;
            font-weight: 700;
            color: #12384f;
            margin: 0 0 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid rgba(11, 122, 117, 0.12);
        }
        .seo-section-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            margin: 0 0 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid rgba(11, 122, 117, 0.12);
        }
        .seo-section-heading h3 {
            margin: 0;
            padding: 0;
            border: none;
            flex: 1;
            min-width: 0;
        }
        .seo-field {
            display: flex;
            flex-direction: column;
            margin-bottom: 1.25rem;
        }
        .seo-field:last-child { margin-bottom: 0; }
        .seo-field label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #12384f;
            margin-bottom: 0.4rem;
        }
        .seo-field .seo-hint {
            font-size: 0.75rem;
            color: #667788;
            margin-bottom: 0.4rem;
        }
        .seo-field input[type="text"],
        .seo-field input[type="url"],
        .seo-field textarea {
            padding: 0.6rem 0.8rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.9rem;
            font-family: inherit;
            background: #fff;
            transition: border-color 0.2s;
        }
        .seo-field input:focus,
        .seo-field textarea:focus {
            outline: none;
            border-color: #0b7a75;
            box-shadow: 0 0 0 3px rgba(11,122,117,0.1);
        }
        .seo-field textarea { resize: vertical; }
        .seo-counter {
            font-size: 0.72rem;
            margin-top: 4px;
            font-weight: 600;
        }
        .counter--good { color: #0b7a75; }
        .counter--warn { color: #d97706; }
        .counter--bad  { color: #c95d37; }

        /* Google snippet preview */
        .seo-preview-card {
            background: #fff;
            border: 1px solid rgba(11, 122, 117, 0.12);
            border-radius: 10px;
            padding: 1.5rem;
            position: sticky;
            top: 1.5rem;
        }
        .seo-preview-card h4 {
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #667788;
            margin: 0 0 1rem;
        }
        .google-preview {
            background: #fff;
            border: 1px solid rgba(11, 122, 117, 0.12);
            border-radius: 8px;
            padding: 1rem;
        }
        .google-preview__site {
            font-size: 0.78rem;
            color: #22313d;
            margin-bottom: 2px;
        }
        .google-preview__title {
            font-size: 1.1rem;
            color: #0b7a75;
            line-height: 1.3;
            margin-bottom: 4px;
            font-weight: 400;
            cursor: pointer;
            word-break: break-word;
        }
        .google-preview__title:hover { text-decoration: underline; }
        .google-preview__desc {
            font-size: 0.82rem;
            color: #667788;
            line-height: 1.55;
            word-break: break-word;
        }

        /* OG preview */
        .og-preview {
            margin-top: 1.5rem;
            border: 1px solid #dfe1e5;
            border-radius: 8px;
            overflow: hidden;
        }
        .og-preview__img {
            width: 100%;
            height: 130px;
            background: linear-gradient(135deg, #0f8b8d20, #f27d2d20);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 0.8rem;
        }
        .og-preview__img img {
            width: 100%;
            height: 130px;
            object-fit: cover;
        }
        .og-preview__body {
            padding: 0.75rem;
            background: #f8f9fa;
        }
        .og-preview__site { font-size: 0.7rem; color: #667788; text-transform: uppercase; }
        .og-preview__title { font-size: 0.88rem; font-weight: 700; color: #12384f; margin: 2px 0; }
        .og-preview__desc { font-size: 0.78rem; color: #667788; }

        .seo-toggle-wrap {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .seo-toggle-wrap input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        .seo-toggle-label {
            font-size: 0.88rem;
            color: #22313d;
            cursor: pointer;
        }

        .seo-schema-options {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }
        .seo-schema-chip {
            padding: 0.3rem 0.75rem;
            background: #e8f5f4;
            color: #0b7a75;
            border: 1px solid rgba(11, 122, 117, 0.25);
            border-radius: 99px;
            font-size: 0.78rem;
            cursor: pointer;
            transition: all 0.15s;
        }
        .seo-schema-chip:hover { background: #0b7a75; color: #fff; }

        .seo-save-bar {
            display: flex;
            gap: 1rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
            margin-top: 1rem;
        }
        .seo-save-bar .btn-save {
            padding: 0.75rem 2rem;
            background: #0b7a75;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: background 0.2s;
        }
        .seo-save-bar .btn-save:hover { background: #085f5b; }
        .seo-save-bar .btn-cancel {
            padding: 0.75rem 1.5rem;
            background: #f4f7fb;
            color: #12384f;
            border: 1px solid rgba(11, 122, 117, 0.18);
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        .seo-defaults-note {
            background: #e8f5f4;
            border: 1px solid rgba(11, 122, 117, 0.25);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.82rem;
            color: #0b7a75;
            margin-bottom: 1.5rem;
        }
        /* Match blog post "AI Fill SEO" button (form.blade.php .ai-btn) */
        .ai-btn {
            background: #fff0ec;
            padding: 0.85rem 1.4rem;
            margin: 0;
            display: inline-flex;
            align-items: center;
            gap: 0.7rem;
            transform: translate(0%, 0%);
            overflow: hidden;
            color: #d17453;
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-align: center;
            text-transform: none;
            text-decoration: none;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }
        .ai-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #ff8c42;
            opacity: 0;
            transition: 0.2s opacity ease-in-out;
        }
        .ai-btn:hover::before {
            opacity: 0.1;
        }
        .ai-btn:hover {
            transform: translateY(-3px);
            color: #ff6b35;
        }
        .ai-btn span:not(.ai-btn__label) {
            position: absolute;
            pointer-events: none;
        }
        .ai-btn span:nth-child(2) {
            top: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(to left, rgba(255, 140, 66, 0), #ff8c42);
            animation: seo-ai-animate-top 2s linear infinite;
        }
        .ai-btn span:nth-child(3) {
            top: 0;
            right: 0;
            height: 100%;
            width: 2px;
            background: linear-gradient(to top, rgba(255, 140, 66, 0), #ff8c42);
            animation: seo-ai-animate-right 2s linear -1s infinite;
        }
        .ai-btn span:nth-child(4) {
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(to right, rgba(255, 140, 66, 0), #ff8c42);
            animation: seo-ai-animate-bottom 2s linear infinite;
        }
        .ai-btn span:nth-child(5) {
            top: 0;
            left: 0;
            height: 100%;
            width: 2px;
            background: linear-gradient(to bottom, rgba(255, 140, 66, 0), #ff8c42);
            animation: seo-ai-animate-left 2s linear -1s infinite;
        }
        @keyframes seo-ai-animate-top {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        @keyframes seo-ai-animate-right {
            0% { transform: translateY(100%); }
            100% { transform: translateY(-100%); }
        }
        @keyframes seo-ai-animate-bottom {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        @keyframes seo-ai-animate-left {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(100%); }
        }
        .ai-btn__label {
            position: relative;
            z-index: 1;
        }
        .ai-btn[disabled] {
            opacity: 0.6;
            cursor: not-allowed;
        }
        @media (max-width: 520px) {
            .seo-section-heading .ai-btn {
                width: 100%;
                justify-content: center;
            }
        }
        .ai-status {
            margin-bottom: 1rem;
            padding: 0.8rem 0.95rem;
            border-radius: 10px;
            font-size: 0.83rem;
            line-height: 1.5;
            display: none;
        }
        .ai-status.is-info {
            display: block;
            background: #eef8f7;
            border: 1px solid #c7e8e2;
            color: #0b6c68;
        }
        .ai-status.is-error {
            display: block;
            background: #fff0ec;
            border: 1px solid #f1c8bf;
            color: #b6402a;
        }
        .seo-score {
            display: grid;
            gap: 1rem;
        }
        .seo-score__top {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        .seo-score__ring {
            width: 82px;
            height: 82px;
            border-radius: 50%;
            background: conic-gradient(#0b7a75 0deg, #0b7a75 var(--score-angle, 0deg), #e7eef3 var(--score-angle, 0deg), #e7eef3 360deg);
            position: relative;
            display: grid;
            place-items: center;
            flex-shrink: 0;
        }
        .seo-score__ring::before {
            content: '';
            position: absolute;
            inset: 8px;
            border-radius: 50%;
            background: #fff;
        }
        .seo-score__value {
            position: relative;
            z-index: 1;
            font-size: 1.12rem;
            font-weight: 800;
            color: #12384f;
        }
        .seo-score__meta {
            display: grid;
            gap: 0.35rem;
        }
        .seo-score__status {
            display: inline-flex;
            width: fit-content;
            padding: 0.35rem 0.7rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            background: #eef2f6;
            color: #607080;
        }
        .seo-score__status.is-strong { background: #e9f8f3; color: #0b7a75; }
        .seo-score__status.is-fair { background: #fff6e9; color: #b87812; }
        .seo-score__status.is-weak { background: #fff0ec; color: #c95d37; }
        .seo-score__label {
            font-size: 0.8rem;
            line-height: 1.5;
            color: #667788;
        }
        .seo-score__breakdown {
            display: grid;
            gap: 0.55rem;
        }
        .seo-score__item {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 0.7rem;
            align-items: center;
            padding: 0.6rem 0.72rem;
            border: 1px solid #e7edf2;
            border-radius: 10px;
            background: #fbfdff;
        }
        .seo-score__item.is-pass { background: #f4fbf8; border-color: #cbe8dd; }
        .seo-score__item.is-warn { background: #fffaf3; border-color: #f0dfc8; }
        .seo-score__item.is-optional { border-style: dashed; }
        .seo-score__item-title {
            font-size: 0.79rem;
            font-weight: 700;
            color: #12384f;
        }
        .seo-score__item-hint {
            margin-top: 0.15rem;
            font-size: 0.74rem;
            line-height: 1.45;
            color: #6a7c89;
        }
        .seo-score__item-points {
            font-size: 0.76rem;
            font-weight: 800;
            color: #0b7a75;
            white-space: nowrap;
        }
        .seo-note {
            margin-top: 1rem;
            padding: 0.8rem 0.9rem;
            border-radius: 8px;
            background: #f7fbfd;
            border: 1px solid rgba(11,122,117,0.12);
            font-size: 0.78rem;
            color: #667788;
            line-height: 1.5;
        }
    </style>

    <div class="seo-defaults-note">
        <strong>Defaults shown below</strong> — leave fields blank to keep the hardcoded fallback. Any saved value overrides it.
    </div>

    <div class="ai-status" id="pageSeoAiStatus"></div>

    <form method="POST" action="{{ route('admin.seo.update', $pageKey) }}" id="seoForm">
        @csrf
        @method('PUT')

        <div class="seo-edit-grid">
            {{-- LEFT: Fields --}}
            <div>
                {{-- Basic Meta --}}
                <div class="seo-section">
                    <div class="seo-section-heading">
                        <h3>🔍 Basic Meta Tags</h3>
                        <button type="button" class="ai-btn" id="pageSeoAiBtn">
                            <span class="ai-btn__label">AI Generate SEO</span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>

                    <div class="seo-field">
                        <label for="focus_keyword">Focus Keyword</label>
                        <span class="seo-hint">Editorial guidance only. Google does not use a meta keywords tag, so use this to optimize the visible title, description, and URL intent.</span>
                        <input type="text" id="focus_keyword" name="focus_keyword"
                            value="{{ old('focus_keyword', $record?->focus_keyword) }}"
                            placeholder="Example: new to Australia guide"
                            maxlength="120">
                    </div>

                    <div class="seo-field">
                        <label for="secondary_keywords">Secondary Keywords</label>
                        <span class="seo-hint">Optional related phrases, separated by commas.</span>
                        <input type="text" id="secondary_keywords" name="secondary_keywords"
                            value="{{ old('secondary_keywords', $record?->secondary_keywords) }}"
                            placeholder="moving to Australia, Australian newcomer checklist"
                            maxlength="1000">
                    </div>

                    <div class="seo-field">
                        <label for="meta_title">Meta Title <span style="color:#667788;font-weight:400">(max 60 chars)</span></label>
                        <span class="seo-hint">Shown in Google search results and browser tabs. Ideal: 50–60 characters.</span>
                        <input type="text" id="meta_title" name="meta_title"
                            value="{{ old('meta_title', $record?->meta_title) }}"
                            placeholder="{{ $pageConfig['default_title'] }}"
                            maxlength="60"
                            data-preview="google-title" data-counter="meta_title_count">
                        <div class="seo-counter" id="meta_title_count_wrap">
                            <span id="meta_title_count">{{ strlen(old('meta_title', $record?->meta_title ?? '')) }}</span>/60
                            <span id="meta_title_status"></span>
                        </div>
                    </div>

                    <div class="seo-field">
                        <label for="meta_description">Meta Description <span style="color:#667788;font-weight:400">(max 160 chars)</span></label>
                        <span class="seo-hint">Shown below the title in Google. Ideal: 140–160 characters. Should include your main keyword.</span>
                        <textarea id="meta_description" name="meta_description" rows="3"
                            placeholder="{{ $pageConfig['default_description'] }}"
                            maxlength="160"
                            data-preview="google-desc" data-counter="meta_desc_count">{{ old('meta_description', $record?->meta_description) }}</textarea>
                        <div class="seo-counter" id="meta_desc_count_wrap">
                            <span id="meta_desc_count">{{ strlen(old('meta_description', $record?->meta_description ?? '')) }}</span>/160
                            <span id="meta_desc_status"></span>
                        </div>
                    </div>

                    <div class="seo-field">
                        <label for="canonical_url">Canonical URL</label>
                        <span class="seo-hint">Leave blank to use the page's own URL. Set this if content appears at multiple URLs.</span>
                        <input type="url" id="canonical_url" name="canonical_url"
                            value="{{ old('canonical_url', $record?->canonical_url) }}"
                            placeholder="https://settleanz.com{{ $pageConfig['url'] }}">
                    </div>
                </div>

                {{-- Open Graph / Social --}}
                <div class="seo-section">
                    <h3>📣 Open Graph & Social Sharing</h3>
                    <span class="seo-hint" style="display:block;margin-bottom:1rem;color:#667788">Used when your page is shared on Facebook, LinkedIn, WhatsApp, etc. Falls back to Meta Title/Description if blank.</span>

                    <div class="seo-field">
                        <label for="og_title">OG Title</label>
                        <span class="seo-hint">Can be slightly longer / more engaging than the meta title.</span>
                        <input type="text" id="og_title" name="og_title"
                            value="{{ old('og_title', $record?->og_title) }}"
                            placeholder="Leave blank to use Meta Title"
                            data-preview="og-title" maxlength="120">
                    </div>

                    <div class="seo-field">
                        <label for="og_description">OG Description</label>
                        <textarea id="og_description" name="og_description" rows="2"
                            placeholder="Leave blank to use Meta Description"
                            data-preview="og-desc" maxlength="200">{{ old('og_description', $record?->og_description) }}</textarea>
                    </div>

                    <div class="seo-field">
                        <label for="og_image">OG Image URL</label>
                        <span class="seo-hint">Recommended size: 1200×630px. Must be an absolute URL. Leave blank to use the site default OG image.</span>
                        <input type="text" id="og_image" name="og_image"
                            value="{{ old('og_image', $record?->og_image) }}"
                            placeholder="https://settleanz.com/media/og/page-name.jpg"
                            data-preview="og-image">
                    </div>
                </div>

                {{-- Technical / Advanced --}}
                <div class="seo-section">
                    <h3>⚙️ Technical & Advanced</h3>

                    <div class="seo-field">
                        <label>Schema.org Type</label>
                        <span class="seo-hint">Controls the JSON-LD structured data type. Click a chip to set or type your own.</span>
                        <input type="text" id="schema_type" name="schema_type"
                            value="{{ old('schema_type', $record?->schema_type) }}"
                            placeholder="WebPage">
                        <div class="seo-schema-options">
                            @foreach (['WebPage', 'Article', 'FAQPage', 'Service', 'ContactPage', 'AboutPage', 'LocalBusiness'] as $type)
                                <span class="seo-schema-chip" onclick="document.getElementById('schema_type').value='{{ $type }}'">{{ $type }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="seo-field">
                        <div class="seo-toggle-wrap">
                            <input type="hidden" name="no_index" value="0">
                            <input type="checkbox" id="no_index" name="no_index" value="1"
                                @checked(old('no_index', $record?->no_index ?? false))>
                            <label class="seo-toggle-label" for="no_index">
                                <strong>Block search engines (noindex, nofollow)</strong><br>
                                <span style="font-weight:400;color:#667788">Use for admin, thank-you, or duplicate pages. Do NOT use for main site pages.</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="seo-save-bar">
                    <button type="submit" class="btn-save">💾 Save SEO Settings</button>
                    <a href="{{ route('admin.seo.index') }}" class="btn-cancel">Cancel</a>
                </div>
            </div>

            {{-- RIGHT: Live Preview --}}
            <div>
                <div class="seo-preview-card">
                    <h4>SEO Score</h4>
                    <div class="seo-score">
                        <div class="seo-score__top">
                            <div class="seo-score__ring" id="seoScoreRing" style="--score-angle: 0deg;">
                                <div class="seo-score__value" id="seoScoreValue">0</div>
                            </div>
                            <div class="seo-score__meta">
                                <div class="seo-score__status" id="seoScoreStatus">Needs Work</div>
                                <div class="seo-score__label" id="seoScoreLabel">Add the main title, description, keyword, and social signals for this page.</div>
                            </div>
                        </div>
                        <div class="seo-score__breakdown" id="seoScoreBreakdown"></div>
                    </div>

                    <div class="seo-note">
                        <strong style="color:#12384f">No meta keywords tag is being added.</strong>
                        Google ignores the `keywords` meta tag, so this editor uses a focus keyword and on-page scoring instead.
                    </div>

                    <h4>Google Preview</h4>
                    <div class="google-preview">
                        <div class="google-preview__site">settleanz.com{{ $pageConfig['url'] }}</div>
                        <div class="google-preview__title" id="prev-google-title">
                            {{ $record?->meta_title ?: $pageConfig['default_title'] }}
                        </div>
                        <div class="google-preview__desc" id="prev-google-desc">
                            {{ $record?->meta_description ?: $pageConfig['default_description'] }}
                        </div>
                    </div>

                    <h4 style="margin-top:1.5rem">Social Sharing Preview</h4>
                    <div class="og-preview">
                        <div class="og-preview__img" id="prev-og-image-wrap">
                            @if ($record?->og_image)
                                <img src="{{ $record->og_image }}" alt="OG Image" id="prev-og-image">
                            @else
                                <span id="prev-og-placeholder">No OG image set — default used</span>
                                <img src="" alt="" id="prev-og-image" style="display:none">
                            @endif
                        </div>
                        <div class="og-preview__body">
                            <div class="og-preview__site">settleanz.com</div>
                            <div class="og-preview__title" id="prev-og-title">
                                {{ $record?->og_title ?: ($record?->meta_title ?: $pageConfig['default_title']) }}
                            </div>
                            <div class="og-preview__desc" id="prev-og-desc">
                                {{ $record?->og_description ?: ($record?->meta_description ?: $pageConfig['default_description']) }}
                            </div>
                        </div>
                    </div>

                    <div style="margin-top:1.25rem;padding:0.75rem;background:#f7fbfd;border-radius:8px;font-size:0.78rem;color:#667788;border:1px solid rgba(11,122,117,0.1)">
                        <strong style="color:#12384f">SEO Checklist</strong>
                        <ul id="seo-checklist" style="margin:0.5rem 0 0 1rem;padding:0;line-height:1.8">
                            <li id="chk-title">⬜ Meta title 50–60 chars</li>
                            <li id="chk-desc">⬜ Meta description 140–160 chars</li>
                            <li id="chk-og-title">⬜ OG title set</li>
                            <li id="chk-og-image">⬜ OG image set</li>
                            <li id="chk-schema">⬜ Schema type set</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
(function () {
    const $ = id => document.getElementById(id);
    const pageSeoAiBtn = $('pageSeoAiBtn');
    const pageSeoAiStatus = $('pageSeoAiStatus');

    function truncate(str, max) {
        return str.length > max ? str.slice(0, max) + '…' : str;
    }

    function setAiStatus(message, type) {
        if (!pageSeoAiStatus) return;
        if (!message) {
            pageSeoAiStatus.className = 'ai-status';
            pageSeoAiStatus.textContent = '';
            pageSeoAiStatus.style.display = 'none';
            return;
        }
        pageSeoAiStatus.className = 'ai-status is-' + (type || 'info');
        pageSeoAiStatus.textContent = message;
        pageSeoAiStatus.style.display = 'block';
    }

    function setAiBusy(button, busy, label) {
        if (!button) return;
        const labelEl = button.querySelector('.ai-btn__label');
        if (busy) {
            if (labelEl) {
                button.dataset.originalLabel = labelEl.textContent;
                labelEl.textContent = label;
            } else {
                button.dataset.originalLabel = button.textContent;
                button.textContent = label;
            }
            button.disabled = true;
            return;
        }
        if (labelEl) {
            labelEl.textContent = button.dataset.originalLabel || labelEl.textContent;
        } else {
            button.textContent = button.dataset.originalLabel || button.textContent;
        }
        button.disabled = false;
    }

    function updateCounter(inputEl, countId, statusId, min, max) {
        const len = inputEl.value.length;
        const countEl = $(countId);
        const statusEl = $(statusId);
        if (!countEl) return;
        countEl.textContent = len;
        let cls = 'counter--warn', msg = 'Too short';
        if (len >= min && len <= max) { cls = 'counter--good'; msg = '✓ Good'; }
        else if (len > max)           { cls = 'counter--bad';  msg = 'Too long'; }
        countEl.className = cls;
        if (statusEl) { statusEl.textContent = ' — ' + msg; statusEl.className = cls; }
    }

    function updateChecklist() {
        const titleLen  = $('meta_title')?.value.trim().length || 0;
        const descLen   = $('meta_description')?.value.trim().length || 0;
        const ogTitle   = $('og_title')?.value.trim() || '';
        const ogImage   = $('og_image')?.value.trim() || '';
        const schema    = $('schema_type')?.value.trim() || '';

        const set = (id, ok) => { const el = $(id); if (el) el.textContent = (ok ? '✅' : '⬜') + el.textContent.slice(1); };
        set('chk-title',    titleLen >= 50 && titleLen <= 60);
        set('chk-desc',     descLen >= 140 && descLen <= 160);
        set('chk-og-title', ogTitle.length > 0);
        set('chk-og-image', ogImage.length > 0);
        set('chk-schema',   schema.length > 0);
    }

    function updateSeoScore() {
        const focusKeyword = ($('focus_keyword')?.value || '').trim().toLowerCase();
        const metaTitle = ($('meta_title')?.value || '').trim() || $('meta_title')?.placeholder || '';
        const metaDescription = ($('meta_description')?.value || '').trim() || $('meta_description')?.placeholder || '';
        const ogTitle = ($('og_title')?.value || '').trim();
        const ogImage = ($('og_image')?.value || '').trim();
        const canonical = ($('canonical_url')?.value || '').trim();
        const schemaType = ($('schema_type')?.value || '').trim();
        const noIndex = !!$('no_index')?.checked;
        const pagePath = @json($pageConfig['url']);
        const normalizedPath = String(pagePath || '').toLowerCase();
        const titleLength = metaTitle.length;
        const descLength = metaDescription.length;
        const hasKeywordInTitle = !!focusKeyword && metaTitle.toLowerCase().includes(focusKeyword);
        const hasKeywordInDescription = !!focusKeyword && metaDescription.toLowerCase().includes(focusKeyword);
        const hasKeywordInUrl = !!focusKeyword && normalizedPath.includes(focusKeyword.replace(/\s+/g, '-'));

        const scoreItems = [
            { title: 'Focus keyword selected', hint: 'Set one primary phrase for this page.', passed: !!focusKeyword, points: 10, optional: false },
            { title: 'Keyword appears in meta title', hint: 'Helps align the page title with search intent.', passed: hasKeywordInTitle, points: 14, optional: false },
            { title: 'Keyword appears in meta description', hint: 'Supports stronger topical relevance in snippets.', passed: hasKeywordInDescription, points: 12, optional: false },
            { title: 'Keyword matches page URL intent', hint: 'The URL path should reflect the topic naturally.', passed: hasKeywordInUrl, points: 8, optional: false },
            { title: 'Meta title length is in range', hint: `Current length: ${titleLength}. Aim for about 50 to 60 characters.`, passed: titleLength >= 50 && titleLength <= 60, points: 12, optional: false },
            { title: 'Meta description length is in range', hint: `Current length: ${descLength}. Aim for about 140 to 160 characters.`, passed: descLength >= 140 && descLength <= 160, points: 12, optional: false },
            { title: 'Open Graph title is set', hint: 'Useful for stronger social sharing control.', passed: ogTitle.length > 0, points: 8, optional: false },
            { title: 'Open Graph image is set', hint: 'Pages shared on WhatsApp, Facebook, or LinkedIn benefit from a strong image.', passed: ogImage.length > 0, points: 10, optional: false },
            { title: 'Schema type is set', hint: 'Use a page type that matches the content.', passed: schemaType.length > 0, points: 8, optional: false },
            { title: 'Custom canonical URL configured', hint: canonical ? 'Custom canonical is set.' : 'Optional. Only use when another preferred URL should rank.', passed: canonical.length > 0, points: 4, optional: true },
            { title: 'Page is intentionally no-indexed', hint: noIndex ? 'This page is marked no-index.' : 'Optional. Only use for pages you do not want in search.', passed: noIndex, points: 2, optional: true },
        ];

        const requiredItems = scoreItems.filter(item => !item.optional);
        const earnedRequired = requiredItems.filter(item => item.passed).reduce((sum, item) => sum + item.points, 0);
        const totalRequired = requiredItems.reduce((sum, item) => sum + item.points, 0);
        const optionalBonus = scoreItems.filter(item => item.optional && item.passed).reduce((sum, item) => sum + item.points, 0);
        const score = Math.max(0, Math.min(100, Math.round(((earnedRequired + optionalBonus) / (totalRequired + 6)) * 100)));

        const ring = $('seoScoreRing');
        const scoreValue = $('seoScoreValue');
        const scoreStatus = $('seoScoreStatus');
        const scoreLabel = $('seoScoreLabel');
        const scoreBreakdown = $('seoScoreBreakdown');

        if (ring) ring.style.setProperty('--score-angle', `${Math.round((score / 100) * 360)}deg`);
        if (scoreValue) scoreValue.textContent = String(score);
        if (scoreStatus) {
            scoreStatus.classList.remove('is-strong', 'is-fair', 'is-weak');
            if (score >= 80) {
                scoreStatus.textContent = 'Strong';
                scoreStatus.classList.add('is-strong');
            } else if (score >= 55) {
                scoreStatus.textContent = 'Fair';
                scoreStatus.classList.add('is-fair');
            } else {
                scoreStatus.textContent = 'Needs Work';
                scoreStatus.classList.add('is-weak');
            }
        }
        if (scoreLabel) {
            scoreLabel.textContent = score >= 80
                ? 'This page covers the main search and social signals we can control here.'
                : score >= 55
                    ? 'Good progress. Tighten the missing items below for a stronger page setup.'
                    : 'Add the main title, description, keyword, image, and schema signals first.';
        }
        if (scoreBreakdown) {
            scoreBreakdown.innerHTML = '';
            scoreItems.forEach((item) => {
                const row = document.createElement('div');
                row.className = `seo-score__item ${item.passed ? 'is-pass' : 'is-warn'} ${item.optional ? 'is-optional' : ''}`;
                row.innerHTML = `
                    <div>
                        <div class="seo-score__item-title">${item.title}${item.optional ? ' (Optional)' : ''}</div>
                        <div class="seo-score__item-hint">${item.hint}</div>
                    </div>
                    <div class="seo-score__item-points">${item.passed ? '+' : ''}${item.passed ? item.points : 0} pts</div>
                `;
                scoreBreakdown.appendChild(row);
            });
        }
    }

    // Meta title live preview
    const titleInput = $('meta_title');
    if (titleInput) {
        titleInput.addEventListener('input', () => {
            const val = titleInput.value.trim();
            $('prev-google-title').textContent = truncate(val || titleInput.placeholder, 60);
            $('prev-og-title').textContent = truncate($('og_title')?.value.trim() || val || titleInput.placeholder, 60);
            updateCounter(titleInput, 'meta_title_count', 'meta_title_status', 50, 60);
            updateChecklist();
            updateSeoScore();
        });
        updateCounter(titleInput, 'meta_title_count', 'meta_title_status', 50, 60);
    }

    // Meta description live preview
    const descInput = $('meta_description');
    if (descInput) {
        descInput.addEventListener('input', () => {
            const val = descInput.value.trim();
            $('prev-google-desc').textContent = truncate(val || descInput.placeholder, 160);
            $('prev-og-desc').textContent = truncate($('og_description')?.value.trim() || val || descInput.placeholder, 160);
            updateCounter(descInput, 'meta_desc_count', 'meta_desc_status', 140, 160);
            updateChecklist();
            updateSeoScore();
        });
        updateCounter(descInput, 'meta_desc_count', 'meta_desc_status', 140, 160);
    }

    // OG title live preview
    const ogTitleInput = $('og_title');
    if (ogTitleInput) {
        ogTitleInput.addEventListener('input', () => {
            const val = ogTitleInput.value.trim() || titleInput?.value.trim() || titleInput?.placeholder || '';
            $('prev-og-title').textContent = truncate(val, 70);
            updateChecklist();
            updateSeoScore();
        });
    }

    // OG description live preview
    const ogDescInput = $('og_description');
    if (ogDescInput) {
        ogDescInput.addEventListener('input', () => {
            const val = ogDescInput.value.trim() || descInput?.value.trim() || descInput?.placeholder || '';
            $('prev-og-desc').textContent = truncate(val, 160);
            updateSeoScore();
        });
    }

    // OG image live preview
    const ogImageInput = $('og_image');
    if (ogImageInput) {
        ogImageInput.addEventListener('input', () => {
            const url = ogImageInput.value.trim();
            const img = $('prev-og-image');
            const placeholder = $('prev-og-placeholder');
            if (url) {
                img.src = url;
                img.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
            } else {
                img.style.display = 'none';
                if (placeholder) placeholder.style.display = '';
            }
            updateChecklist();
            updateSeoScore();
        });
    }

    // Schema type
    const schemaInput = $('schema_type');
    if (schemaInput) {
        schemaInput.addEventListener('input', () => {
            updateChecklist();
            updateSeoScore();
        });
    }

    ['focus_keyword', 'secondary_keywords', 'canonical_url', 'no_index'].forEach((id) => {
        const el = $(id);
        if (!el) return;
        el.addEventListener(el.type === 'checkbox' ? 'change' : 'input', updateSeoScore);
    });

    if (pageSeoAiBtn) {
        pageSeoAiBtn.addEventListener('click', async function() {
            setAiStatus('Generating SEO suggestions with AI…', 'info');
            setAiBusy(pageSeoAiBtn, true, 'Thinking…');
            try {
                const response = await fetch("{{ route('admin.ai.page-seo') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || '',
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        page_label: @json($pageConfig['label']),
                        page_url: @json($pageConfig['url']),
                        default_title: @json($pageConfig['default_title']),
                        default_description: @json($pageConfig['default_description']),
                        meta_title: $('meta_title')?.value || '',
                        meta_description: $('meta_description')?.value || '',
                        schema_type: $('schema_type')?.value || '',
                    }),
                });
                const payload = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(payload.message || 'AI SEO generation failed.');
                }

                const data = payload.data || {};
                const fieldMap = {
                    meta_title: 'meta_title',
                    meta_description: 'meta_description',
                    focus_keyword: 'focus_keyword',
                    secondary_keywords: 'secondary_keywords',
                    og_title: 'og_title',
                    og_description: 'og_description',
                    schema_type: 'schema_type',
                };

                Object.entries(fieldMap).forEach(([key, id]) => {
                    const field = $(id);
                    if (field && typeof data[key] === 'string' && data[key].trim() !== '') {
                        field.value = data[key];
                        field.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                });

                setAiStatus('AI SEO suggestions applied. Review them before saving.', 'info');
            } catch (error) {
                setAiStatus(error.message || 'AI SEO generation failed.', 'error');
            } finally {
                setAiBusy(pageSeoAiBtn, false, '');
            }
        });
    }

    updateChecklist();
    updateSeoScore();
})();
</script>
@endsection
