@extends('admin.layouts.app')

@section('page-title', 'Edit Feature Flag')

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">System / Feature Flags</p>
            <h2>Edit: {{ $flag->name }}</h2>
        </div>
        <a class="button button--small" href="{{ route('admin.system.feature-flags.index') }}">Back to Flags</a>
    </section>

    <form method="POST" action="{{ route('admin.system.feature-flags.update', $flag) }}">
        @csrf @method('PUT')
        <section class="admin-panel-card">
            <div class="admin-form-group">
                <label class="admin-label">Module Key *</label>
                <input class="admin-input" name="module_key" value="{{ old('module_key', $flag->module_key) }}" required>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Name *</label>
                <input class="admin-input" name="name" value="{{ old('name', $flag->name) }}" required>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Group *</label>
                <input class="admin-input" name="group" value="{{ old('group', $flag->group) }}" required>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Description</label>
                <textarea class="admin-input" name="description" rows="3">{{ old('description', $flag->description) }}</textarea>
            </div>
            <div class="admin-form-group" style="display:flex;gap:1.5rem">
                <label><input type="checkbox" name="is_enabled" value="1" {{ $flag->is_enabled ? 'checked' : '' }}> Enabled</label>
                <label><input type="checkbox" name="is_visible" value="1" {{ $flag->is_visible ? 'checked' : '' }}> Visible</label>
            </div>
        </section>
        <div style="display:flex;gap:0.75rem;margin-top:1.5rem">
            <button class="button button--small" type="submit">Update Flag</button>
            <a class="button button--small button--secondary" href="{{ route('admin.system.feature-flags.index') }}">Cancel</a>
        </div>
    </form>
</div>
@endsection