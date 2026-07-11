@extends('admin.layouts.app')

@section('page-title', 'Edit Permission')

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">System / Permissions</p>
            <h2>Edit Permission: {{ $permission->name }}</h2>
        </div>
        <a class="button button--small" href="{{ route('admin.system.permissions.index') }}">Back to Permissions</a>
    </section>

    <form method="POST" action="{{ route('admin.system.permissions.update', $permission) }}">
        @csrf @method('PUT')
        <section class="admin-panel-card">
            <div class="admin-form-group">
                <label class="admin-label">Permission Name *</label>
                <input class="admin-input" name="name" value="{{ old('name', $permission->name) }}" required>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Slug *</label>
                <input class="admin-input" name="slug" value="{{ old('slug', $permission->slug) }}" required>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Group *</label>
                <select class="admin-input" name="group_id" required>
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}" {{ old('group_id', $permission->group_id) == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="admin-form-group">
                <label class="admin-label">Description</label>
                <textarea class="admin-input" name="description" rows="3">{{ old('description', $permission->description) }}</textarea>
            </div>
        </section>
        <div style="display:flex;gap:0.75rem;margin-top:1.5rem">
            <button class="button button--small" type="submit">Update Permission</button>
            <a class="button button--small button--secondary" href="{{ route('admin.system.permissions.index') }}">Cancel</a>
        </div>
    </form>
</div>
@endsection