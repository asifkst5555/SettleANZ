<?php

namespace App\Services\Verification\Drivers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TurnstileDriver implements VerificationDriverInterface
{
    /**
     * Generate the verification challenge data.
     *
     * @return array
     */
    public function generate(): array
    {
        return [
            'driver' => 'turnstile',
            'site_key' => config('verification.drivers.turnstile.site_key'),
        ];
    }

    /**
     * Validate the request payload.
     *
     * @param Request $request
     * @return bool
     */
    public function validate(Request $request): bool
    {
        $token = $request->input('cf-turnstile-response');

        if (empty($token)) {
            return false;
        }

        $secret = config('verification.drivers.turnstile.secret_key');
        if (empty($secret)) {
            logger()->warning('Cloudflare Turnstile secret key is missing in .env configuration.');
            return false;
        }

        try {
            $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $secret,
                'response' => $token,
                'remoteip' => $request->ip(),
            ]);

            return $response->ok() && $response->json('success') === true;
        } catch (\Throwable $e) {
            logger()->error('Cloudflare Turnstile validation request failed', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
