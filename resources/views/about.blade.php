@extends('layouts.app')

@section('page_styles')
    <style>
        .about-page {
            background: linear-gradient(180deg, #f6f8fb 0%, #ffffff 24%, #f7fbfa 100%);
        }

        .about-hero {
            padding: 3.2rem 0 2.4rem;
        }

        .about-hero__layout {
            display: grid;
            grid-template-columns: minmax(0, 1.05fr) minmax(320px, 0.95fr);
            gap: clamp(1.2rem, 2.8vw, 2.4rem);
            align-items: stretch;
        }

        .about-hero__content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            border-radius: 28px;
            text-align: center;
            background:
                radial-gradient(circle at 10% 14%, rgba(242, 125, 43, 0.12), transparent 34%),
                linear-gradient(135deg, #0e4f58 0%, #13344f 58%, #1a4f71 100%);
            box-shadow: 0 26px 65px rgba(12, 43, 63, 0.24);
        }

        .about-hero__content .eyebrow,
        .about-hero__content h1,
        .about-hero__content p {
            color: #ffffff;
        }

        .about-hero__content .eyebrow {
            color: rgba(255, 255, 255, 0.76);
        }

        .about-hero h1 {
            margin-top: 0.65rem;
            max-width: 7ch;
            font-size: clamp(2.8rem, 5.2vw, 4.75rem);
            line-height: 1.0;
            letter-spacing: -0.03em;
        }

        .about-hero__lead {
            margin-top: 0.85rem;
            max-width: 58ch;
            color: rgba(255, 255, 255, 0.86);
            line-height: 1.72;
        }


        .about-hero__quote {
            width: min(100%, 500px);
            margin-top: 1.25rem;
            padding: 0.9rem 1rem;
            border-radius: 14px;
            border-left: 3px solid #f7a14f;
            background: rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.95);
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 0.98rem;
            line-height: 1.6;
            text-align: center;
        }

        .about-hero__media {
            position: relative;
            overflow: hidden;
            border-radius: 28px;
            border: 1px solid rgba(15, 23, 42, 0.08);
            background: #dfe8ec;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.14);
            min-height: 520px;
        }

        .about-hero__media img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
        }


        .about-story {
            padding: 0.75rem 0 2.8rem;
        }

        .about-page .section-heading.section-heading--narrow {
            max-width: 850px;
            margin: 0 auto 1.2rem;
            text-align: center;
        }

        .about-page .section-heading.section-heading--narrow p {
            max-width: 650px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }

        .about-story__intro {
            max-width: 1180px;
            margin: 0 auto 1.2rem;
            text-align: center;
        }

        .about-story__intro p {
            margin-top: 0.8rem;
            color: #556678;
            max-width: 750px;
            margin-left: auto;
            margin-right: auto;
        }

        .about-story__intro h2 {
            max-width: 850px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }

        .about-story__narrative {
            max-width: 1180px;
            margin: 0 auto;
            padding: 2rem 0;
        }

        .about-story__narrative p {
            margin: 0 0 1.1rem;
            color: #374151;
            font-size: 1.05rem;
            line-height: 1.75;
        }

        .about-story__lead {
            font-size: 1.25rem !important;
            font-weight: 600;
            color: #1f2937 !important;
            margin-bottom: 1.5rem !important;
        }

        .about-story__closing {
            font-size: 1.15rem !important;
            font-weight: 600;
            color: #0b7a75 !important;
            margin-top: 2rem !important;
            margin-bottom: 0.5rem !important;
        }

        .about-story__signature {
            font-size: 1rem !important;
            font-weight: 700;
            color: #0b7a75 !important;
            margin-bottom: 0 !important;
        }
            font-size: 1.1rem;
            line-height: 1.3;
        }

        .about-values {
            padding: 0 0 3rem;
        }

        .about-values__grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
        }

        .about-values .section-heading h2 {
            max-width: 850px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }

        .about-values__item {
            padding: 1.2rem 1.1rem;
            border: 1px solid rgba(11, 122, 117, 0.1);
            border-radius: 18px;
            background: #ffffff;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        }

        .about-values__item h3 {
            margin: 0 0 0.55rem;
            color: var(--primary-dark);
            font-size: 1.1rem;
        }

        .about-values__item p {
            margin: 0;
            color: #566779;
            line-height: 1.68;
            font-size: 0.94rem;
        }

        .about-cta {
            padding: 2rem 0 4rem;
        }

        .about-cta__panel {
            position: relative;
            overflow: hidden;
            padding: clamp(3.5rem, 6vw, 5rem) clamp(1.2rem, 3.2vw, 2rem) clamp(2.2rem, 4vw, 3.6rem);
            border-radius: 30px;
            text-align: center;
            background: linear-gradient(135deg, #15324a 0%, #0f5967 100%);
            box-shadow: 0 26px 65px rgba(9, 34, 45, 0.3);
        }

        .about-cta__panel::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 30% 25%, rgba(255, 255, 255, 0.12), transparent 40%);
            pointer-events: none;
        }

        .about-cta__content {
            position: relative;
            z-index: 1;
            max-width: 850px;
            margin: 0 auto;
        }

        .about-cta__eyebrow {
            margin: 0;
            color: rgba(233, 245, 246, 0.88);
            font-size: 0.82rem;
            letter-spacing: 0.16em;
            font-weight: 700;
            text-transform: uppercase;
        }

        .about-cta__copy h2,
        .about-cta__copy p {
            color: #ffffff;
            margin: 0;
        }

        .about-cta__copy h2 {
            margin-top: 0.8rem;
            font-size: clamp(1.8rem, 4.6vw, 3.75rem);
            line-height: 0.96;
            letter-spacing: -0.02em;
        }

        .about-cta__copy p {
            margin: 0.95rem auto 0;
            max-width: 70ch;
            color: rgba(235, 245, 247, 0.9);
        }

        .about-cta__actions {
            margin-top: 1.5rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            justify-content: center;
        }

        .about-cta__actions .button {
            min-width: 220px;
            border-radius: 10px;
        }

        .about-cta__actions .button--accent {
            background: #f28f3a;
            border-color: #f28f3a;
            color: #ffffff;
        }

        .about-cta__actions .button--ghost-light {
            border-color: rgba(255, 255, 255, 0.3);
            background: rgba(4, 16, 23, 0.42);
            color: #ffffff;
        }

        @media (max-width: 1080px) {
            .about-hero__layout,
            .about-values__grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 767px) {
            .about-hero {
                padding-top: 2.2rem;
            }

            .about-hero h1 {
                max-width: 80%;
            }

            .about-hero__content {
                padding: 1.2rem;
                border-radius: 20px;
            }

            .about-hero__media {
                min-height: 360px;
                border-radius: 20px;
            }

            .about-story__narrative {
                padding: 1.5rem 0;
            }

            .about-story__narrative p {
                text-align: center;
            }

            .about-cta__panel {
                border-radius: 22px;
                padding: 2rem 1rem;
            }

            .about-cta__copy h2 {
                line-height: 1.02;
            }

            .about-cta__actions .button {
                width: 100%;
                min-width: 0;
            }
        }
    </style>
@endsection

@section('content')
    <div class="about-page">
        <section id="top" class="about-hero">
            <div class="container about-hero__layout">
                <div class="about-hero__content">
                    <p class="eyebrow">About SettleANZ</p>
                    <h1>I Built the Guide I Wish I Had in 2001</h1>
                    <p class="about-hero__lead">SettleANZ is not generic relocation advice. It comes from the mistakes, stress, and lessons of building a life in Australia from zero, then turning that experience into a practical roadmap for others.</p>
                    <p class="about-hero__quote">"You worked too hard to get here to spend your first year lost. I am here to make sure you do not."</p>
                </div>

                <div class="about-hero__media">
                    <img src="{{ asset('media/about/about.webp') }}" alt="Entel and the SettleANZ journey in Australia">
                </div>
            </div>
        </section>

        <section class="about-story">
            <div class="container">
                <div class="about-story__intro section-heading section-heading--narrow">
                    <p class="eyebrow">The journey behind the mission</p>
                    <h2>What happened when I arrived in Australia</h2>
                    <p>This is the exact experience that shaped SettleANZ, from early confusion to the turning point that changed everything.</p>
                </div>

                <div class="about-story__narrative">
                    <p class="about-story__lead">I moved to Australia in 2001 without knowing a single person.</p>
                    <p>No family. No contacts. No one who had done it before me to call when things went wrong.</p>
                    <p>Nobody told me about the hidden bank fees. Nobody warned me about the suburb traps. Nobody explained how Medicare actually worked or in what order to do any of it.</p>
                    <p>I arrived with one suitcase and a head full of dreams. Then reality hit.</p>
                    <p>I couldn't open a bank account without an address. I couldn't get an address without a rental history. And I couldn't have a rental history without having already lived somewhere.</p>
                    <p>The system wasn't built for people like me. It was built for people who were already inside it.</p>
                    <p>I worked jobs I was overqualified for. I signed contracts I didn't fully understand just to have a roof over my head. I sent money home through transfer services that swallowed hundreds in fees.</p>
                    <p>Every night, alone in a shared house, I used to question my decision. Life in front of me looked nothing like what I had been promised when I decided to leave home.</p>
                    <p>Nobody tells you about that part.</p>
                    <p>What got me through wasn't luck. It was the occasional stranger who took ten minutes to explain how something actually worked. A landlord who gave me a chance. A colleague who referred me to his friend. Small moments of guidance that changed everything and made me realise how much difference the right information at the right time can make.</p>
                    <p>By 2004 I had permanent residency. By the end of that year, I was an Australian citizen.</p>
                    <p>But I never forgot how those first years felt. The confusion. The loneliness. The expensive mistakes made simply because nobody told me the right order to do things.</p>
                    <p>That's why I built SettleANZ. Not to repeat what government websites already say. But to be the person I desperately needed when I arrived — someone who had already walked the same road, made the same mistakes, and could hand you a guide map instead of leaving you to find your own way in the dark.</p>
                    <p class="about-story__closing">You worked too hard to get here to spend your first year lost. I'm here to make sure you don't.</p>
                    <p class="about-story__signature">— Entel</p>
                </div>
            </div>
        </section>

        <section class="about-values">
            <div class="container">
                <div class="section-heading section-heading--narrow">
                    <p class="eyebrow">What you get from this approach</p>
                    <h2>Professional, practical help for your first year</h2>
                </div>
                <div class="about-values__grid">
                    <article class="about-values__item">
                        <h3>Right order, right timing</h3>
                        <p>Clear step-by-step priorities so you do not miss dependencies that create delays.</p>
                    </article>
                    <article class="about-values__item">
                        <h3>Fewer costly errors</h3>
                        <p>Guidance that helps you avoid common mistakes in housing, banking, and first-month admin.</p>
                    </article>
                    <article class="about-values__item">
                        <h3>Support that feels human</h3>
                        <p>Advice based on lived experience and your real context, not only government checklists.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="about-cta">
            <div class="container">
                <div class="about-cta__panel">
                    <div class="about-cta__content">
                        <p class="about-cta__eyebrow">About SettleANZ</p>
                        <div class="about-cta__copy">
                            <h2>Ready to settle with confidence?</h2>
                            <p>Use the guide first, then reach out if you want one-to-one help with your move.</p>
                        </div>
                    </div>
                    <div class="about-cta__actions">
                        <a class="button button--large button--accent" href="{{ route('guides.new-to-australia') }}">Start with the New Arrival Guide</a>
                        <a class="button button--large button--ghost-light" href="{{ route('contact') }}">Contact SettleANZ</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
