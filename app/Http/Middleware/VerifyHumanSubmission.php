<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Verification\VerificationManager;
use App\Rules\MathVerificationRule;

class VerifyHumanSubmission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $manager = app(VerificationManager::class);

        if ($manager->isEnabled()) {
            $driver = $manager->getDriverName();

            if ($driver === 'math') {
                $request->validate([
                    'math_answer' => ['required', new MathVerificationRule()],
                    'verification_token' => ['required', 'string'],
                ], [
                    'math_answer.required' => 'The math verification answer is incorrect.',
                    'verification_token.required' => 'The math verification token is missing.',
                ]);
            } elseif ($driver === 'recaptcha') {
                $request->validate([
                    'g-recaptcha-response' => ['required', new MathVerificationRule()],
                ], [
                    'g-recaptcha-response.required' => 'The human verification failed.',
                ]);
            } elseif ($driver === 'turnstile') {
                $request->validate([
                    'cf-turnstile-response' => ['required', new MathVerificationRule()],
                ], [
                    'cf-turnstile-response.required' => 'The human verification failed.',
                ]);
            } elseif ($driver === 'hcaptcha') {
                $request->validate([
                    'h-captcha-response' => ['required', new MathVerificationRule()],
                ], [
                    'h-captcha-response.required' => 'The human verification failed.',
                ]);
            }
        }

        return $next($request);
    }
}
