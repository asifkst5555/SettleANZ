@extends('admin.layouts.app')

@section('page-title', 'Edit User: ' . $user->name)

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">System / Users</p>
            <h2>Edit User: {{ $user->name }}</h2>
            <p>{{ $user->email }}</p>
        </div>
        <div style="display:flex;gap:0.5rem">
            @if ($user->is_suspended)
                <form method="POST" action="{{ route('admin.system.users.activate', $user) }}" style="display:inline">
                    @csrf @method('PUT')
                    <button class="button button--small" type="submit">Activate</button>
                </form>
            @elseif (!$user->isSuperAdmin())
                <form method="POST" action="{{ route('admin.system.users.suspend', $user) }}" style="display:inline" onsubmit="return prompt('Suspension reason:')">
                    @csrf @method('PUT')
                    <input type="hidden" name="reason" id="suspendReason">
                    <button class="button button--small button--secondary" type="submit" id="suspendBtn">Suspend</button>
                </form>
            @endif
            <a class="button button--small" href="{{ route('admin.system.users.index') }}">Back to Users</a>
        </div>
    </section>

    @if (session('status'))
        <div class="admin-alert admin-alert--success">{{ session('status') }}</div>
    @endif
    @if (session('error'))
        <div class="admin-alert admin-alert--error">{{ session('error') }}</div>
    @endif

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem">
        <div>
            <form method="POST" action="{{ route('admin.system.users.update', $user) }}">
                @csrf @method('PUT')
                <section class="admin-panel-card">
                    <h3 style="margin-top:0">Account Details</h3>
                    <div class="admin-form-group">
                        <label class="admin-label">Name *</label>
                        <input class="admin-input" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">Email *</label>
                        <input class="admin-input" name="email" type="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="admin-form-group">
                        <label><input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }} {{ $user->isSuperAdmin() ? 'disabled' : '' }}> Active</label>
                    </div>
                </section>

                <section class="admin-panel-card">
                    <h3 style="margin-top:0">Role Assignment</h3>
                    <div style="display:grid;gap:0.5rem">
                        @forelse ($roles as $role)
                            <label style="display:flex;align-items:center;gap:0.5rem;padding:0.5rem;border:1px solid rgba(16,88,98,0.1);border-radius:8px;cursor:pointer">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}" {{ in_array($role->id, $userRoles) ? 'checked' : '' }}>
                                @if ($role->color)
                                    <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:{{ $role->color }}"></span>
                                @endif
                                <div>
                                    <strong style="font-size:0.88rem">{{ $role->name }}</strong>
                                    @if ($role->description)
                                        <small style="display:block;color:var(--admin-muted);font-size:0.78rem">{{ $role->description }}</small>
                                    @endif
                                </div>
                            </label>
                        @empty
                            <p style="color:var(--admin-muted)">No roles available.</p>
                        @endforelse
                    </div>
                </section>

                <div style="margin-top:0.75rem">
                    <button class="button button--small" type="submit">Update User</button>
                </div>
            </form>

            <section class="admin-panel-card" style="margin-top:1.5rem">
                <h3 style="margin-top:0">Reset Password</h3>
                <form method="POST" action="{{ route('admin.system.users.reset-password', $user) }}" style="display:flex;gap:0.75rem;align-items:end">
                    @csrf @method('PUT')
                    <div class="admin-form-group" style="flex:1;margin-bottom:0">
                        <label class="admin-label">New Password *</label>
                        <input class="admin-input" name="password" type="password" required minlength="8">
                    </div>
                    <div class="admin-form-group" style="flex:1;margin-bottom:0">
                        <label class="admin-label">Confirm *</label>
                        <input class="admin-input" name="password_confirmation" type="password" required>
                    </div>
                    <button class="button button--small" type="submit">Reset</button>
                </form>
            </section>

            @if (!$user->isSuperAdmin() && $user->id !== auth()->id())
                <form method="POST" action="{{ route('admin.system.users.destroy', $user) }}" style="margin-top:1.5rem" onsubmit="return confirm('Delete this user permanently?')">
                    @csrf @method('DELETE')
                    <button class="button button--small button--danger" type="submit" style="background:#dc3545;color:white;border-color:#dc3545">Delete User</button>
                </form>
            @endif
        </div>

        <div>
            <section class="admin-panel-card">
                <h3 style="margin-top:0">Account Status</h3>
                <div style="display:grid;gap:0.75rem">
                    <div style="display:flex;justify-content:space-between">
                        <span>Status</span>
                        <span>
                            @if ($user->is_suspended)
                                <span class="admin-badge admin-badge--error">Suspended</span>
                            @elseif ($user->is_active)
                                <span class="admin-badge admin-badge--success">Active</span>
                            @else
                                <span class="admin-badge admin-badge--muted">Inactive</span>
                            @endif
                        </span>
                    </div>
                    <div style="display:flex;justify-content:space-between">
                        <span>Last Login</span>
                        <small>{{ $user->last_login_at?->format('d M Y H:i') ?? 'Never' }}</small>
                    </div>
                    <div style="display:flex;justify-content:space-between">
                        <span>Created</span>
                        <small>{{ $user->created_at?->format('d M Y') }}</small>
                    </div>
                    @if ($user->is_suspended)
                        <div style="display:flex;justify-content:space-between">
                            <span>Suspended</span>
                            <small>{{ $user->suspended_at?->format('d M Y H:i') }}</small>
                        </div>
                    @endif
                </div>
            </section>

            <section class="admin-panel-card" style="margin-top:1rem">
                <div class="admin-section-head">
                    <h3 style="margin:0">Recent Logins</h3>
                    <a class="text-link" href="{{ route('admin.system.users.login-history', $user) }}">View all</a>
                </div>
                <div style="margin-top:0.75rem">
                    @forelse ($loginHistory as $entry)
                        <div style="display:flex;justify-content:space-between;padding:0.4rem 0;border-bottom:1px solid rgba(16,88,98,0.06)">
                            <small>{{ $entry->event }}</small>
                            <small style="color:var(--admin-muted)">{{ $entry->created_at->diffForHumans() }}</small>
                        </div>
                    @empty
                        <p style="color:var(--admin-muted);font-size:0.88rem">No login history.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var suspendBtn = document.getElementById('suspendBtn');
    if (suspendBtn) {
        suspendBtn.addEventListener('click', function(e) {
            var reason = prompt('Suspension reason:');
            if (reason) {
                document.getElementById('suspendReason').value = reason;
            } else {
                e.preventDefault();
            }
        });
    }
});
</script>
@endsection