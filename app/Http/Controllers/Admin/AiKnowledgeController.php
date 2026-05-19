<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiKnowledgeEntry;
use App\Services\AiKnowledgeGenerateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AiKnowledgeController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);

        $query = AiKnowledgeEntry::query();

        if ($request->filled('category')) {
            $query->byCategory($request->string('category'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhere('search_keywords', 'like', "%{$search}%");
            });
        }

        $entries = $query->orderByDesc('priority')->orderByDesc('updated_at')->paginate(20);

        $categories = AiKnowledgeEntry::query()
            ->selectRaw('category, count(*) as count')
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        $totalActive = AiKnowledgeEntry::query()->where('is_active', true)->count();
        $totalInactive = AiKnowledgeEntry::query()->where('is_active', false)->count();

        return view('admin.ai-knowledge.index', [
            'metaTitle' => 'AI Knowledge Base | SettleANZ Admin',
            'entries' => $entries,
            'categories' => $categories,
            'totalActive' => $totalActive,
            'totalInactive' => $totalInactive,
        ]);
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);

        return view('admin.ai-knowledge.create', [
            'metaTitle' => 'Add AI Knowledge | SettleANZ Admin',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:5000'],
            'search_keywords' => ['nullable', 'string', 'max:500'],
            'category' => ['required', 'string', 'max:100'],
            'is_active' => ['nullable', 'in:0,1'],
            'priority' => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['priority'] = (int) ($validated['priority'] ?? 0);

        AiKnowledgeEntry::create($validated);

        return redirect()->route('admin.ai-knowledge.index')
            ->with('status', 'Knowledge entry added successfully.');
    }

    public function edit(Request $request, AiKnowledgeEntry $aiKnowledge): View
    {
        abort_unless($request->user()?->is_admin, 403);

        return view('admin.ai-knowledge.edit', [
            'metaTitle' => 'Edit AI Knowledge | SettleANZ Admin',
            'entry' => $aiKnowledge,
        ]);
    }

    public function update(Request $request, AiKnowledgeEntry $aiKnowledge): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:5000'],
            'search_keywords' => ['nullable', 'string', 'max:500'],
            'category' => ['required', 'string', 'max:100'],
            'is_active' => ['nullable', 'in:0,1'],
            'priority' => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['priority'] = (int) ($validated['priority'] ?? 0);

        $aiKnowledge->update($validated);

        return redirect()->route('admin.ai-knowledge.index')
            ->with('status', 'Knowledge entry updated successfully.');
    }

    public function destroy(Request $request, AiKnowledgeEntry $aiKnowledge): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $aiKnowledge->delete();

        return redirect()->route('admin.ai-knowledge.index')
            ->with('status', 'Knowledge entry deleted successfully.');
    }

    public function toggleActive(Request $request, AiKnowledgeEntry $aiKnowledge): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $aiKnowledge->update(['is_active' => !$aiKnowledge->is_active]);

        return redirect()->route('admin.ai-knowledge.index')
            ->with('status', 'Knowledge entry ' . ($aiKnowledge->is_active ? 'activated' : 'deactivated') . '.');
    }

    public function generateForm(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);

        return view('admin.ai-knowledge.generate', [
            'metaTitle' => 'Bulk Generate AI Knowledge | SettleANZ Admin',
        ]);
    }

    public function generate(Request $request, AiKnowledgeGenerateService $generateService): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $request->validate([
            'prompt' => ['required', 'string', 'min:10', 'max:2000'],
            'category' => ['required', 'string', 'max:100'],
            'count' => ['nullable', 'integer', 'min:1', 'max:30'],
        ]);

        $count = (int) ($validated['count'] ?? 10);
        $result = $generateService->generateEntries($validated['prompt'], $validated['category'], $count);

        if (!$result['success']) {
            return redirect()->back()
                ->withInput()
                ->with('error', $result['message']);
        }

        $created = 0;
        foreach ($result['entries'] as $entry) {
            AiKnowledgeEntry::create(array_merge($entry, ['is_active' => true]));
            $created++;
        }

        return redirect()->route('admin.ai-knowledge.index')
            ->with('status', $created . ' knowledge entries generated and added successfully.');
    }
}
