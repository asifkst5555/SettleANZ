@extends('admin.layouts.app')

@section('content')
<style>
.se-grid { display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1fr);gap:1.1rem;align-items:start; }
@media(max-width:768px){ .se-grid{grid-template-columns:1fr;} }
.se-card { background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:1.15rem; }
.se-card h3 { margin:0 0 .85rem;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#64748b; }
.se-field { display:flex;flex-direction:column;gap:.2rem;margin-bottom:.75rem; }
.se-field label { font-size:.75rem;font-weight:600;color:#475569; }
.se-field input,.se-field select,.se-field textarea { padding:.4rem .6rem;border:1px solid #e2e8f0;border-radius:8px;font-size:.82rem;transition:border-color .15s; }
.se-field input:focus,.se-field select:focus,.se-field textarea:focus { outline:2px solid #14a394;outline-offset:-1px;border-color:transparent; }
.se-field textarea { min-height:70px;resize:vertical; }
</style>

<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">Edit Lead</p>
            <h2>{{ $lead->full_name ?: $lead->first_name ?: 'Lead' }}</h2>
            <p>{{ $lead->email }} · {{ $lead->lead_source_label }} · {{ $lead->created_at?->format('d M Y h:i A') }}</p>
        </div>
        <div class="admin-topbar__actions">
            <a class="button button--small button--ghost" href="{{ route('admin.leads.index') }}">Back to list</a>
            <a class="button button--small" href="{{ route('admin.leads.show', $lead) }}">View profile</a>
            @can('lead_center.delete')
            <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}" onsubmit="return confirmDelete(this, 'lead')">
                @csrf @method('DELETE')
                <button class="button button--small button--danger" type="submit">Delete</button>
            </form>
            @endcan
        </div>
    </section>

    <form method="POST" action="{{ route('admin.leads.update', $lead) }}">
        @csrf @method('PUT')
        <div class="se-grid">
            <div>
                <div class="se-card">
                    <h3>Contact Information</h3>
                    <div class="se-field"><label>Full Name</label><input type="text" name="full_name" value="{{ old('full_name', $lead->full_name) }}"></div>
                    <div class="se-field"><label>Email</label><input type="email" name="email" value="{{ old('email', $lead->email) }}"></div>
                    <div class="se-field"><label>Phone</label><input type="text" name="phone" value="{{ old('phone', $lead->phone) }}"></div>
                    <div class="se-field"><label>Company</label><input type="text" name="company" value="{{ old('company', $lead->company) }}"></div>
                    <div class="se-field"><label>Country</label><input type="text" name="country" value="{{ old('country', $lead->country) }}"></div>
                </div>

                <div class="se-card">
                    <h3>Lead Classification</h3>
                    <div class="se-field">
                        <label>Status</label>
                        <select name="status">
                            @foreach(array_keys($statusColors) as $s)
                                <option value="{{ $s }}" @selected(old('status', $lead->status) === $s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="se-field">
                        <label>Priority</label>
                        <select name="priority">
                            @foreach($priorities as $p)
                                <option value="{{ $p }}" @selected(old('priority', $lead->priority) === $p)>{{ ucfirst($p) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="se-field">
                        <label>Assigned Staff</label>
                        <select name="assigned_to">
                            <option value="">Unassigned</option>
                            @foreach($staff as $s)
                                <option value="{{ $s->id }}" @selected(old('assigned_to', $lead->assigned_to) == $s->id)>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="se-field" style="flex-direction:row;gap:.5rem;align-items:center;">
                        <label style="margin:0;font-weight:400;"><input type="checkbox" name="is_archived" value="1" @checked(old('is_archived', $lead->is_archived))> Archived</label>
                    </div>
                </div>

                <div class="se-card">
                    <h3>Service Details</h3>
                    <div class="se-field">
                        <label>Visa Type</label>
                        <select name="visa_type">
                            <option value="">Not applicable</option>
                            @foreach($visaTypes as $k => $v)
                                <option value="{{ $k }}" @selected(old('visa_type', $lead->visa_type) === $k)>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="se-field"><label>Package Name</label><input type="text" name="package_name" value="{{ old('package_name', $lead->package_name) }}"></div>
                    <div class="se-field"><label>Interested Service</label><input type="text" name="interested_service" value="{{ old('interested_service', $lead->interested_service) }}"></div>
                    <div class="se-field"><label>Budget ($)</label><input type="number" name="budget" step="0.01" min="0" value="{{ old('budget', $lead->budget) }}"></div>
                </div>
            </div>

            <div>
                <div class="se-card">
                    <h3>Tags</h3>
                    <div style="display:flex;gap:4px;flex-wrap:wrap;">
                        @foreach($tags as $tag)
                            @php $sel = $lead->tags->contains($tag->id); @endphp
                            <label style="display:inline-flex;align-items:center;gap:3px;padding:.2rem .5rem;border-radius:999px;font-size:.72rem;font-weight:600;cursor:pointer;background:{{ $tag->color }}22;color:{{ $tag->color }};border:2px solid {{ $sel ? $tag->color : 'transparent' }};">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ $sel ? 'checked' : '' }} style="display:none;">
                                {{ $tag->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="se-card">
                    <h3>Source Information</h3>
                    <div class="se-field">
                        <label>Lead Source</label>
                        <select name="form_type">
                            @foreach($formTypes as $k => $v)
                                <option value="{{ $k }}" @selected(old('form_type', $lead->form_type) === $k)>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="se-field">
                        <label>Website Page</label>
                        <select name="source_page">
                            <option value="">Auto-detect</option>
                            @foreach($sourcePages as $k => $v)
                                <option value="{{ $k }}" @selected(old('source_page', $lead->source_page) === $k)>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="se-field"><label>Landing Page Name</label><input type="text" name="landing_page_name" value="{{ old('landing_page_name', $lead->landing_page_name) }}" placeholder="e.g. Student Visa Landing"></div>
                </div>

                <div class="se-card">
                    <h3>Notes</h3>
                    <div class="se-field">
                        <textarea name="notes" rows="8">{{ old('notes', $lead->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:.65rem;justify-content:flex-end;margin-top:1rem;padding-top:.85rem;border-top:1px solid #e2e8f0;">
            <a href="{{ route('admin.leads.index') }}" class="button button--small button--ghost">Cancel</a>
            <button class="button button--large" type="submit">Save Changes</button>
        </div>
    </form>
</div>
@endsection
