<?php

namespace App\Http\Middleware;

use App\Services\FeatureToggleService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FeatureMiddleware
{
    public function __construct(
        private FeatureToggleService $featureToggle,
    ) {}

    public function handle(Request $request, Closure $next, string $moduleKey): Response
    {
        if (!$this->featureToggle->isEnabled($moduleKey)) {
            abort(404, 'This feature is not available.');
        }

        return $next($request);
    }
}
