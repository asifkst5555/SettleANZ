<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\FeatureFlag;
use App\Services\AuditService;
use App\Services\FeatureToggleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeatureFlagController extends Controller
{
    public function __construct(
        private FeatureToggleService $featureToggle,
        private AuditService $auditService,
    ) {}

    public function index(): View
    {
        $flags = FeatureFlag::orderBy('group')->orderBy('name')->paginate(20);
        $groups = FeatureFlag::select('group')->distinct()->orderBy('group')->pluck('group');

        return view('admin.system.feature-flags.index', compact('flags', 'groups'));
    }

    public function toggle(Request $request, FeatureFlag $flag): RedirectResponse
    {
        $validated = $request->validate([
            'is_enabled' => 'boolean',
            'is_visible' => 'boolean',
        ]);

        $old = $flag->toArray();
        $flag->update($validated);

        $this->auditService->logSettingsChange("Toggled feature flag: {$flag->name}", $old, $flag->toArray());

        return back()->with('status', 'Feature flag updated successfully.');
    }

    public function create(): View
    {
        return view('admin.system.feature-flags.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'module_key' => 'required|string|max:255|unique:feature_flags,module_key',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'group' => 'required|string|max:255',
            'is_enabled' => 'boolean',
            'is_visible' => 'boolean',
        ]);

        $flag = FeatureFlag::create($validated);

        $this->auditService->logCreate('feature_flag', $flag->id, "Created feature flag: {$flag->name}");

        return redirect()->route('admin.system.feature-flags.index')
            ->with('status', 'Feature flag created successfully.');
    }

    public function edit(FeatureFlag $flag): View
    {
        return view('admin.system.feature-flags.edit', compact('flag'));
    }

    public function update(Request $request, FeatureFlag $flag): RedirectResponse
    {
        $validated = $request->validate([
            'module_key' => 'required|string|max:255|unique:feature_flags,module_key,' . $flag->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'group' => 'required|string|max:255',
            'is_enabled' => 'boolean',
            'is_visible' => 'boolean',
        ]);

        $old = $flag->toArray();
        $flag->update($validated);

        $this->auditService->logUpdate('feature_flag', $flag->id, "Updated feature flag: {$flag->name}", $old, $flag->toArray());

        return redirect()->route('admin.system.feature-flags.index')
            ->with('status', 'Feature flag updated successfully.');
    }

    public function destroy(FeatureFlag $flag): RedirectResponse
    {
        $this->auditService->logDelete('feature_flag', $flag->id, "Deleted feature flag: {$flag->name}", $flag->toArray());
        $flag->delete();

        return redirect()->route('admin.system.feature-flags.index')
            ->with('status', 'Feature flag deleted successfully.');
    }
}
