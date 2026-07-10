@extends('admin.layouts.app')

@section('content')
    <div class="admin-main__inner">
        <section class="admin-topbar">
            <div>
                <p class="eyebrow">Lead Management</p>
                <h2>Inbox & Submissions</h2>
                <p>Review migration consultations, contact enquiries, popup captures, and ebook download leads.</p>
            </div>
        </section>

        @if (session('status'))
            <div style="background:#e8f5e9;border:1px solid #66bb6a;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;color:#2e7d32;font-weight:600;">
                {{ session('status') }}
            </div>
        @endif

        <section class="admin-filters-card" style="margin-bottom: 1.5rem;">
            <header class="admin-filters-card__title">
                <h3>Filter Enquiries</h3>
                <p class="admin-filters-card__intro">Narrow the list by status or form type.</p>
            </header>
            <div class="admin-filters-card__body">
                <nav class="admin-filter-chips" style="flex: 1;">
                    <a class="admin-filter-chip {{ !request('type') ? 'is-active' : '' }}" href="{{ route('admin.leads.index', array_filter(['status' => request('status')])) }}">All Enquiries</a>
                    <a class="admin-filter-chip {{ request('type') === 'contact-page' ? 'is-active' : '' }}" href="{{ route('admin.leads.index', array_filter(['type' => 'contact-page', 'status' => request('status')])) }}">Contact submissions</a>
                    <a class="admin-filter-chip {{ request('type') === 'consultation-booking' ? 'is-active' : '' }}" href="{{ route('admin.leads.index', array_filter(['type' => 'consultation-booking', 'status' => request('status')])) }}">Book consultations</a>
                    <a class="admin-filter-chip {{ request('type') === 'migration-consultation' ? 'is-active' : '' }}" href="{{ route('admin.leads.index', array_filter(['type' => 'migration-consultation', 'status' => request('status')])) }}">Migration consultations</a>
                    <a class="admin-filter-chip {{ request('type') === 'directory-enquiry' ? 'is-active' : '' }}" href="{{ route('admin.leads.index', array_filter(['type' => 'directory-enquiry', 'status' => request('status')])) }}">Directory enquiries</a>
                    <a class="admin-filter-chip {{ request('type') === 'popup' ? 'is-active' : '' }}" href="{{ route('admin.leads.index', array_filter(['type' => 'popup', 'status' => request('status')])) }}">Popup leads</a>
                    <a class="admin-filter-chip {{ request('type') === 'ebook_download' ? 'is-active' : '' }}" href="{{ route('admin.leads.index', array_filter(['type' => 'ebook_download', 'status' => request('status')])) }}">Ebook downloads</a>
                </nav>

                <form method="GET" action="{{ route('admin.leads.index') }}" style="display:flex;gap:0.75rem;align-items:flex-end;flex-wrap:wrap;">
                    @if (request('type'))
                        <input type="hidden" name="type" value="{{ request('type') }}">
                    @endif
                    <div style="display:flex;flex-direction:column;gap:0.25rem;">
                        <span style="font-size:0.75rem;font-weight:700;color:#0b7a75;text-transform:uppercase;">Status</span>
                        <select class="admin-form-select" name="status" style="width:140px;height:2.25rem;padding:0.25rem 0.5rem;font-size:0.85rem;">
                            <option value="">All statuses</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:0.25rem;">
                        <span style="font-size:0.75rem;font-weight:700;color:#0b7a75;text-transform:uppercase;">Search</span>
                        <input type="text" name="search" class="admin-form-input" value="{{ request('search') }}" placeholder="Name or email..." style="width:180px;height:2.25rem;padding:0.25rem 0.5rem;font-size:0.85rem;">
                    </div>
                    <button class="button button--small" type="submit">Filter</button>
                    @if (request('type') || request('status') || request('search'))
                        <a href="{{ route('admin.leads.index') }}" class="button button--small" style="background:#f1f5f9;color:#475569;border:1px solid #cbd5e1;">Clear</a>
                    @endif
                </form>
            </div>
        </section>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">Lead</th>
                        <th style="width: 25%;">Origin / Target</th>
                        <th style="width: 20%;">Date & Status</th>
                        <th style="width: 30%; text-align: right; padding-right: 1.5rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leads as $lead)
                        <tr>
                            <td>
                                <strong>{{ $lead->full_name ?: $lead->first_name ?: 'Unknown' }}</strong>
                                <small>{{ $lead->email }}</small>
                                @if (data_get($lead->metadata, 'phone') || $lead->phone)
                                    <small style="color:#64748b;">📞 {{ $lead->phone ?: data_get($lead->metadata, 'phone') }}</small>
                                @endif
                            </td>
                            <td>
                                @if ($lead->form_type === 'ebook_download')
                                    <x-admin-badge color="indigo">Ebook Download</x-admin-badge>
                                    @if ($lead->ebook)
                                        <small style="margin-top:0.25rem;font-weight:600;color:#0f172a;">{{ $lead->ebook->title }}</small>
                                    @endif
                                @else
                                    <x-admin-badge color="gray">{{ str_replace('_', ' ', ucfirst($lead->form_type)) }}</x-admin-badge>
                                @endif
                                @if ($lead->source_page && $lead->source_page !== $lead->form_type)
                                    <small style="margin-top:0.2rem;color:var(--admin-muted);">Page: {{ $lead->source_page }}</small>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $lead->created_at?->format('d M Y') }}</strong>
                                <div style="margin-top:0.35rem;">
                                    <x-admin-badge :color="match($lead->status->value ?? $lead->status) {
                                        'qualified' => 'green',
                                        'new' => 'indigo',
                                        'reviewing' => 'teal',
                                        'contacted' => 'orange',
                                        'closed' => 'gray',
                                        default => 'gray'
                                    }">
                                        {{ ucfirst($lead->status->value ?? $lead->status) }}
                                    </x-admin-badge>
                                </div>
                            </td>
                            <td style="text-align: right; padding-right: 1.5rem; vertical-align: middle;">
                                <div style="display: inline-flex; gap: 0.35rem; align-items: center; justify-content: flex-end;">
                                    @if ($lead->form_type === 'ebook_download')
                                        <a href="{{ route('admin.ebook-leads.show', $lead) }}" class="button button--small" style="background:#f0fdfa;color:#0f766e;border:1px solid #99f6e4;">
                                            View Ebook Lead
                                        </a>
                                    @else
                                        <a href="{{ route('admin.leads.show', $lead) }}" class="button button--small" style="background:#e0f2fe;color:#0369a1;border:1px solid #bae6fd;">
                                            View
                                        </a>
                                        <a href="{{ route('admin.leads.edit', $lead) }}" class="button button--small" style="background:#f1f5f9;color:#334155;border:1px solid #cbd5e1;">
                                            Edit
                                        </a>
                                    @endif
                                    
                                    <form method="POST" action="{{ $lead->form_type === 'ebook_download' ? route('admin.ebook-leads.destroy', $lead) : route('admin.leads.destroy', $lead) }}" onsubmit="return confirm('Are you sure you want to delete this lead?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="button button--small" style="background:#fef2f2;color:#b91c1c;border:1px solid #fecaca;">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 4rem;">
                                <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
                                <h3 style="color:#0f172a;">No leads matched your filters.</h3>
                                <p style="color: var(--admin-muted); margin-top:0.25rem;">Try clearing your filters or search terms.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $leads->links() }}
        </div>
    </div>
@endsection
