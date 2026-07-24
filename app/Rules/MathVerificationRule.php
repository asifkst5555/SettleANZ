<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Services\Verification\VerificationManager;

class MathVerificationRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $manager = app(VerificationManager::class);

        if ($manager->isEnabled()) {
            if (!$manager->validate(request())) {
                $driver = $manager->getDriverName();
                $message = $driver === 'math'
                    ? 'The math verification answer is incorrect.'
                    : 'The human verification failed.';
                $fail($message);
            }
        }
    }
}
