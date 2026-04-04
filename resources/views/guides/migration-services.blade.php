@extends('layouts.app')

@section('content')
    <section id="top" class="guide-hero guide-hero--migration">
        <div class="container guide-hero__inner guide-hero__inner--split">
            <div>
                <p class="eyebrow">Migration services</p>
                <h1>Australian Visa Help for Expats &amp; Migrants</h1>
                <p class="guide-hero__copy">Navigating Australian immigration is complex. Connect with a registered migration agent who specialises in expat visa pathways.</p>
                <div class="guide-hero__actions"><a class="button button--large" href="#migration-directory">Find a Migration Agent</a></div>
            </div>
        </div>
    </section>

    <section class="guide-page migration-page">
        <div class="container migration-layout migration-layout--full">
            <div class="migration-content">
                <section class="guide-block guide-block--white">
                    <h2>Overview of Common Visa Types</h2>
                    <p>Most people coming to Australia start by narrowing down the visa pathway that best matches their purpose and timing. This page keeps that overview practical and simple so visitors can move quickly toward professional help when they need it.</p>
                    <div class="visa-type-grid">
                        @foreach ($visaTypes as $visaType)
                            <article class="visa-type-card"><h3>{{ $visaType }}</h3><p>Useful as a starting point when comparing eligibility, timing, and the kind of migration advice you may need next.</p></article>
                        @endforeach
                    </div>
                </section>

                <section class="guide-block guide-block--sand">
                    <h2>Why Use a Registered Migration Agent</h2>
                    <p>A registered migration agent helps you avoid choosing the wrong pathway, missing documentation, or relying on generic advice that does not fit your case. The value is not only paperwork; it is knowing which steps actually matter for your situation.</p>
                    <p>This section is meant to build trust, not pressure. For many people, the right next step is simply a short consultation to understand their options clearly.</p>
                </section>

                <section id="migration-directory" class="guide-block guide-block--white">
                    <h2>Featured Migration Agents</h2>
                    <p>These featured partners are presented in a simple card format so visitors can compare specialisation areas and choose the conversation that fits them best.</p>
                    <div class="partner-card-grid">
                        @foreach ($agents as $agent)
                            <article class="partner-card migration-agent-card migration-agent-card--booking">
                                <div class="partner-card__logo" aria-hidden="true">{{ strtoupper(substr($agent->name, 0, 2)) }}</div>
                                <h3>{{ $agent->name }}</h3>
                                <p class="migration-agent-card__specialisation">{{ $agent->description }}</p>
                                <p class="migration-agent-card__rating">{{ $agent->rating }} / 5 rating</p>
                                <div class="migration-agent-card__actions">
                                    <button class="button button--small" type="button" data-open-booking-modal data-agent-name="{{ $agent->name }}" data-agent-id="{{ $agent->id }}" data-agent-email="{{ $agent->email }}">{{ $settings['migration_cta_label'] }}</button>
                                    @if ($agent->whatsapp)
                                        <a class="text-link" href="{{ $agent->whatsapp }}" target="_blank" rel="noreferrer">Chat on WhatsApp</a>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>

                <section class="guide-block guide-block--sand">
                    <h2>Not sure which visa is right for you?</h2>
                    <p>Fill in your details and one of our migration partners will be in touch within 24 hours.</p>
                    <form class="lead-form migration-form migration-form--pro" method="POST" action="{{ route('lead-capture.store') }}">
                        @csrf
                        <input type="hidden" name="form_type" value="migration-consultation">
                        <input type="hidden" name="source_page" value="migration-services-page">
                        <div class="migration-form__grid migration-form__grid--pro">
                            <div class="migration-form__row">
                                <label class="migration-form__field"><span>First Name</span><input type="text" name="first_name" value="{{ old('first_name') }}" required>@error('first_name')<small>{{ $message }}</small>@enderror</label>
                                <label class="migration-form__field"><span>Last Name</span><input type="text" name="last_name" value="{{ old('last_name') }}" required>@error('last_name')<small>{{ $message }}</small>@enderror</label>
                            </div>
                            <div class="migration-form__row">
                                <label class="migration-form__field"><span>Email</span><input type="email" name="email" value="{{ old('email') }}" required>@error('email')<small>{{ $message }}</small>@enderror</label>
                                <label class="migration-form__field"><span>Phone (optional)</span><input type="text" name="phone" value="{{ old('phone') }}">@error('phone')<small>{{ $message }}</small>@enderror</label>
                            </div>
                            <div class="migration-form__row">
                                <label class="migration-form__field"><span>Current Visa Status</span><select name="current_visa_status" required><option value="">Select one</option><option value="Student" @selected(old('current_visa_status') === 'Student')>Student</option><option value="Working Holiday" @selected(old('current_visa_status') === 'Working Holiday')>Working Holiday</option><option value="Partner" @selected(old('current_visa_status') === 'Partner')>Partner</option><option value="Skilled" @selected(old('current_visa_status') === 'Skilled')>Skilled</option><option value="Visitor" @selected(old('current_visa_status') === 'Visitor')>Visitor</option><option value="Other" @selected(old('current_visa_status') === 'Other')>Other</option></select>@error('current_visa_status')<small>{{ $message }}</small>@enderror</label>
                                <label class="migration-form__field"><span>Country of Citizenship</span><input type="text" name="country_of_citizenship" value="{{ old('country_of_citizenship') }}" required>@error('country_of_citizenship')<small>{{ $message }}</small>@enderror</label>
                            </div>
                            <label class="migration-form__field migration-form__field--full"><span>What do you need help with?</span><textarea name="help_details" rows="5" placeholder="Share a few details about your situation and what you need help with." required>{{ old('help_details') }}</textarea>@error('help_details')<small>{{ $message }}</small>@enderror</label>
                        </div>
                        <input type="hidden" name="goal" value="Migration consultation request">
                        <button class="button button--large button--full" type="submit">Request Free Consultation</button>
                        <small class="migration-form__privacy">Your details are only shared with our verified migration partners. We do not sell your data.</small>
                    </form>
                </section>
            </div>
        </div>
    </section>

    <div class="booking-modal" data-booking-modal hidden>
        <div class="booking-modal__backdrop" data-close-booking-modal></div>
        <div class="booking-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="booking-modal-title">
            <button class="booking-modal__close" type="button" aria-label="Close booking popup" data-close-booking-modal>&times;</button>
            <div class="booking-modal__grid">
                <aside class="booking-modal__aside">
                    <p class="booking-modal__eyebrow">Consultation booking</p>
                    <h2 id="booking-modal-title">Book a Consultation</h2>
                    <p class="booking-modal__agent" data-booking-agent-name>Migration Agent</p>
                    <ul class="booking-modal__summary">
                        <li>30 minute introductory call</li>
                        <li>Video call or phone consultation</li>
                        <li>Best next steps for your visa pathway</li>
                    </ul>
                </aside>
                <div class="booking-modal__form-wrap">
                    <form class="booking-form" method="POST" action="{{ route('lead-capture.store') }}" data-async-form data-success-target="booking-form-success" novalidate>
                        @csrf
                        <input type="hidden" name="form_type" value="consultation-booking">
                        <input type="hidden" name="source_page" value="migration-agent-booking-modal">
                        <input type="hidden" name="goal" value="Book migration consultation">
                        <input type="hidden" name="listing_id" value="" data-booking-agent-id>
                        <input type="hidden" name="listing_name" value="" data-booking-agent-field>
                        <div class="booking-form__split">
                            <label><span>First Name</span><input type="text" name="first_name" required></label>
                            <label><span>Last Name</span><input type="text" name="last_name" required></label>
                        </div>
                        <div class="booking-form__split">
                            <label><span>Email</span><input type="email" name="email" required></label>
                            <label><span>Phone</span><input type="text" name="phone" required></label>
                        </div>
                        <div class="booking-form__split">
                            <label><span>Preferred Date</span><input type="date" name="preferred_date" required></label>
                            <label><span>Preferred Time</span><select name="preferred_time" required><option value="">Select time</option><option value="09:00 AM - 11:00 AM">09:00 AM - 11:00 AM</option><option value="11:00 AM - 01:00 PM">11:00 AM - 01:00 PM</option><option value="02:00 PM - 04:00 PM">02:00 PM - 04:00 PM</option><option value="05:00 PM - 07:00 PM">05:00 PM - 07:00 PM</option></select></label>
                        </div>
                        <label><span>Consultation Format</span><select name="consultation_format" required><option value="">Select format</option><option value="Google Meet">Google Meet</option><option value="Zoom">Zoom</option><option value="Phone Call">Phone Call</option></select></label>
                        <label><span>Notes</span><textarea name="booking_notes" rows="4" placeholder="Tell us briefly what you need help with."></textarea></label>
                        <button class="button button--large button--full" type="submit">Confirm Consultation</button>
                        <p id="booking-form-success" class="async-form-status" hidden></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
