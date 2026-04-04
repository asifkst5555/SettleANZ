@extends('layouts.app')

@section('content')
<div class="contact-page-shell">
    <section id="top" class="guide-hero contact-hero contact-hero--pro">
        <div class="container contact-hero__layout">
            <div class="contact-hero__content">
                <p class="eyebrow">Contact us</p>
                <h1>Get in Touch</h1>
                <p class="guide-hero__copy">Whether you need help settling in, want to list your business, or have a question, we'd love to hear from you.</p>
            </div>
            <div class="contact-hero__media" aria-hidden="true">
                <div class="contact-hero__image-shell">
                    <img src="{{ asset('media/contact/contact.png') }}" alt="" class="contact-hero__image">
                </div>
            </div>
        </div>
    </section>

    <section class="contact-page contact-page--pro">
        <div class="container contact-layout contact-layout--pro">
            <aside class="contact-sidebar">
                <section class="contact-panel contact-panel--info">
                    <div class="contact-panel__heading">
                        <span class="contact-panel__badge" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 8h16v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8Z"/><path d="M3 8l9-5 9 5"/><path d="M8 13h8"/></svg>
                        </span>
                        <h2>Contact Us</h2>
                    </div>
                    <ul class="contact-points">
                        <li><strong>Email:</strong> <a href="mailto:{{ $settings['contact_email'] }}">{{ $settings['contact_email'] }}</a></li>
                        <li><strong>WhatsApp:</strong> <a href="{{ $settings['contact_whatsapp'] }}" target="_blank" rel="noreferrer">Click to chat</a></li>
                        <li>{{ $settings['contact_response_time'] }}</li>
                    </ul>
                    <div class="contact-socials-pro" aria-label="Social links">
                        <a href="{{ $settings['social_linkedin'] }}" aria-label="LinkedIn">in</a>
                        <a href="{{ $settings['social_instagram'] }}" aria-label="Instagram">ig</a>
                        <a href="{{ $settings['social_facebook'] }}" aria-label="Facebook">f</a>
                        <a href="{{ $settings['social_pinterest'] }}" aria-label="Pinterest">p</a>
                    </div>
                </section>

                <section class="contact-panel contact-panel--cta">
                    <h2>Want to reach thousands of expats?</h2>
                    <p>List your business in the SettleANZ Directory and be seen by new arrivals actively looking for your services.</p>
                    <a class="button button--large contact-cta-button" href="{{ $settings['directory_apply_link'] }}">Apply for a Directory Listing</a>
                </section>
            </aside>

            <div class="contact-form-wrap contact-form-wrap--pro">
                <section class="contact-panel contact-panel--form">
                    <h2>Send Us a Message</h2>
                    <form class="lead-form contact-form contact-form--pro" method="POST" action="{{ route('lead-capture.store') }}" data-async-form data-success-target="contact-form-success" novalidate>
                        @csrf
                        <input type="hidden" name="form_type" value="contact-page">
                        <input type="hidden" name="source_page" value="contact-page">
                        <div class="form-split-grid">
                            <label><span>First Name</span><input type="text" name="first_name" required></label>
                            <label><span>Last Name</span><input type="text" name="last_name" required></label>
                        </div>
                        <div class="form-split-grid">
                            <label><span>Email</span><input type="email" name="email" required></label>
                            <label><span>Subject</span><select name="subject" required><option value="">Select subject</option>@foreach ($contactSubjects as $subject)<option value="{{ $subject }}">{{ $subject }}</option>@endforeach</select></label>
                        </div>
                        <label><span>Message</span><textarea name="message" rows="6" placeholder="Tell us how we can help." required></textarea></label>
                        <button class="button button--large button--full contact-submit-button" type="submit">Send Message</button>
                        <p id="contact-form-success" class="async-form-status" hidden></p>
                    </form>
                </section>
            </div>
        </div>
    </section>
@endsection

