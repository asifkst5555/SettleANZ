<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\LoginHistory;
use App\Models\Role;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function __construct(
        private AuditService $auditService,
    ) {}

    public function index(): View
    {
        $users = User::with('roles')
            ->withCount('loginHistory')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.system.users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::active()->orderBy('priority')->orderBy('name')->get();
        return view('admin.system.users.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        $this->auditService->logCreate('user', $user->id, "Created user: {$user->name} ({$user->email})");

        return redirect()->route('admin.system.users.index')
            ->with('status', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        $roles = Role::active()->orderBy('priority')->orderBy('name')->get();
        $userRoles = $user->roles->pluck('id')->toArray();
        $loginHistory = $user->loginHistory()->latest()->limit(10)->get();

        return view('admin.system.users.edit', compact('user', 'roles', 'userRoles', 'loginHistory'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'is_active' => 'boolean',
        ]);

        if ($request->has('is_active') && $user->isSuperAdmin() && !$request->boolean('is_active')) {
            return back()->with('error', 'Super Admin account cannot be deactivated.');
        }

        $superRole = Role::where('is_super', true)->first();

        if ($request->has('roles') && $user->isSuperAdmin() && $request->user()->id === $user->id) {
            if ($superRole && !in_array($superRole->id, $request->roles)) {
                return back()->with('error', 'You cannot remove Super Admin role from yourself.');
            }
        }

        $oldData = $user->toArray();
        $user->update($validated);

        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        $this->auditService->logUpdate('user', $user->id, "Updated user: {$user->name}", $oldData, $user->toArray());

        return redirect()->route('admin.system.users.edit', $user)
            ->with('status', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Super Admin account cannot be deleted.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $this->auditService->logDelete('user', $user->id, "Deleted user: {$user->name} ({$user->email})", $user->toArray());
        $user->delete();

        return redirect()->route('admin.system.users.index')
            ->with('status', 'User deleted successfully.');
    }

    public function suspend(User $user, Request $request): RedirectResponse
    {
        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Super Admin cannot be suspended.');
        }

        $user->update([
            'is_suspended' => true,
            'suspended_at' => now(),
            'suspension_reason' => $request->input('reason'),
        ]);

        $this->auditService->logUpdate('user', $user->id, "Suspended user: {$user->name}");

        return back()->with('status', 'User suspended successfully.');
    }

    public function activate(User $user): RedirectResponse
    {
        $user->update([
            'is_suspended' => false,
            'is_active' => true,
            'suspended_at' => null,
            'suspension_reason' => null,
        ]);

        $this->auditService->logUpdate('user', $user->id, "Activated user: {$user->name}");

        return back()->with('status', 'User activated successfully.');
    }

    public function resetPassword(User $user, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update(['password' => Hash::make($validated['password'])]);

        $this->auditService->logUpdate('user', $user->id, "Password reset for user: {$user->name}");

        return back()->with('status', 'Password reset successfully.');
    }

    public function forceLogout(User $user): RedirectResponse
    {
        $this->auditService->logUpdate('user', $user->id, "Force logout for user: {$user->name}");

        return back()->with('status', 'User will be logged out on next request.');
    }

    public function loginHistory(User $user): View
    {
        $history = $user->loginHistory()->latest()->paginate(50);
        return view('admin.system.users.login-history', compact('user', 'history'));
    }

    public function activity(User $user): View
    {
        $activities = $user->activityLogs()->latest()->paginate(50);
        return view('admin.system.users.activity', compact('user', 'activities'));
    }
}
