<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginHistory;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        private AuditService $auditService,
    ) {}

    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->is_admin || $user->roles()->exists()) {
                return redirect()->route('admin.dashboard');
            }
        }

        return view('admin.auth.login', [
            'metaTitle' => 'Admin Login | SettleANZ',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        if ($user->isSuspended()) {
            $this->logLoginAttempt($user->id, 'failed');
            return back()->withErrors([
                'email' => 'This account has been suspended. Contact your administrator.',
            ])->onlyInput('email');
        }

        if ($user->isLocked()) {
            $this->logLoginAttempt($user->id, 'failed');
            return back()->withErrors([
                'email' => 'This account is temporarily locked. Try again later.',
            ])->onlyInput('email');
        }

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            $this->logLoginAttempt($user->id, 'failed');
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        $authenticatedUser = Auth::user();

        if (!$authenticatedUser->is_admin && !$authenticatedUser->roles()->exists()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'This account does not have admin access.',
            ])->onlyInput('email');
        }

        $authenticatedUser->update(['last_login_at' => now()]);

        $this->logLoginAttempt($authenticatedUser->id, 'login');

        $this->auditService->log('login', 'user', (string) $authenticatedUser->id, "User logged in: {$authenticatedUser->name}");

        return redirect()->intended(route('admin.dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user) {
            $this->logLoginAttempt($user->id, 'logout');
            $this->auditService->log('logout', 'user', (string) $user->id, "User logged out: {$user->name}");
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    private function logLoginAttempt(int $userId, string $event): void
    {
        $agent = request()->userAgent();

        LoginHistory::create([
            'user_id' => $userId,
            'event' => $event,
            'ip_address' => request()->ip(),
            'user_agent' => $agent,
            'browser' => $this->detectBrowser($agent),
            'platform' => $this->detectPlatform($agent),
            'device' => $this->detectDevice($agent),
        ]);
    }

    private function detectBrowser(?string $agent): string
    {
        if (!$agent) return 'Unknown';
        if (str_contains($agent, 'Chrome')) return 'Chrome';
        if (str_contains($agent, 'Firefox')) return 'Firefox';
        if (str_contains($agent, 'Safari')) return 'Safari';
        if (str_contains($agent, 'Edge')) return 'Edge';
        if (str_contains($agent, 'Opera')) return 'Opera';
        return 'Other';
    }

    private function detectPlatform(?string $agent): string
    {
        if (!$agent) return 'Unknown';
        if (str_contains($agent, 'Windows')) return 'Windows';
        if (str_contains($agent, 'Mac')) return 'macOS';
        if (str_contains($agent, 'Linux')) return 'Linux';
        if (str_contains($agent, 'Android')) return 'Android';
        if (str_contains($agent, 'iOS')) return 'iOS';
        return 'Other';
    }

    private function detectDevice(?string $agent): string
    {
        if (!$agent) return 'Unknown';
        if (str_contains($agent, 'Mobile')) return 'Mobile';
        if (str_contains($agent, 'Tablet')) return 'Tablet';
        return 'Desktop';
    }
}
