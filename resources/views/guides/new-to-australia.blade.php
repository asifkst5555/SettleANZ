@extends('layouts.app')

@section('content')
    <section id="top" class="guide-hero">
        <div class="container guide-hero__inner">
            <p class="eyebrow">New to Australia guide</p>
            <h1>New to Australia? Here's Everything You Need to Know.</h1>
            <p class="guide-hero__copy">The honest, practical guide from someone who's been through it. Start with the first things to sort before you fly, then work through your first week, healthcare, banking, work, and staying connected.</p>
            <div class="guide-hero__meta">
                <span>{{ $readingTime }}</span>
                <span>Last updated {{ $lastUpdated }}</span>
            </div>
        </div>
    </section>

    <section class="guide-page">
        <div class="container guide-layout">
            <aside class="guide-sidebar">
                <div class="guide-sidebar__panel">
                    <p class="guide-sidebar__title">On this page</p>
                    <nav class="guide-toc" aria-label="Table of contents">
                        @foreach ($tocItems as $item)
                            <a href="#{{ $item['id'] }}">{{ $item['label'] }}</a>
                        @endforeach
                    </nav>
                </div>

                <div class="guide-share" aria-label="Share this guide">
                    <span>Share</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noreferrer">Facebook</a>
                    <a href="https://wa.me/?text={{ urlencode('Useful SettleANZ guide: ' . url()->current()) }}" target="_blank" rel="noreferrer">WhatsApp</a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" rel="noreferrer">LinkedIn</a>
                </div>
            </aside>

            <div class="guide-content">
                <section id="before-you-arrive" class="guide-block guide-block--sand">
                    <h2>Before You Arrive</h2>
                    <p>Give yourself a calmer landing by finishing the essentials before you board. Confirm your visa conditions, decide what health cover you need, lock in your first accommodation, and shortlist a bank so you're not choosing under pressure on day one.</p>
                    <ul class="guide-list">
                        <li>Save digital and printed copies of your passport, visa grant, insurance, and offer letters.</li>
                        <li>Book at least your first one to two weeks of accommodation in an area with simple transport access.</li>
                        <li>Compare bank account options for newcomers so you know what documents each bank will request.</li>
                        <li>Make a first-month budget that includes bond, transport, groceries, and mobile setup.</li>
                    </ul>
                    <div class="guide-cta-box">
                        <h3>Need a smoother start?</h3>
                        <p>Get the SettleANZ starter guide for the key first-week tasks most new arrivals miss.</p>
                        <button class="button button--small" type="button" data-open-lead-modal>Get the starter guide</button>
                    </div>
                </section>

                <section id="first-week" class="guide-block guide-block--white">
                    <h2>First Week Checklist</h2>
                    <p>Your first week is about getting functional fast. Focus on the systems that unlock everything else: a working SIM, your tax identity, Medicare if you're eligible, and a bank account you can actually use for rent and pay.</p>
                    <ul class="guide-list">
                        <li>Buy a SIM with enough data for maps, inspections, and calls.</li>
                        <li>Apply for a Tax File Number as soon as you're eligible.</li>
                        <li>Enrol in Medicare if your visa status allows it.</li>
                        <li>Open your bank account and set up online access right away.</li>
                    </ul>
                </section>

                <section id="culture" class="guide-block guide-block--sand">
                    <h2>Understanding Australian Culture</h2>
                    <p>Australia can feel relaxed on the surface, but the practical culture matters: punctuality counts, direct communication is normal, and people expect you to organise things independently. It's friendly, but not always explained.</p>
                    <p>Approach the first few weeks with curiosity. Ask plain questions, confirm processes in writing when needed, and don't assume systems work the same way they did at home.</p>
                </section>

                <section id="where-to-live" class="guide-block guide-block--white">
                    <h2>Where to Live</h2>
                    <p>Your first suburb choice affects your commute, budget, and stress level more than most people expect. Start with transport access, weekly rent range, and whether you need furnished short-term or a full lease.</p>
                    <p>Use your first stay to inspect areas in person before signing anything longer term. A suburb that looks good online can feel very different once you test the commute.</p>
                    <div class="guide-cta-box guide-cta-box--inline">
                        <h3>Want suburb and rental help?</h3>
                        <p>Read the housing guide for inspections, bonds, lease types, and the questions to ask before applying.</p>
                        <a class="text-link" href="/#guides">Read the full housing guide</a>
                    </div>
                </section>

                <section id="banking-finance" class="guide-block guide-block--sand">
                    <h2>Banking and Finance</h2>
                    <p>Set up a transaction account first, then look at savings, transfer tools, and any fees that matter for international movement. The wrong account can quietly cost you through transfer spreads, ATM charges, or avoidable monthly fees.</p>
                    <ul class="guide-list">
                        <li>Choose a bank with simple newcomer onboarding and good branch or ID support.</li>
                        <li>Check international transfer costs before sending larger amounts.</li>
                        <li>Understand how superannuation works once you're employed.</li>
                    </ul>
                    <div class="guide-cta-box guide-cta-box--inline">
                        <h3>Compare your setup</h3>
                        <p>Our banking content helps you choose accounts, move money, and avoid the common fee traps.</p>
                        <a class="text-link" href="/#guides">Go to the banking guide</a>
                    </div>
                </section>

                <section id="healthcare" class="guide-block guide-block--white">
                    <h2>Healthcare</h2>
                    <p>Healthcare depends heavily on your visa type and whether you're already eligible for Medicare. If you're not covered yet, private or visitor health insurance can protect you from large surprise costs while you settle in.</p>
                    <p>Make this decision early. Many people leave healthcare until something goes wrong, then discover too late that they are not covered the way they assumed.</p>
                    <div class="guide-cta-box guide-cta-box--inline">
                        <h3>Still waiting on Medicare?</h3>
                        <p>Explore trusted health insurance options for migrants who need interim cover during the move.</p>
                        <a class="text-link" href="/#partners">See healthcare partners</a>
                    </div>
                </section>

                <section id="working" class="guide-block guide-block--sand">
                    <h2>Working in Australia</h2>
                    <p>Once you're ready to work, the basics are simple but important: confirm your visa work rights, set up your TFN, understand superannuation, and tailor your CV for the local market. Employers often expect a clear, direct resume rather than an over-detailed one.</p>
                    <ul class="guide-list">
                        <li>Match your resume format to the role and industry, not your home-country default.</li>
                        <li>Keep copies of tax, banking, and identification documents ready for onboarding.</li>
                        <li>Learn the difference between salary, PAYG tax withholding, and super contributions.</li>
                    </ul>
                </section>

                <section id="staying-connected" class="guide-block guide-block--white">
                    <h2>Staying Connected</h2>
                    <p>A reliable mobile plan helps with inspections, job applications, transport, banking verification, and everyday coordination. In the first month, simplicity matters more than chasing the absolute cheapest plan.</p>
                    <p>Choose strong coverage first, then adjust later once you know your suburb, commute, and data needs.</p>
                </section>

                <section id="resources" class="guide-block guide-block--sand">
                    <h2>Useful Contacts and Resources</h2>
                    <p>Keep a short list of official and practical resources nearby so you do not search for them under pressure. That includes Medicare information, tax details, transport apps, migration help, and any settlement services you may need later.</p>
                    <ul class="guide-list">
                        <li>Medicare and health eligibility information</li>
                        <li>Tax File Number application resources</li>
                        <li>Migration services and visa support contacts</li>
                        <li>Directory links for housing, banking, and newcomer setup tools</li>
                    </ul>
                    <div class="guide-cta-box">
                        <h3>Keep the full checklist handy</h3>
                        <p>Get the starter guide in your inbox so you can work through the first 90 days step by step.</p>
                        <button class="button button--small" type="button" data-open-lead-modal>Send me the guide</button>
                    </div>
                </section>
            </div>
        </div>
    </section>
@endsection
