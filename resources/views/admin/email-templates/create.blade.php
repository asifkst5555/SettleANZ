@extends('admin.layouts.app')

@section('page-title', 'Create Email Template')

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">Ebook System</p>
            <h2>Create Email Template</h2>
            <p>Design a new email template for download notifications or campaigns</p>
        </div>
        <a href="{{ route('admin.email-templates.index') }}" class="button button--small button--ghost">&larr; Back</a>
    </section>

    <form action="{{ route('admin.email-templates.store') }}" method="POST" class="admin-edit-form">
        @csrf
        <section class="admin-panel-card">
            <div class="admin-settings-section">
                <div class="admin-settings-section__head">
                    <h3>Template Details</h3>
                </div>
                <div class="admin-form-grid">
                    <label class="admin-form-grid__full">
                        <span>Name *</span>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </label>
                    <label>
                        <span>Type *</span>
                        <select name="type" required class="pro-select w-full">
                            <option value="">Select type...</option>
                            @foreach($types as $t)
                            <option value="{{ $t }}" @selected(old('type') === $t)>{{ ucfirst(str_replace('_', ' ', $t)) }}</option>
                            @endforeach
                        </select>
                        @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </label>
                    <label>
                        <span>Active</span>
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" checked style="accent-color:#0f8b8d;width:1.25rem;height:1.25rem;">
                    </label>
                    <label class="admin-form-grid__full">
                        <span>Subject *</span>
                        <input type="text" name="subject" value="{{ old('subject') }}" required class="w-full border rounded px-3 py-2 @error('subject') border-red-500 @enderror">
                        <small style="display:block;margin-top:6px;color:#667788;">Use variables like <code>{{ '{{ lead_name }}' }}</code>, <code>{{ '{{ ebook_title }}' }}</code>, etc.</small>
                        @error('subject') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </label>
                    <label class="admin-form-grid__full">
                        <span>HTML Body *</span>
                        <textarea name="body_html" rows="15" required class="w-full border rounded px-3 py-2 font-mono text-sm @error('body_html') border-red-500 @enderror">{{ old('body_html') }}</textarea>
                        <small style="display:block;margin-top:6px;color:#667788;">
                            Available variables:
                            @foreach($availableVariables as $var)
                            <code style="background:#f3f4f6;padding:0.125rem 0.375rem;border-radius:0.25rem;">{{ $var }}</code>
                            @endforeach
                        </small>
                        @error('body_html') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </label>
                </div>
            </div>
        </section>

        <div style="display:flex;gap:1rem;padding-top:1rem;">
            <button type="submit" style="background:linear-gradient(135deg,#10b981 0%,#059669 100%);color:white;padding:0.75rem 2rem;border:none;border-radius:0.375rem;cursor:pointer;font-weight:600;font-size:0.9375rem;">Create Template</button>
            <a href="{{ route('admin.email-templates.index') }}" style="padding:0.75rem 2rem;border:1px solid #d7e1ea;border-radius:0.375rem;text-decoration:none;color:#476072;font-weight:500;">Cancel</a>
        </div>
    </form>
</div>
@endsection
