<?php

namespace App\Services\Verification\Drivers;

use Illuminate\Http\Request;

interface VerificationDriverInterface
{
    /**
     * Generate the verification challenge data.
     *
     * @return array
     */
    public function generate(): array;

    /**
     * Validate the request payload against the challenge.
     *
     * @param Request $request
     * @return bool
     */
    public function validate(Request $request): bool;
}
