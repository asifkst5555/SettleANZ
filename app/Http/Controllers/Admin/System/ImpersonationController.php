<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function __construct(
        private AuditService $auditService,
    ) {}

    public function impersonate(User $user, Request $request): RedirectResponse
    {
        $currentUser = $request->user();

        if ($user->isSuperAdmin() && !$currentUser->isSuperAdmin()) {
            return back()->with('error', 'Cannot impersonate a Super Admin.');
        }

        if ($user->id === $currentUser->id) {
            return back()->with('error', 'Cannot impersonate yourself.');
        }

        session()->put('impersonated_by', $currentUser->id);
        session()->put('impersonated_user_id', $user->id);

        $user->update([
            'impersonated_at' => now(),
            'impersonated_by' => $currentUser->id,
        ]);

        Auth::login($user);

        $this->auditService->log('impersonate', 'user', $user->id, "User {$currentUser->name} impersonated {$user->name}");

        return redirect()->route('admin.dashboard')
            ->with('status', "You are now impersonating {$user->name}.");
    }

    public function leave(Request $request): RedirectResponse
    {
        $impersonatedById = session()->pull('impersonated_by');
        $impersonatedUserId = session()->pull('impersonated_user_id');

        if (!$impersonatedById) {
            return redirect()->route('admin.dashboard');
        }

        $originalUser = User::findOrFail($impersonatedById);

        if ($impersonatedUserId) {
            User::where('id', $impersonatedUserId)->update([
                'impersonated_at' => null,
                'impersonated_by' => null,
            ]);
        }

        Auth::login($originalUser);

        $this->auditService->log('impersonate_end', 'user', (string) $impersonatedUserId, "User {$originalUser->name} stopped impersonating");

        return redirect()->route('admin.dashboard')
            ->with('status', 'You are back to your original account.');
    }
}
