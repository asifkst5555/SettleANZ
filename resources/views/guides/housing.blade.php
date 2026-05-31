@extends('layouts.app')

@section('content')
    <section id="top" class="guide-hero guide-hero--housing">
        <div class="container guide-hero__inner guide-hero__inner--split">
            <div>
                <p class="eyebrow">Housing guide</p>
                <h1>Finding a Home in Australia as an Expat</h1>
                <p class="guide-hero__copy">From short-term serviced apartments to long-term rentals, here's what you need to know before you sign anything.</p>
                <div class="guide-hero__actions">
                    <a class="button button--large" href="#housing-booking">Talk to a Relocation Expert</a>
                </div>
            </div>
            <aside id="housing-booking" class="housing-booking-card housing-booking-card--desktop">
                <p class="housing-booking-card__eyebrow">Free 15-minute call</p>
                <h2>{{ $bookingLabel }}</h2>
                <p>Speak with a relocation consultant about suburb options, short-term accommodation, rental paperwork, and what to book before you arrive.</p>
                <button class="button button--large button--full" type="button" data-open-lead-modal>Book a Free Relocation Call</button>
            </aside>
        </div>
    </section>

    <section class="guide-page housing-page">
        <div class="container housing-layout">
            <div class="housing-content">
                <aside class="housing-booking-mobile">
                    <div class="housing-booking-card">
                        <p class="housing-booking-card__eyebrow">Free 15-minute call</p>
                        <h2>{{ $bookingLabel }}</h2>
                        <p>Quick relocation help for short stays, leases, suburb choices, and your first home setup.</p>
                        <button class="button button--large button--full" type="button" data-open-lead-modal>Book a Free Relocation Call</button>
                    </div>
                </aside>

                <section class="guide-block guide-block--sand">
                    <h2>Short-Term vs Long-Term Accommodation</h2>
                    <p>Before you land, convenience usually matters more than perfection. A short-term stay gives you breathing room to inspect suburbs in person, understand commute times, and avoid signing a long lease based only on photos.</p>
                    <p>Once you're settled, a longer-term rental can make more sense financially. The trick is knowing when to switch, and not locking yourself into the wrong area too early.</p>
                    <ul class="guide-list">
                        <li>Use short-term accommodation for arrival week logistics and early suburb research.</li>
                        <li>Move into a long-term rental only after testing commute, transport, and daily costs.</li>
                        <li>Budget separately for bond, furniture, and utility setup if you plan to rent unfurnished.</li>
                    </ul>
                </section>

                <section class="guide-block guide-block--white">
                    <h2>How Australian Renting Works</h2>
                    <p>Australian rentals move quickly, and applications often depend on documentation, references, income clarity, and timing. Bonds, lease lengths, and inspection expectations can also feel unfamiliar if you've rented under a different system before.</p>
                    <p>Keep your documents ready in advance (including Australian <a class="text-link" href="/banking">bank statements</a>) and expect to inspect, compare, and apply quickly in competitive suburbs.</p>
                    <div class="guide-cta-box guide-cta-box--inline">
                        <h3>Need a short-stay option first?</h3>
                        <p>Use a serviced apartment or flexible stay partner while you learn the market and attend inspections.</p>
                        <a class="text-link" href="https://www.booking.com" target="_blank" rel="noreferrer">Browse serviced apartments</a>
                    </div>
                </section>

                <section class="guide-block guide-block--sand">
                    <h2>Best Cities for Expats</h2>
                    <p>Each city gives you a different trade-off between rent, transport, lifestyle, and job access. Start by matching the city to your work pattern, budget, and how quickly you need to feel settled.</p>
                    <div class="city-grid">
                        <article class="city-card">
                            <h3>Sydney</h3>
                            <p>Best for large job markets and strong transport links, but expect high rent and faster competition.</p>
                        </article>
                        <article class="city-card">
                            <h3>Melbourne</h3>
                            <p>Popular for culture and inner-suburb variety, with a wide spread of rental styles and commuting patterns.</p>
                        </article>
                        <article class="city-card">
                            <h3>Brisbane</h3>
                            <p>Often attractive for lifestyle and climate, though availability and suburb differences still matter a lot.</p>
                        </article>
                        <article class="city-card">
                            <h3>Perth</h3>
                            <p>Useful for a slower pace and distinct job pockets, with housing considerations that vary sharply by area.</p>
                        </article>
                        <article class="city-card">
                            <h3>Auckland</h3>
                            <p>Relevant for the broader SettleANZ audience and useful when comparing cross-market relocation options.</p>
                        </article>
                    </div>
                </section>

                <section class="guide-block guide-block--white">
                    <h2>What to Avoid</h2>
                    <p>Most newcomer housing mistakes come from rushing. People overcommit before understanding the suburb, underestimate setup costs, or mistake a polished listing for a low-risk rental.</p>
                    <ul class="guide-list">
                        <li>Do not commit to a long lease before testing the commute in real conditions.</li>
                        <li>Do not ignore setup costs like bond, internet, furniture, and transport.</li>
                        <li>Do not rely on suburb reputation alone; inspect streets and transport links yourself if possible.</li>
                        <li>Do not assume every furnished stay is good value just because it looks flexible.</li>
                    </ul>
                </section>

                <section class="guide-block guide-block--sand">
                    <h2>Featured Relocation Partners</h2>
                    <p>If you want practical support rather than doing everything alone, a relocation partner can help with suburb matching, property search support, local orientation, and arrival planning.</p>
                    <div class="partner-card-grid">
                        @foreach ($featuredPartners as $partner)
                            <article class="partner-card">
                                <div class="partner-card__logo" aria-hidden="true">{{ strtoupper(substr($partner['name'], 0, 2)) }}</div>
                                <h3>{{ $partner['name'] }}</h3>
                                <p>{{ $partner['description'] }}</p>
                                <button class="button button--small" type="button" data-open-lead-modal>{{ $partner['cta'] }}</button>
                            </article>
                        @endforeach
                    </div>
                </section>

                <section class="guide-block guide-block--white">
                    <h2>Next Steps</h2>
                    <p>With housing sorted, set up your finances with the <a class="text-link" href="/banking">Banking Guide</a>. If you need visa assistance, visit our <a class="text-link" href="/migration-services">Migration Services</a> page.</p>
                </section>
            </div>
        </div>
    </section>
@endsection
