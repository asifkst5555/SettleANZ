@extends('layouts.app')

@section('page_styles')
    <style>
        .contact-page-final {
            background: #f5f5f2;
        }

        .contact-page-final__hero {
            background: #108380;
            padding: 0.3rem 0 0;
        }

        .contact-page-final__hero-wrap {
            display: grid;
            grid-template-columns: minmax(0, 0.92fr) minmax(320px, 1.08fr);
            align-items: end;
            gap: 1.2rem;
        }

        .contact-page-final__hero-copy {
            padding-bottom: 5rem;
        }

        .contact-page-final__hero .eyebrow,
        .contact-page-final__hero h1,
        .contact-page-final__hero p {
            color: #fff;
        }

        .contact-page-final__hero h1 {
            margin: 0.5rem 0 0.9rem;
            font-size: clamp(2.25rem, 4.2vw, 3.55rem);
            line-height: 0.98;
            letter-spacing: -0.04em;
        }

        .contact-page-final__hero p {
            max-width: 30ch;
            margin: 0;
            color: rgba(255, 255, 255, 0.9);
        }

        .contact-page-final__hero-art img {
            display: block;
            width: min(100%, 784px);
            margin-left: auto;
            height: auto;
        }

        .contact-page-final__body {
            padding: 1.35rem 0 3.4rem;
        }

        .contact-page-final__layout {
            display: grid;
            grid-template-columns: minmax(280px, 0.93fr) minmax(0, 1.07fr);
            gap: 1.35rem;
            align-items: stretch;
        }

        .contact-page-final__left {
            display: grid;
            grid-template-rows: repeat(2, minmax(0, 1fr));
            gap: 0.8rem;
            height: 100%;
        }

        .contact-page-final__panel {
            padding: 32px;
            border: var(--border-card, 1px solid rgba(16, 88, 98, 0.08));
            border-radius: var(--radius-card, 18px);
            background: #fff;
            box-shadow: var(--shadow-card, 0 10px 30px rgba(10, 35, 45, 0.04));
            height: 100%;
        }

        .contact-page-final__panel-head {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            margin-bottom: 0.7rem;
        }

        .contact-page-final__panel-badge {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            border-radius: 13px;
            background: linear-gradient(180deg, #eff9f8 0%, #dff4f2 100%);
            color: #136f77;
        }

        .contact-page-final__panel-badge svg {
            width: 20px;
            height: 20px;
        }

        .contact-page-final__left h2 {
            margin: 0 0 0.6rem;
            color: #156571;
            font-size: clamp(2rem, 3.1vw, 2.55rem);
            line-height: 1.03;
        }

        .contact-page-final__left p {
            margin: 0;
            color: var(--body-text);
        }

        .contact-page-final__contact-points {
            display: grid;
            gap: 0.28rem;
            margin: 0;
            padding: 0;
            list-style: none;
            font-size: 0.96rem;
            color: var(--body-text);
        }

        .contact-page-final__contact-points a {
            color: var(--primary-dark);
            font-weight: 700;
        }

        .contact-page-final__socials {
            display: flex;
            gap: 0.35rem;
            margin-top: 0.6rem;
            margin-bottom: 1.05rem;
        }

        .contact-page-final__socials a {
            width: 34px;
            height: 34px;
            display: grid;
            place-items: center;
            border: 1px solid rgba(18, 96, 101, 0.16);
            border-radius: 999px;
            background: #edf2f3;
            color: #60737b;
            text-decoration: none;
            transition: color 0.2s, background 0.2s;
        }
        .contact-page-final__socials a:hover {
            color: #0b7a75;
            background: #ffffff;
        }
        .contact-page-final__socials a svg {
            width: 16px;
            height: 16px;
        }

        .contact-page-final__listing {
            margin-top: 0;
        }

        .contact-page-final__listing p {
            margin-top: 0.4rem;
            margin-bottom: 0.9rem;
        }

        .contact-page-final__form-card {
            padding: 32px;
            border: var(--border-card, 1px solid rgba(16, 88, 98, 0.08));
            border-radius: var(--radius-card, 18px);
            background: #fff;
            box-shadow: var(--shadow-card, 0 10px 30px rgba(10, 35, 45, 0.04));
            width: 100%;
            max-width: none;
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: visible !important;
        }

        .contact-page-final__form-card h2 {
            margin: 0 0 0.7rem;
            text-align: left;
            color: #156571;
            font-size: clamp(2rem, 3.2vw, 2.6rem);
            line-height: 1.04;
        }

        .contact-page-final__form {
            display: grid;
            gap: 20px; /* Field spacing 20px */
            flex: 1;
        }

        .contact-page-final__row {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px; /* Field spacing 20px */
        }

        .contact-page-final__form label > span {
            display: block;
            margin-bottom: 8px; /* Label spacing 8px */
            color: #35535d;
            font-size: 0.76rem;
            font-weight: 700;
        }

        .contact-page-final__form input,
        .contact-page-final__form select:not(.pro-select-native),
        .contact-page-final__form textarea {
            width: 100%;
            padding: 0.62rem 0.66rem;
            border: 1px solid rgba(16, 88, 98, 0.16);
            border-radius: var(--radius-input, 8px);
            background: #fff;
            font-size: 0.86rem;
        }

        .contact-page-final__form .pro-select-display {
            position: relative;
            z-index: 1;
        }

        .contact-page-final__form .pro-select-dropdown {
            z-index: 1000;
        }

        .contact-page-final__form textarea {
            resize: vertical;
            min-height: 108px;
            height: 100%;
        }

        .contact-page-final__button {
            width: 100%;
            min-height: 42px;
            justify-content: center;
            font-size: 0.88rem;
            background: linear-gradient(180deg, #ff9a4b 0%, #f17b2f 100%);
            border-color: #f17b2f;
            color: #fff;
        }

        @media (max-width: 1024px) {
            .contact-page-final__hero-wrap,
            .contact-page-final__layout {
                grid-template-columns: 1fr;
            }

            .contact-page-final__hero-copy {
                text-align: center;
                margin-inline: auto;
            }

            .contact-page-final__hero p,
            .contact-page-final__hero-art img {
                margin-inline: auto;
            }

            .contact-page-final__form-card {
                max-width: none;
                height: auto;
            }

            .contact-page-final__left {
                grid-template-rows: auto;
                height: auto;
            }
        }

        @media (max-width: 767px) {
            .contact-page-final__hero {
                padding: 0.3rem 0 0 !important;
            }

            .contact-page-final__hero-wrap {
                gap: 0.4rem !important;
            }

            .contact-page-final__hero-copy {
                padding: 1.6rem 1.2rem 0.4rem !important;
                text-align: center;
            }

            .contact-page-final__hero-copy p {
                margin-inline: auto;
            }

            .contact-page-final__hero-art {
                margin-bottom: 0 !important;
            }

            .contact-page-final__hero-art img {
                display: block;
                margin-bottom: 0 !important;
                vertical-align: bottom;
            }

            .contact-page-final__body {
                padding-top: 1.4rem;
            }

            .contact-page-final__row {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div class="contact-page-final">
        <section id="top" class="contact-page-final__hero">
            <div class="container">
                <div class="contact-page-final__hero-wrap">
                    <div class="contact-page-final__hero-copy">
                        <p class="eyebrow">Contact us</p>
                        <h1>Get in Touch</h1>
                        <p>Whether you need help settling in, want to list your business, or have a question, we'd love to hear from you.</p>
                    </div>
                    <div class="contact-page-final__hero-art" aria-hidden="true">
                        <img src="{{ asset('media/contact/contact.png') }}" alt="" width="500" height="316">
                    </div>
                </div>
            </div>
        </section>

        <section class="contact-page-final__body">
            <div class="container contact-page-final__layout">
                <aside class="contact-page-final__left">
                    <section class="contact-page-final__panel contact-page-final__info">
                        <div class="contact-page-final__panel-head">
                            <span class="contact-page-final__panel-badge" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 8h16v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8Z"></path><path d="M3 8l9-5 9 5"></path><path d="M8 13h8"></path></svg>
                            </span>
                            <h2>Contact Us</h2>
                        </div>
                        <ul class="contact-page-final__contact-points">
                            <li><strong>Address:</strong> P.O. Box 446, Gungahlin ACT 2912</li>
                            <li><strong>Phone:</strong> <a href="tel:+61416874058">+61 416 874 058</a></li>
                            <li><strong>Email:</strong> <a href="mailto:{{ $settings['contact_email'] }}">{{ $settings['contact_email'] }}</a></li>
                            <li><strong>WhatsApp:</strong> <a href="{{ $settings['contact_whatsapp'] }}" target="_blank" rel="noreferrer">Click to chat</a></li>
                            <li>{{ $settings['contact_response_time'] }}</li>
                        </ul>
                        <div class="contact-page-final__socials" aria-label="Social links">
                            <a href="{{ $settings['social_facebook'] }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12S0 5.446 0 12.073c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                            <a href="{{ $settings['social_instagram'] }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></a>
                            <a href="{{ $settings['social_youtube'] }}" target="_blank" rel="noopener noreferrer" aria-label="YouTube"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg></a>
                            <a href="{{ $settings['social_tiktok'] }}" target="_blank" rel="noopener noreferrer" aria-label="TikTok"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg></a>
                            <a href="{{ $settings['social_reddit'] }}" target="_blank" rel="noopener noreferrer" aria-label="Reddit"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0zm5.01 4.744c.688 0 1.25.561 1.25 1.249a1.25 1.25 0 0 1-2.498.056l-2.597-.547-.8 3.747c1.824.07 3.48.632 4.674 1.488.308-.309.73-.491 1.207-.491.968 0 1.754.786 1.754 1.754 0 .696-.397 1.296-1.007 1.586a.23.23 0 0 0-.012.09c0 2.45-3.08 4.442-6.874 4.442-3.795 0-6.874-1.994-6.874-4.442 0-.03.001-.06.002-.09-.611-.29-1.008-.89-1.008-1.585 0-.968.786-1.754 1.754-1.754.464 0 .889.176 1.196.467a7.99 7.99 0 0 1 4.86-1.464l.872-4.051a.155.155 0 0 1 .172-.119l2.707.571a1.248 1.248 0 0 1 .936-1.066zM7.5 12.338c-.702 0-1.27.568-1.27 1.27 0 .702.568 1.27 1.27 1.27a1.27 1.27 0 0 0 1.27-1.27c0-.702-.568-1.27-1.27-1.27zm6.732 0c-.702 0-1.27.568-1.27 1.27 0 .702.568 1.27 1.27 1.27 1.157 0 2.095-.939 2.095-2.096 0-.256-.046-.503-.13-.73-.257-.282-.628-.456-1.036-.456l.001.001zm-3.363 3.659c-.58 0-1.12.18-1.57.502-.45-.322-.99-.502-1.57-.502-.371 0-.712.073-1.02.203.228 1.144 1.332 2.002 2.659 2.002 1.326 0 2.431-.858 2.66-2.002-.309-.13-.65-.203-1.02-.203l.001.001z"/></svg></a>
                            <a href="{{ $settings['social_pinterest'] }}" target="_blank" rel="noopener noreferrer" aria-label="Pinterest"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.372 0 12c0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.936 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738a.36.36 0 01.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.632-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12 24c6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z"/></svg></a>
                        </div>
                    </section>

                    <section class="contact-page-final__panel contact-page-final__listing">
                        <h2>Want to reach thousands of expats?</h2>
                        <p>List your business in the SettleANZ Directory and be seen by new arrivals actively looking for your services.</p>
                        <a class="button button--large contact-page-final__button" href="{{ $settings['directory_apply_link'] }}">Apply for a Directory Listing</a>
                    </section>
                </aside>

                <section class="contact-page-final__form-card">
                    <h2 style="text-align: center;">Send Us a Message</h2>
                    <form class="lead-form contact-page-final__form" method="POST" action="{{ route('lead-capture.store') }}" novalidate>
                        @csrf
                        <input type="hidden" name="form_type" value="contact-page">
                        <input type="hidden" name="source_page" value="contact-page">

                        <div class="contact-page-final__row">
                            <label>
                                <span>First Name</span>
                                <input type="text" name="first_name" required>
                            </label>
                            <label>
                                <span>Last Name</span>
                                <input type="text" name="last_name" required>
                            </label>
                        </div>

                        <div class="contact-page-final__row">
                            <label>
                                <span>Email</span>
                                <input type="email" name="email" required>
                            </label>
                            <label>
                                <span>Phone</span>
                                <input type="tel" name="phone" placeholder="+61 400 000 000">
                            </label>
                        </div>

                        <label>
                            <span>Subject</span>
                            <select class="pro-select" name="subject" required>
                                <option value="" disabled selected hidden>Select subject</option>
                                @foreach ($contactSubjects as $subject)
                                    <option value="{{ $subject }}">{{ $subject }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label>
                            <span>Message</span>
                            <textarea name="message" rows="6" placeholder="Tell us how we can help." required></textarea>
                        </label>

                        <button class="button button--large button--full contact-page-final__button" type="submit" data-submit-btn>Send Message</button>
                        <p id="contact-form-success" class="async-form-status" hidden></p>
                    </form>
                </section>
            </div>
        </section>
    </div>

    <!-- Form Submission Modal -->
    <div class="form-modal-overlay" id="formModalOverlay" hidden>
        <div class="form-modal">
            <div class="form-modal__loading" id="formModalLoading">
                <div class="form-modal__spinner"></div>
                <h3>Sending your message...</h3>
                <p>Please wait while we process your request.</p>
            </div>
            <div class="form-modal__success" id="formModalSuccess" hidden>
                <div class="form-modal__icon form-modal__icon--success">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <h3>Message Sent!</h3>
                <p>Thank you for contacting us. We'll get back to you within 24 hours.</p>
                <button class="button button--large" type="button" onclick="closeFormModal()">Got it</button>
            </div>
            <div class="form-modal__error" id="formModalError" hidden>
                <div class="form-modal__icon form-modal__icon--error">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </div>
                <h3>Oops! Something went wrong</h3>
                <p id="formModalErrorText">Please try again later.</p>
                <button class="button button--large" type="button" onclick="closeFormModal()">Try Again</button>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
.form-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}
.form-modal-overlay.is-visible {
    opacity: 1;
    visibility: visible;
}
.form-modal {
    background: #fff;
    border-radius: 20px;
    padding: 2.5rem;
    max-width: 400px;
    width: 90%;
    text-align: center;
    transform: scale(0.9) translateY(20px);
    transition: transform 0.3s ease;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
}
.form-modal-overlay.is-visible .form-modal {
    transform: scale(1) translateY(0);
}
.form-modal__spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #e6f4f3;
    border-top-color: #0b7a75;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 1.5rem;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}
.form-modal h3 {
    color: #065e5b;
    font-size: 1.5rem;
    margin: 0 0 0.5rem;
    font-weight: 700;
}
.form-modal p {
    color: #607080;
    font-size: 1rem;
    margin: 0 0 1.5rem;
}
.form-modal__icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.25rem;
}
.form-modal__icon svg {
    width: 36px;
    height: 36px;
}
.form-modal__icon--success {
    background: linear-gradient(135deg, #e6f4f3 0%, #d0ede9 100%);
    color: #0b7a75;
}
.form-modal__icon--error {
    background: linear-gradient(135deg, #fee 0%, #fdd 100%);
    color: #c53030;
}
.form-modal .button {
    background: linear-gradient(180deg, #0b7a75 0%, #065e5b 100%);
    border-color: #065e5b;
    color: #fff;
    padding: 0.9rem 2rem;
    font-size: 1rem;
}
</style>
@endpush

@push('scripts')
<script>
function showFormModal(type, message) {
    var overlay = document.getElementById('formModalOverlay');
    var loading = document.getElementById('formModalLoading');
    var success = document.getElementById('formModalSuccess');
    var error = document.getElementById('formModalError');
    var errorText = document.getElementById('formModalErrorText');

    loading.hidden = true;
    success.hidden = true;
    error.hidden = true;

    if (type === 'loading') {
        loading.hidden = false;
    } else if (type === 'success') {
        success.hidden = false;
    } else if (type === 'error') {
        error.hidden = false;
        if (message) errorText.textContent = message;
    }

    overlay.hidden = false;
    setTimeout(function() {
        overlay.classList.add('is-visible');
    }, 10);
}

function closeFormModal() {
    var overlay = document.getElementById('formModalOverlay');
    overlay.classList.remove('is-visible');
    setTimeout(function() {
        overlay.hidden = true;
    }, 300);
}

document.addEventListener('DOMContentLoaded', function() {
    var form = document.querySelector('.lead-form.contact-page-final__form');
    if (!form) return;

    var submitBtn = form.querySelector('[data-submit-btn]');
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        showFormModal('loading');

        var formData = new FormData(form);
        var originalBtnText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Sending...';

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(function(response) {
            if (!response.ok) {
                return response.json().then(function(data) {
                    throw new Error(data.message || 'Error');
                });
            }
            return response.json();
        })
        .then(function(data) {
            form.reset();
            showFormModal('success');
        })
        .catch(function(error) {
            showFormModal('error', error.message || 'Something went wrong. Please try again.');
        })
        .finally(function() {
            submitBtn.disabled = false;
            submitBtn.textContent = originalBtnText;
        });
    });
});
</script>
@endpush

