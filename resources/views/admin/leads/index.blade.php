@extends('admin.layouts.app')

@section('content')
    <style>
        .leads-table-wrap {
            border: 1px solid #edf2f7;
            border-radius: 0.75rem;
            background: #fff;
            width: 100%;
            overflow-x: visible;
        }
        .leads-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .leads-table thead {
            background: #f3f4f6;
        }
        .leads-table th {
            padding: 1rem 0.75rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
            white-space: nowrap;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .leads-table td {
            padding: 1rem 0.75rem;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }
        .leads-table tbody tr:hover {
            background: #f9fafb;
        }
        .leads-table td strong,
        .leads-table td small {
            display: block;
        }
        .leads-table td small {
            color: #6b7280;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }
        .leads-type-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            border-radius: 999px;
            background: #e0e7ff;
            color: #3730a3;
            font-size: 0.82rem;
            font-weight: 600;
            white-space: nowrap;
            line-height: 1.2;
        }
        .leads-actions-cell {
            display: flex;
            gap: 0.4rem;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-start;
            max-width: 100%;
        }
        .leads-action-btn {
            padding: 0.4rem 0.75rem;
            border-radius: 0.375rem;
            border: none;
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            white-space: nowrap;
            box-sizing: border-box;
        }
        .leads-edit-btn {
            background: #dbeafe;
            color: #0c4a6e;
        }
        .leads-edit-btn:hover {
            background: #93c5fd;
        }
        .leads-view-btn {
            background: #ecfdf5;
            color: #065f46;
        }
        .leads-view-btn:hover {
            background: #a7f3d0;
        }
        .leads-delete-btn {
            background: #fee2e2;
            color: #7f1d1d;
        }
        .leads-delete-btn:hover {
            background: #fca5a5;
        }
        .leads-date-cell strong {
            color: #334155;
            font-weight: 600;
            white-space: nowrap;
        }

        /* —— Lead inbox: filter bar (scoped pro layout) —— */
        .leads-filters-card {
            padding: 1.35rem 1.5rem;
            border-radius: 16px;
            border: 1px solid rgba(11, 122, 117, 0.12);
            background: linear-gradient(180deg, #ffffff 0%, #f8fbfd 100%);
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06);
        }
        .leads-filters-card__head {
            margin-bottom: 0.25rem;
        }
        .leads-filters-card__title {
            margin: 0 0 0.4rem;
            font-size: 1.125rem;
            font-weight: 700;
            color: #12384f;
            letter-spacing: -0.02em;
            line-height: 1.25;
        }
        .leads-filters-card__intro {
            margin: 0;
            font-size: 0.875rem;
            color: #64748b;
            line-height: 1.55;
            max-width: 40rem;
        }
        .leads-filters-card__body {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 1rem 1.5rem;
            margin-top: 1.15rem;
            padding-top: 1.15rem;
            border-top: 1px solid rgba(11, 122, 117, 0.1);
        }
        .leads-filters-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 0.45rem;
            flex: 1 1 220px;
            align-items: center;
            min-width: 0;
        }
        .leads-filter-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 2.125rem;
            padding: 0.4rem 0.9rem;
            border-radius: 999px;
            font-size: 0.8125rem;
            font-weight: 600;
            text-decoration: none;
            color: #334155;
            background: #fff;
            border: 1px solid #e2e8f0;
            transition: border-color 0.15s ease, color 0.15s ease, background 0.15s ease, box-shadow 0.15s ease;
            box-shadow: 0 1px 0 rgba(255, 255, 255, 0.8) inset;
        }
        .leads-filter-chip:hover {
            border-color: rgba(11, 122, 117, 0.45);
            color: #0b7a75;
            background: #f0fdfa;
        }
        .leads-filter-chip.is-active {
            background: #0b7a75;
            border-color: #0b7a75;
            color: #fff;
            box-shadow: 0 1px 2px rgba(11, 122, 117, 0.2);
        }
        .leads-filter-chip.is-active:hover {
            background: #085f5b;
            border-color: #085f5b;
            color: #fff;
        }
        .leads-filters-form {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: 0.75rem 1rem;
            margin: 0;
            flex: 0 1 auto;
        }
        .leads-filters-form__fields {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: 0.75rem 1rem;
        }
        .leads-filters-field {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            margin: 0;
        }
        .leads-filters-field__label {
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: #0b7a75;
        }
        .leads-filters-select {
            min-width: 11.75rem;
            height: 2.5rem;
            padding: 0 2rem 0 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            background-color: #fff;
            font-size: 0.875rem;
            font-weight: 500;
            color: #1e293b;
            cursor: pointer;
            box-sizing: border-box;
        }
        .leads-filters-select:focus {
            outline: none;
            border-color: #0b7a75;
            box-shadow: 0 0 0 3px rgba(11, 122, 117, 0.14);
        }
        .leads-filters-submit {
            height: 2.5rem;
            padding: 0 1.2rem;
            border: none;
            border-radius: 10px;
            background: #0b7a75;
            color: #fff;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.2s ease, box-shadow 0.2s ease, transform 0.12s ease;
            box-shadow: 0 1px 2px rgba(11, 122, 117, 0.22);
        }
        .leads-filters-submit:hover {
            background: #085f5b;
            box-shadow: 0 2px 6px rgba(11, 122, 117, 0.28);
        }
        .leads-filters-submit:active {
            transform: translateY(1px);
        }

        @media (min-width: 900px) {
            .leads-filters-card__body {
                justify-content: space-between;
            }
            .leads-filters-form {
                margin-left: auto;
            }
        }

        @media (max-width: 1100px) {
            .leads-table-wrap {
                overflow-x: auto;
            }
            .leads-table {
                min-width: 720px;
            }
        }
        
        /* Mobile responsive fixes */
        @media (max-width: 768px) {
            .leads-filters-card {
                padding: 1rem 1.1rem !important;
            }
            .leads-filters-card__body {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }
            .leads-filters-chips {
                flex: none;
                width: 100%;
            }
            .leads-filter-chip {
                flex: 1 1 auto;
                min-width: calc(50% - 0.25rem);
                justify-content: center;
                text-align: center;
            }
            .leads-filters-form {
                flex-direction: column;
                align-items: stretch;
                width: 100%;
            }
            .leads-filters-form__fields {
                flex-direction: column;
                width: 100%;
            }
            .leads-filters-field {
                width: 100%;
            }
            .leads-filters-select {
                width: 100%;
                min-width: 0;
            }
            .leads-filters-submit {
                width: 100%;
                margin-top: 0.25rem;
            }

            /* Reset table wrapper - no horizontal scroll on mobile */
            .leads-table-wrap {
                overflow-x: hidden !important;
                border: none !important;
                background: transparent !important;
            }
            
            .leads-table {
                min-width: auto !important;
                width: 100% !important;
                table-layout: auto !important;
            }
            
            /* Card view for mobile */
            .leads-table thead {
                display: none;
            }
            
            .leads-table tbody tr {
                display: block;
                margin-bottom: 1rem;
                background: #fff;
                border-radius: 12px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                padding: 1rem;
                border: 1px solid #e5e7eb;
                overflow: hidden;
            }
            
            .leads-table td {
                display: grid;
                grid-template-columns: 100px 1fr;
                gap: 0.5rem;
                padding: 0.5rem 0;
                border-bottom: 1px solid #e5e7eb;
                align-items: flex-start;
            }
            
            .leads-table td:last-child {
                border-bottom: none;
            }
            
            .leads-table td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #6b7280;
                font-size: 0.75rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                padding-top: 0.25rem;
            }
            
            /* First cell (Lead) special styling */
            .leads-table td[data-label="Lead"] {
                background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
                margin: -1rem -1rem 0.5rem -1rem;
                padding: 1rem;
                border-radius: 12px 12px 0 0;
                border-bottom: 2px solid #e5e7eb;
            }
            
            .leads-table td[data-label="Lead"]::before {
                display: none;
            }

            .leads-table td[data-label="Actions"] {
                display: block !important;
                margin: 0.65rem -1rem -1rem;
                width: calc(100% + 2rem);
                max-width: calc(100% + 2rem);
                box-sizing: border-box;
                padding: 0.85rem 1rem 1rem !important;
                border-top: 1px solid #e5e7eb !important;
                border-bottom: none !important;
                background: #f8fafc !important;
                border-radius: 0 0 11px 11px;
            }
            .leads-table td[data-label="Actions"]::before {
                display: block;
                margin-bottom: 0.6rem;
                padding-top: 0;
            }
            .leads-table.admin-table-mobile-cards .leads-actions-cell {
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: wrap !important;
                width: 100% !important;
                gap: 0.45rem !important;
                justify-content: stretch !important;
                align-items: stretch !important;
            }
            .leads-table.admin-table-mobile-cards .leads-actions-cell > a,
            .leads-table.admin-table-mobile-cards .leads-actions-cell > form {
                flex: 1 1 calc(33.333% - 0.3rem) !important;
                min-width: 0 !important;
                margin: 0 !important;
                display: flex !important;
            }
            .leads-table.admin-table-mobile-cards .leads-actions-cell .leads-action-btn {
                width: 100% !important;
                justify-content: center !important;
                box-sizing: border-box !important;
            }

            /* Action buttons styled by unified rules in admin.css */
        }

        @media (max-width: 480px) {
            .leads-filter-chip {
                min-width: 100%;
                width: 100%;
                flex: none;
            }
        }
    </style>

    <div class="admin-main__inner">
        <section class="admin-topbar">
            <div>
                <p class="eyebrow">Lead management</p>
                <h2>Lead inbox</h2>
                <p>Review migration consultations, contact enquiries, popup captures, and directory leads.</p>
            </div>
        </section>

        <section class="admin-panel-card leads-filters-card" aria-labelledby="leads-filters-heading">
            <header class="leads-filters-card__head">
                <h3 class="leads-filters-card__title" id="leads-filters-heading">Filter enquiries</h3>
                <p class="leads-filters-card__intro">Narrow the list by status or form type.</p>
            </header>
            <div class="leads-filters-card__body">
                <nav class="leads-filters-chips" aria-label="Quick filters by form type">
                    <a class="leads-filter-chip{{ request('type') === 'contact-page' ? ' is-active' : '' }}" href="{{ route('admin.leads.index', array_filter(['type' => 'contact-page', 'status' => request('status')])) }}">Contact submissions</a>
                    <a class="leads-filter-chip{{ request('type') === 'consultation-booking' ? ' is-active' : '' }}" href="{{ route('admin.leads.index', array_filter(['type' => 'consultation-booking', 'status' => request('status')])) }}">Book consultations</a>
                    <a class="leads-filter-chip{{ request('type') === 'migration-consultation' ? ' is-active' : '' }}" href="{{ route('admin.leads.index', array_filter(['type' => 'migration-consultation', 'status' => request('status')])) }}">Migration consultations</a>
                    <a class="leads-filter-chip{{ request('type') === 'directory-enquiry' ? ' is-active' : '' }}" href="{{ route('admin.leads.index', array_filter(['type' => 'directory-enquiry', 'status' => request('status')])) }}">Directory enquiries</a>
                    <a class="leads-filter-chip{{ request('type') === 'popup' ? ' is-active' : '' }}" href="{{ route('admin.leads.index', array_filter(['type' => 'popup', 'status' => request('status')])) }}">Popup leads</a>
                </nav>
                <form class="leads-filters-form" method="GET" action="{{ route('admin.leads.index') }}">
                    <div class="leads-filters-form__fields">
                        <label class="leads-filters-field">
                            <span class="leads-filters-field__label">Status</span>
                            <select class="leads-filters-select" name="status">
                                <option value="">All statuses</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="leads-filters-field">
                            <span class="leads-filters-field__label">Type</span>
                            <select class="leads-filters-select" name="type">
                                <option value="">All types</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type }}" @selected(request('type') === $type)>{{ $type }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                    <button class="leads-filters-submit" type="submit">Apply filters</button>
                </form>
            </div>
        </section>

        <section class="admin-panel-card" style="padding: 0;">
            <div class="leads-table-wrap admin-table-responsive">
                <table class="leads-table admin-table-mobile-cards">
                    <thead>
                        <tr>
                            <th style="width: 30%;">Lead</th>
                            <th style="width: 18%;">Type</th>
                            <th style="width: 18%;">Created</th>
                            <th style="width: 34%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($leads as $lead)
                            <tr>
                                <td data-label="Lead">
                                    <div class="leads-info-cell">
                                        <div class="leads-info-name">{{ $lead->full_name ?: $lead->first_name ?: 'Unknown' }}</div>
                                        <div class="leads-info-email">
                                            <svg class="leads-info-icon" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                            <span>{{ $lead->email }}</span>
                                        </div>
                                        @if(data_get($lead->metadata, 'phone'))
                                            <div class="leads-info-phone">
                                                <svg class="leads-info-icon" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                                <span>{{ data_get($lead->metadata, 'phone') }}</span>
                                            </div>
                                        @endif
                                        <div class="leads-info-status">
                                            <span class="leads-status-badge status-{{ $lead->status ?? 'new' }}">{{ ucfirst($lead->status ?? 'new') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Type"><span class="leads-type-badge">{{ $lead->form_type }}</span></td>
                                <td data-label="Created" class="leads-date-cell">
                                    <strong>{{ $lead->created_at?->format('d M Y') }}</strong>
                                    <small>{{ $lead->created_at?->format('h:i A') }}</small>
                                </td>
                                <td data-label="Actions">
                                    <div class="leads-actions-cell">
                                        <a href="{{ route('admin.leads.show', $lead) }}" class="leads-action-btn leads-view-btn">
                                            <svg class="leads-action-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                            <span>View</span>
                                        </a>
                                        <a href="{{ route('admin.leads.edit', $lead) }}" class="leads-action-btn leads-edit-btn">
                                            <svg class="leads-action-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                            <span>Edit</span>
                                        </a>
                                        <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}" onsubmit="return confirmDelete(this, 'lead');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="leads-action-btn leads-delete-btn">
                                                <svg class="leads-action-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/></svg>
                                                <span>Delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 3rem;">
                                    <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
                                    <h3>No leads captured yet.</h3>
                                    <p style="color: #6b7280;">Leads will appear here when submitted through contact forms.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="padding: 1rem 1.25rem; border-top: 1px solid #e5e7eb;">{{ $leads->links() }}</div>
        </section>
    </div>
@endsection
