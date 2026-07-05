@section('page_styles')
    <style>
        .service-detail-page {
            --service-ink: #0A4A45;
            --service-teal: var(--primary-dark, #065e5b);
            --service-teal-2: var(--primary-brand, #0b7a75);
            --service-orange: var(--cta-accent, #e8773a);
            --service-paper: #f8f7f4;
            --service-muted: #607080;
            overflow: hidden;
            background: #ffffff;
            color: var(--body-text, #2c3a47);
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        .service-stage-hero {
            position: relative;
            overflow: hidden;
            padding: 88px 0 112px;
            background:
                radial-gradient(circle at 80% 18%, rgba(159, 225, 203, 0.14), transparent 30%),
                linear-gradient(135deg, #0a4a45 0%, #0f6c6b 55%, #0a4a45 100%);
            color: #ffffff;
        }

        .service-stage-hero::after {
            content: '';
            position: absolute;
            left: -5%;
            right: -5%;
            bottom: -54px;
            height: 110px;
            background: var(--service-paper);
            border-radius: 50% 50% 0 0 / 42% 42% 0 0;
        }

        .service-stage-hero__grid {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(360px, 520px);
            gap: clamp(2rem, 5vw, 4rem);
            align-items: center;
        }

        .service-stage-hero__meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.75rem;
            color: #9FE1CB;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.24em;
            text-transform: uppercase;
        }

        .service-stage-hero__number {
            display: grid;
            place-items: center;
            width: 48px;
            height: 48px;
            border-radius: 999px;
            background: var(--service-orange);
            color: #ffffff;
            letter-spacing: 0;
        }

        .service-stage-hero h1 {
            max-width: 13ch;
            margin: 0 0 1.55rem;
            color: #ffffff;
            font-size: clamp(2.55rem, 5vw, 4.35rem);
            font-weight: 800;
            line-height: 1.04;
            letter-spacing: -1.2px;
        }

        .service-stage-hero__accent {
            color: var(--service-orange);
        }

        .service-stage-hero__copy {
            max-width: 58ch;
            margin: 0 0 2rem;
            color: rgba(255, 255, 255, 0.84);
            font-size: clamp(1rem, 1.4vw, 1.18rem);
            line-height: 1.75;
        }

        .service-stage-hero__chips {
            display: flex;
            flex-wrap: wrap;
            gap: 0.9rem;
        }

        .service-stage-hero__chip {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            min-height: 50px;
            padding: 0 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.24);
            border-radius: var(--radius-button, 8px);
            background: rgba(255, 255, 255, 0.09);
            color: #ffffff;
            font-weight: 800;
            line-height: 1.2;
        }

        .service-stage-hero__chip:first-child {
            background: var(--service-orange);
            border-color: var(--service-orange);
        }

        .service-stage-hero__chip svg {
            width: 17px;
            height: 17px;
            flex: 0 0 auto;
        }

        .service-stage-hero__image {
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 16px;
            aspect-ratio: 4 / 3;
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 28px 70px rgba(0, 0, 0, 0.26);
        }

        .service-stage-hero__image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .service-package {
            padding: clamp(4.5rem, 7vw, 7rem) 0;
            scroll-margin-top: 96px;
        }

        .service-package:nth-of-type(even) {
            background: var(--service-paper);
        }

        .service-package:nth-of-type(odd) {
            background: #ffffff;
        }

        .service-package__head {
            margin-bottom: 3rem;
        }

        .service-kicker {
            display: inline-block;
            margin-bottom: 0.75rem;
            color: var(--service-orange);
            font-size: 12px;
            font-weight: 900;
            letter-spacing: 0.24em;
            text-transform: uppercase;
        }

        .service-package h2,
        .service-next h2 {
            max-width: 18ch;
            margin: 0 0 1rem;
            color: var(--service-ink);
            font-size: clamp(2rem, 3.1vw, 3rem);
            font-weight: 800;
            line-height: 1.12;
            letter-spacing: -0.8px;
        }

        .service-package__tagline {
            max-width: 62ch;
            color: #475569;
            font-size: clamp(1rem, 1.4vw, 1.15rem);
            font-style: italic;
            line-height: 1.7;
        }

        .service-package__grid {
            display: grid;
            grid-template-columns: minmax(0, 1.27fr) minmax(370px, 0.73fr);
            gap: clamp(2rem, 5vw, 4rem);
            align-items: start;
        }

        .service-package--reverse .service-package__grid {
            grid-template-columns: minmax(370px, 0.73fr) minmax(0, 1.27fr);
        }

        /* Package 1 (Pre-Arrival) card 10% wider */
        #pre-arrival .service-package__grid {
            grid-template-columns: minmax(0, 1.20fr) minmax(407px, 0.80fr);
        }

        .service-package--reverse .service-package__pricing {
            order: -1;
        }

        .service-section-title {
            margin: 0 0 1.5rem;
            color: rgba(10, 74, 69, 0.74);
            font-size: 13px;
            font-weight: 900;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .service-inclusion-list {
            display: grid;
            gap: 1.5rem;
        }

        .service-inclusion {
            display: grid;
            grid-template-columns: 38px minmax(0, 1fr);
            gap: 1rem;
            align-items: start;
        }

        .service-inclusion__icon {
            display: grid;
            place-items: center;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: rgba(232, 119, 58, 0.1);
            color: var(--service-orange);
        }

        .service-inclusion__icon svg {
            width: 19px;
            height: 19px;
        }

        .service-inclusion h3 {
            margin: 0 0 0.25rem;
            color: var(--service-ink);
            font-size: 1.05rem;
            font-weight: 800;
            line-height: 1.3;
        }

        .service-inclusion p {
            margin: 0;
            color: #607080;
            font-size: 0.95rem;
            line-height: 1.65;
        }

        .service-price-card {
            position: sticky;
            top: 112px;
            border: 1px solid rgba(10, 74, 69, 0.09);
            border-radius: 16px;
            background: #ffffff;
            padding: 2rem;
            box-shadow: 0 18px 42px rgba(10, 35, 45, 0.09);
        }

        .service-price-card__intro {
            margin: -0.5rem 0 1.35rem;
            color: #7b8b96;
            font-size: 0.88rem;
            line-height: 1.55;
        }

        .service-price-options {
            display: grid;
            gap: 0.85rem;
            margin-bottom: 1.25rem;
        }

        .service-price-option {
            position: relative;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 1rem;
            align-items: center;
            min-height: 78px;
            padding: 1rem;
            border: 1px solid rgba(10, 74, 69, 0.1);
            border-radius: 12px;
            background: #ffffff;
        }

        .service-price-option.is-featured {
            border: 2px solid var(--service-orange);
            background: #fffaf4;
        }

        .service-price-option__badge {
            position: absolute;
            top: -10px;
            right: 12px;
            padding: 0.18rem 0.5rem;
            border-radius: 999px;
            background: var(--service-orange);
            color: #ffffff !important;
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .service-price-option strong {
            display: block;
            color: var(--service-ink);
            font-size: 0.95rem;
            font-weight: 900;
            line-height: 1.25;
        }

        .service-price-option span {
            display: block;
            margin-top: 0.25rem;
            color: #64748b;
            font-size: 0.82rem;
            line-height: 1.4;
        }

        .service-price-option__price {
            color: var(--service-teal);
            font-size: 0.95rem;
            font-weight: 900;
            white-space: nowrap;
        }

        .service-package__button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.65rem;
            width: 100%;
            min-height: 52px;
            border: 0;
            border-radius: var(--radius-button, 8px);
            background: var(--service-orange);
            color: #ffffff;
            box-shadow: 0 12px 24px rgba(232, 119, 58, 0.22);
            font-weight: 900;
            cursor: pointer;
            transition: transform 0.2s ease, background 0.2s ease;
        }

        .service-package__button:hover {
            background: #d86424;
            transform: translateY(-2px);
        }

        .service-price-card__note {
            margin-top: 0.8rem;
            color: #9aa6ad;
            font-size: 0.78rem;
            text-align: center;
        }

        .service-stats {
            background: var(--service-teal);
            color: #ffffff;
            padding: 3rem 0;
        }

        .service-stats__grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1.5rem;
            text-align: center;
        }

        .service-stats__value {
            color: #ffffff;
            font-size: clamp(2rem, 3vw, 2.7rem);
            font-weight: 900;
            line-height: 1;
        }

        .service-stats__item:nth-child(3) .service-stats__value {
            color: var(--service-orange);
        }

        .service-stats__label {
            margin-top: 0.5rem;
            color: #9FE1CB;
            font-size: 0.9rem;
            font-weight: 800;
        }

        .service-next {
            background: var(--service-paper);
            padding: 4rem 0;
            text-align: center;
        }

        .service-next h2 {
            max-width: 760px;
            margin-inline: auto;
            font-size: clamp(1.7rem, 2.5vw, 2.35rem);
        }

        .service-next__subheading {
            max-width: 680px;
            margin: 0.35rem auto 0.75rem;
            color: var(--service-orange);
            font-size: clamp(1.25rem, 2vw, 1.65rem);
            font-weight: 700;
            font-style: italic;
            line-height: 1.3;
        }

        .service-next p {
            max-width: 680px;
            margin: 0 auto 1.75rem;
            color: #607080;
            font-size: 1rem;
            line-height: 1.7;
        }

        .service-next__button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            min-height: 50px;
            padding: 0 1.35rem;
            border-radius: var(--radius-button, 8px);
            background: var(--service-teal);
            color: #ffffff;
            font-weight: 900;
            box-shadow: 0 12px 24px rgba(10, 74, 69, 0.14);
        }

        .service-next__button:hover {
            background: var(--service-teal-2);
            color: #ffffff;
        }

        .service-disclaimer {
            background: #ffffff;
            border-top: 1px solid rgba(15, 23, 42, 0.06);
            padding: 2rem 0;
        }

        .service-disclaimer__card {
            max-width: 980px;
            margin: 0 auto;
            padding: 1.25rem 1.5rem;
            border: 1px solid rgba(232, 119, 58, 0.28);
            border-radius: 10px;
            background: #fffaf0;
            color: #a44e25;
            font-size: 0.9rem;
            line-height: 1.65;
        }

        .service-disclaimer__card h2 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0 0 0.35rem;
            color: #a44e25;
            font-size: 1rem;
            font-weight: 900;
        }

        @media (max-width: 991px) {
            .service-stage-hero {
                padding: 68px 0 96px;
            }

            .service-stage-hero__grid,
            .service-package__grid {
                grid-template-columns: 1fr;
            }

            #pre-arrival .service-package__grid {
                grid-template-columns: 1fr;
            }

            .service-stage-hero__content {
                text-align: center;
            }

            .service-stage-hero h1,
            .service-stage-hero__copy {
                margin-left: auto;
                margin-right: auto;
            }

            .service-stage-hero__meta,
            .service-stage-hero__chips {
                justify-content: center;
            }

            .service-stage-hero__image {
                max-width: 560px;
                margin: 0 auto;
            }

            .service-package--reverse .service-package__pricing {
                order: initial;
            }

            .service-package--reverse .service-package__grid {
                grid-template-columns: 1fr;
            }

            .service-price-card {
                position: static;
            }
        }

        @media (max-width: 767px) {
            .service-stage-hero__meta {
                flex-direction: column;
                gap: 0.75rem;
                letter-spacing: 0.16em;
                text-align: center;
            }

            .service-stage-hero__chip {
                width: 100%;
                justify-content: center;
            }

            .service-package {
                padding: 4rem 0;
            }

            .service-package__head {
                text-align: center;
            }

            .service-package h2,
            .service-package__tagline {
                margin-left: auto;
                margin-right: auto;
            }

            .service-inclusion {
                text-align: center;
                grid-template-columns: 1fr;
                justify-items: center;
            }

            .service-inclusion__icon {
                margin-bottom: 0.5rem;
            }

            .service-price-card {
                padding: 1.25rem;
            }

            .service-price-option {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            .service-price-option__badge {
                position: static;
                font-size: 9px;
                padding: 0.25rem 0.75rem;
                text-align: center;
                display: inline-block;
                margin-bottom: 0.35rem;
            }

            .service-price-option__price {
                white-space: normal;
            }

            .service-package__button {
                width: 100%;
            }

            .service-stats__grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 575px) {
            .service-stage-hero {
                padding: 48px 0 72px;
            }

            .service-stage-hero::after {
                height: 60px;
                bottom: -30px;
            }

            .service-stage-hero h1 {
                font-size: 1.75rem;
                max-width: 100%;
            }

            .service-stage-hero__copy {
                font-size: 0.95rem;
            }

            .service-stage-hero__meta {
                font-size: 11px;
            }

            .service-package {
                padding: 1.5rem 0;
            }

            .service-package__head {
                margin-bottom: 1.25rem;
            }

            .service-package__grid {
                gap: 1.25rem;
            }

            .service-package h2 {
                font-size: 1.55rem;
            }

            .service-price-card {
                text-align: center;
                padding: 1rem;
                border-color: rgba(10, 74, 69, 0.18);
            }

            .service-price-card__intro {
                text-align: center;
            }

            .service-inclusion-list {
                gap: 1.25rem;
            }

            .service-section-title {
                text-align: center;
            }

            .service-stats {
                padding: 1.5rem 0;
            }

            .service-stats__grid {
                gap: 1rem;
            }

            .service-next {
                padding: 2.5rem 0;
            }

            .service-next__button {
                width: 100%;
            }

            .service-disclaimer {
                padding: 1.25rem 0;
            }

            .service-disclaimer__card {
                text-align: center;
            }

            .service-disclaimer__card h2 {
                justify-content: center;
            }
        }
    </style>
@endsection

@section('content')
    <div class="service-detail-page">
        <section class="service-stage-hero">
            <div class="container">
                <div class="service-stage-hero__grid">
                    <div class="service-stage-hero__content">
                        <div class="service-stage-hero__meta">
                            <span class="service-stage-hero__number">{{ $service['stage_number'] }}</span>
                            <span>{{ $service['stage_label'] }}</span>
                        </div>
                        <h1>{!! $service['hero_title'] !!}</h1>
                        <p class="service-stage-hero__copy">{{ $service['hero_copy'] }}</p>
                        <div class="service-stage-hero__chips">
                            @foreach ($service['chips'] as $chip)
                                <a class="service-stage-hero__chip" href="{{ $chip['href'] ?? '#package-' . ($loop->index + 1) }}">
                                    {{ $chip['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="service-stage-hero__image">
                        <img src="{{ \App\Support\AssetVersion::url($service['hero_image']) }}" alt="{{ $service['hero_image_alt'] }}" loading="eager">
                    </div>
                </div>
            </div>
        </section>

        @php
            $serviceIcons = [
                'check'       => '<path d="M20 6 9 17l-5-5"/>',
                'document'    => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>',
                'clipboard'   => '<path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/><path d="m9 14 2 2 4-4"/>',
                'users'       => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
                'video'       => '<polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>',
                'pencil'      => '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>',
                'chat'        => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
                'phone'       => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13 19.79 19.79 0 0 1 1.61 4.35 2 2 0 0 1 3.6 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
                'location'    => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>',
                'gift'        => '<polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/>',
                'home'        => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
                'book'        => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/><line x1="12" y1="6" x2="12" y2="10"/><line x1="10" y1="8" x2="14" y2="8"/>',
                'credit-card' => '<rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>',
                'award'       => '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>',
                'briefcase'   => '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>',
                'dollar'      => '<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>',
                'trending'    => '<polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>',
                'target'      => '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>',
                'key'         => '<path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/>',
                'heart'       => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>',
                'compass'     => '<circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>',
                'calendar'    => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
                'car'         => '<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>',
                'star'        => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
                'shield'      => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/>',
                'map'         => '<polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/>',
                'network'     => '<circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>',
                'office'      => '<path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>',
                'smile'       => '<circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/>',
            ];
        @endphp

        @foreach ($service['packages'] as $package)
            <section class="service-package {{ $loop->even ? 'service-package--reverse' : '' }}" id="{{ $package['id'] }}">
                <div class="container">
                    <div class="service-package__head">
                        <span class="service-kicker">{{ $package['eyebrow'] }}</span>
                        <h2>{{ $package['title'] }}</h2>
                        <p class="service-package__tagline">{{ $package['tagline'] }}</p>
                    </div>

                    <div class="service-package__grid">
                        <div class="service-package__included">
                            <h3 class="service-section-title">What's included</h3>
                            <div class="service-inclusion-list">
                                @foreach ($package['included'] as $item)
                                    @php
                                        $iconKey = $item['icon'] ?? 'check';
                                        $iconSvg = $serviceIcons[$iconKey] ?? $serviceIcons['check'];
                                    @endphp
                                    <article class="service-inclusion">
                                        <span class="service-inclusion__icon">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $iconSvg !!}</svg>
                                        </span>
                                        <div>
                                            <h3>{{ $item['title'] }}</h3>
                                            <p>{{ $item['desc'] }}</p>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </div>

                        <aside class="service-package__pricing">
                            <div class="service-price-card">
                                <h3 class="service-section-title">Choose what suits you best</h3>
                                <p class="service-price-card__intro">{{ $package['pricing_intro'] }}</p>
                                <div class="service-price-options">
                                    @foreach ($package['pricing'] as $option)
                                        <div class="service-price-option {{ !empty($option['featured']) ? 'is-featured' : '' }}">
                                            @if (!empty($option['badge']))
                                                <span class="service-price-option__badge">{{ $option['badge'] }}</span>
                                            @endif
                                            <div>
                                                <strong>{{ $option['title'] }}</strong>
                                                <span>{{ $option['desc'] }}</span>
                                            </div>
                                            <div class="service-price-option__price">{{ $option['price'] }}</div>
                                        </div>
                                    @endforeach
                                </div>
                                <button class="service-package__button" type="button"
                                    data-open-package-modal
                                    data-package-number="{{ $package['number'] }}"
                                    data-package-stage="{{ $service['modal_stage'] }}"
                                    data-package-headline="{{ $package['title'] }}"
                                    data-package-price="{{ $package['modal_price'] }}">
                                    {{ $package['cta'] }}
                                    <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                                </button>
                                <p class="service-price-card__note">{{ $package['note'] }}</p>
                            </div>
                        </aside>
                    </div>
                </div>
            </section>
        @endforeach

        <section class="service-stats">
            <div class="container">
                <div class="service-stats__grid">
                    @foreach ($service['stats'] as $stat)
                        <div class="service-stats__item">
                            <div class="service-stats__value">{{ $stat['value'] }}</div>
                            <div class="service-stats__label">{{ $stat['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="service-next">
            <div class="container">
                <span class="service-kicker">What comes next</span>
                @if(!empty($service['next']['heading']))
                    <h2>{{ $service['next']['heading'] }}</h2>
                    <p class="service-next__subheading">{{ $service['next']['subheading'] }}</p>
                @else
                    <h2>{{ $service['next']['title'] }}</h2>
                @endif
                <p>{{ $service['next']['copy'] }}</p>
                <a class="service-next__button" href="{{ $service['next']['href'] }}">
                    {{ $service['next']['label'] }}
                    <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                </a>
            </div>
        </section>

        <section class="service-disclaimer">
            <div class="container">
                <div class="service-disclaimer__card">
                    <h2>Important Disclaimer</h2>
                    <p>
                        We are NOT a registered migration agent and do NOT provide immigration advice, visa assistance, or visa application services. We provide practical settlement guidance for newcomers to Australia and New Zealand (housing, banking, healthcare, employment, community integration). For visa advice, contact a MARA-registered migration agent (search: <a href="https://www.mara.gov.au" target="_blank" rel="noopener">www.mara.gov.au</a>).
                    </p>
                </div>
            </div>
        </section>
    </div>
@endsection
