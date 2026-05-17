@extends('layouts.app')

@section('content')
<section id="top" class="hero-section hero-section--image hero-section--reference section section--hero">
        <div class="hero-reference__video-wrap" aria-hidden="true">
            <video class="hero-reference__video" autoplay muted loop playsinline preload="metadata" poster="{{ asset('media/hero/hero.webp') }}">
                <source src="{{ asset('media/home/hero.webm') }}" type="video/webm">
            </video>
        </div>
        <div class="container hero-reference__inner">
            <div class="hero-reference__content">
                <h1 style="max-width: 50ch;">Landing in a New Country Shouldn't Feel Like Guessing Every Step!</h1>
                <p class="hero-reference__copy">Bank accounts, rentals, jobs, transport. One wrong step and you lose weeks fixing it. We show you exactly what to do first so nothing slips through the cracks.</p>
                <div class="hero-reference__actions">
                    <button class="button button--large" type="button" data-open-lead-modal>Get SettleANZ Guide</button>
                    <a class="button button--large button--contact" href="{{ route('contact') }}">Contact Us</a>
                </div>
            </div>
        </div>
    </section>
    <style>
.hero-section--reference {
    overflow: hidden;
}
.hero-section--reference .hero-reference__video-wrap {
    position: absolute;
    inset: 0;
    z-index: 0;
    pointer-events: none;
}
.hero-section--reference .hero-reference__video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transform: scale(1.02);
}
.hero-section--reference::after {
    content: '';
    position: absolute;
    inset: 0;
    z-index: 1;
    pointer-events: none;
    background: linear-gradient(180deg, rgba(4, 22, 36, 0.75) 0%, rgba(5, 32, 50, 0.7) 50%, rgba(6, 50, 70, 0.75) 100%);
}
.hero-section--reference .hero-reference__inner {
    position: relative;
    z-index: 2;
}

/* Hero section proper center alignment */
.hero-section--reference {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-height: 100vh !important;
    padding-top: 120px !important;
    padding-bottom: 120px !important;
}

.hero-reference__inner {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 100% !important;
}

.hero-reference__content {
    max-width: 1200px !important;
    text-align: center !important;
    margin: 0 auto !important;
}

.hero-reference__content h1 {
    max-width: 25ch !important;
    margin-left: auto;
    margin-right: auto;
    font-size: clamp(1.93rem, 4.09vw, 3.38rem) !important;
}

/* Mobile hero buttons - match desktop colors */
@media (max-width: 767px) {
    .hero-section .hero-reference__actions .button:first-child,
    .hero-section--reference .hero-reference__actions .button:first-child {
        background-color: var(--cta-accent, #e8773a) !important;
        border-color: var(--cta-accent, #e8773a) !important;
    }

    .hero-section .hero-reference__actions .button--contact,
    .hero-section--reference .hero-reference__actions .button--contact {
        background-color: #1AA3A3 !important;
        border-color: #1AA3A3 !important;
    }
}

.hero-reference__copy {
    max-width: 66ch !important;
    margin-left: auto;
    margin-right: auto;
}

/* Contact Us Button - Lighter teal default, darker on hover */
.button--contact {
    background-color: #1AA3A3;
    border-color: #1AA3A3;
    color: #fff;
}

.button--contact:hover {
    background-color: #0E8789;
    border-color: #0E8789;
}

/* Owner info section */
.owner-info {
    background: #fff;
    padding: 2.5rem 0 3.5rem;
}

.owner-info__container {
    display: grid;
    grid-template-columns: 200px 1fr;
    gap: 3rem;
    align-items: center;
}

.owner-photo-wrap {
    display: flex;
    justify-content: center;
    flex-shrink: 0;
}

.owner-photo {
    width: 160px;
    height: 160px;
    border-radius: 50%;
    border: 4px solid #0b7a75;
    object-fit: cover;
    flex-shrink: 0;
}

.owner-content h2 {
    margin: 0 0 0.8rem;
    color: #0b7a75;
    font-size: 2rem;
    line-height: 1.2;
    letter-spacing: -0.01em;
    font-weight: 700;
}

.owner-content p {
    margin: 0.75rem 0 0;
    color: #556975;
    font-size: 0.95rem;
    line-height: 1.6;
}

.owner-content a {
    display: inline-block;
    margin-top: 1rem;
    color: #0b7a75;
    text-decoration: none;
    font-size: 0.95rem;
    font-weight: 600;
    transition: opacity 0.2s ease;
}

.owner-content a:hover {
    opacity: 0.7;
}

/* Desktop: founder photo larger than base 160px (was +40%, then +20% more) */
@media (min-width: 768px) {
    .owner-info__container {
        grid-template-columns: 300px 1fr;
    }

    .owner-photo {
        width: 270px;
        height: 270px;
    }
}


.empathy-section--inline.empathy-section {
    padding-bottom: 4.25rem;
    position: relative;
    background: #ffffff;
}
.empathy-section--inline::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(circle at 12% 14%, rgba(242, 125, 43, 0.09) 0 1px, transparent 1px),
        radial-gradient(circle at 88% 12%, rgba(242, 125, 43, 0.08) 0 1px, transparent 1px),
        radial-gradient(circle at 10% 88%, rgba(242, 125, 43, 0.08) 0 1px, transparent 1px);
    background-size: 14px 14px, 16px 16px, 14px 14px;
    pointer-events: none;
    opacity: 0.45;
}
.empathy-section--inline > .container {
    position: relative;
    z-index: 1;
}
.empathy-section--inline .empathy-section__heading {
    max-width: 100%;
    width: 100%;
    margin: 0 auto 7.5rem;
    text-align: center;
    display: grid;
    justify-items: center;
}
.empathy-section--inline .empathy-section__heading .eyebrow {
    display: block;
    width: 100%;
    margin-bottom: 1.1rem;
    text-align: center;
}
.empathy-section--inline .empathy-section__heading h2 {
    max-width: min(100%, 52ch);
    margin-inline: auto;
}
.empathy-section--inline .empathy-section__intro {
    max-width: min(100%, 72ch);
    margin: 1rem auto 0;
    color: #5d6670;
    font-size: 1.05rem;
    line-height: 1.75;
}
.empathy-section--inline .empathy-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1.35rem;
}
.empathy-section--inline .empathy-card {
    display: grid;
    grid-template-columns: minmax(250px, 44%) minmax(0, 56%);
    align-items: stretch;
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 18px;
    box-shadow: 0 12px 28px rgba(15, 23, 42, 0.07);
    overflow: hidden;
}
.empathy-section--inline .empathy-card__media {
    position: relative;
    min-height: 248px;
    padding: 0.9rem;
    display: flex;
    align-items: stretch;
    background:
        linear-gradient(160deg, rgba(15, 139, 141, 0.12), rgba(15, 139, 141, 0.02)),
        linear-gradient(135deg, #f7fbfb 0%, #edf6f6 100%);
}
.empathy-section--inline .empathy-card__media::before {
    content: '';
    position: absolute;
    inset: 0.9rem;
    border-radius: 14px;
    background:
        linear-gradient(180deg, rgba(255,255,255,0.12), rgba(255,255,255,0)),
        linear-gradient(135deg, rgba(15,139,141,0.06), rgba(242,125,43,0.06));
}
.empathy-section--inline .empathy-card__image {
    position: relative;
    z-index: 1;
    width: 100%;
    height: 100%;
    min-height: 230px;
    object-fit: cover;
    border-radius: 14px;
    display: block;
}
.empathy-section--inline .empathy-card__body {
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
    padding: 1rem 1.1rem 1.05rem;
}
.empathy-section--inline .empathy-card__kicker {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    margin: 0;
    color: #2454a6;
    font-size: 0.88rem;
    font-weight: 600;
}
.empathy-section--inline .empathy-card__kicker::before {
    content: '';
    width: 8px;
    height: 8px;
    border-radius: 999px;
    background: #2454a6;
    box-shadow: 0 0 0 4px rgba(36,84,166,0.12);
}
.empathy-section--inline .empathy-card__body h3 {
    margin: 0;
    color: #0a6b6d;
    font-size: 1.55rem;
    line-height: 1.25;
}
.empathy-section--inline .empathy-card__points {
    display: grid;
    gap: 0.65rem;
    margin: 0;
    padding: 0 0 1rem;
    list-style: none;
    border-bottom: 1px dashed rgba(148,163,184,0.45);
}
.empathy-section--inline .empathy-card__points li {
    position: relative;
    padding-left: 1rem;
    color: #4b5563;
    font-size: 0.98rem;
    line-height: 1.6;
}
.empathy-section--inline .empathy-card__points li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0.72rem;
    width: 6px;
    height: 6px;
    border-radius: 999px;
    background: #0f8b8d;
}
.empathy-section--inline .empathy-card__footer {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 1rem;
    margin-top: auto;
}
.empathy-section--inline .empathy-card__cta {
    min-height: 50px;
    margin-top: 0;
    padding-inline: 1.2rem;
    white-space: nowrap;
    border-radius: 999px;
}
@media (max-width: 1279px) {
    .empathy-section--inline .empathy-grid {
        grid-template-columns: 1fr;
    }
}
.value-stack {
    background:
        linear-gradient(180deg, #f7f3ec 0%, #f2ece3 100%);
}
.value-stack__shell {
    display: grid;
    gap: 2.5rem;
}
.value-stack__eyebrow {
    margin: 0 0 1rem;
    color: #26323d;
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    text-align: center;
}
.value-stack__heading h2 {
    max-width: 28ch;
    margin: 0 auto 1rem;
    color: var(--primary-brand);
    text-align: center;
}
.value-stack__intro {
    max-width: 62ch;
    margin: 0 auto;
    color: #52606d;
    font-size: 1.05rem;
    line-height: 1.8;
    text-align: center;
}
.value-stack__grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1.5rem;
}
.value-stack__card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-height: 100%;
    padding: 1.7rem 1.5rem 1.6rem;
    border: 1px solid rgba(21, 50, 74, 0.08);
    border-radius: 24px;
    background: rgba(255, 255, 255, 0.92);
    box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
    overflow: hidden;
}
.value-stack__card::before {
    content: '';
    position: absolute;
    inset: 0 0 auto 0;
    height: 5px;
    background: linear-gradient(90deg, #0b7a75 0%, #2b8db8 100%);
}
.value-stack__index {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.4rem;
    height: 2.4rem;
    margin-bottom: 1.1rem;
    border-radius: 999px;
    background: #e8f5f4;
    color: #0b7a75;
    font-size: 0.9rem;
    font-weight: 700;
}
.value-stack__card h3 {
    margin: 0 0 1rem;
    color: var(--primary-brand);
    font-size: 1.45rem;
    line-height: 1.25;
}
.value-stack__list {
    display: grid;
    gap: 0.9rem;
    margin: 0;
    padding: 0;
    list-style: none;
}
.value-stack__list li {
    position: relative;
    padding-left: 1.1rem;
    color: #4b5563;
    line-height: 1.72;
}
.value-stack__list li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0.72rem;
    width: 7px;
    height: 7px;
    border-radius: 999px;
    background: #f27d2b;
    box-shadow: 0 0 0 4px rgba(242, 125, 43, 0.12);
}
.value-stack__cta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    padding: 1.6rem 1.8rem;
    border: 1px solid rgba(21, 50, 74, 0.08);
    border-radius: 24px;
    background: linear-gradient(135deg, #15324a 0%, #1d4d69 100%);
    box-shadow: 0 20px 40px rgba(15, 23, 42, 0.12);
}
.value-stack__cta-copy p {
    margin: 0 0 0.45rem;
    color: rgba(203, 251, 247, 0.82);
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}
.value-stack__cta-copy h3 {
    margin: 0;
    color: #ffffff;
    font-size: clamp(1.4rem, 2vw, 1.8rem);
    line-height: 1.25;
}
.value-stack__cta .button {
    flex: 0 0 auto;
    min-width: 260px;
}
@media (max-width: 1023px) {
    .value-stack__grid {
        grid-template-columns: 1fr;
    }

    .value-stack__cta {
        flex-direction: column;
        align-items: flex-start;
    }
}
.testimonial-band {
    background:
        radial-gradient(circle at top left, rgba(11, 122, 117, 0.08), transparent 28%),
        linear-gradient(180deg, #ffffff 0%, #f7fbfa 100%);
}
.testimonial-band__shell {
    display: grid;
    gap: 0;
}
.testimonial-band__heading {
    max-width: 100%;
    width: 100%;
    margin: 0 auto;
    text-align: center;
    display: grid;
    justify-items: center;
}
.testimonial-band__heading-top {
    width: 100%;
    display: grid;
    justify-items: center;
    gap: 1.1rem;
}
.testimonial-band__eyebrow {
    display: block;
    width: 100%;
    margin: 0;
    color: #f27d2b;
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}
.testimonial-band__heading h2 {
    width: 100%;
    max-width: 100%;
    margin: 0;
    color: var(--primary-brand);
}
.testimonial-band__heading p {
    width: 100%;
    max-width: 1000px;
    margin: 1rem auto 0;
    color: #617181;
    line-height: 1.75;
    text-align: center;
}
.testimonial-band__carousel {
    position: relative;
    margin-top: 2rem;
}
.testimonial-band__carousel.is-paused .testimonial-band__viewport {
    scroll-behavior: auto;
}
.testimonial-band__viewport {
    display: grid;
    grid-auto-flow: column;
    grid-auto-columns: calc((100% - 1.5rem) / 2);
    gap: 1.5rem;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    scroll-behavior: smooth;
    scrollbar-width: none;
    padding: 0.25rem 0 2rem;
    scroll-padding-inline: 0.25rem;
}
.testimonial-band__viewport::-webkit-scrollbar {
    display: none;
}
.testimonial-band__card {
    display: flex;
    scroll-snap-align: start;
    flex-direction: column;
    min-height: 100%;
    padding: 1.55rem;
    border: 1px solid rgba(11, 122, 117, 0.1);
    border-radius: 26px;
    background: #ffffff;
}
.testimonial-band__icon {
    display: block;
    margin-bottom: 0;
    color: #f27d2b;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 4.8rem;
    font-weight: 700;
    line-height: 0.65;
}
.testimonial-band__quote {
    margin: 0 0 1.4rem;
    color: #425466;
    font-size: 1rem;
    line-height: 1.78;
}
.testimonial-band__footer {
    display: flex;
    align-items: center;
    gap: 0.9rem;
    padding-top: 1rem;
    margin-top: auto;
    border-top: 1px solid rgba(15, 23, 42, 0.08);
}
.testimonial-band__media {
    flex: 0 0 58px;
    width: 58px;
}
.testimonial-band__media img {
    width: 58px;
    height: 58px;
    object-fit: cover;
    display: block;
    border-radius: 999px;
    border: 3px solid #edf7f6;
}
.testimonial-band__meta {
    display: grid;
    gap: 0.18rem;
}
.testimonial-band__name {
    color: #213547;
    font-size: 1.02rem;
    font-weight: 700;
}
.testimonial-band__role {
    color: #7b8794;
    font-size: 0.88rem;
}
.testimonial-band__note {
    justify-self: center;
    max-width: 64ch;
    color: #7b8794;
    font-size: 0.9rem;
    line-height: 1.7;
    text-align: center;
}
.testimonial-band__controls {
    pointer-events: none;
}
.testimonial-band__control {
    position: absolute;
    top: 50%;
    z-index: 2;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.75rem;
    height: 2.75rem;
    padding: 0;
    border: 1px solid rgba(11, 122, 117, 0.12);
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.95);
    color: #0b7a75;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.12);
    transform: translateY(-50%);
    pointer-events: auto;
    cursor: pointer;
}
.testimonial-band__control:disabled {
    opacity: 0.35;
    cursor: not-allowed;
    box-shadow: none;
}
.testimonial-band__control--prev {
    left: -1.2rem;
}
.testimonial-band__control--next {
    right: -1.2rem;
}
@media (max-width: 1023px) {
    .testimonial-band__viewport {
        grid-auto-columns: 82%;
    }

    .testimonial-band__control--prev {
        left: -0.65rem;
    }

    .testimonial-band__control--next {
        right: -0.65rem;
    }
}

/* Rebuilt mobile layout */
@media (max-width: 767px) {
    .section {
        padding: 56px 0;
    }

    body.is-homepage .hero-section--reference {
        min-height: auto;
        padding-top: 96px;
        padding-bottom: 48px;
    }

    .hero-section--reference .hero-reference__video {
        transform: scale(1.12);
        object-position: center center;
    }

    body.is-homepage .hero-reference__inner,
    body.is-homepage .hero-reference__content {
        min-height: auto;
        max-width: 100%;
        text-align: center;
        margin-inline: auto;
    }

    body.is-homepage .hero-reference__eyebrow {
        margin: 0 auto 0.85rem;
        font-size: 0.72rem;
        letter-spacing: 0.09em;
        text-align: center;
    }

    body.is-homepage .hero-section--reference h1 {
        max-width: 100%;
        margin: 0 auto 0.95rem;
        font-size: clamp(1.9rem, 8.2vw, 2.35rem);
        line-height: 1.08;
        letter-spacing: -0.025em;
        text-align: center;
    }

    body.is-homepage .hero-reference__copy {
        max-width: 100%;
        margin: 0 auto 1.2rem;
        font-size: 0.98rem;
        line-height: 1.62;
        text-align: center;
    }

    body.is-homepage .hero-reference__actions {
        width: 100%;
        display: grid !important;
        grid-template-columns: 1fr;
        gap: 0.65rem;
    }

    body.is-homepage .hero-reference__actions .button {
        width: 100%;
        min-width: 0;
        max-width: 100%;
        height: 52px !important;
        min-height: 52px !important;
        padding-inline: 1rem !important;
        white-space: normal;
    }

    .empathy-section--inline.empathy-section {
        padding-bottom: 3rem;
    }

    .empathy-section--inline .empathy-section__heading {
        display: block !important;
        margin: 0 auto 2rem;
        width: 100%;
        max-width: 100%;
        text-align: center;
    }

    .empathy-section--inline .empathy-section__heading .eyebrow {
        width: 100%;
        max-width: 100%;
    }

    .empathy-section--inline .empathy-section__heading h2 {
        width: 100%;
        max-width: 100% !important;
        margin-inline: 0;
        font-size: clamp(1.55rem, 6.6vw, 1.95rem);
        line-height: 1.2;
        text-align: center;
    }

    .empathy-section--inline .empathy-section__intro {
        width: 100%;
        max-width: 100% !important;
        font-size: 0.95rem;
        line-height: 1.6;
        text-align: center;
    }

    .empathy-section--inline .empathy-card {
        grid-template-columns: 1fr;
        border-radius: 14px;
    }

    .empathy-section--inline .empathy-card__media {
        min-height: 0;
        padding: 0.72rem;
        aspect-ratio: 1 / 1;
    }

    .empathy-section--inline .empathy-card__media::before {
        inset: 0.72rem;
    }

    .empathy-section--inline .empathy-card__image {
        min-height: 0;
        aspect-ratio: 1 / 1;
    }

    .empathy-section--inline .empathy-card__body {
        padding: 0.92rem;
    }

    .empathy-section--inline .empathy-card__body h3 {
        font-size: 1.22rem;
    }

    .empathy-section--inline .empathy-card__points li {
        font-size: 0.93rem;
        line-height: 1.55;
    }

    .empathy-section--inline .empathy-card__footer,
    .value-stack__cta {
        flex-direction: column;
        align-items: stretch;
    }

    .empathy-section--inline .empathy-card__cta,
    .value-stack__cta .button {
        width: 100%;
        min-width: 0;
    }

    /* Center-align clarity-call CTA text on mobile */
    .value-stack__cta-copy,
    .value-stack__cta-copy p,
    .value-stack__cta-copy h3 {
        text-align: center;
    }

    /* Founder story section */
    .founder-strip {
        padding: 3.5rem 0 !important;
    }

    .value-stack__shell {
        gap: 1.5rem;
    }

    .value-stack__heading h2 {
        max-width: 100%;
        font-size: clamp(1.55rem, 6.4vw, 1.95rem);
    }

    .value-stack__intro {
        font-size: 0.94rem;
        line-height: 1.6;
    }

    .value-stack__card {
        padding: 1.2rem 1rem 1.1rem;
        border-radius: 16px;
    }

    .value-stack__card h3 {
        font-size: 1.22rem;
    }

    .value-stack__list li {
        font-size: 0.93rem;
        line-height: 1.58;
    }

    .owner-info__container {
        grid-template-columns: 1fr;
        gap: 2rem;
        text-align: center;
    }

    .owner-photo-wrap {
        justify-content: center;
        display: flex;
    }

    .owner-content {
        text-align: center;
    }

    .owner-content h2 {
        font-size: 2rem;
        margin-left: auto;
        margin-right: auto;
    }

    .owner-content p {
        margin-left: auto;
        margin-right: auto;
    }

    .owner-content a {
        margin-left: auto;
        margin-right: auto;
        display: block;
        width: fit-content;
    }

    .testimonial-band__heading h2 {
        font-size: clamp(1.5rem, 6.3vw, 1.9rem);
    }

    .testimonial-band__heading p {
        font-size: 0.94rem;
        line-height: 1.62;
        padding-inline: 0.25rem;
    }

    /* ================================================================
       MOBILE REDESIGN — vertical stack, no carousel.
       Ditches the horizontal scroll/snap/cropping entirely and shows
       every testimonial one below the other for a clean mobile UX.
       ================================================================ */
    .testimonial-band__carousel {
        margin-top: 1.5rem;
        margin-inline: 0;
        padding: 0;
        overflow: visible !important;
    }

    .testimonial-band__controls {
        display: none !important;
    }

    .testimonial-band__viewport {
        display: flex !important;
        flex-direction: column !important;
        grid-auto-flow: unset !important;
        grid-auto-columns: unset !important;
        gap: 1rem !important;
        padding: 0 !important;
        margin: 0 !important;
        overflow: visible !important;
        scroll-snap-type: none !important;
        scroll-behavior: auto !important;
        width: 100%;
        max-width: 100%;
    }

    .testimonial-band__card {
        display: flex;
        flex-direction: column;
        width: 100%;
        max-width: 100%;
        min-height: 0;
        padding: 1.35rem 1.2rem;
        border-radius: 18px;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.06);
        scroll-snap-align: none;
    }

    .testimonial-band__icon {
        font-size: 3.2rem;
        margin-bottom: 0.1rem;
        line-height: 0.65;
    }

    .testimonial-band__quote {
        font-size: 0.97rem;
        line-height: 1.62;
        margin-bottom: 1rem;
    }

    .testimonial-band__footer {
        gap: 0.75rem;
        padding-top: 0.85rem;
    }

    .testimonial-band__media,
    .testimonial-band__media img {
        flex: 0 0 48px;
        width: 48px;
        height: 48px;
    }

    .testimonial-band__name {
        font-size: 0.95rem;
    }

    .testimonial-band__role {
        font-size: 0.82rem;
    }

    /* Lead strip "Get the Free SettleANZ Starter Guide" — mobile polish */
    .lead-strip {
        padding: 2.75rem 0;
    }

    .lead-strip__copy h2 {
        font-size: clamp(1.45rem, 6.2vw, 1.85rem);
        line-height: 1.2;
        margin: 0;
    }

    .lead-strip__copy p {
        font-size: 0.95rem;
        line-height: 1.55;
        margin-top: 0.55rem;
    }

    .lead-strip__inner {
        gap: 1.1rem;
        text-align: center;
    }

    .lead-strip__form {
        flex-direction: column;
        align-items: stretch;
        gap: 0.65rem;
        width: 100%;
        max-width: 100%;
    }

    .lead-strip__form label {
        flex: 1 1 auto;
        max-width: 100%;
        width: 100%;
    }

    .lead-strip__form input {
        width: 100%;
        min-height: 50px;
        padding: 0.85rem 0.95rem;
        font-size: 0.95rem;
    }

    .lead-strip__form button {
        flex: 0 0 auto;
        width: 100%;
        min-height: 50px;
        font-size: 0.95rem;
    }
}

@media (max-width: 390px) {
    .section {
        padding: 50px 0;
    }

    body.is-homepage .hero-section--reference {
        padding-top: 90px;
        padding-bottom: 40px;
    }

    body.is-homepage .hero-reference__eyebrow {
        font-size: 0.67rem;
    }

    body.is-homepage .hero-section--reference h1 {
        max-width: 13ch;
        font-size: clamp(1.72rem, 8.2vw, 2.05rem);
    }

    body.is-homepage .hero-reference__copy {
        font-size: 0.91rem;
        line-height: 1.56;
    }

    body.is-homepage .hero-reference__actions .button {
        height: 50px !important;
        min-height: 50px !important;
        font-size: 0.9rem;
    }

    .empathy-section--inline .empathy-section__heading h2,
    .value-stack__heading h2,
    .testimonial-band__heading h2 {
        font-size: clamp(1.38rem, 7vw, 1.72rem);
    }

    .empathy-section--inline .empathy-card__body h3,
    .value-stack__card h3 {
        font-size: 1.12rem;
    }
}
</style>
<section id="why-settleanz" class="section section--white empathy-section empathy-section--inline">
        <div class="container">
            <div class="section-heading section-heading--narrow empathy-section__heading">
                <p class="eyebrow">SettleANZ is for you whether you have already landed or arrive in the next few weeks</p>
                <h2 style="color: #0b7a75;">My Goal is Simple: You Should not Have to Struggle for Years the Way I did.</h2>
                <p class="empathy-section__intro">The rejections. The confusion. The nights wondering what’s next. I felt every one of them. Now I help new arrivals skip the struggle and settle with confidence.</p>
            </div>
            <div class="empathy-grid">
                <article class="empathy-card reveal-from-left">
                    <div class="empathy-card__media">
                        <picture>
                            <source media="(max-width: 767px)" srcset="{{ str_replace(' ', '%20', asset('media/home/mobile_version/International Students_mobile_version.webp')) }}">
                            <img class="empathy-card__image" src="{{ asset('media/home/International Students.webp') }}" alt="International students preparing for life in Australia">
                        </picture>
                    </div>
                    <div class="empathy-card__body">
                        <p class="empathy-card__kicker">Pre-arrival study setup</p>
                        <h3>International Students</h3>
                        <ul class="empathy-card__points">
                            <li>You are coming to Australia mainly for study (uni, TAFE, language or vocational course).</li>
                            <li>You are worried about banking, SIM cards, and rentals close to campus before classes start.</li>
                            <li>You don't fully understand your visa work limits and you can't afford to get it wrong.</li>
                        </ul>
                        <div class="empathy-card__footer">
                            <a class="button button--outline-accent empathy-card__cta" href="{{ route('guides.new-to-australia') }}">Show me What to Do First</a>
                        </div>
                    </div>
                </article>

                <article class="empathy-card reveal-from-right">
                    <div class="empathy-card__media">
                        <picture>
                            <source media="(max-width: 767px)" srcset="{{ str_replace(' ', '%20', asset('media/home/mobile_version/Skilled Workers_mobile_version.webp')) }}">
                            <img class="empathy-card__image" src="{{ asset('media/home/Skilled Workers.webp') }}" alt="Skilled workers planning their move and career">
                        </picture>
                    </div>
                    <div class="empathy-card__body">
                        <p class="empathy-card__kicker">Career and landing plan</p>
                        <h3>Skilled Workers</h3>
                        <ul class="empathy-card__points">
                            <li>You hold (or are about to hold) a skilled visa and plan to work in your profession.</li>
                            <li>You want help choosing suburbs, setting up tax and banking, and reading employment offers.</li>
                            <li>You're worried about workplace culture and whether your overseas experience will actually count.</li>
                        </ul>
                        <div class="empathy-card__footer">
                            <a class="button button--outline-accent empathy-card__cta" href="{{ route('guides.banking') }}">Help Me Hit the Ground Running</a>
                        </div>
                    </div>
                </article>

                <article class="empathy-card reveal-from-left">
                    <div class="empathy-card__media">
                        <picture>
                            <source media="(max-width: 767px)" srcset="{{ str_replace(' ', '%20', asset('media/home/mobile_version/New Immigrants_mobile_version.webp')) }}">
                            <img class="empathy-card__image" src="{{ asset('media/home/New Immigrants.webp') }}" alt="New immigrants settling into daily life">
                        </picture>
                    </div>
                    <div class="empathy-card__body">
                        <p class="empathy-card__kicker">Family settlement support</p>
                        <h3>New Immigrants</h3>
                        <ul class="empathy-card__points">
                            <li>You have recently arrived as a permanent migrant or on a long-term pathway with family/partner.</li>
                            <li>You need to sort housing, schools (if you have kids), healthcare and day-to-day routines.</li>
                            <li>You feel overwhelmed by all the systems and want a step-by-step starting plan.</li>
                        </ul>
                        <div class="empathy-card__footer">
                            <a class="button button--outline-accent empathy-card__cta" href="{{ route('guides.housing') }}">Start My Family Settlement Plan</a>
                        </div>
                    </div>
                </article>

                <article class="empathy-card reveal-from-right">
                    <div class="empathy-card__media">
                        <picture>
                            <source media="(max-width: 767px)" srcset="{{ str_replace(' ', '%20', asset('media/home/mobile_version/Applying for Immigration_mobile_version.webp')) }}">
                            <img class="empathy-card__image" src="{{ asset('media/home/Applying for Immigration.webp') }}" alt="Preparing documents and plans for immigration">
                        </picture>
                    </div>
                    <div class="empathy-card__body">
                        <p class="empathy-card__kicker">Before-you-land planning</p>
                        <h3>Applying for Immigration</h3>
                        <ul class="empathy-card__points">
                            <li>You are still overseas and in the process of applying for a visa or planning your move.</li>
                            <li>You're drowning in conflicting advice about documents and what to prepare.</li>
                            <li>You want a realistic picture of costs, first-year priorities and common mistakes to avoid.</li>
                        </ul>
                        <div class="empathy-card__footer">
                            <a class="button button--outline-accent empathy-card__cta" href="{{ route('guides.migration-services') }}">Prepare before You Land</a>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="owner-info">
        <div class="container owner-info__container">
            <div class="owner-photo-wrap reveal-on-scroll reveal-from-left">
                <img class="owner-photo" src="{{ asset('media/founder/founder.webp') }}" alt="Owner of SettleANZ">
            </div>
            <div class="owner-content reveal-on-scroll reveal-from-right">
                <h2>My First Year in Australia was Lonely, Expensive, and Nothing Like I was Promised.</h2>
                <p>I made every mistake a new immigrant can make: the wrong suburb, the hidden bank fees, the contracts I signed just to have a roof over my head.</p>
                <p>Nobody should have to learn all of that the hard way.</p>
                <p>That's why I built SettleANZ, so your story starts better than mine did.</p>
                <a href="{{ route('about') }}">Read my full story</a>
            </div>
        </div>
    </section>

    <section class="section value-stack">
        <div class="container value-stack__shell">
            <div class="value-stack__heading">
                <h2>You land. Nothing is set up. You don’t know what to do first.</h2>
                <p class="value-stack__intro">And in hurry you choose the wrong options. Wrong suburb. Wrong setup. Missed steps that create delays. Most immigrants lose 3 to 6 months fixing these early mistakes. SettleANZ helps you get it right the first time.</p>
            </div>

            <div class="value-stack__grid">
                <article class="value-stack__card">
                    <span class="value-stack__index">01</span>
                    <h3>Stop Being Scared of the System</h3>
                    <ul class="value-stack__list">
                        <li>Get your TFN, Medicare, and MyGov set up in the right order so nothing blocks anything else.</li>
                        <li>Understand superannuation instead of losing money by ignoring it.</li>
                        <li>Know exactly which forms matter, which deadlines are real, and which ones can wait.</li>
                    </ul>
                </article>

                <article class="value-stack__card">
                    <span class="value-stack__index">02</span>
                    <h3>Know Where Your Money Is Going and Where It Shouldn't</h3>
                    <ul class="value-stack__list">
                        <li>Build a realistic budget for your actual city, not the optimistic one your agent gave.</li>
                        <li>Understand how rental applications work and what landlords actually look for.</li>
                        <li>Stop losing money on bank fees and transfer costs that most new arrivals don't notice.</li>
                    </ul>
                </article>

                <article class="value-stack__card">
                    <span class="value-stack__index">03</span>
                    <h3>Feel Like You Belong, Not Just Like You're Surviving</h3>
                    <ul class="value-stack__list">
                        <li>Find your community faster so weekends don't feel isolating.</li>
                        <li>Understand the unwritten rules of Australian workplace culture.</li>
                        <li>Get answers that apply to your actual situation, not generic advice.</li>
                    </ul>
                </article>
            </div>

            <div class="value-stack__cta">
                <div class="value-stack__cta-copy">
                    <p>Not sure what you need yet?</p>
                    <h3>Schedule a free 20-minute clarity call.</h3>
                </div>
                <button class="button button--large" type="button" data-open-lead-modal>Book Your Free Clarity Call</button>
            </div>
        </div>
    </section>

    <section class="section testimonial-band">
        <div class="container testimonial-band__shell">
            <div class="section-heading testimonial-band__heading">
                <div class="testimonial-band__heading-top">
                    <p class="testimonial-band__eyebrow">Testimonials</p>
                    <h2>What New Arrivals Want Help With Most</h2>
                </div>
                <p>These sample testimonials show the kind of problems SettleANZ is designed to solve in the first year, from paperwork and budgeting to feeling settled socially and professionally.</p>
            </div>

            <div class="testimonial-band__carousel" data-testimonial-carousel data-autoplay-interval="4500">
                <div class="testimonial-band__controls">
                    <button class="testimonial-band__control testimonial-band__control--prev" type="button" data-testimonial-prev aria-label="Previous testimonials">‹</button>
                    <button class="testimonial-band__control testimonial-band__control--next" type="button" data-testimonial-next aria-label="Next testimonials">›</button>
                </div>

                <div class="testimonial-band__viewport" data-testimonial-track tabindex="0" aria-label="Testimonials carousel">
                <article class="testimonial-band__card">
                    <span class="testimonial-band__icon" aria-hidden="true">&ldquo;</span>
                    <p class="testimonial-band__quote">&ldquo;I was overwhelmed by Medicare, TFN, and rental paperwork in my first month in Melbourne. The guidance helped me do things in the right order, and that alone saved me weeks of stress.&rdquo;</p>

                    <div class="testimonial-band__footer">
                        <div class="testimonial-band__media">
                            <img src="{{ asset('media/testimonials/T1.webp') }}" alt="Portrait of Aisha Rahman">
                        </div>
                        <div class="testimonial-band__meta">
                            <span class="testimonial-band__name">Aisha Rahman</span>
                            <span class="testimonial-band__role">Graduate Student, Melbourne</span>
                        </div>
                    </div>
                </article>

                <article class="testimonial-band__card">
                    <span class="testimonial-band__icon" aria-hidden="true">&ldquo;</span>
                    <p class="testimonial-band__quote">&ldquo;The budgeting and banking advice was the biggest eye-opener for me. I finally understood where my money was disappearing and what to set up first after landing in Sydney.&rdquo;</p>

                    <div class="testimonial-band__footer">
                        <div class="testimonial-band__media">
                            <img src="{{ asset('media/testimonials/T2.webp') }}" alt="Portrait of Daniel Kim">
                        </div>
                        <div class="testimonial-band__meta">
                            <span class="testimonial-band__name">Daniel Kim</span>
                            <span class="testimonial-band__role">Software Engineer, Sydney</span>
                        </div>
                    </div>
                </article>

                <article class="testimonial-band__card">
                    <span class="testimonial-band__icon" aria-hidden="true">&ldquo;</span>
                    <p class="testimonial-band__quote">&ldquo;I didn't need more generic migration content. I needed someone to explain what actually mattered in Brisbane once I arrived, especially around school options, everyday admin, and avoiding expensive mistakes.&rdquo;</p>

                    <div class="testimonial-band__footer">
                        <div class="testimonial-band__media">
                            <img src="{{ asset('media/testimonials/T3.webp') }}" alt="Portrait of Priya Menon">
                        </div>
                        <div class="testimonial-band__meta">
                            <span class="testimonial-band__name">Priya Menon</span>
                            <span class="testimonial-band__role">Parent Relocating with Family, Brisbane</span>
                        </div>
                    </div>
                </article>

                <article class="testimonial-band__card">
                    <span class="testimonial-band__icon" aria-hidden="true">&ldquo;</span>
                    <p class="testimonial-band__quote">&ldquo;What helped most was feeling less alone. The advice around community, workplace culture, and what to expect socially made the move feel manageable instead of constantly uncertain.&rdquo;</p>

                    <div class="testimonial-band__footer">
                        <div class="testimonial-band__media">
                            <img src="{{ asset('media/testimonials/T4.webp') }}" alt="Portrait of Mateo Silva">
                        </div>
                        <div class="testimonial-band__meta">
                            <span class="testimonial-band__name">Mateo Silva</span>
                            <span class="testimonial-band__role">Marketing Specialist, Perth</span>
                        </div>
                    </div>
                </article>
                </div>
            </div>

        </div>
    </section>
    <section id="guides" class="section section--white">
        <div class="container">
            <div class="section-heading"><h2 style="color: #0b7a75; max-width: 50ch;">Start Here, Our Most-Read Guides</h2></div>
            <div class="blog-grid blog-grid--v2" data-reveal-stagger="home-guides">
                @foreach ($latestPosts as $post)
                    <article class="blog-card blog-card--v2 home-guides-card" data-reveal-stagger-item data-reveal-stagger-index="{{ $loop->index }}">
                        <a class="blog-card__media-link" href="{{ route('blog.show', $post->slug) }}">
                            @if (!empty($post->image))
                                <img class="blog-card__image blog-card__image--file" src="{{ $post->image_url }}" alt="{{ $post->title }}">
                            @else
                                <div class="blog-card__image {{ $post->image_class }}" aria-hidden="true"></div>
                            @endif
                        </a>
                        <div class="blog-card__body blog-card__body--v2">
                            <p class="blog-card__tag">{{ $post->category }}</p>
                            <h3><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h3>
                            <p class="blog-card__excerpt">{{ $post->excerpt }}</p>
                            <div class="blog-card__footer">
                                <div class="blog-card__meta">
                                    <span>{{ $post->author_name }}</span>
                                    @if (!empty($post->reading_time))
                                        <span>{{ $post->reading_time }}</span>
                                    @endif
                                    <span>{{ optional($post->published_at)->format('F j, Y') }}</span>
                                </div>
                                <a class="text-link blog-card__read" href="{{ route('blog.show', $post->slug) }}">Read article</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="section-cta-center"><a class="button button--outline-accent button--large" href="{{ route('blog.index') }}">Browse all guides</a></div>
        </div>
    </section>

    <section id="partners" class="partner-strip">
        <div class="container">
            <div class="partner-strip__viewport">
                <div class="partner-strip__logos" aria-label="Trusted partner logos">
                    <div class="partner-strip__group">
                        <div class="partner-strip__logo"><img src="{{ asset('media/partners/logos/wise.png') }}" alt="Wise"></div>
                        <div class="partner-strip__logo partner-strip__logo--large"><img src="{{ asset('media/partners/logos/safetywing.png') }}" alt="SafetyWing"></div>
                        <div class="partner-strip__logo"><img src="{{ asset('media/partners/logos/Booking.com.png') }}" alt="Booking.com"></div>
                        <div class="partner-strip__logo partner-strip__logo--large"><img src="{{ asset('media/partners/logos/cigna.png') }}" alt="Cigna"></div>
                        <div class="partner-strip__logo"><img src="{{ asset('media/partners/logos/OFX.png') }}" alt="OFX"></div>
                    </div>
                    <div class="partner-strip__group" aria-hidden="true">
                        <div class="partner-strip__logo"><img src="{{ asset('media/partners/logos/wise.png') }}" alt=""></div>
                        <div class="partner-strip__logo partner-strip__logo--large"><img src="{{ asset('media/partners/logos/safetywing.png') }}" alt=""></div>
                        <div class="partner-strip__logo"><img src="{{ asset('media/partners/logos/Booking.com.png') }}" alt=""></div>
                        <div class="partner-strip__logo partner-strip__logo--large"><img src="{{ asset('media/partners/logos/cigna.png') }}" alt=""></div>
                        <div class="partner-strip__logo"><img src="{{ asset('media/partners/logos/OFX.png') }}" alt=""></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="lead-strip" class="lead-strip">
        <div class="container lead-strip__inner">
            <div class="lead-strip__copy"><h2>Get the Free SettleANZ Starter Guide</h2><p>Your first 90 days in Australia or New Zealand, step by step. Straight to your inbox.</p></div>
            <form class="lead-strip__form" method="POST" action="{{ route('lead-capture.store') }}">
                @csrf
                <input type="hidden" name="form_type" value="inline-guide-strip">
                <input type="hidden" name="source_page" value="homepage-guide-strip">
                <label><span class="sr-only">First Name</span><input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="First Name" required></label>
                <label><span class="sr-only">Email Address</span><input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address" required></label>
                <button class="button button--large" type="submit">Send It</button>
            </form>
        </div>
    </section>

    <style>
        .country-acknowledgement {
            background: #f7faf9;
            border-top: 1px solid rgba(10, 107, 109, 0.12);
            padding: clamp(2rem, 5vw, 3.75rem) 0;
        }

        .country-acknowledgement__inner {
            position: relative;
            width: 100%;
            padding: clamp(1.35rem, 3vw, 2rem) clamp(1.2rem, 3vw, 2.25rem);
            border-left: 5px solid var(--cta-accent);
            background: #ffffff;
            box-shadow: 0 12px 32px rgba(15, 23, 42, 0.07);
        }

        .country-acknowledgement__label {
            margin: 0 0 0.65rem;
            color: var(--primary-dark);
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 0.88rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .country-acknowledgement p {
            max-width: 100%;
            margin: 0;
            color: #394953;
            font-size: clamp(1rem, 1.5vw, 1.12rem);
            line-height: 1.8;
        }

        @media (max-width: 767px) {
            .country-acknowledgement {
                padding: 2rem 0;
            }

            .country-acknowledgement__inner {
                border-left-width: 4px;
                box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
            }
        }
    </style>

    <section class="country-acknowledgement" aria-labelledby="country-acknowledgement-title">
        <div class="container">
            <div class="country-acknowledgement__inner">
                <h2 id="country-acknowledgement-title" class="country-acknowledgement__label">Acknowledgement of Country</h2>
                <p>SettleANZ acknowledges the Traditional Custodians of the lands on which we operate throughout Australia. We pay our respects to Elders past, present, and emerging. We extend that respect to all Aboriginal and Torres Strait Islander peoples today.</p>
            </div>
        </div>
    </section>
@endsection




























