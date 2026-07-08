@extends('admin.layouts.app')

@section('page-title', 'Create Campaign')

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">Ebook System</p>
            <h2>Create Campaign</h2>
            <p>Set up a new email campaign for your ebook</p>
        </div>
        <a href="{{ route('admin.campaigns.index') }}" class="button button--small button--ghost">&larr; Back</a>
    </section>

    <form action="{{ route('admin.campaigns.store') }}" method="POST" class="admin-edit-form">
        @csrf
        <section class="admin-panel-card">
            <div class="admin-settings-section">
                <div class="admin-settings-section__head">
                    <h3>Campaign Details</h3>
                </div>
                <div class="admin-form-grid">
                    <label class="admin-form-grid__full">
                        <span>Campaign Name *</span>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </label>
                    <label>
                        <span>Email Template *</span>
                        <select name="email_template_id" required class="pro-select w-full">
                            <option value="">Select template...</option>
                            @foreach($templates as $t)
                            <option value="{{ $t->id }}" @selected(old('email_template_id') == $t->id)>{{ $t->name }}</option>
                            @endforeach
                        </select>
                        @error('email_template_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </label>
                    <label>
                        <span>Ebook</span>
                        <select name="ebook_id" class="pro-select w-full">
                            <option value="">None</option>
                            @foreach($ebooks as $ebook)
                            <option value="{{ $ebook->id }}" @selected(old('ebook_id') == $ebook->id)>{{ $ebook->title }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="admin-form-grid__full">
                        <span>Description</span>
                        <textarea name="description" rows="3" class="w-full border rounded px-3 py-2 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </label>
                </div>
            </div>
        </section>

        <div style="display:flex;gap:1rem;padding-top:1rem;">
            <button type="submit" style="background:linear-gradient(135deg,#10b981 0%,#059669 100%);color:white;padding:0.75rem 2rem;border:none;border-radius:0.375rem;cursor:pointer;font-weight:600;font-size:0.9375rem;">Create Campaign</button>
            <a href="{{ route('admin.campaigns.index') }}" style="padding:0.75rem 2rem;border:1px solid #d7e1ea;border-radius:0.375rem;text-decoration:none;color:#476072;font-weight:500;">Cancel</a>
        </div>
    </form>
</div>
@endsection
