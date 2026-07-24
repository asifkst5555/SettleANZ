<?php

namespace App\Services\Verification\Drivers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class MathDriver implements VerificationDriverInterface
{
    /**
     * Generate a unique challenge and save its answer to the session.
     *
     * @return array
     */
    public function generate(): array
    {
        $operators = ['+', '-'];
        $operator = $operators[array_rand($operators)];

        $num1 = rand(1, 10);
        $num2 = rand(1, 10);

        if ($operator === '-') {
            if ($num1 < $num2) {
                $temp = $num1;
                $num1 = $num2;
                $num2 = $temp;
            }
            $answer = $num1 - $num2;
        } else {
            $answer = $num1 + $num2;
        }

        $question = "$num1 $operator $num2";
        $token = (string) Str::uuid();

        // Retrieve existing challenges, prune old ones to avoid session bloat
        $challenges = Session::get('verification_challenges', []);
        if (count($challenges) >= 10) {
            array_shift($challenges);
        }

        $challenges[$token] = [
            'answer' => (int) $answer,
            'expires_at' => now()->addMinutes(15)->timestamp,
        ];

        Session::put('verification_challenges', $challenges);

        return [
            'driver' => 'math',
            'question' => $question,
            'token' => $token,
        ];
    }

    /**
     * Validate the request payload against stored challenge answers.
     *
     * @param Request $request
     * @return bool
     */
    public function validate(Request $request): bool
    {
        $token = $request->input('verification_token');
        $answer = $request->input('math_answer');

        if (empty($token) || is_null($answer) || $answer === '') {
            return false;
        }

        $challenges = Session::get('verification_challenges', []);

        if (!isset($challenges[$token])) {
            return false;
        }

        $challenge = $challenges[$token];

        // Immediately prune token from session to prevent reuse
        unset($challenges[$token]);
        Session::put('verification_challenges', $challenges);

        // Check expiration (15 minutes)
        if (now()->timestamp > $challenge['expires_at']) {
            return false;
        }

        return (int) $answer === (int) $challenge['answer'];
    }
}
