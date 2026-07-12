@extends('admin.layouts.app')

@section('page-title', 'Feature Flags')

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">System</p>
            <h2>Feature Flags</h2>
            <p>Enable, disable, or hide modules across the system.</p>
        </div>
        <a class="button button--small" href="{{ route('admin.system.feature-flags.create') }}">Create Flag</a>
    </section>

    @if (session('status'))
        <div class="admin-alert admin-alert--success">{{ session('status') }}</div>
    @endif

    <section class="admin-panel-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Module</th>
                        <th>Group</th>
                        <th>Enabled</th>
                        <th>Visible</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($flags as $flag)
                        <tr>
                            <td>
                                <strong>{{ $flag->name }}</strong>
                                <small style="display:block;color:var(--admin-muted);font-size:0.78rem">{{ $flag->module_key }}</small>
                            </td>
                            <td><span class="admin-badge">{{ $flag->group }}</span></td>
                            <td>
                                <form method="POST" action="{{ route('admin.system.feature-flags.toggle', $flag) }}" style="display:inline">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="is_enabled" value="{{ $flag->is_enabled ? '0' : '1' }}">
                                    <input type="hidden" name="is_visible" value="{{ $flag->is_visible ? '1' : '0' }}">
                                    <button type="submit" class="admin-toggle {{ $flag->is_enabled ? 'admin-toggle--on' : '' }}" aria-label="Toggle enabled">
                                        <span class="admin-toggle__thumb"></span>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.system.feature-flags.toggle', $flag) }}" style="display:inline">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="is_visible" value="{{ $flag->is_visible ? '0' : '1' }}">
                                    <input type="hidden" name="is_enabled" value="{{ $flag->is_enabled ? '1' : '0' }}">
                                    <button type="submit" class="admin-toggle {{ $flag->is_visible ? 'admin-toggle--on' : '' }}" aria-label="Toggle visibility">
                                        <span class="admin-toggle__thumb"></span>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div style="display:flex;gap:0.35rem">
                                    <a class="text-link" href="{{ route('admin.system.feature-flags.edit', $flag) }}">Edit</a>
                                    <form method="POST" action="{{ route('admin.system.feature-flags.destroy', $flag) }}" style="display:inline" onsubmit="return confirmDelete(this, 'flag')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-link text-link--danger" style="background:none;border:none;cursor:pointer;padding:0;font:inherit;color:var(--admin-danger)">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align:center;padding:2rem;color:var(--admin-muted)">No feature flags defined.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:1rem"> {{ $flags->links() }} </div>
    </section>
</div>
<style>
.admin-toggle { display:inline-flex; align-items:center; width:44px; height:24px; padding:2px; border-radius:999px; border:1px solid rgba(16,88,98,0.16); background:#e2e8f0; cursor:pointer; transition:all 0.2s }
.admin-toggle--on { background:#14a394; border-color:#14a394 }
.admin-toggle__thumb { display:block; width:18px; height:18px; border-radius:50%; background:white; box-shadow:0 1px 3px rgba(0,0,0,0.15); transition:transform 0.2s }
.admin-toggle--on .admin-toggle__thumb { transform:translateX(20px) }
</style>
@endsection