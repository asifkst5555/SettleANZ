@extends('admin.layouts.app')

@section('page-title', 'Users')

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">System</p>
            <h2>User Management</h2>
            <p>Manage all admin users, roles, and account status.</p>
        </div>
        <a class="button button--small" href="{{ route('admin.system.users.create') }}">Create User</a>
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
                        <th>User</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:0.6rem">
                                    <div class="admin-profile__avatar" style="width:32px;height:32px;font-size:0.85rem">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <strong>{{ $user->name }}</strong>
                                </div>
                            </td>
                            <td><small>{{ $user->email }}</small></td>
                            <td>
                                @forelse ($user->roles as $role)
                                    <span class="admin-badge" @if($role->color) style="border-left:3px solid {{ $role->color }}" @endif>
                                        {{ $role->name }}
                                    </span>
                                @empty
                                    <span class="admin-badge admin-badge--muted">No role</span>
                                @endforelse
                            </td>
                            <td>
                                @if ($user->is_suspended)
                                    <span class="admin-badge admin-badge--error">Suspended</span>
                                @elseif ($user->is_active)
                                    <span class="admin-badge admin-badge--success">Active</span>
                                @else
                                    <span class="admin-badge admin-badge--muted">Inactive</span>
                                @endif
                            </td>
                            <td><small>{{ $user->last_login_at?->diffForHumans() ?? 'Never' }}</small></td>
                            <td>
                                <div style="display:flex;gap:0.35rem;flex-wrap:wrap">
                                    <a class="text-link" href="{{ route('admin.system.users.edit', $user) }}">Edit</a>
                                    <a class="text-link" href="{{ route('admin.system.users.login-history', $user) }}">Logins</a>
                                    <a class="text-link" href="{{ route('admin.system.users.activity', $user) }}">Activity</a>
                                    @if (!$user->isSuperAdmin())
                                        <form method="POST" action="{{ route('admin.system.impersonate', $user) }}" style="display:inline">
                                            @csrf
                                            <button type="submit" class="text-link" style="background:none;border:none;cursor:pointer;padding:0;font:inherit;color:var(--admin-link)">Impersonate</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="text-align:center;padding:2rem;color:var(--admin-muted)">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:1rem"> {{ $users->links() }} </div>
    </section>
</div>
@endSection