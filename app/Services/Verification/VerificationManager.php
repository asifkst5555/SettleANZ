<?php

namespace App\Services\Verification;

use App\Services\Verification\Drivers\VerificationDriverInterface;
use App\Services\Verification\Drivers\MathDriver;
use App\Services\Verification\Drivers\ReCaptchaDriver;
use App\Services\Verification\Drivers\TurnstileDriver;
use App\Services\Verification\Drivers\HCaptchaDriver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class VerificationManager
{
    /**
     * Determine if verification is enabled globally.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (bool) config('verification.enabled', true);
    }

    /**
     * Get the active driver name.
     *
     * @return string
     */
    public function getDriverName(): string
    {
        return config('verification.driver', 'math');
    }

    /**
     * Resolve the verification driver instance.
     *
     * @param string|null $driver
     * @return VerificationDriverInterface
     * @throws InvalidArgumentException
     */
    public function driver(?string $driver = null): VerificationDriverInterface
    {
        $driver = $driver ?: $this->getDriverName();

        return match ($driver) {
            'math' => new MathDriver(),
            'recaptcha' => new ReCaptchaDriver(),
            'turnstile' => new TurnstileDriver(),
            'hcaptcha' => new HCaptchaDriver(),
            default => throw new InvalidArgumentException("Verification driver [{$driver}] is not supported."),
        };
    }

    /**
     * Generate dynamic challenge payload.
     *
     * @return array
     */
    public function generate(): array
    {
        return $this->driver()->generate();
    }

    /**
     * Validate request payload and log attempts securely.
     *
     * @param Request $request
     * @return bool
     */
    public function validate(Request $request): bool
    {
        if (!$this->isEnabled()) {
            return true;
        }

        $driver = $this->getDriverName();
        $success = false;

        try {
            $success = $this->driver($driver)->validate($request);
        } catch (\Throwable $e) {
            Log::error('Verification driver error during validation', [
                'driver' => $driver,
                'error' => $e->getMessage(),
            ]);
            $success = false;
        }

        // Attempt count tracking per driver in session
        $attemptsKey = 'verification_attempts_' . $driver;
        $attempts = session($attemptsKey, 0) + 1;
        session([$attemptsKey => $attempts]);

        if ($success) {
            session()->forget($attemptsKey);
        }

        // Structured logging - NEVER log correct answers
        Log::info('Human verification attempt', [
            'timestamp' => now()->toIso8601String(),
            'ip' => $request->ip(),
            'route' => $request->route()?->getName() ?: 'unknown',
            'url' => $request->fullUrl(),
            'user_agent' => $request->userAgent(),
            'attempt_count' => $attempts,
            'driver' => $driver,
            'status' => $success ? 'success' : 'failure',
        ]);

        return $success;
    }
}
