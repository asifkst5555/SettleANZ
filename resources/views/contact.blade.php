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
            padding: 1.15rem;
            border: 1px solid rgba(13, 79, 89, 0.1);
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 18px 36px rgba(15, 23, 42, 0.08);
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
            width: 30px;
            height: 30px;
            display: grid;
            place-items: center;
            border: 1px solid rgba(18, 96, 101, 0.16);
            border-radius: 999px;
            background: #edf2f3;
            color: #60737b;
            text-decoration: none;
            font-size: 0.62rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .contact-page-final__listing {
            margin-top: 0;
        }

        .contact-page-final__listing p {
            margin-top: 0.4rem;
            margin-bottom: 0.9rem;
        }

        .contact-page-final__form-card {
            padding: 1rem;
            border: 1px solid rgba(13, 79, 89, 0.12);
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.06);
            width: 100%;
            max-width: none;
            height: 100%;
            display: flex;
            flex-direction: column;
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
            gap: 0.8rem;
            flex: 1;
        }

        .contact-page-final__row {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.7rem;
        }

        .contact-page-final__form label > span {
            display: block;
            margin-bottom: 0.35rem;
            color: #35535d;
            font-size: 0.76rem;
            font-weight: 700;
        }

        .contact-page-final__form input,
        .contact-page-final__form select,
        .contact-page-final__form textarea {
            width: 100%;
            padding: 0.62rem 0.66rem;
            border: 1px solid rgba(16, 88, 98, 0.16);
            border-radius: 6px;
            background: #fff;
            font-size: 0.86rem;
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
                        <img src="{{ asset('media/contact/contact.png') }}" alt="">
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
                            <a href="{{ $settings['social_linkedin'] }}" aria-label="LinkedIn">in</a>
                            <a href="{{ $settings['social_instagram'] }}" aria-label="Instagram">ig</a>
                            <a href="{{ $settings['social_facebook'] }}" aria-label="Facebook">f</a>
                            <a href="{{ $settings['social_pinterest'] }}" aria-label="Pinterest">p</a>
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
                                <option value="">Select subject</option>
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

