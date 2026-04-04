@extends('layouts.app')

@section('content')
    <section id="top" class="guide-hero guide-hero--banking">
        <div class="container guide-hero__inner">
            <p class="banking-disclosure">Disclosure: Some links on this page may be affiliate links. We may earn a commission at no extra cost to you.</p>
            <p class="eyebrow">Banking guide</p>
            <h1>Banking in Australia as an Expat — The Complete Guide</h1>
            <p class="guide-hero__copy">Open an account before you land, avoid hidden fees, and keep more of your money.</p>
        </div>
    </section>

    <section class="guide-page banking-page">
        <div class="container banking-layout">
            <div class="banking-content">
                <section class="guide-block guide-block--white">
                    <h2>Can You Open an Australian Bank Account Before You Arrive?</h2>
                    <p>Yes, in many cases you can begin the process before landing. Some providers make this far easier than others, especially if you want digital-first onboarding and fewer branch visits once you arrive.</p>
                    <p>The practical goal is simple: land with less admin waiting for you. That means checking ID requirements, when activation happens, and whether the provider supports newcomers smoothly.</p>
                    <div class="guide-cta-box guide-cta-box--inline">
                        <h3>Most practical pre-arrival option</h3>
                        <p>Airwallex stands out for smoother online setup and a cleaner experience for people moving money internationally.</p>
                        <a class="button button--small" href="https://www.airwallex.com" target="_blank" rel="noreferrer">View Airwallex</a>
                    </div>
                </section>

                <section class="guide-block guide-block--sand">
                    <h2>Best Banks for Expats in Australia</h2>
                    <p>Comparison tables are the hero of this page because the right answer depends on how you move money, whether you need branch access, and how much friction you can tolerate during setup.</p>
                    <div class="bank-table-wrap">
                        <table class="bank-table">
                            <thead>
                                <tr>
                                    <th>Bank Name</th>
                                    <th>Monthly Fee</th>
                                    <th>International Transfer Fee</th>
                                    <th>Online Setup</th>
                                    <th>Rating</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bankComparison as $bank)
                                    <tr>
                                        <td>
                                            <strong>{{ $bank['name'] }}</strong>
                                            @if ($bank['recommended'])
                                                <span class="winner-badge">Best for most people</span>
                                            @endif
                                        </td>
                                        <td>{{ $bank['monthly_fee'] }}</td>
                                        <td>{{ $bank['transfer_fee'] }}</td>
                                        <td>{{ $bank['online_setup'] }}</td>
                                        <td>{{ $bank['rating'] }}</td>
                                        <td><a class="table-link" href="https://www.google.com/search?q={{ urlencode($bank['name'] . ' Australia bank account') }}" target="_blank" rel="noreferrer">Visit offer</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="guide-block guide-block--white">
                    <h2>International Money Transfers</h2>
                    <p>Once your account is open, the next cost center is international transfers. This is where spreads, flat fees, and payout speed quietly add up. Specialist transfer tools often outperform traditional banks for usability and pricing.</p>
                    <div class="transfer-tool-grid">
                        @foreach ($transferTools as $tool)
                            <article class="transfer-tool-card">
                                <h3>{{ $tool['name'] }}</h3>
                                <p>{{ $tool['summary'] }}</p>
                                <a class="button button--small" href="https://www.google.com/search?q={{ urlencode($tool['name'] . ' money transfer') }}" target="_blank" rel="noreferrer">{{ $tool['cta'] }}</a>
                            </article>
                        @endforeach
                    </div>
                </section>

                <section class="guide-block guide-block--sand">
                    <h2>Understanding the Australian Tax File Number</h2>
                    <p>Your Tax File Number is central to getting paid correctly and avoiding unnecessary tax complications. It is not a bank feature, but it affects how smoothly your financial setup works once employment begins.</p>
                    <ul class="guide-list">
                        <li>Apply as early as you're eligible after arrival.</li>
                        <li>Keep your TFN secure and only share it where necessary.</li>
                        <li>Use the correct details with your employer and bank-linked records where relevant.</li>
                    </ul>
                </section>

                <section class="guide-block guide-block--white">
                    <h2>Superannuation for Expats</h2>
                    <p>Superannuation is the retirement contribution system tied to your employment in Australia. You do not need to master everything immediately, but you do need to understand that it exists, where it is going, and how it connects to your work setup.</p>
                    <div class="guide-cta-box guide-cta-box--inline">
                        <h3>Need the full work setup context?</h3>
                        <p>Read the newcomer guide section on working in Australia for TFN, pay, and super basics together.</p>
                        <a class="text-link" href="{{ route('guides.new-to-australia') }}#working">Go to working in Australia</a>
                    </div>
                </section>
            </div>

            <aside class="banking-sidebar">
                <div class="banking-sidebar__panel">
                    <p class="guide-sidebar__title">Banking page plan</p>
                    <ul class="guide-list guide-list--compact">
                        <li>Pre-arrival account setup</li>
                        <li>Clean bank comparison table</li>
                        <li>Transfer tools with affiliate actions</li>
                        <li>TFN basics for new arrivals</li>
                        <li>Superannuation explainer</li>
                    </ul>
                </div>
            </aside>
        </div>
    </section>
@endsection
