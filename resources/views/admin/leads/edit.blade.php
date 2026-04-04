@extends('admin.layouts.app')

@section('content')
    <div class="admin-main__inner">
        <section class="admin-topbar">
            <div>
                <p class="eyebrow">Lead details</p>
                <h2>{{ $lead->full_name ?: $lead->first_name ?: 'Lead record' }}</h2>
                <p>{{ $lead->email }} · {{ $lead->form_type }} · {{ $lead->created_at?->format('d M Y h:i A') }}</p>
            </div>
            <div class="admin-topbar__actions">
                <a class="button button--small button--ghost" href="{{ route('admin.leads.index') }}">Back to inbox</a>
                <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}" onsubmit="return confirm('Delete this lead permanently?');">
                    @csrf
                    @method('DELETE')
                    <button class="button button--small button--danger" type="submit">Delete lead</button>
                </form>
            </div>
        </section>

        <div class="admin-two-column-grid admin-two-column-grid--narrow">
            <section class="admin-panel-card">
                <h3>Lead details</h3>
                <dl class="admin-detail-list">
                    <div><dt>Source page</dt><dd>{{ $lead->source_page ?: 'homepage' }}</dd></div>
                    <div><dt>Goal</dt><dd>{{ $lead->goal ?: 'General enquiry' }}</dd></div>
                    <div><dt>Phone</dt><dd>{{ data_get($lead->metadata, 'phone') ?: 'Not provided' }}</dd></div>
                    <div><dt>Subject</dt><dd>{{ data_get($lead->metadata, 'subject') ?: 'Not provided' }}</dd></div>
                    <div><dt>Visa status</dt><dd>{{ data_get($lead->metadata, 'current_visa_status') ?: 'Not provided' }}</dd></div>
                    <div><dt>Citizenship</dt><dd>{{ data_get($lead->metadata, 'country_of_citizenship') ?: 'Not provided' }}</dd></div>
                    <div><dt>Listing</dt><dd>{{ data_get($lead->metadata, 'listing_name') ?: 'Not linked' }}</dd></div>
                    <div><dt>Preferred date</dt><dd>{{ data_get($lead->metadata, 'preferred_date') ?: 'Not provided' }}</dd></div>
                    <div><dt>Preferred time</dt><dd>{{ data_get($lead->metadata, 'preferred_time') ?: 'Not provided' }}</dd></div>
                    <div><dt>Consultation format</dt><dd>{{ data_get($lead->metadata, 'consultation_format') ?: 'Not provided' }}</dd></div>
                </dl>

                @if (data_get($lead->metadata, 'message') || data_get($lead->metadata, 'help_details') || data_get($lead->metadata, 'booking_notes'))
                    <div class="admin-note-block">
                        <h4>Message</h4>
                        <p>{{ data_get($lead->metadata, 'message') ?: data_get($lead->metadata, 'help_details') ?: data_get($lead->metadata, 'booking_notes') }}</p>
                    </div>
                @endif
            </section>

            <section class="admin-panel-card">
                <h3>Update lead</h3>
                <form class="admin-edit-form" method="POST" action="{{ route('admin.leads.update', $lead) }}">
                    @csrf
                    @method('PUT')
                    <label><span>Status</span><select name="status">@foreach ($statuses as $status)<option value="{{ $status }}" @selected($lead->status === $status)>{{ ucfirst($status) }}</option>@endforeach</select></label>
                    <label><span>Internal notes</span><textarea name="notes" rows="8">{{ old('notes', $lead->notes) }}</textarea></label>
                    <button class="button button--large" type="submit">Save changes</button>
                </form>
            </section>
        </div>
    </div>
@endsection
