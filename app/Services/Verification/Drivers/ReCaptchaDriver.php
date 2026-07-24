<?php

namespace App\Services\Verification\Drivers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReCaptchaDriver implements VerificationDriverInterface
{
    /**
     * Generate the verification challenge data.
     *
     * @return array
     */
    public function generate(): array
    {
        return [
            'driver' => 'recaptcha',
            'site_key' => config('verification.drivers.recaptcha.site_key'),
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
        $token = $request->input('g-recaptcha-response');

        if (empty($token)) {
            return false;
        }

        $secret = config('verification.drivers.recaptcha.secret_key');
        if (empty($secret)) {
            logger()->warning('reCAPTCHA secret key is missing in .env configuration.');
            return false;
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $token,
                'remoteip' => $request->ip(),
            ]);

            return $response->ok() && $response->json('success') === true;
        } catch (\Throwable $e) {
            logger()->error('reCAPTCHA validation request failed', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
