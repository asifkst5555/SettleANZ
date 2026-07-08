@extends('admin.layouts.app')

@section('page-title', 'Edit Campaign')

@section('content')
<style>
    .cmp-perf-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; text-align:center; }
    .cmp-perf-value { font-size:1.5rem; font-weight:700; color:#1f2937; }
    .cmp-perf-label { font-size:0.8125rem; color:#6b7280; }
</style>

<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">Ebook System</p>
            <h2>Edit: {{ $campaign->name }}</h2>
            <p>Update campaign settings or send it now</p>
        </div>
        <div style="display:flex;gap:0.5rem;">
            <a href="{{ route('admin.campaigns.index') }}" class="button button--small button--ghost">&larr; Back</a>
            @if($campaign->isDraft())
            <form action="{{ route('admin.campaigns.duplicate', $campaign) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="button button--small" style="background:#f3f4f6;color:#374151;border:1px solid #d7e1ea;">Duplicate</button>
            </form>
            <form action="{{ route('admin.campaigns.send', $campaign) }}" method="POST" style="display:inline;" onsubmit="return confirm('Send this campaign now?')">
                @csrf
                <button type="submit" class="button button--small" style="background:linear-gradient(135deg,#10b981 0%,#059669 100%);color:white;">Send Now</button>
            </form>
            @endif
        </div>
    </section>

    <form action="{{ route('admin.campaigns.update', $campaign) }}" method="POST" class="admin-edit-form">
        @csrf
        @method('PUT')
        <section class="admin-panel-card">
            <div class="admin-settings-section">
                <div class="admin-settings-section__head">
                    <h3>Campaign Details</h3>
                </div>
                <div class="admin-form-grid">
                    <label class="admin-form-grid__full">
                        <span>Campaign Name *</span>
                        <input type="text" name="name" value="{{ old('name', $campaign->name) }}" required class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </label>
                    <label>
                        <span>Email Template *</span>
                        <select name="email_template_id" required class="pro-select w-full">
                            @foreach($templates as $t)
                            <option value="{{ $t->id }}" @selected(old('email_template_id', $campaign->email_template_id) == $t->id)>{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label>
                        <span>Ebook</span>
                        <select name="ebook_id" class="pro-select w-full">
                            <option value="">None</option>
                            @foreach($ebooks as $ebook)
                            <option value="{{ $ebook->id }}" @selected(old('ebook_id', $campaign->ebook_id) == $ebook->id)>{{ $ebook->title }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="admin-form-grid__full">
                        <span>Description</span>
                        <textarea name="description" rows="3" class="w-full border rounded px-3 py-2 @error('description') border-red-500 @enderror">{{ old('description', $campaign->description) }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </label>

                    @if($campaign->sent_count > 0)
                    <div class="admin-form-grid__full" style="background:#f9fafb;border-radius:0.75rem;padding:1.25rem;">
                        <h4 style="font-size:0.875rem;font-weight:600;color:#374151;margin-bottom:0.75rem;">Performance</h4>
                        <div class="cmp-perf-grid">
                            <div><div class="cmp-perf-value">{{ $campaign->sent_count }}</div><div class="cmp-perf-label">Sent</div></div>
                            <div><div class="cmp-perf-value">{{ $campaign->open_count }}</div><div class="cmp-perf-label">Opens</div></div>
                            <div><div class="cmp-perf-value">{{ $campaign->click_count }}</div><div class="cmp-perf-label">Clicks</div></div>
                            <div><div class="cmp-perf-value">{{ $campaign->bounce_count }}</div><div class="cmp-perf-label">Bounces</div></div>
                        </div>
                    </div>
                    @endif

                    <label>
                        <span>Status</span>
                        <div style="padding-top:0.375rem;">
                            <span style="display:inline-block;padding:0.375rem 0.75rem;border-radius:0.25rem;font-size:0.85rem;font-weight:500;
                                @if($campaign->status === 'sent') background:#d1fae5;color:#065f46;
                                @elseif($campaign->status === 'scheduled') background:#dbeafe;color:#1e40af;
                                @elseif($campaign->status === 'sending') background:#fef3c7;color:#92400e;
                                @elseif($campaign->status === 'cancelled') background:#fee2e2;color:#7f1d1d;
                                @else background:#f3f4f6;color:#6b7280; @endif">
                                {{ ucfirst($campaign->status) }}
                            </span>
                            @if($campaign->sent_at) &mdash; Sent {{ $campaign->sent_at->diffForHumans() }} @endif
                            @if($campaign->completed_at) &mdash; Completed {{ $campaign->completed_at->diffForHumans() }} @endif
                        </div>
                    </label>
                </div>
            </div>
        </section>

        <div style="display:flex;gap:1rem;padding-top:1rem;">
            <button type="submit" style="background:linear-gradient(135deg,#10b981 0%,#059669 100%);color:white;padding:0.75rem 2rem;border:none;border-radius:0.375rem;cursor:pointer;font-weight:600;font-size:0.9375rem;">Update Campaign</button>
            <a href="{{ route('admin.campaigns.index') }}" style="padding:0.75rem 2rem;border:1px solid #d7e1ea;border-radius:0.375rem;text-decoration:none;color:#476072;font-weight:500;">Cancel</a>
        </div>
    </form>
</div>
@endsection
