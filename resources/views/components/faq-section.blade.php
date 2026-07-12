@props([
    'heading' => '',
    'image' => '',
    'alt' => '',
])

{{-- FAQ Premium Section — shared by Australia and New Zealand pages --}}
<section id="faq" class="guide-section guide-section--cream">
    <div class="guide-container">
        <p class="eyebrow">Common Questions</p>
        <h2 class="faq-heading">{{ $heading }}</h2>
        <div class="faq-heading-spacer"></div>
        <div class="arrival-faq-grid">
            <div class="arrival-faqs">
                {{ $slot }}
            </div>
            <div class="arrival-photo-card">
                <img src="{{ $image }}" alt="{{ $alt }}" loading="lazy" width="600" height="650">
            </div>
        </div>
    </div>
</section>

<style>
        /* ======= FAQ — PREMIUM (shared component) ======= */
        #faq.guide-section {
            padding: 100px 0;
            background: #F8F4EC;
        }

        #faq .eyebrow {
            text-align: center;
            margin-bottom: 16px;
            color: #0a7a75;
        }

        #faq .faq-heading {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            text-align: center;
            max-width: 700px;
            margin: 0 auto 48px;
            font-size: clamp(1.8rem, 3.5vw, 2.8rem);
            font-weight: 700;
            color: #0f172a;
            line-height: 1.15;
        }

        #faq .faq-heading-spacer {
            display: none;
        }

        .arrival-faq-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.08fr) minmax(300px, 0.92fr);
            gap: 32px;
            align-items: stretch;
        }

        .arrival-faqs {
            display: grid;
            gap: 16px;
        }

        .arrival-faq {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 24px 28px;
            cursor: pointer;
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        }

        .arrival-faq:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.06);
        }

        .arrival-faq[open] {
            background: #f0faf9;
            border-color: #0a7a75;
            box-shadow: 0 20px 46px rgba(12, 55, 66, 0.1);
        }

        .arrival-faq summary {
            list-style: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 16px;
            color: #0a4f51;
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 1.125rem;
            font-weight: 600;
            line-height: 1.4;
            text-align: left;
        }

        .arrival-faq summary::-webkit-details-marker {
            display: none;
        }

        .arrival-faq summary::before {
            content: '+';
            display: inline-grid;
            place-items: center;
            width: 36px;
            height: 36px;
            border-radius: 999px;
            background: #eff8f7;
            color: #0a7a75;
            font-size: 1.6rem;
            font-weight: 300;
            line-height: 1;
            flex-shrink: 0;
            transition: transform 0.3s ease;
        }

        .arrival-faq[open] summary::before {
            content: '−';
            transform: rotate(180deg);
        }

        .arrival-faq p {
            margin: 16px 0 0;
            padding: 0 0 0 52px;
            font-size: 1rem;
            line-height: 1.7;
            color: #475569;
            text-align: left;
            font-family: 'Inter', system-ui, sans-serif;
        }

        .arrival-photo-card {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 52px rgba(12, 55, 66, 0.12);
            position: relative;
        }

        .arrival-photo-card img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        @media (max-width: 1024px) {
            .arrival-faq-grid {
                grid-template-columns: 1fr;
            }
            .arrival-photo-card {
                min-height: 400px;
            }
        }

        @media (max-width: 767px) {
            .arrival-faq {
                padding: 16px;
                border-radius: 18px;
            }
            .arrival-faq p {
                padding-left: 0;
            }
        }
    </style>

