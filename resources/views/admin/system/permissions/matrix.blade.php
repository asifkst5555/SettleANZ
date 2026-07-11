@extends('admin.layouts.app')

@section('page-title', 'Permission Matrix')

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">System</p>
            <h2>Permission Matrix</h2>
            <p>Overview of all permissions across all roles.</p>
        </div>
        <a class="button button--small" href="{{ route('admin.system.roles.index') }}">Manage Roles</a>
    </section>

    @if ($roles->isEmpty())
        <section class="admin-panel-card">
            <p style="text-align:center;color:var(--admin-muted);padding:2rem">No roles defined yet. <a href="{{ route('admin.system.roles.create') }}">Create a role</a>.</p>
        </section>
    @else
        <section class="admin-panel-card" style="overflow-x:auto">
            <table class="admin-table" style="font-size:0.82rem;min-width:800px">
                <thead>
                    <tr>
                        <th style="position:sticky;left:0;background:white;z-index:1">Module / Permission</th>
                        @foreach ($roles as $role)
                            <th style="text-align:center;min-width:120px">
                                @if ($role->color)
                                    <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:{{ $role->color }};margin-right:4px"></span>
                                @endif
                                {{ $role->name }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissionMatrix as $groupKey => $group)
                        <tr style="background:rgba(11,122,117,0.04)">
                            <td colspan="{{ $roles->count() + 1 }}" style="font-weight:700;padding:0.6rem 1rem">
                                {{ $group['name'] }}
                            </td>
                        </tr>
                        @foreach ($group['permissions'] as $perm)
                            <tr>
                                <td style="padding:0.45rem 1rem">
                                    <span>{{ $perm['name'] }}</span>
                                    <small style="display:block;color:var(--admin-muted);font-size:0.72rem">{{ $perm['slug'] }}</small>
                                </td>
                                @foreach ($roles as $role)
                                    @php
                                        $allowed = isset($rolePermissions[$role->id][$perm['id']]) && $rolePermissions[$role->id][$perm['id']];
                                    @endphp
                                    <td style="text-align:center">
                                        @if ($role->is_super)
                                            <span style="color:#14a394;font-weight:700">&#10003;</span>
                                        @else
                                            <span style="color:{{ $allowed ? '#14a394' : '#ccc' }};font-size:1.1rem">
                                                {{ $allowed ? '&#10003;' : '&#8212;' }}
                                            </span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </section>
    @endif
</div>
@endsection