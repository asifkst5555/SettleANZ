<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Services\AuditService;
use App\Services\PermissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function __construct(
        private PermissionService $permissionService,
        private AuditService $auditService,
    ) {}

    public function index(): View
    {
        $roles = Role::withCount('users', 'permissions')
            ->orderBy('priority')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.system.roles.index', compact('roles'));
    }

    public function create(): View
    {
        $roles = Role::active()->notSuper()->orderBy('name')->get();
        $permissionMatrix = $this->permissionService->getPermissionMatrix();

        return view('admin.system.roles.create', compact('roles', 'permissionMatrix'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:100',
            'landing_page' => 'nullable|string|max:255',
            'priority' => 'nullable|integer|min:0|max:999',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'clone_from' => 'nullable|exists:roles,id',
            'permissions' => 'nullable|array',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_super'] = false;

        $role = Role::create($validated);

        if ($request->filled('clone_from')) {
            $sourceRole = Role::findOrFail($request->clone_from);
            $this->permissionService->cloneRolePermissions($sourceRole, $role);
        } elseif ($request->has('permissions')) {
            $this->permissionService->syncRolePermissions($role, $request->permissions);
        }

        $this->auditService->logCreate('role', $role->id, "Created role: {$role->name}", [
            'name' => $role->name,
            'slug' => $role->slug,
            'is_default' => $role->is_default,
        ]);

        return redirect()->route('admin.system.roles.edit', $role)
            ->with('status', 'Role created successfully.');
    }

    public function edit(Role $role): View
    {
        $permissionMatrix = $this->permissionService->getPermissionMatrix();
        $rolePermissions = $this->permissionService->getRolePermissions($role);
        $roles = Role::active()->notSuper()->where('id', '!=', $role->id)->orderBy('name')->get();

        return view('admin.system.roles.edit', compact('role', 'permissionMatrix', 'rolePermissions', 'roles'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        if ($role->is_super) {
            return back()->with('error', 'Super Admin role cannot be modified.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:100',
            'landing_page' => 'nullable|string|max:255',
            'priority' => 'nullable|integer|min:0|max:999',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'permissions' => 'nullable|array',
        ]);

        $oldData = $role->toArray();
        $role->update($validated);

        if ($request->has('permissions')) {
            $this->permissionService->syncRolePermissions($role, $request->permissions);
        }

        $this->auditService->logUpdate('role', $role->id, "Updated role: {$role->name}", $oldData, $role->toArray());

        return redirect()->route('admin.system.roles.edit', $role)
            ->with('status', 'Role updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($role->is_super) {
            return back()->with('error', 'Super Admin role cannot be deleted.');
        }

        if ($role->users()->exists()) {
            return back()->with('error', 'Cannot delete role with assigned users. Remove all users first.');
        }

        $this->auditService->logDelete('role', $role->id, "Deleted role: {$role->name}", $role->toArray());
        $role->delete();

        return redirect()->route('admin.system.roles.index')
            ->with('status', 'Role deleted successfully.');
    }

    public function clone(Role $role, Request $request): RedirectResponse
    {
        if ($role->is_super) {
            return back()->with('error', 'Super Admin role cannot be cloned.');
        }

        $newRole = Role::create([
            'name' => $role->name . ' (Clone)',
            'slug' => Str::slug($role->name . '-clone'),
            'description' => $role->description,
            'color' => $role->color,
            'icon' => $role->icon,
            'landing_page' => $role->landing_page,
            'priority' => $role->priority,
            'is_active' => false,
        ]);

        $this->permissionService->cloneRolePermissions($role, $newRole);

        $this->auditService->logCreate('role', $newRole->id, "Cloned role: {$newRole->name} from {$role->name}");

        return redirect()->route('admin.system.roles.edit', $newRole)
            ->with('status', 'Role cloned successfully.');
    }

    public function permissions(Role $role): View
    {
        $permissionMatrix = $this->permissionService->getPermissionMatrix();
        $rolePermissions = $this->permissionService->getRolePermissions($role);

        return view('admin.system.roles.permissions', compact('role', 'permissionMatrix', 'rolePermissions'));
    }

    public function updatePermissions(Request $request, Role $role): RedirectResponse
    {
        if ($role->is_super) {
            return back()->with('error', 'Super Admin permissions cannot be modified.');
        }

        $this->permissionService->syncRolePermissions($role, $request->input('permissions', []));

        $this->auditService->logPermissionChange("Updated permissions for role: {$role->name}");

        return redirect()->route('admin.system.roles.permissions', $role)
            ->with('status', 'Permissions updated successfully.');
    }
}
