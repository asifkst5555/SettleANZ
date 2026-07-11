@extends('admin.layouts.app')

@section('page-title', 'Create User')

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">System / Users</p>
            <h2>Create User</h2>
            <p>Add a new admin user with role assignments.</p>
        </div>
        <a class="button button--small" href="{{ route('admin.system.users.index') }}">Back to Users</a>
    </section>

    <form method="POST" action="{{ route('admin.system.users.store') }}">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem">
            <section class="admin-panel-card">
                <h3 style="margin-top:0">Account Details</h3>
                <div class="admin-form-group">
                    <label class="admin-label">Name *</label>
                    <input class="admin-input" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Email *</label>
                    <input class="admin-input" name="email" type="email" value="{{ old('email') }}" required>
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Password *</label>
                    <input class="admin-input" name="password" type="password" required>
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Confirm Password *</label>
                    <input class="admin-input" name="password_confirmation" type="password" required>
                </div>
                <div class="admin-form-group">
                    <label><input type="checkbox" name="is_active" value="1" checked> Active</label>
                </div>
            </section>

            <section class="admin-panel-card">
                <h3 style="margin-top:0">Role Assignment</h3>
                <p style="color:var(--admin-muted);font-size:0.88rem">Select one or more roles for this user.</p>
                <div style="display:grid;gap:0.5rem;margin-top:0.75rem">
                    @forelse ($roles as $role)
                        <label style="display:flex;align-items:center;gap:0.5rem;padding:0.5rem;border:1px solid rgba(16,88,98,0.1);border-radius:8px;cursor:pointer">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
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
                        <p style="color:var(--admin-muted)">No roles available. <a href="{{ route('admin.system.roles.create') }}">Create a role</a> first.</p>
                    @endforelse
                </div>
            </section>
        </div>

        <div style="display:flex;gap:0.75rem;margin-top:1.5rem">
            <button class="button button--small" type="submit">Create User</button>
            <a class="button button--small button--secondary" href="{{ route('admin.system.users.index') }}">Cancel</a>
        </div>
    </form>
</div>
@endsection