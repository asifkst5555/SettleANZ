@extends('admin.layouts.app')

@section('page-title', 'Ebook Leads')

@section('content')
    <div class="admin-main__inner">
        <section class="admin-topbar">
            <div>
                <p class="eyebrow">Ebook System</p>
                <h2>Ebook Leads</h2>
                <p>View all leads generated through ebook downloads and landing pages.</p>
            </div>
        </section>

        @if (session('status'))
            <div style="background:#e8f5e9;border:1px solid #66bb6a;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;color:#2e7d32;font-weight:600;">
                {{ session('status') }}
            </div>
        @endif

        <section class="admin-filters-card" style="margin-bottom: 1.5rem;">
            <header class="admin-filters-card__title">
                <h3>Filter Ebook Enquiries</h3>
                <p class="admin-filters-card__intro">Narrow the list by status, ebook library, or keyword.</p>
            </header>
            <div class="admin-filters-card__body">
                <form method="GET" style="display:flex;gap:0.75rem;align-items:flex-end;flex-wrap:wrap;width:100%;">
                    <div style="display:flex;flex-direction:column;gap:0.25rem;">
                        <span style="font-size:0.75rem;font-weight:700;color:#0b7a75;text-transform:uppercase;">Status</span>
                        <select class="admin-form-select" name="status" style="width:140px;height:2.25rem;padding:0.25rem 0.5rem;font-size:0.85rem;">
                            <option value="">All statuses</option>
                            @foreach ($statuses as $s)
                                <option value="{{ $s->value ?? $s }}" @selected(request('status') === ($s->value ?? $s))>{{ ucfirst($s->value ?? $s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:0.25rem;">
                        <span style="font-size:0.75rem;font-weight:700;color:#0b7a75;text-transform:uppercase;">Ebook Library</span>
                        <select class="admin-form-select" name="ebook_id" style="width:200px;height:2.25rem;padding:0.25rem 0.5rem;font-size:0.85rem;">
                            <option value="">All ebooks</option>
                            @foreach ($ebooks as $ebook)
                                <option value="{{ $ebook->id }}" @selected(request('ebook_id') == $ebook->id)>{{ $ebook->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:0.25rem;">
                        <span style="font-size:0.75rem;font-weight:700;color:#0b7a75;text-transform:uppercase;">Search</span>
                        <input type="text" name="search" class="admin-form-input" value="{{ request('search') }}" placeholder="Name or email..." style="width:180px;height:2.25rem;padding:0.25rem 0.5rem;font-size:0.85rem;">
                    </div>
                    <button class="button button--small" type="submit">Filter</button>
                    @if (request('status') || request('ebook_id') || request('search'))
                        <a href="{{ route('admin.ebook-leads.index') }}" class="button button--small" style="background:#f1f5f9;color:#475569;border:1px solid #cbd5e1;">Clear</a>
                    @endif
                </form>
            </div>
        </section>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">Lead</th>
                        <th style="width: 25%;">Ebook Target</th>
                        <th style="width: 20%;">Date & Status</th>
                        <th style="width: 30%; text-align: right; padding-right: 1.5rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leads as $lead)
                        <tr>
                            <td>
                                <strong>{{ $lead->full_name }}</strong>
                                <small>{{ $lead->email }}</small>
                            </td>
                            <td>
                                <x-admin-badge color="indigo">Ebook Download</x-admin-badge>
                                <small style="display:block;margin-top:0.25rem;font-weight:600;color:#0f172a;">{{ $lead->ebook?->title ?? '—' }}</small>
                            </td>
                            <td>
                                <strong>{{ $lead->created_at->format('M j, Y') }}</strong>
                                <div style="margin-top:0.35rem;">
                                    <x-admin-badge :color="match($lead->status->value ?? $lead->status) {
                                        'qualified' => 'green',
                                        'new' => 'indigo',
                                        'downloaded' => 'orange',
                                        default => 'gray'
                                    }">
                                        {{ ucfirst($lead->status->value ?? $lead->status) }}
                                    </x-admin-badge>
                                </div>
                            </td>
                            <td style="text-align: right; padding-right: 1.5rem; vertical-align: middle;">
                                <div style="display: inline-flex; gap: 0.35rem; align-items: center; justify-content: flex-end;">
                                    <a href="{{ route('admin.ebook-leads.show', $lead) }}" class="button button--small" style="background:#e0f2fe;color:#0369a1;border:1px solid #bae6fd;">
                                        View details
                                    </a>
                                    
                                    <form method="POST" action="{{ route('admin.ebook-leads.destroy', $lead) }}" onsubmit="return confirm('Are you sure you want to delete this lead?');" style="display:inline;">
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
                                <h3 style="color:#0f172a;">No ebook leads found.</h3>
                                <p style="color: var(--admin-muted); margin-top:0.25rem;">Try clearing your filter criteria or search keyword.</p>
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
