<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadFile;
use App\Models\LeadNote;
use App\Models\LeadTask;
use App\Models\Tag;
use App\Models\User;
use App\Services\LeadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function __construct(protected LeadService $leadService) {}

    public function index(Request $request): View
    {
        abort_unless($request->user()?->hasPermission('lead_center.view'), 403);

        $filters = $request->only([
            'search', 'form_type', 'source_page', 'status', 'priority',
            'visa_type', 'package_name', 'country', 'assigned_to',
            'from_date', 'to_date',
        ]);

        $leads = $this->leadService->advancedSearch(
            $filters,
            $request->query('sort', 'created_at'),
            $request->query('dir', 'desc'),
            (int) $request->query('per_page', 25)
        );

        $stats = $this->leadService->dashboardCards();

        return view('admin.leads.index', [
            'metaTitle' => 'Lead Center | SettleANZ Admin',
            'leads' => $leads,
            'filters' => $filters,
            'stats' => $stats,
            'chartData' => $this->leadService->chartData($request->query('period', '30')),
            'statusColors' => Lead::statusColors(),
            'priorityColors' => Lead::priorityColors(),
            'staff' => User::where('is_admin', true)->orderBy('name')->get(['id', 'name']),
            'formTypes' => Lead::sourceLabels(),
            'sourcePages' => Lead::pageLabels(),
            'visaTypes' => Lead::visaTypes(),
            'countries' => Lead::notArchived()->select('country')->distinct()->whereNotNull('country')->orderBy('country')->pluck('country'),
            'leadsBySource' => $this->leadService->leadsBySource(),
            'leadsByPage' => $this->leadService->leadsByPage(),
        ]);
    }

    public function show(Request $request, Lead $lead): View
    {
        abort_unless($request->user()?->hasPermission('lead_center.view'), 403);

        $lead->load([
            'assignedStaff:id,name',
            'tags:id,name,color',
            'activities.user:id,name',
            'notes.user:id,name',
            'tasks.user:id,name', 'tasks.assignee:id,name',
            'files.user:id,name',
            'ebook:id,title',
        ]);

        return view('admin.leads.show', [
            'metaTitle' => "Lead: {$lead->full_name} | SettleANZ Admin",
            'lead' => $lead,
            'statusColors' => Lead::statusColors(),
            'staff' => User::where('is_admin', true)->orderBy('name')->get(['id', 'name']),
            'tags' => Tag::orderBy('name')->get(),
            'visaTypes' => Lead::visaTypes(),
        ]);
    }

    public function edit(Request $request, Lead $lead): View
    {
        abort_unless($request->user()?->hasPermission('lead_center.view'), 403);

        $lead->load(['tags:id,name,color', 'assignedStaff:id,name']);

        return view('admin.leads.edit', [
            'metaTitle' => 'Edit Lead | SettleANZ Admin',
            'lead' => $lead,
            'statusColors' => Lead::statusColors(),
            'staff' => User::where('is_admin', true)->orderBy('name')->get(['id', 'name']),
            'tags' => Tag::orderBy('name')->get(),
            'formTypes' => Lead::sourceLabels(),
            'sourcePages' => Lead::pageLabels(),
            'visaTypes' => Lead::visaTypes(),
            'priorities' => ['low', 'medium', 'high', 'urgent'],
        ]);
    }

    public function update(Request $request, Lead $lead): RedirectResponse
    {
        abort_unless($request->user()?->hasPermission('lead_center.edit'), 403);

        $validated = $request->validate([
            'status' => 'sometimes|string|max:30',
            'priority' => 'sometimes|string|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'full_name' => 'sometimes|string|max:200',
            'first_name' => 'nullable|string|max:100',
            'email' => 'sometimes|email|max:200',
            'phone' => 'nullable|string|max:60',
            'company' => 'nullable|string|max:200',
            'country' => 'nullable|string|max:100',
            'visa_type' => 'nullable|string|max:100',
            'package_name' => 'nullable|string|max:200',
            'interested_service' => 'nullable|string|max:200',
            'budget' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'is_archived' => 'boolean',
        ]);

        if ($request->has('status') && $request->status !== $lead->status) {
            $lead->recordActivity('status_changed', "Status changed to {$request->status}");
        }

        $lead->update($validated);

        if ($request->has('tags') && is_array($request->tags)) {
            $lead->tags()->sync($request->tags);
        }

        $this->leadService->recalculateScore($lead);

        return redirect()->route('admin.leads.index')
            ->with('status', 'Lead updated successfully.');
    }

    public function destroy(Request $request, Lead $lead): RedirectResponse
    {
        abort_unless($request->user()?->hasPermission('lead_center.delete'), 403);

        $lead->delete();

        return redirect()->route('admin.leads.index')->with('status', 'Lead deleted successfully.');
    }

    public function bulkAction(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->hasPermission('lead_center.edit'), 403);

        $validated = $request->validate([
            'lead_ids' => 'required|array',
            'lead_ids.*' => 'exists:leads,id',
            'action' => 'required|string|in:assign,status,delete,archive',
            'value' => 'nullable|string|max:255',
        ]);

        $leads = Lead::whereIn('id', $validated['lead_ids'])->get();

        $message = match ($validated['action']) {
            'assign' => $this->handleBulkAssign($leads, $validated),
            'status' => $this->handleBulkStatus($leads, $validated),
            'delete' => $this->handleBulkDelete($leads),
            'archive' => $this->handleBulkArchive($leads),
            default => 'Action completed.',
        };

        return redirect()->back()->with('status', $message);
    }

    protected function handleBulkAssign($leads, $validated): string
    {
        $userId = (int) ($validated['value'] ?? 0);
        if (!$userId) return 'No staff selected.';
        return "Assigned {$this->leadService->bulkAssign($leads, $userId)} lead(s).";
    }

    protected function handleBulkStatus($leads, $validated): string
    {
        if (empty($validated['value'])) return 'No status selected.';
        return "Updated {$this->leadService->bulkStatus($leads, $validated['value'])} lead(s).";
    }

    protected function handleBulkDelete($leads): string
    {
        abort_unless(request()->user()?->hasPermission('lead_center.delete'), 403);
        return "Deleted {$this->leadService->bulkDelete($leads)} lead(s).";
    }

    protected function handleBulkArchive($leads): string
    {
        return "Archived {$this->leadService->bulkArchive($leads)} lead(s).";
    }

    public function updateStatus(Request $request, Lead $lead): JsonResponse
    {
        abort_unless($request->user()?->hasPermission('lead_center.edit'), 403);

        $validated = $request->validate(['status' => 'required|string|max:30']);

        if ($validated['status'] !== $lead->status) {
            $lead->recordActivity('status_changed', "Status changed to {$validated['status']}");
        }

        $lead->update($validated);

        return response()->json(['success' => true, 'lead' => $lead->fresh()]);
    }

    public function addNote(Request $request, Lead $lead): JsonResponse
    {
        abort_unless($request->user()?->hasPermission('lead_center.edit'), 403);

        $validated = $request->validate([
            'content' => 'required|string',
            'is_private' => 'boolean',
            'is_pinned' => 'boolean',
        ]);

        $note = $lead->notes()->create([
            'user_id' => $request->user()->id,
            'content' => $validated['content'],
            'is_private' => $validated['is_private'] ?? false,
            'is_pinned' => $validated['is_pinned'] ?? false,
        ]);

        $lead->recordActivity('note_added', 'A note was added');
        $note->load('user:id,name');

        return response()->json(['success' => true, 'note' => $note]);
    }

    public function deleteNote(Request $request, Lead $lead, LeadNote $note): JsonResponse
    {
        abort_unless($request->user()?->hasPermission('lead_center.edit'), 403);

        if ($note->user_id !== $request->user()->id && !$request->user()->hasPermission('super_admin')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $note->delete();
        return response()->json(['success' => true]);
    }

    public function addTask(Request $request, Lead $lead): JsonResponse
    {
        abort_unless($request->user()?->hasPermission('lead_center.edit'), 403);

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'type' => 'required|string|in:follow_up,reminder,meeting,call,email',
            'priority' => 'required|string|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'due_at' => 'nullable|date',
        ]);

        $task = $lead->tasks()->create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'priority' => $validated['priority'],
            'assigned_to' => $validated['assigned_to'] ?? null,
            'due_at' => $validated['due_at'] ?? null,
        ]);

        $lead->recordActivity('task_created', "Task created: {$task->title}");
        $task->load(['user:id,name', 'assignee:id,name']);

        return response()->json(['success' => true, 'task' => $task]);
    }

    public function updateTask(Request $request, Lead $lead, LeadTask $task): JsonResponse
    {
        abort_unless($request->user()?->hasPermission('lead_center.edit'), 403);

        $validated = $request->validate([
            'status' => 'sometimes|string|in:pending,completed,cancelled',
            'title' => 'sometimes|string|max:200',
        ]);

        if ($request->has('status') && $request->status === 'completed' && !$task->completed_at) {
            $task->completed_at = now();
        } elseif ($request->has('status') && $request->status !== 'completed') {
            $task->completed_at = null;
        }

        $task->update($validated);

        if ($request->has('status') && $request->status === 'completed') {
            $lead->recordActivity('task_completed', "Task completed: {$task->title}");
        }

        return response()->json(['success' => true, 'task' => $task->fresh()->load(['user:id,name', 'assignee:id,name'])]);
    }

    public function deleteTask(Request $request, Lead $lead, LeadTask $task): JsonResponse
    {
        abort_unless($request->user()?->hasPermission('lead_center.edit'), 403);

        $task->delete();
        return response()->json(['success' => true]);
    }

    public function uploadFile(Request $request, Lead $lead): JsonResponse
    {
        abort_unless($request->user()?->hasPermission('lead_center.edit'), 403);

        $validated = $request->validate([
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,csv,png,jpg,jpeg,gif,txt',
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('leads/' . $lead->id, $filename, 'public');

        $leadFile = $lead->files()->create([
            'user_id' => $request->user()->id,
            'filename' => $filename,
            'original_filename' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        $lead->recordActivity('file_uploaded', "File uploaded: {$leadFile->original_filename}");

        return response()->json(['success' => true, 'file' => $leadFile->load('user:id,name')]);
    }

    public function deleteFile(Request $request, Lead $lead, LeadFile $file): JsonResponse
    {
        abort_unless($request->user()?->hasPermission('lead_center.edit'), 403);

        Storage::disk('public')->delete($file->path);
        $file->delete();

        return response()->json(['success' => true]);
    }

    public function createTag(Request $request): JsonResponse
    {
        abort_unless($request->user()?->hasPermission('lead_center.edit'), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:tags,name',
            'color' => 'nullable|string|max:20',
        ]);

        $tag = Tag::create([
            'name' => $validated['name'],
            'slug' => str($validated['name'])->slug(),
            'color' => $validated['color'] ?? '#6366f1',
        ]);

        return response()->json(['success' => true, 'tag' => $tag]);
    }

    public function attachTag(Request $request, Lead $lead): JsonResponse
    {
        abort_unless($request->user()?->hasPermission('lead_center.edit'), 403);

        $validated = $request->validate(['tag_id' => 'required|exists:tags,id']);
        $lead->tags()->syncWithoutDetaching([$validated['tag_id']]);

        return response()->json(['success' => true]);
    }

    public function detachTag(Request $request, Lead $lead, Tag $tag): JsonResponse
    {
        abort_unless($request->user()?->hasPermission('lead_center.edit'), 403);

        $lead->tags()->detach($tag->id);
        return response()->json(['success' => true]);
    }

    public function updateLeadTags(Request $request, Lead $lead): JsonResponse
    {
        abort_unless($request->user()?->hasPermission('lead_center.edit'), 403);

        $validated = $request->validate([
            'tag_ids' => 'present|array',
            'tag_ids.*' => 'exists:tags,id',
        ]);

        $lead->tags()->sync($validated['tag_ids']);

        return response()->json(['success' => true, 'tags' => $lead->tags()->get()]);
    }

    public function charts(Request $request): JsonResponse
    {
        abort_unless($request->user()?->hasPermission('lead_center.view'), 403);

        return response()->json([
            'trend' => $this->leadService->chartData($request->query('period', '30')),
            'bySource' => $this->leadService->leadsBySource(),
            'byPage' => $this->leadService->leadsByPage(),
            'byVisa' => $this->leadService->leadsByVisaType(),
        ]);
    }

    public function reports(Request $request): View
    {
        abort_unless($request->user()?->hasPermission('lead_center.view'), 403);

        $period = $request->query('period', '30');

        return view('admin.leads.reports', [
            'metaTitle' => 'Lead Reports | SettleANZ Admin',
            'leadsBySource' => $this->leadService->leadsBySource(),
            'leadsByPage' => $this->leadService->leadsByPage(),
            'leadsByVisaType' => $this->leadService->leadsByVisaType(),
            'monthlyTrend' => $this->leadService->monthlyTrend(12),
            'chartData' => $this->leadService->chartData($period),
            'topStaff' => $this->leadService->topStaff(),
            'stats' => $this->leadService->dashboardCards(),
            'period' => $period,
        ]);
    }

    public function export(Request $request)
    {
        abort_unless($request->user()?->hasPermission('lead_center.export'), 403);

        $format = $request->query('format', 'csv');
        $filters = $request->only(['form_type', 'source_page', 'status', 'from_date', 'to_date']);

        if ($format === 'pdf') {
            return $this->leadService->exportPdf($filters);
        }

        $data = $this->leadService->exportData($filters, $format);
        $filename = 'leads-' . now()->format('Y-m-d') . '.csv';

        return response($data, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function calendar(Request $request): View
    {
        abort_unless($request->user()?->hasPermission('lead_center.view'), 403);

        return view('admin.leads.calendar', [
            'metaTitle' => 'Lead Calendar | SettleANZ Admin',
        ]);
    }

    public function calendarEvents(Request $request): JsonResponse
    {
        abort_unless($request->user()?->hasPermission('lead_center.view'), 403);

        $start = $request->query('start', now()->startOfMonth()->format('Y-m-d'));
        $end = $request->query('end', now()->endOfMonth()->format('Y-m-d'));

        return response()->json($this->leadService->calendarData($start, $end));
    }

    public function recalculateScore(Request $request, Lead $lead): JsonResponse
    {
        abort_unless($request->user()?->hasPermission('lead_center.edit'), 403);

        return response()->json([
            'success' => true,
            'score' => $this->leadService->recalculateScore($lead),
        ]);
    }

    public function tagsList(Request $request): JsonResponse
    {
        return response()->json(Tag::orderBy('name')->get());
    }

    public function searchStaff(Request $request): JsonResponse
    {
        $search = $request->query('q', '');
        $staff = User::where('is_admin', true)
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'email']);

        return response()->json($staff);
    }
}
