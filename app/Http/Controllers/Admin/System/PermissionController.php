<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\Role;
use App\Services\AuditService;
use App\Services\PermissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public function __construct(
        private PermissionService $permissionService,
        private AuditService $auditService,
    ) {}

    public function index(): View
    {
        $groups = PermissionGroup::with(['permissions' => function ($query) {
            $query->orderBy('name');
        }])
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return view('admin.system.permissions.index', compact('groups'));
    }

    public function create(): View
    {
        $groups = PermissionGroup::orderBy('name')->get();
        return view('admin.system.permissions.create', compact('groups'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:permissions,slug',
            'description' => 'nullable|string|max:1000',
            'group_id' => 'required|exists:permission_groups,id',
        ]);

        $permission = Permission::create($validated);

        $this->auditService->logCreate('permission', $permission->id, "Created permission: {$permission->name}");

        return redirect()->route('admin.system.permissions.index')
            ->with('status', 'Permission created successfully.');
    }

    public function edit(Permission $permission): View
    {
        $groups = PermissionGroup::orderBy('name')->get();
        return view('admin.system.permissions.edit', compact('permission', 'groups'));
    }

    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:permissions,slug,' . $permission->id,
            'description' => 'nullable|string|max:1000',
            'group_id' => 'required|exists:permission_groups,id',
        ]);

        $oldData = $permission->toArray();
        $permission->update($validated);

        $this->auditService->logUpdate('permission', $permission->id, "Updated permission: {$permission->name}", $oldData, $permission->toArray());

        return redirect()->route('admin.system.permissions.index')
            ->with('status', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $this->auditService->logDelete('permission', $permission->id, "Deleted permission: {$permission->name}", $permission->toArray());
        $permission->delete();

        return redirect()->route('admin.system.permissions.index')
            ->with('status', 'Permission deleted successfully.');
    }

    public function matrix(): View
    {
        $permissionMatrix = $this->permissionService->getPermissionMatrix();
        $roles = Role::active()->orderBy('priority')->orderBy('name')->get();
        $rolePermissions = [];

        foreach ($roles as $role) {
            $rolePermissions[$role->id] = $this->permissionService->getRolePermissions($role);
        }

        return view('admin.system.permissions.matrix', compact('permissionMatrix', 'roles', 'rolePermissions'));
    }
}
