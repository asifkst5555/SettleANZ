@extends('admin.layouts.app')

@section('page-title', 'Role Permissions: ' . $role->name)

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">System / Roles</p>
            <h2>Permissions: {{ $role->name }}</h2>
            <p>Configure granular permissions for this role.</p>
        </div>
        <a class="button button--small" href="{{ route('admin.system.roles.edit', $role) }}">Edit Role</a>
    </section>

    @if (session('status'))
        <div class="admin-alert admin-alert--success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.system.roles.update-permissions', $role) }}">
        @csrf @method('PUT')

        <section class="admin-panel-card">
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:1rem">
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
            <button class="button button--small" type="submit">Update Permissions</button>
            <a class="button button--small button--secondary" href="{{ route('admin.system.roles.edit', $role) }}">Cancel</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.group-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            document.querySelectorAll('.perm-' + this.dataset.group).forEach(function(cb) {
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