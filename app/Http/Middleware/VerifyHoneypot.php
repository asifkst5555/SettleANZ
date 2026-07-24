<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class VerifyHoneypot
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $honeypotKey = $request->input('honeypot_key');

        if ($honeypotKey) {
            try {
                // Decrypt the randomized honeypot field name
                $fieldName = Crypt::decryptString($honeypotKey);

                if ($request->has($fieldName) && !empty($request->input($fieldName))) {
                    // Bot detected! Log attempt and immediately reject
                    Log::warning('Spam bot detected via Honeypot', [
                        'ip' => $request->ip(),
                        'url' => $request->fullUrl(),
                        'user_agent' => $request->userAgent(),
                        'honeypot_field' => $fieldName,
                        'honeypot_value' => $request->input($fieldName),
                    ]);

                    abort(400, 'Spam request rejected.');
                }
            } catch (\Throwable $e) {
                // Reject invalid honeypot configuration/decryption errors
                Log::warning('Honeypot verification failed due to decryption error', [
                    'ip' => $request->ip(),
                    'error' => $e->getMessage(),
                ]);

                abort(400, 'Invalid request metadata.');
            }
        }

        return $next($request);
    }
}
