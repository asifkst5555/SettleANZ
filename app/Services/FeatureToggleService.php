<?php

namespace App\Services;

use App\Models\FeatureFlag;
use Illuminate\Support\Facades\Cache;

class FeatureToggleService
{
    public function isEnabled(string $moduleKey): bool
    {
        return $this->getFlag($moduleKey)?->is_enabled ?? true;
    }

    public function isVisible(string $moduleKey): bool
    {
        return $this->getFlag($moduleKey)?->is_visible ?? true;
    }

    public function isEnabledAndVisible(string $moduleKey): bool
    {
        $flag = $this->getFlag($moduleKey);
        return ($flag?->is_enabled ?? true) && ($flag?->is_visible ?? true);
    }

    public function toggleEnabled(string $moduleKey, bool $enabled): FeatureFlag
    {
        $flag = FeatureFlag::updateOrCreate(
            ['module_key' => $moduleKey],
            ['is_enabled' => $enabled]
        );
        $this->clearCache();
        return $flag;
    }

    public function toggleVisibility(string $moduleKey, bool $visible): FeatureFlag
    {
        $flag = FeatureFlag::updateOrCreate(
            ['module_key' => $moduleKey],
            ['is_visible' => $visible]
        );
        $this->clearCache();
        return $flag;
    }

    public function getAllFlags(): array
    {
        return Cache::remember('feature_flags.all', 3600, function () {
            return FeatureFlag::orderBy('group')->orderBy('name')->get()->toArray();
        });
    }

    public function getFlagsByGroup(): array
    {
        $flags = $this->getAllFlags();
        $grouped = [];
        foreach ($flags as $flag) {
            $grouped[$flag['group']][] = $flag;
        }
        return $grouped;
    }

    private function getFlag(string $moduleKey): ?FeatureFlag
    {
        $flags = Cache::remember('feature_flags.map', 3600, function () {
            return FeatureFlag::pluck('is_enabled', 'module_key')->toArray();
        });

        if (!array_key_exists($moduleKey, $flags)) {
            return null;
        }

        return FeatureFlag::where('module_key', $moduleKey)->first();
    }

    private function clearCache(): void
    {
        Cache::forget('feature_flags.all');
        Cache::forget('feature_flags.map');
    }
}
