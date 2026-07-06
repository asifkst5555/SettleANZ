@extends('admin.layouts.app')

@php
    $meta = is_array($lead->metadata) ? $lead->metadata : [];

    $formTypeLabel = function (?string $type): string {
        return match ($type) {
            'contact-page' => 'Contact form',
            'package_booking' => 'Service package booking',
            'consultation-booking' => 'Consultation booking',
            'migration-consultation' => 'Migration consultation',
            'directory-enquiry' => 'Directory enquiry',
            'popup' => 'Homepage popup',
            'inline-guide-strip' => 'Homepage guide',
            'general', null, '' => 'Website enquiry',
            default => (string) str($type)->replace(['-', '_'], ' ')->title(),
        };
    };

    $sourcePageLabel = function (?string $page): string {
        if ($page === null || $page === '') {
            return 'Website';
        }

        return match ($page) {
            'contact-page' => 'Contact page',
            'homepage', 'homepage-popup' => 'Homepage',
            'homepage-guide-strip' => 'Homepage guide',
            'settlement-services' => 'Settlement services',
            'migration-services-page' => 'Contact page (legacy)',
            'migration-agent-booking-modal' => 'Migration agents',
            default => (string) str($page)->replace(['-', '_'], ' ')->title(),
        };
    };

    $displayName = $lead->full_name ?: trim(implode(' ', array_filter([$lead->first_name, data_get($meta, 'last_name')]))) ?: 'Unknown contact';
    $initials = str($displayName)->explode(' ')->filter()->take(2)->map(fn ($w) => str($w)->substr(0, 1)->upper())->implode('');

    $subject = data_get($meta, 'subject');
    $message = data_get($meta, 'message');
    $helpDetails = data_get($meta, 'help_details');
    $bookingNotes = data_get($meta, 'booking_notes');
    $phone = data_get($meta, 'phone');
    $lastName = data_get($meta, 'last_name');

    $showLastNameRow = filled($lastName)
        && (! $lead->full_name || ! str_contains(strtolower((string) $lead->full_name), strtolower((string) $lastName)));

    $goal = $lead->goal;
    $goalDistinct = filled($goal) && filled($subject) && str($goal)->lower()->trim()->value() !== str($subject)->lower()->trim()->value();

    $viaSame = $lead->form_type && $lead->source_page && (string) $lead->form_type === (string) $lead->source_page;
    $viaLine = $viaSame
        ? $formTypeLabel($lead->form_type)
        : $formTypeLabel($lead->form_type).' · '.$sourcePageLabel($lead->source_page);

    $hasConsultationBlock = collect([
        data_get($meta, 'preferred_date'),
        data_get($meta, 'preferred_time'),
        data_get($meta, 'consultation_format'),
    ])->filter()->isNotEmpty();

    $hasBackgroundBlock = collect([
        data_get($meta, 'current_visa_status'),
        data_get($meta, 'country_of_citizenship'),
        data_get($meta, 'listing_name'),
        data_get($meta, 'listing_id'),
    ])->filter()->isNotEmpty();

    $technicalMetaKeys = ['referrer', 'session_id'];
    $extraMeta = collect($meta)->except(array_merge(array_keys([
        'phone' => 1,
        'last_name' => 1,
        'subject' => 1,
        'message' => 1,
        'current_visa_status' => 1,
        'country_of_citizenship' => 1,
        'help_details' => 1,
        'listing_id' => 1,
        'listing_name' => 1,
        'preferred_date' => 1,
        'preferred_time' => 1,
        'consultation_format' => 1,
        'booking_notes' => 1,
        'referrer' => 1,
        'session_id' => 1,
    ]), $technicalMetaKeys))->filter(fn ($v) => $v !== null && $v !== '');
@endphp

@section('content')
    <style>
        .lead-show { max-width: 1080px; margin: 0 auto; }
        .lead-show-hero {
            display: flex; flex-wrap: wrap; gap: 1.5rem; align-items: flex-start; justify-content: space-between;
            padding: 1.75rem 1.5rem; margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0; border-radius: 14px;
        }
        .lead-show-hero__person { display: flex; gap: 1.1rem; align-items: flex-start; min-width: 0; flex: 1; }
        .lead-show-hero__body { min-width: 0; flex: 1; }
        .lead-show-avatar {
            width: 56px; height: 56px; border-radius: 14px; flex-shrink: 0;
            display: grid; place-items: center; font-weight: 700; font-size: 1.1rem;
            background: linear-gradient(145deg, #0f766e, #0d9488); color: #fff;
            letter-spacing: 0.02em;
        }
        .lead-show-hero h2 { margin: 0 0 0.35rem; font-size: 1.45rem; color: #0f172a; line-height: 1.25; }
        .lead-show-contact-inline {
            display: flex; flex-wrap: wrap; align-items: center; gap: 0.35rem 1.25rem;
            margin-top: 0.2rem;
        }
        .lead-show-contact__item {
            display: flex;
            align-items: flex-start;
            gap: 0.45rem;
            min-width: 0;
            font-size: 1rem;
            color: #0d9488;
        }
        .lead-show-contact__icon {
            display: flex;
            flex-shrink: 0;
            color: #0d9488;
            margin-top: 0.2em;
        }
        .lead-show-contact__icon svg {
            width: 16px; height: 16px; display: block;
        }
        .lead-show-contact__item a {
            color: inherit;
            text-decoration: none;
            font-weight: 600;
            flex: 1;
            min-width: 0;
            overflow-wrap: break-word;
            word-break: normal;
        }
        .lead-show-contact__item a:hover { text-decoration: underline; }
        .lead-show-meta-line { margin-top: 0.65rem; font-size: 0.9rem; color: #64748b; max-width: 42rem; line-height: 1.45; }
        .lead-show-badges { display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center; justify-content: flex-end; }
        .lead-show-badge {
            display: inline-flex; align-items: center; padding: 0.35rem 0.75rem; border-radius: 999px;
            font-size: 0.8rem; font-weight: 600;
        }
        .lead-show-badge--status { background: #e0f2fe; color: #075985; text-transform: capitalize; }
        .lead-show-badge--date { background: #fff; border: 1px solid #e2e8f0; color: #475569; }
        .lead-show-grid {
            display: grid; grid-template-columns: minmax(0, 1.15fr) minmax(0, 0.85fr); gap: 1.25rem; align-items: start;
        }
        @media (max-width: 900px) { .lead-show-grid { grid-template-columns: 1fr; } }
        .lead-show-card {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem 1.35rem; margin-bottom: 1.25rem;
        }
        .lead-show-card h3 { margin: 0 0 0.85rem; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; font-weight: 700; }
        .lead-show-card h4 { margin: 1rem 0 0.5rem; font-size: 0.95rem; color: #0f172a; font-weight: 600; }
        .lead-show-card h4:first-of-type { margin-top: 0; }
        .lead-show-prose {
            margin: 0; color: #334155; font-size: 1rem; line-height: 1.6; white-space: pre-wrap; word-break: break-word;
        }
        .lead-show-topic { margin: 0; font-size: 1.05rem; font-weight: 600; color: #0f172a; line-height: 1.45; }
        .lead-show-goal { margin: 0.5rem 0 0; font-size: 0.92rem; color: #64748b; line-height: 1.45; }
        .lead-show-kv { display: grid; gap: 0.65rem 1rem; }
        .lead-show-kv__row { display: grid; grid-template-columns: 8.5rem 1fr; gap: 0.5rem 1rem; font-size: 0.92rem; align-items: baseline; }
        @media (max-width: 520px) { .lead-show-kv__row { grid-template-columns: 1fr; } }
        .lead-show-kv__k { color: #64748b; font-weight: 500; }
        .lead-show-kv__v { color: #0f172a; word-break: break-word; }
        .lead-show-tech {
            margin-top: 0.5rem; border: 1px solid #e2e8f0; border-radius: 10px; background: #fafafa; overflow: hidden;
        }
        .lead-show-tech summary {
            cursor: pointer; padding: 0.85rem 1.1rem; font-size: 0.88rem; font-weight: 600; color: #475569;
            list-style: none; display: flex; align-items: center; gap: 0.5rem; user-select: none;
        }
        .lead-show-tech summary::-webkit-details-marker { display: none; }
        .lead-show-tech summary::before { content: '▸'; font-size: 0.75rem; transition: transform 0.15s; }
        .lead-show-tech[open] summary::before { transform: rotate(90deg); }
        .lead-show-tech__body { padding: 0 1.1rem 1.1rem; border-top: 1px solid #e8e8e8; }
        .lead-show-tech .lead-show-kv__k { font-size: 0.82rem; }
        .lead-show-tech .lead-show-kv__v { font-family: ui-monospace, monospace; font-size: 0.78rem; color: #475569; }
        .lead-show-notes { border-left: 4px solid #f59e0b; background: #fffbeb; }
        .lead-show-notes .lead-show-prose { color: #78350f; }

        /* Lead show page: mobile-friendly topbar + hero */
        @media (max-width: 720px) {
            .lead-show.admin-main__inner > .admin-topbar > div:first-of-type {
                min-width: 0;
            }
            .lead-show.admin-main__inner > .admin-topbar {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }
            .lead-show.admin-main__inner .admin-topbar__actions {
                width: 100%;
                flex-direction: column;
                align-items: stretch;
                gap: 0.5rem;
            }
            .lead-show.admin-main__inner .admin-topbar__actions > a,
            .lead-show.admin-main__inner .admin-topbar__actions > form,
            .lead-show.admin-main__inner .admin-topbar__actions > form .button {
                width: 100%;
                justify-content: center;
                text-align: center;
                box-sizing: border-box;
            }
            .lead-show.admin-main__inner .admin-topbar__actions > form {
                display: block;
                width: 100%;
            }
            .lead-show.admin-main__inner .admin-topbar__actions > form .button {
                display: inline-flex;
                justify-content: center;
                width: 100%;
                box-sizing: border-box;
            }
            .lead-show.admin-main__inner .admin-topbar__actions > a.button {
                display: inline-flex;
                justify-content: center;
                align-items: center;
                box-sizing: border-box;
            }
        }

        @media (max-width: 640px) {
            .lead-show-hero {
                flex-direction: column;
                align-items: stretch;
                padding: 1.25rem 1rem;
                gap: 1rem;
            }
            .lead-show-badges {
                justify-content: flex-start;
                width: 100%;
            }
            .lead-show-hero__person {
                flex-direction: row;
                align-items: flex-start;
            }
            .lead-show-hero h2 { font-size: 1.2rem; word-wrap: break-word; }
            .lead-show-contact-inline {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            .lead-show-contact__item {
                width: 100%;
                max-width: 100%;
                align-items: flex-start;
            }
            .lead-show-contact__item a {
                line-height: 1.45;
            }
            .lead-show-meta-line { font-size: 0.85rem; }
            .lead-show-card { padding: 1rem 1rem; }
            .lead-show-tech summary {
                padding: 0.75rem 0.85rem;
                font-size: 0.8rem;
                flex-wrap: wrap;
                gap: 0.35rem;
            }
        }

        @media (max-width: 400px) {
            .lead-show-avatar {
                width: 48px;
                height: 48px;
                font-size: 1rem;
                border-radius: 12px;
            }
        }
    </style>

    <div class="admin-main__inner lead-show">
        <section class="admin-topbar">
            <div>
                <p class="eyebrow">Lead</p>
                <h2>Overview</h2>
                <p>Who contacted you, what they said, and where it came from.</p>
            </div>
            <div class="admin-topbar__actions">
                <a class="button button--small button--ghost" href="{{ route('admin.leads.index', request()->only(['status', 'type'])) }}">Back to inbox</a>
                <a class="button button--small" href="{{ route('admin.leads.edit', $lead) }}">Update status</a>
                <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}" onsubmit="return confirmDelete(this, 'lead');">
                    @csrf
                    @method('DELETE')
                    <button class="button button--small button--danger" type="submit">Delete</button>
                </form>
            </div>
        </section>

        <div class="lead-show-hero">
            <div class="lead-show-hero__person">
                <div class="lead-show-avatar" aria-hidden="true">{{ $initials ?: '?' }}</div>
                <div class="lead-show-hero__body">
                    <h2>{{ $displayName }}</h2>
                    <div class="lead-show-contact-inline">
                        <span class="lead-show-contact__item">
                            <span class="lead-show-contact__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </span>
                            <a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a>
                        </span>
                        @if ($phone)
                            <span class="lead-show-contact__item">
                                <span class="lead-show-contact__icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                </span>
                                <a href="tel:{{ preg_replace('/\s+/', '', (string) $phone) }}">{{ $phone }}</a>
                            </span>
                        @endif
                    </div>
                    <p class="lead-show-meta-line">
                        <strong style="color: #334155;">{{ $viaLine }}</strong>
                        @if ($lead->created_at)
                            <span style="color: #94a3b8;"> · </span>Received {{ $lead->created_at->format('j M Y, g:i a') }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="lead-show-badges">
                <span class="lead-show-badge lead-show-badge--status">{{ ucfirst($lead->status ?? 'new') }}</span>
                @if ($lead->created_at)
                    <span class="lead-show-badge lead-show-badge--date">{{ $lead->created_at->diffForHumans() }}</span>
                @endif
            </div>
        </div>

        <div class="lead-show-grid">
            <div class="lead-show-main">
                @if (filled($subject) || filled($goal))
                    <div class="lead-show-card">
                        <h3>What they asked about</h3>
                        @if (filled($subject))
                            <p class="lead-show-topic">{{ $subject }}</p>
                        @endif
                        @if ($goalDistinct)
                            <p class="lead-show-goal">{{ $goal }}</p>
                        @elseif (! filled($subject) && filled($goal))
                            <p class="lead-show-topic">{{ $goal }}</p>
                        @endif
                    </div>
                @endif

                @if (filled($message))
                    <div class="lead-show-card">
                        <h3>Message</h3>
                        <p class="lead-show-prose">{{ $message }}</p>
                    </div>
                @endif

                @if (filled($helpDetails) && $helpDetails !== $message)
                    <div class="lead-show-card">
                        <h3>How we can help</h3>
                        <p class="lead-show-prose">{{ $helpDetails }}</p>
                    </div>
                @endif

                @if (filled($bookingNotes) && $bookingNotes !== $message && $bookingNotes !== $helpDetails)
                    <div class="lead-show-card">
                        <h3>Booking notes</h3>
                        <p class="lead-show-prose">{{ $bookingNotes }}</p>
                    </div>
                @endif

                @if (! filled($subject) && ! filled($goal) && ! filled($message) && ! filled($helpDetails) && ! filled($bookingNotes))
                    <div class="lead-show-card">
                        <h3>Message</h3>
                        <p class="lead-show-prose" style="color: #64748b;">No message was left with this submission.</p>
                    </div>
                @endif
            </div>

            <aside class="lead-show-aside">
                @if ($hasConsultationBlock)
                    <div class="lead-show-card">
                        <h3>Consultation preferences</h3>
                        <div class="lead-show-kv">
                            @if (filled(data_get($meta, 'preferred_date')))
                                <div class="lead-show-kv__row"><span class="lead-show-kv__k">Date</span><span class="lead-show-kv__v">{{ data_get($meta, 'preferred_date') }}</span></div>
                            @endif
                            @if (filled(data_get($meta, 'preferred_time')))
                                <div class="lead-show-kv__row"><span class="lead-show-kv__k">Time</span><span class="lead-show-kv__v">{{ data_get($meta, 'preferred_time') }}</span></div>
                            @endif
                            @if (filled(data_get($meta, 'consultation_format')))
                                <div class="lead-show-kv__row"><span class="lead-show-kv__k">Format</span><span class="lead-show-kv__v">{{ data_get($meta, 'consultation_format') }}</span></div>
                            @endif
                        </div>
                    </div>
                @endif

                @if ($hasBackgroundBlock || $showLastNameRow)
                    <div class="lead-show-card">
                        <h3>Background</h3>
                        <div class="lead-show-kv">
                            @if ($showLastNameRow)
                                <div class="lead-show-kv__row"><span class="lead-show-kv__k">Last name</span><span class="lead-show-kv__v">{{ $lastName }}</span></div>
                            @endif
                            @if (filled(data_get($meta, 'current_visa_status')))
                                <div class="lead-show-kv__row"><span class="lead-show-kv__k">Visa status</span><span class="lead-show-kv__v">{{ data_get($meta, 'current_visa_status') }}</span></div>
                            @endif
                            @if (filled(data_get($meta, 'country_of_citizenship')))
                                <div class="lead-show-kv__row"><span class="lead-show-kv__k">Citizenship</span><span class="lead-show-kv__v">{{ data_get($meta, 'country_of_citizenship') }}</span></div>
                            @endif
                            @if (filled(data_get($meta, 'listing_name')))
                                <div class="lead-show-kv__row"><span class="lead-show-kv__k">Listing</span><span class="lead-show-kv__v">{{ data_get($meta, 'listing_name') }}</span></div>
                            @endif
                            @if (filled(data_get($meta, 'listing_id')))
                                <div class="lead-show-kv__row"><span class="lead-show-kv__k">Listing ID</span><span class="lead-show-kv__v">{{ data_get($meta, 'listing_id') }}</span></div>
                            @endif
                        </div>
                    </div>
                @endif

                @foreach ($extraMeta as $key => $value)
                    <div class="lead-show-card">
                        <h3>{{ str($key)->replace('_', ' ')->title() }}</h3>
                        <p class="lead-show-prose">{{ is_scalar($value) ? $value : json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</p>
                    </div>
                @endforeach
            </aside>
        </div>

        @if ($lead->notes)
            <div class="lead-show-card lead-show-notes">
                <h3>Your team notes</h3>
                <p class="lead-show-prose">{{ $lead->notes }}</p>
            </div>
        @endif

        <details class="lead-show-tech">
            <summary>Technical details (for troubleshooting)</summary>
            <div class="lead-show-tech__body">
                <div class="lead-show-kv">
                    <div class="lead-show-kv__row"><span class="lead-show-kv__k">Record ID</span><span class="lead-show-kv__v">{{ $lead->id }}</span></div>
                    <div class="lead-show-kv__row"><span class="lead-show-kv__k">Form code</span><span class="lead-show-kv__v">{{ $lead->form_type }}</span></div>
                    <div class="lead-show-kv__row"><span class="lead-show-kv__k">Source code</span><span class="lead-show-kv__v">{{ $lead->source_page ?: '—' }}</span></div>
                    @if ($lead->first_name && $lead->first_name !== $displayName)
                        <div class="lead-show-kv__row"><span class="lead-show-kv__k">First name (field)</span><span class="lead-show-kv__v">{{ $lead->first_name }}</span></div>
                    @endif
                    @if ($lead->full_name && $lead->full_name !== $displayName)
                        <div class="lead-show-kv__row"><span class="lead-show-kv__k">Full name (field)</span><span class="lead-show-kv__v">{{ $lead->full_name }}</span></div>
                    @endif
                    @if (filled($goal))
                        <div class="lead-show-kv__row"><span class="lead-show-kv__k">Goal (stored)</span><span class="lead-show-kv__v">{{ $goal }}</span></div>
                    @endif
                    @if ($lead->conversation_id)
                        <div class="lead-show-kv__row"><span class="lead-show-kv__k">Conversation</span><span class="lead-show-kv__v">{{ $lead->conversation_id }}</span></div>
                    @endif
                    <div class="lead-show-kv__row"><span class="lead-show-kv__k">IP address</span><span class="lead-show-kv__v">{{ $lead->ip_address ?: '—' }}</span></div>
                    <div class="lead-show-kv__row"><span class="lead-show-kv__k">User agent</span><span class="lead-show-kv__v">{{ $lead->user_agent ?: '—' }}</span></div>
                    @if (filled(data_get($meta, 'referrer')))
                        <div class="lead-show-kv__row"><span class="lead-show-kv__k">Referrer</span><span class="lead-show-kv__v">{{ data_get($meta, 'referrer') }}</span></div>
                    @endif
                    @if (filled(data_get($meta, 'session_id')))
                        <div class="lead-show-kv__row"><span class="lead-show-kv__k">Session ID</span><span class="lead-show-kv__v">{{ data_get($meta, 'session_id') }}</span></div>
                    @endif
                    <div class="lead-show-kv__row"><span class="lead-show-kv__k">Created</span><span class="lead-show-kv__v">{{ $lead->created_at?->format('Y-m-d H:i:s') }}</span></div>
                    <div class="lead-show-kv__row"><span class="lead-show-kv__k">Updated</span><span class="lead-show-kv__v">{{ $lead->updated_at?->format('Y-m-d H:i:s') }}</span></div>
                </div>
            </div>
        </details>
    </div>
@endsection
