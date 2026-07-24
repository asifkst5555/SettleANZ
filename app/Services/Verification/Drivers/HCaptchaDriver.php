<?php

namespace App\Services\Verification\Drivers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HCaptchaDriver implements VerificationDriverInterface
{
    /**
     * Generate the verification challenge data.
     *
     * @return array
     */
    public function generate(): array
    {
        return [
            'driver' => 'hcaptcha',
            'site_key' => config('verification.drivers.hcaptcha.site_key'),
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
        $token = $request->input('h-captcha-response');

        if (empty($token)) {
            return false;
        }

        $secret = config('verification.drivers.hcaptcha.secret_key');
        if (empty($secret)) {
            logger()->warning('hCaptcha secret key is missing in .env configuration.');
            return false;
        }

        try {
            $response = Http::asForm()->post('https://hcaptcha.com/siteverify', [
                'secret' => $secret,
                'response' => $token,
                'remoteip' => $request->ip(),
            ]);

            return $response->ok() && $response->json('success') === true;
        } catch (\Throwable $e) {
            logger()->error('hCaptcha validation request failed', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
