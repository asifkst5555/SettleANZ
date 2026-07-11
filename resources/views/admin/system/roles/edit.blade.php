@extends('admin.layouts.app')

@section('page-title', 'Edit Role: ' . $role->name)

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">System / Roles</p>
            <h2>Edit Role: {{ $role->name }}</h2>
            <p>{{ $role->description }}</p>
        </div>
        <div style="display:flex;gap:0.5rem">
            <a class="button button--small" href="{{ route('admin.system.roles.permissions', $role) }}">Permissions</a>
            <a class="button button--small" href="{{ route('admin.system.roles.index') }}">Back to Roles</a>
        </div>
    </section>

    @if (session('status'))
        <div class="admin-alert admin-alert--success">{{ session('status') }}</div>
    @endif
    @if (session('error'))
        <div class="admin-alert admin-alert--error">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.system.roles.update', $role) }}">
        @csrf @method('PUT')

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem">
            <section class="admin-panel-card">
                <h3 style="margin-top:0">Role Details</h3>
                <div class="admin-form-group">
                    <label class="admin-label">Role Name *</label>
                    <input class="admin-input" name="name" value="{{ old('name', $role->name) }}" required>
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Description</label>
                    <textarea class="admin-input" name="description" rows="3">{{ old('description', $role->description) }}</textarea>
                </div>
                <div class="admin-form-group" style="display:flex;gap:0.5rem;align-items:center">
                    <label class="admin-label" style="margin-bottom:0">Color</label>
                    <input type="color" name="color" value="{{ old('color', $role->color ?? '#e8773a') }}" style="width:48px;height:38px;border:1px solid rgba(16,88,98,0.16);border-radius:6px;cursor:pointer">
                    <input class="admin-input" name="color" value="{{ old('color', $role->color) }}" placeholder="#e8773a" style="max-width:120px">
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Icon</label>
                    <input class="admin-input" name="icon" value="{{ old('icon', $role->icon) }}" placeholder="e.g. users">
                </div>
            </section>

            <section class="admin-panel-card">
                <h3 style="margin-top:0">Configuration</h3>
                <div class="admin-form-group">
                    <label class="admin-label">Landing Page</label>
                    <input class="admin-input" name="landing_page" value="{{ old('landing_page', $role->landing_page) }}" placeholder="admin.dashboard">
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Priority</label>
                    <input class="admin-input" name="priority" type="number" value="{{ old('priority', $role->priority) }}" style="max-width:120px">
                </div>
                <div class="admin-form-group" style="display:flex;gap:1rem">
                    <label><input type="checkbox" name="is_active" value="1" {{ $role->is_active ? 'checked' : '' }}> Active</label>
                    <label><input type="checkbox" name="is_default" value="1" {{ $role->is_default ? 'checked' : '' }} {{ $role->is_super ? 'disabled' : '' }}> Default Role</label>
                </div>
            </section>
        </div>

        <section class="admin-panel-card">
            <div class="admin-section-head">
                <div><h3>Permission Matrix</h3><p>Toggle permissions for this role.</p></div>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:1rem;margin-top:1rem">
                @foreach ($permissionMatrix as $groupKey => $group)
                    <div class="admin-permission-group">
                        <div class="admin-permission-group__header">
                            <strong>{{ $group['name'] }}</strong>
                            <label style="font-size:0.8rem;cursor:pointer">
                                <input type="checkbox" class="group-toggle" data-group="{{ $groupKey }}"> Toggle All
                            </label>
                        </div>
                        <div class="admin-permission-group__items">
                            @foreach ($group['permissions'] as $perm)
                                @php $checked = isset($rolePermissions[$perm['id']]) && $rolePermissions[$perm['id']] @endphp
                                <label class="admin-permission-item">
                                    <input type="checkbox" name="permissions[{{ $perm['id'] }}]" value="1" {{ $checked ? 'checked' : '' }} class="perm-{{ $groupKey }}">
                                    <span>{{ $perm['name'] }}</span>
                                    <small>{{ $perm['slug'] }}</small>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <div style="display:flex;gap:0.75rem;margin-top:1.5rem">
            <button class="button button--small" type="submit">Update Role</button>
            <a class="button button--small button--secondary" href="{{ route('admin.system.roles.index') }}">Cancel</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.group-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            var group = this.dataset.group;
            document.querySelectorAll('.perm-' + group).forEach(function(cb) {
                cb.checked = toggle.checked;
            });
        });
    });
});
</script>
<style>
.admin-permission-group { border:1px solid rgba(16,88,98,0.12); border-radius:10px; overflow:hidden }
.admin-permission-group__header { display:flex; justify-content:space-between; align-items:center; padding:0.75rem 1rem; background:rgba(11,122,117,0.05); border-bottom:1px solid rgba(16,88,98,0.08) }
.admin-permission-group__items { display:grid; gap:0.15rem; padding:0.5rem }
.admin-permission-item { display:flex; align-items:center; gap:0.5rem; padding:0.4rem 0.5rem; border-radius:6px; cursor:pointer; font-size:0.88rem }
.admin-permission-item:hover { background:rgba(11,122,117,0.04) }
.admin-permission-item small { color:var(--admin-muted); font-size:0.75rem; margin-left:auto }
</style>
@endsection