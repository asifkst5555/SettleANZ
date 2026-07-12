@extends('admin.layouts.app')

@section('page-title', 'Permissions')

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">System</p>
            <h2>Permission Management</h2>
            <p>All available permissions organized by group.</p>
        </div>
        <div style="display:flex;gap:0.5rem">
            <a class="button button--small" href="{{ route('admin.system.permissions.matrix') }}">Permission Matrix</a>
            <a class="button button--small" href="{{ route('admin.system.permissions.create') }}">Create Permission</a>
        </div>
    </section>

    @if (session('status'))
        <div class="admin-alert admin-alert--success">{{ session('status') }}</div>
    @endif

    @foreach ($groups as $group)
        <section class="admin-panel-card" style="margin-bottom:1rem">
            <div class="admin-section-head">
                <div>
                    <h3>{{ $group->name }}</h3>
                    @if ($group->description)
                        <p>{{ $group->description }}</p>
                    @endif
                </div>
                <span class="admin-badge">{{ $group->permissions->count() }} permissions</span>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:0.5rem;margin-top:0.75rem">
                @foreach ($group->permissions as $perm)
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:0.5rem 0.75rem;border:1px solid rgba(16,88,98,0.08);border-radius:8px">
                        <div>
                            <strong style="font-size:0.88rem">{{ $perm->name }}</strong>
                            <small style="display:block;color:var(--admin-muted);font-size:0.78rem">{{ $perm->slug }}</small>
                        </div>
                        <div style="display:flex;gap:0.35rem">
                            <a class="text-link" href="{{ route('admin.system.permissions.edit', $perm) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.system.permissions.destroy', $perm) }}" style="display:inline" onsubmit="return confirmDelete(this, 'permission')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-link text-link--danger" style="background:none;border:none;cursor:pointer;padding:0;font:inherit;color:var(--admin-danger)">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endforeach
</div>
@endsection