@extends('admin.layouts.app')

@section('page-title', 'Create Feature Flag')

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">System / Feature Flags</p>
            <h2>Create Feature Flag</h2>
        </div>
        <a class="button button--small" href="{{ route('admin.system.feature-flags.index') }}">Back to Flags</a>
    </section>

    <form method="POST" action="{{ route('admin.system.feature-flags.store') }}">
        @csrf
        <section class="admin-panel-card">
            <div class="admin-form-group">
                <label class="admin-label">Module Key *</label>
                <input class="admin-input" name="module_key" value="{{ old('module_key') }}" required placeholder="e.g. dashboard, lead_center, ai_writer">
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Name *</label>
                <input class="admin-input" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Group *</label>
                <input class="admin-input" name="group" value="{{ old('group') }}" required placeholder="e.g. General, AI, Marketing">
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Description</label>
                <textarea class="admin-input" name="description" rows="3">{{ old('description') }}</textarea>
            </div>
            <div class="admin-form-group" style="display:flex;gap:1.5rem">
                <label><input type="checkbox" name="is_enabled" value="1" checked> Enabled</label>
                <label><input type="checkbox" name="is_visible" value="1" checked> Visible</label>
            </div>
        </section>
        <div style="display:flex;gap:0.75rem;margin-top:1.5rem">
            <button class="button button--small" type="submit">Create Flag</button>
            <a class="button button--small button--secondary" href="{{ route('admin.system.feature-flags.index') }}">Cancel</a>
        </div>
    </form>
</div>
@endSection