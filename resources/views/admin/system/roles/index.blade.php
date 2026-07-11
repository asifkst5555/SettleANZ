@extends('admin.layouts.app')

@section('page-title', 'Roles')

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">System</p>
            <h2>Role Management</h2>
            <p>Create, edit, and manage roles and their permissions across the system.</p>
        </div>
        <div style="display:flex;gap:0.5rem">
            <a class="button button--small" href="{{ route('admin.system.permissions.matrix') }}">Permission Matrix</a>
            <a class="button button--small" href="{{ route('admin.system.roles.create') }}">Create Role</a>
        </div>
    </section>

    @if (session('status'))
        <div class="admin-alert admin-alert--success">{{ session('status') }}</div>
    @endif
    @if (session('error'))
        <div class="admin-alert admin-alert--error">{{ session('error') }}</div>
    @endif

    <section class="admin-panel-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Role</th>
                        <th>Description</th>
                        <th>Priority</th>
                        <th>Users</th>
                        <th>Permissions</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $role)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:0.5rem">
                                    @if ($role->color)
                                        <span style="display:inline-block;width:12px;height:12px;border-radius:50%;background:{{ $role->color }}"></span>
                                    @endif
                                    <strong>{{ $role->name }}</strong>
                                </div>
                                <small style="color:var(--admin-muted)">{{ $role->slug }}</small>
                            </td>
                            <td><small>{{ Str::limit($role->description, 60) }}</small></td>
                            <td>{{ $role->priority }}</td>
                            <td>{{ $role->users_count }}</td>
                            <td>{{ $role->permissions_count }}</td>
                            <td>
                                @if ($role->is_super)
                                    <span class="admin-badge admin-badge--super">Super Admin</span>
                                @elseif ($role->is_default)
                                    <span class="admin-badge admin-badge--default">Default</span>
                                @endif
                                <span class="admin-badge {{ $role->is_active ? 'admin-badge--success' : 'admin-badge--muted' }}">
                                    {{ $role->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div style="display:flex;gap:0.35rem">
                                    <a class="text-link" href="{{ route('admin.system.roles.edit', $role) }}">Edit</a>
                                    @if (!$role->is_super)
                                        <a class="text-link" href="{{ route('admin.system.roles.permissions', $role) }}">Permissions</a>
                                        <form method="POST" action="{{ route('admin.system.roles.clone', $role) }}" style="display:inline">
                                            @csrf
                                            <button type="submit" class="text-link" style="background:none;border:none;cursor:pointer;padding:0;font:inherit;color:var(--admin-link)">Clone</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.system.roles.destroy', $role) }}" style="display:inline" onsubmit="return confirm('Delete this role?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-link text-link--danger" style="background:none;border:none;cursor:pointer;padding:0;font:inherit">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--admin-muted)">No roles found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:1rem"> {{ $roles->links() }} </div>
    </section>
</div>
@endsection