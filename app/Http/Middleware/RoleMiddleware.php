<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('admin.login');
        }

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        if ($user->isSuspended()) {
            abort(403, 'Your account has been suspended.');
        }

        if ($user->isLocked()) {
            abort(403, 'Your account is temporarily locked.');
        }

        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized. Required role: ' . implode(', ', $roles));
    }
}
