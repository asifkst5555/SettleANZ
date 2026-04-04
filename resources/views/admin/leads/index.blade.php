@extends('admin.layouts.app')

@section('content')
    <div class="admin-main__inner">
        <section class="admin-topbar">
            <div>
                <p class="eyebrow">Lead management</p>
                <h2>Lead inbox</h2>
                <p>Review migration consultations, contact enquiries, popup captures, and directory leads.</p>
            </div>
        </section>

        <section class="admin-panel-card admin-panel-card--filters">
            <div>
                <h3>Filter enquiries</h3>
                <p>Narrow the list by status or form type.</p>
                <div class="admin-quick-filters">
                    <a class="admin-quick-filter{{ request('type') === 'contact-page' ? ' is-active' : '' }}" href="{{ route('admin.leads.index', ['type' => 'contact-page']) }}">Contact submissions</a>
                    <a class="admin-quick-filter{{ request('type') === 'consultation-booking' ? ' is-active' : '' }}" href="{{ route('admin.leads.index', ['type' => 'consultation-booking']) }}">Book consultations</a>
                    <a class="admin-quick-filter{{ request('type') === 'migration-consultation' ? ' is-active' : '' }}" href="{{ route('admin.leads.index', ['type' => 'migration-consultation']) }}">Migration consultations</a>
                    <a class="admin-quick-filter{{ request('type') === 'directory-enquiry' ? ' is-active' : '' }}" href="{{ route('admin.leads.index', ['type' => 'directory-enquiry']) }}">Directory enquiries</a>
                    <a class="admin-quick-filter{{ request('type') === 'popup' ? ' is-active' : '' }}" href="{{ route('admin.leads.index', ['type' => 'popup']) }}">Popup leads</a>
                </div>
            </div>
            <form class="admin-filter-form" method="GET" action="{{ route('admin.leads.index') }}">
                <label><span>Status</span><select name="status"><option value="">All</option>@foreach ($statuses as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>@endforeach</select></label>
                <label><span>Type</span><select name="type"><option value="">All</option>@foreach ($types as $type)<option value="{{ $type }}" @selected(request('type') === $type)>{{ $type }}</option>@endforeach</select></label>
                <button class="button button--small" type="submit">Apply filters</button>
            </form>
        </section>

        <section class="admin-panel-card admin-table-card">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Lead</th>
                        <th>Type</th>
                        <th>Details</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leads as $lead)
                        <tr>
                            <td>
                                <strong>{{ $lead->full_name ?: $lead->first_name ?: 'Unknown' }}</strong>
                                <small>{{ $lead->email }}</small>
                                <small>Status: {{ ucfirst($lead->status ?? 'new') }}</small>
                            </td>
                            <td><span class="admin-badge">{{ $lead->form_type }}</span></td>
                            <td>
                                <strong>{{ data_get($lead->metadata, 'listing_name') ?: ($lead->goal ?: 'General enquiry') }}</strong>
                                @if(data_get($lead->metadata, 'preferred_date') || data_get($lead->metadata, 'preferred_time'))
                                    <small>{{ data_get($lead->metadata, 'preferred_date') ?: 'No date selected' }}{{ data_get($lead->metadata, 'preferred_time') ? ' | ' . data_get($lead->metadata, 'preferred_time') : '' }}</small>
                                @elseif(data_get($lead->metadata, 'message'))
                                    <small>{{ \Illuminate\Support\Str::limit(data_get($lead->metadata, 'message'), 110) }}</small>
                                @elseif(data_get($lead->metadata, 'help_details'))
                                    <small>{{ \Illuminate\Support\Str::limit(data_get($lead->metadata, 'help_details'), 110) }}</small>
                                @else
                                    <small>No extra notes provided.</small>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $lead->created_at?->format('d M Y') }}</strong>
                                <small>{{ $lead->created_at?->format('h:i A') }}</small>
                            </td>
                            <td>
                                <div class="admin-table-actions">
                                    <a class="text-link" href="{{ route('admin.leads.edit', $lead) }}">Open</a>
                                    <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}" onsubmit="return confirm('Delete this lead permanently?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="admin-delete-link" type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No leads captured yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="admin-pagination">{{ $leads->links() }}</div>
        </section>
    </div>
@endsection
