<?php

namespace App\Http\Controllers;

use App\Services\Verification\VerificationManager;
use Illuminate\Http\JsonResponse;

class VerificationController extends Controller
{
    /**
     * Generate a new verification challenge and return the question and token.
     *
     * @param VerificationManager $manager
     * @return JsonResponse
     */
    public function refresh(VerificationManager $manager): JsonResponse
    {
        if (!$manager->isEnabled()) {
            return response()->json([
                'enabled' => false,
            ]);
        }

        $challenge = $manager->generate();

        return response()->json(array_merge([
            'enabled' => true,
        ], $challenge))
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->header('Pragma', 'no-cache')
        ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }
}
