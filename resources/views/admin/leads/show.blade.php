@extends('admin.layouts.app')

@php
    $meta = is_array($lead->metadata) ? $lead->metadata : [];
    $displayName = $lead->full_name ?: $lead->first_name ?: 'Unknown';
    $isDrawer = request()->header('X-Requested-With') === 'XMLHttpRequest' || request()->query('drawer');
    $phone = $lead->phone ?: data_get($meta, 'phone');
    $subject = data_get($meta, 'subject');
    $message = data_get($meta, 'message');
@endphp

@section('content')
@if(!$isDrawer)
<style>
.sz-detail { max-width:1080px;margin:0 auto; }
.sz-hero { display:flex;flex-wrap:wrap;gap:1.25rem;align-items:flex-start;justify-content:space-between;padding:1.4rem 1.5rem;margin-bottom:1.25rem;background:linear-gradient(135deg,#f8fafc,#f1f5f9);border:1px solid #e2e8f0;border-radius:14px; }
.sz-hero-person { display:flex;gap:.85rem;align-items:flex-start;flex:1;min-width:0; }
.sz-hero-avatar { width:52px;height:52px;border-radius:12px;flex-shrink:0;display:grid;place-items:center;font-weight:700;font-size:1.05rem;background:{{ $lead->avatar_color }};color:#fff; }
.sz-hero-body h2 { margin:0 0 .15rem;font-size:1.25rem;color:#0f172a; }
.sz-hero-contact { display:flex;flex-wrap:wrap;gap:.15rem 1rem;margin-top:.1rem; }
.sz-hero-contact a { color:#14a394;font-weight:600;text-decoration:none;font-size:.88rem; }
.sz-hero-contact a:hover { text-decoration:underline; }
.sz-hero-badges { display:flex;flex-wrap:wrap;gap:.4rem;align-items:center; }
.sz-grid { display:grid;grid-template-columns:minmax(0,1.15fr) minmax(0,.85fr);gap:1.1rem;align-items:start; }
@media(max-width:900px){ .sz-grid{grid-template-columns:1fr;} }
.sz-card { background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:1rem 1.15rem;margin-bottom:1rem; }
.sz-card h3 { margin:0 0 .65rem;font-size:.68rem;text-transform:uppercase;letter-spacing:.07em;color:#64748b;font-weight:700; }
.sz-kv { display:grid;gap:.45rem; }
.sz-kv-row { display:grid;grid-template-columns:7.5rem 1fr;gap:.35rem .75rem;font-size:.82rem;align-items:baseline; }
.sz-kv-k { color:#64748b;font-weight:500; }
.sz-kv-v { color:#0f172a;word-break:break-word; }
.sz-timeline { position:relative;padding-left:1.4rem; }
.sz-timeline::before { content:'';position:absolute;left:6px;top:4px;bottom:4px;width:2px;background:#e2e8f0; }
.sz-timeline-item { position:relative;padding-bottom:.85rem; }
.sz-timeline-item:last-child { padding-bottom:0; }
.sz-timeline-dot { position:absolute;left:-1.4rem;top:3px;width:12px;height:12px;border-radius:50%;background:#fff;border:2px solid #14a394; }
.sz-timeline-dot.is-orange { border-color:#f59e0b; }
.sz-timeline-dot.is-green { border-color:#10b981; }
.sz-timeline-dot.is-gray { border-color:#94a3b8; }
.sz-timeline-label { font-size:.68rem;font-weight:700;color:#14a394;text-transform:uppercase; }
.sz-timeline-desc { font-size:.82rem;color:#334155;margin:.05rem 0; }
.sz-timeline-meta { font-size:.68rem;color:#94a3b8; }
.sz-note { padding:.75rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;margin-bottom:.5rem; }
.sz-note.is-pinned { border-left:3px solid #f59e0b;background:#fffbeb; }
.sz-note-header { display:flex;align-items:center;justify-content:space-between;margin-bottom:.25rem; }
.sz-note-author { font-size:.72rem;font-weight:600;color:#475569; }
.sz-note-date { font-size:.65rem;color:#94a3b8; }
.sz-note-content { font-size:.82rem;color:#334155;white-space:pre-wrap; }
.sz-task { display:flex;align-items:flex-start;gap:.5rem;padding:.65rem;background:#fff;border:1px solid #e2e8f0;border-radius:10px;margin-bottom:.4rem; }
.sz-task.is-completed { opacity:.6;background:#f8fafc; }
.sz-task-body { flex:1;min-width:0; }
.sz-task-title { font-size:.82rem;font-weight:600;color:#0f172a; }
.sz-task-meta { font-size:.67rem;color:#94a3b8;margin-top:1px; }
.sz-file { display:flex;align-items:center;gap:.5rem;padding:.55rem .65rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;margin-bottom:.4rem; }
.sz-file-info { flex:1;min-width:0; }
.sz-file-name { font-size:.78rem;font-weight:600;color:#0f172a; }
.sz-file-size { font-size:.65rem;color:#94a3b8; }
.sz-form-group { display:flex;gap:.4rem;margin-top:.5rem; }
.sz-form-group input,.sz-form-group select,.sz-form-group textarea { flex:1;padding:.4rem .55rem;border:1px solid #e2e8f0;border-radius:8px;font-size:.78rem; }
.sz-form-group textarea { min-height:50px;resize:vertical; }
.sz-form-group button { padding:.4rem .75rem;background:#14a394;color:#fff;border:none;border-radius:8px;font-size:.78rem;font-weight:600;cursor:pointer;white-space:nowrap; }
.sz-form-group button:hover { opacity:.9; }
.sz-form-group button:disabled { opacity:.5;cursor:not-allowed; }
.sz-details { margin-top:.4rem;border:1px solid #e2e8f0;border-radius:10px;background:#fafafa;overflow:hidden; }
.sz-details summary { cursor:pointer;padding:.55rem 1rem;font-size:.78rem;font-weight:600;color:#475569;list-style:none;display:flex;align-items:center;gap:.4rem;user-select:none; }
.sz-details summary::-webkit-details-marker { display:none; }
.sz-details summary::before { content:'▸';font-size:.65rem;transition:transform .12s; }
.sz-details[open] summary::before { transform:rotate(90deg); }
.sz-details-body { padding:.4rem 1rem .85rem;border-top:1px solid #e8e8e8; }
.sz-tags { display:flex;gap:3px;flex-wrap:wrap;margin-bottom:.4rem; }
.sz-tag { display:inline-flex;align-items:center;gap:3px;padding:.12rem .45rem;border-radius:999px;font-size:.7rem;font-weight:600; }
.sz-tag .rm { cursor:pointer;opacity:.5;margin-left:2px; }
.sz-tag .rm:hover { opacity:1; }
</style>
@endif

<div class="admin-main__inner sz-detail" data-drawer-content>
    @if(!$isDrawer)
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">Lead Profile</p>
            <h2>Lead #{{ $lead->id }}</h2>
            <p>{{ $displayName }} · {{ $lead->lead_source_label }} · {{ $lead->created_at?->format('d M Y h:i A') }}</p>
        </div>
        <div class="admin-topbar__actions">
            <a class="button button--small button--ghost" href="{{ route('admin.leads.index') }}">Back to list</a>
            @can('lead_center.edit')
            <a class="button button--small" href="{{ route('admin.leads.edit', $lead) }}">Edit lead</a>
            @endcan
            @can('lead_center.delete')
            <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}" onsubmit="return confirm('Delete this lead permanently?');">
                @csrf @method('DELETE')
                <button class="button button--small button--danger" type="submit">Delete</button>
            </form>
            @endcan
        </div>
    </section>
    @endif

    {{-- Hero --}}
    <div class="sz-hero">
        <div class="sz-hero-person">
            <div class="sz-hero-avatar">{{ $lead->initials }}</div>
            <div class="sz-hero-body">
                <h2>{{ $displayName }}</h2>
                <div class="sz-hero-contact">
                    <a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a>
                    @if($phone)<a href="tel:{{ preg_replace('/\s+/', '', (string) $phone) }}">{{ $phone }}</a>@endif
                    @if($lead->country)<span style="font-size:.82rem;color:#64748b;">{{ $lead->country }}</span>@endif
                </div>
                <div style="margin-top:.25rem;font-size:.78rem;color:#64748b;">
                    <strong>{{ $lead->lead_source_label }}</strong>
                    @if($lead->form_name) · {{ $lead->form_name }} @endif
                    · <strong>Page:</strong> {{ $lead->website_page_label }}
                    @if($lead->landing_page_name) · {{ $lead->landing_page_name }} @endif
                </div>
            </div>
        </div>
        <div class="sz-hero-badges">
            @php $sc = $statusColors[$lead->status] ?? '#94a3b8'; @endphp
            <span class="sz-badge status" style="background:{{ $sc }};">{{ ucfirst(str_replace('_',' ',$lead->status)) }}</span>
            <span class="sz-badge priority" style="border-left:3px solid {{ \App\Models\Lead::priorityColors()[$lead->priority] ?? '#94a3b8' }};">{{ ucfirst($lead->priority) }}</span>
            <span class="sz-badge" style="background:#f1f5f9;color:#475569;">Score: {{ $lead->lead_score }}</span>
        </div>
    </div>

    {{-- Grid --}}
    <div class="sz-grid">
        <div>
            {{-- Personal Information --}}
            <div class="sz-card">
                <h3>Personal Information</h3>
                <div class="sz-kv">
                    <div class="sz-kv-row"><span class="sz-kv-k">Full Name</span><span class="sz-kv-v">{{ $lead->full_name ?: '-' }}</span></div>
                    <div class="sz-kv-row"><span class="sz-kv-k">Email</span><span class="sz-kv-v">{{ $lead->email }}</span></div>
                    <div class="sz-kv-row"><span class="sz-kv-k">Phone</span><span class="sz-kv-v">{{ $phone ?: '-' }}</span></div>
                    <div class="sz-kv-row"><span class="sz-kv-k">Company</span><span class="sz-kv-v">{{ $lead->company ?: '-' }}</span></div>
                    <div class="sz-kv-row"><span class="sz-kv-k">Country</span><span class="sz-kv-v">{{ $lead->country ?: '-' }}</span></div>
                </div>
            </div>

            {{-- Submission Details --}}
            <div class="sz-card">
                <h3>Submission Details</h3>
                <div class="sz-kv">
                    <div class="sz-kv-row"><span class="sz-kv-k">Lead Source</span><span class="sz-kv-v">{{ $lead->lead_source_label }}</span></div>
                    <div class="sz-kv-row"><span class="sz-kv-k">Form Name</span><span class="sz-kv-v">{{ $lead->form_name_label }}</span></div>
                    <div class="sz-kv-row"><span class="sz-kv-k">Website Page</span><span class="sz-kv-v">{{ $lead->website_page_label }}</span></div>
                    @if($lead->landing_page_name)<div class="sz-kv-row"><span class="sz-kv-k">Landing Page</span><span class="sz-kv-v">{{ $lead->landing_page_name }}</span></div>@endif
                    <div class="sz-kv-row"><span class="sz-kv-k">Submitted</span><span class="sz-kv-v">{{ $lead->created_at?->format('j M Y, g:i A') }}</span></div>
                    @if($lead->referral_url)<div class="sz-kv-row"><span class="sz-kv-k">Referral URL</span><span class="sz-kv-v" style="font-size:.72rem;word-break:break-all;">{{ $lead->referral_url }}</span></div>@endif
                </div>
            </div>

            {{-- Visa / Service --}}
            @if($lead->visa_type || $lead->interested_service || $lead->package_name)
            <div class="sz-card">
                <h3>Service Interest</h3>
                <div class="sz-kv">
                    @if($lead->visa_type)<div class="sz-kv-row"><span class="sz-kv-k">Visa Type</span><span class="sz-kv-v">{{ $visaTypes[$lead->visa_type] ?? $lead->visa_type }}</span></div>@endif
                    @if($lead->interested_service)<div class="sz-kv-row"><span class="sz-kv-k">Service</span><span class="sz-kv-v">{{ $lead->interested_service }}</span></div>@endif
                    @if($lead->package_name)<div class="sz-kv-row"><span class="sz-kv-k">Package</span><span class="sz-kv-v">{{ $lead->package_name }}</span></div>@endif
                    @if($lead->budget)<div class="sz-kv-row"><span class="sz-kv-k">Budget</span><span class="sz-kv-v">${{ number_format($lead->budget, 2) }}</span></div>@endif
                    @if($lead->preferred_date)<div class="sz-kv-row"><span class="sz-kv-k">Preferred Date</span><span class="sz-kv-v">{{ $lead->preferred_date }} @if($lead->preferred_time) at {{ $lead->preferred_time }}@endif</span></div>@endif
                    @if($lead->preferred_contact_method)<div class="sz-kv-row"><span class="sz-kv-k">Contact Method</span><span class="sz-kv-v">{{ ucfirst($lead->preferred_contact_method) }}</span></div>@endif
                </div>
            </div>
            @endif

            {{-- Ebook info --}}
            @if($lead->form_type === 'ebook_download')
            <div class="sz-card">
                <h3>Ebook Download</h3>
                <div class="sz-kv">
                    <div class="sz-kv-row"><span class="sz-kv-k">Ebook</span><span class="sz-kv-v">{{ $lead->ebook?->title ?? $lead->ebook_title ?? 'Unknown' }}</span></div>
                    <div class="sz-kv-row"><span class="sz-kv-k">Downloaded</span><span class="sz-kv-v">{{ $lead->created_at?->format('j M Y, g:i A') }}</span></div>
                </div>
            </div>
            @endif

            {{-- AI Chat Summary --}}
            @if($lead->form_type === 'ai_chat' && $lead->conversation_summary)
            <div class="sz-card">
                <h3>AI Chat Summary</h3>
                <p style="font-size:.82rem;color:#334155;white-space:pre-wrap;">{{ $lead->conversation_summary }}</p>
            </div>
            @endif

            {{-- Subject & Message --}}
            @if(filled($subject) || filled($message))
            <div class="sz-card">
                @if(filled($subject))<h3>Subject</h3><p style="font-size:.9rem;font-weight:600;color:#0f172a;margin-bottom:.65rem;">{{ $subject }}</p>@endif
                @if(filled($message))<h3>Message</h3><p style="font-size:.82rem;color:#334155;white-space:pre-wrap;">{{ $message }}</p>@endif
            </div>
            @endif

            {{-- Notes --}}
            <div class="sz-card">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.65rem;">
                    <h3 style="margin:0;">Notes ({{ $lead->notes->count() }})</h3>
                </div>
                <div id="notes-list">
                    @forelse($lead->notes->sortByDesc('is_pinned')->sortByDesc('created_at') as $note)
                        <div class="sz-note {{ $note->is_pinned ? 'is-pinned' : '' }}">
                            <div class="sz-note-header">
                                <span class="sz-note-author">{{ $note->user?->name ?? 'Unknown' }}</span>
                                <span class="sz-note-date">{{ $note->created_at->diffForHumans() }}@if($note->is_pinned) · Pinned @endif
                                    @if($note->user_id === auth()->id())
                                        <button onclick="deleteNote({{ $lead->id }}, {{ $note->id }})" style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:.65rem;margin-left:3px;">&times;</button>
                                    @endif
                                </span>
                            </div>
                            <div class="sz-note-content">{{ $note->content }}</div>
                        </div>
                    @empty
                        <p style="font-size:.78rem;color:#94a3b8;">No notes yet.</p>
                    @endforelse
                </div>
                @can('lead_center.edit')
                <div class="sz-form-group" style="margin-top:.65rem;">
                    <textarea id="note-content" placeholder="Add a note..." rows="2"></textarea>
                    <button onclick="addNote({{ $lead->id }})" style="align-self:flex-end;">Add Note</button>
                </div>
                @endcan
            </div>

            {{-- Tasks --}}
            <div class="sz-card">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.65rem;">
                    <h3 style="margin:0;">Follow-up Tasks ({{ $lead->tasks->count() }})</h3>
                </div>
                <div id="tasks-list">
                    @forelse($lead->tasks->sortByDesc('created_at') as $task)
                        <div class="sz-task {{ $task->status === 'completed' ? 'is-completed' : '' }}">
                            <div><input type="checkbox" class="sz-checkbox" {{ $task->status === 'completed' ? 'checked' : '' }} onchange="toggleTask({{ $lead->id }}, {{ $task->id }}, this)"></div>
                            <div class="sz-task-body">
                                <div class="sz-task-title" style="{{ $task->status === 'completed' ? 'text-decoration:line-through;' : '' }}">{{ $task->title }}</div>
                                <div class="sz-task-meta">{{ ucfirst($task->type) }} · {{ ucfirst($task->priority) }} @if($task->assignee) · {{ $task->assignee->name }}@endif @if($task->due_at) · Due {{ $task->due_at->format('d M') }}@endif</div>
                            </div>
                            <button onclick="deleteTask({{ $lead->id }}, {{ $task->id }})" style="background:none;border:none;color:#94a3b8;cursor:pointer;font-size:.85rem;padding:2px;">&times;</button>
                        </div>
                    @empty
                        <p style="font-size:.78rem;color:#94a3b8;">No tasks yet.</p>
                    @endforelse
                </div>
                @can('lead_center.edit')
                <div class="sz-form-group" style="flex-wrap:wrap;margin-top:.65rem;">
                    <input type="text" id="task-title" placeholder="Task title..." style="min-width:120px;">
                    <select id="task-type" class="sz-select"><option value="follow_up">Follow up</option><option value="call">Call</option><option value="email">Email</option><option value="meeting">Meeting</option></select>
                    <select id="task-priority" class="sz-select"><option value="medium">Medium</option><option value="low">Low</option><option value="high">High</option><option value="urgent">Urgent</option></select>
                    <input type="date" id="task-due" class="sz-select">
                    <button onclick="addTask({{ $lead->id }})">Add Task</button>
                </div>
                @endcan
            </div>

            {{-- Files --}}
            @if($lead->files->isNotEmpty())
            <div class="sz-card">
                <h3>Documents ({{ $lead->files->count() }})</h3>
                @foreach($lead->files as $file)
                    <div class="sz-file">
                        <span style="font-size:1.1rem;">📄</span>
                        <div class="sz-file-info">
                            <div class="sz-file-name">{{ $file->original_filename }}</div>
                            <div class="sz-file-size">{{ $file->size_for_humans ?? $file->size . ' B' }} · by {{ $file->user?->name ?? 'Unknown' }}</div>
                        </div>
                        @can('lead_center.edit')
                        <button onclick="deleteFile({{ $lead->id }}, {{ $file->id }})" style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:.8rem;">&times;</button>
                        @endcan
                    </div>
                @endforeach
            </div>
            @endif
        </div>

        <div>
            {{-- Status & Score --}}
            <div class="sz-card">
                <h3>Lead Status</h3>
                <div style="display:flex;align-items:center;gap:.85rem;margin-bottom:.65rem;">
                    <div style="width:52px;height:52px;border-radius:50%;display:grid;place-items:center;font-weight:800;font-size:1.05rem;position:relative;background:conic-gradient({{ $lead->lead_score >= 70 ? '#10b981' : ($lead->lead_score >= 40 ? '#f59e0b' : '#94a3b8') }} {{ $lead->lead_score * 3.6 }}deg, #e2e8f0 {{ $lead->lead_score * 3.6 }}deg);">
                        <span style="width:42px;height:42px;border-radius:50%;background:#fff;display:grid;place-items:center;">{{ $lead->lead_score }}</span>
                    </div>
                    <div>
                        <div style="font-size:.82rem;font-weight:600;">{{ ucfirst(str_replace('_',' ',$lead->status)) }}</div>
                        <div style="font-size:.7rem;color:#94a3b8;">Priority: {{ ucfirst($lead->priority) }}</div>
                        @can('lead_center.edit')
                        <button class="button button--small" onclick="recalcScore({{ $lead->id }})" style="margin-top:4px;font-size:.67rem;padding:.15rem .45rem;">Recalculate</button>
                        @endcan
                    </div>
                </div>
                @can('lead_center.edit')
                <div class="sz-form-group">
                    <select id="lead-status" class="sz-select" style="flex:1;">
                        @foreach(array_keys($statusColors) as $s)
                            <option value="{{ $s }}" {{ $lead->status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                        @endforeach
                    </select>
                    <button onclick="updateStatus({{ $lead->id }})">Update</button>
                </div>
                @endcan
            </div>

            {{-- Assignment --}}
            <div class="sz-card">
                <h3>Staff Assignment</h3>
                <div class="sz-kv">
                    <div class="sz-kv-row"><span class="sz-kv-k">Assigned To</span><span class="sz-kv-v">{{ $lead->assignedStaff?->name ?? 'Unassigned' }}</span></div>
                </div>
                @can('lead_center.edit')
                <div class="sz-form-group" style="margin-top:.5rem;">
                    <select id="assign-staff" class="sz-select" style="flex:1;">
                        <option value="">Unassigned</option>
                        @foreach($staff as $s)
                            <option value="{{ $s->id }}" {{ $lead->assigned_to == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                    <button onclick="assignStaff({{ $lead->id }})">Assign</button>
                </div>
                @endcan
            </div>

            {{-- Tags --}}
            <div class="sz-card">
                <h3>Tags</h3>
                <div id="lead-tags" class="sz-tags">
                    @forelse($lead->tags as $tag)
                        <span class="sz-tag" style="background:{{ $tag->color }}22;color:{{ $tag->color }};">
                            {{ $tag->name }}
                            @can('lead_center.edit')
                            <span class="rm" onclick="detachTag({{ $lead->id }}, {{ $tag->id }})">&times;</span>
                            @endcan
                        </span>
                    @empty
                        <span style="font-size:.78rem;color:#94a3b8;">No tags</span>
                    @endforelse
                </div>
                @can('lead_center.edit')
                <div class="sz-form-group" style="margin-top:.5rem;">
                    <select id="tag-select" class="sz-select" style="flex:1;">
                        <option value="">Add tag...</option>
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}" {{ $lead->tags->contains($tag->id) ? 'disabled' : '' }}>{{ $tag->name }}</option>
                        @endforeach
                    </select>
                    <button onclick="attachTag({{ $lead->id }})">+</button>
                </div>
                @endcan
            </div>

            {{-- Activity Timeline --}}
            <div class="sz-card">
                <h3>Activity Timeline</h3>
                <div class="sz-timeline">
                    @forelse($lead->activities->sortByDesc('created_at')->take(15) as $activity)
                        @php $dot = match($activity->type) { 'created','task_completed' => 'is-green', 'status_changed','assigned' => 'is-orange', default => 'is-gray' }; @endphp
                        <div class="sz-timeline-item">
                            <div class="sz-timeline-dot {{ $dot }}"></div>
                            <div class="sz-timeline-label">{{ $activity->label ?? ucwords(str_replace('_',' ',$activity->type)) }}</div>
                            <div class="sz-timeline-desc">{{ $activity->description }}</div>
                            <div class="sz-timeline-meta">{{ $activity->user?->name ?? 'System' }} · {{ $activity->created_at->diffForHumans() }}</div>
                        </div>
                    @empty
                        <p style="font-size:.78rem;color:#94a3b8;">No activity recorded.</p>
                    @endforelse
                </div>
            </div>

            {{-- UTM & Tech Details --}}
            <details class="sz-details">
                <summary>UTM &amp; Technical Data</summary>
                <div class="sz-details-body">
                    <div class="sz-kv">
                        <div class="sz-kv-row"><span class="sz-kv-k">Lead ID</span><span class="sz-kv-v">#{{ $lead->id }}</span></div>
                        <div class="sz-kv-row"><span class="sz-kv-k">Form Type</span><span class="sz-kv-v">{{ $lead->form_type }}</span></div>
                        <div class="sz-kv-row"><span class="sz-kv-k">Source Page</span><span class="sz-kv-v">{{ $lead->source_page ?: '-' }}</span></div>
                        <div class="sz-kv-row"><span class="sz-kv-k">UTM Source</span><span class="sz-kv-v">{{ $lead->utm_source ?: '-' }}</span></div>
                        <div class="sz-kv-row"><span class="sz-kv-k">UTM Medium</span><span class="sz-kv-v">{{ $lead->utm_medium ?: '-' }}</span></div>
                        <div class="sz-kv-row"><span class="sz-kv-k">UTM Campaign</span><span class="sz-kv-v">{{ $lead->utm_campaign ?: '-' }}</span></div>
                        <div class="sz-kv-row"><span class="sz-kv-k">Referral URL</span><span class="sz-kv-v" style="font-size:.7rem;word-break:break-all;">{{ $lead->referral_url ?: '-' }}</span></div>
                        <div class="sz-kv-row"><span class="sz-kv-k">IP</span><span class="sz-kv-v">{{ $lead->ip_address ?: '-' }}</span></div>
                        <div class="sz-kv-row"><span class="sz-kv-k">UA</span><span class="sz-kv-v" style="font-size:.68rem;word-break:break-all;">{{ $lead->user_agent ?: '-' }}</span></div>
                        <div class="sz-kv-row"><span class="sz-kv-k">Created</span><span class="sz-kv-v">{{ $lead->created_at?->format('Y-m-d H:i:s') }}</span></div>
                        <div class="sz-kv-row"><span class="sz-kv-k">Updated</span><span class="sz-kv-v">{{ $lead->updated_at?->format('Y-m-d H:i:s') }}</span></div>
                        <div class="sz-kv-row"><span class="sz-kv-k">Consent</span><span class="sz-kv-v">{{ $lead->consent ? 'Yes' : 'No' }}</span></div>
                    </div>
                </div>
            </details>
        </div>
    </div>

    {{-- Ebook download info --}}
    @if($lead->form_type === 'ebook_download' && $lead->ebook)
    <div class="sz-card" style="margin-top:1rem;">
        <h3>Downloaded Ebook</h3>
        <div class="sz-kv">
            <div class="sz-kv-row"><span class="sz-kv-k">Title</span><span class="sz-kv-v">{{ $lead->ebook->title }}</span></div>
            <div class="sz-kv-row"><span class="sz-kv-k">Downloads</span><span class="sz-kv-v">{{ $lead->downloadLogs?->count() ?? 0 }}</span></div>
        </div>
    </div>
    @endif
</div>

@if(!$isDrawer)
<script>
const t = () => document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
async function api(p, o = {}) {
    const r = await fetch(p, { headers: { 'X-CSRF-TOKEN': t(), 'Content-Type': 'application/json', 'Accept': 'application/json' }, ...o });
    return r.json();
}
async function addNote(id) {
    const e = document.getElementById('note-content'); if (!e.value.trim()) return; e.disabled = true;
    const r = await api('/admin/leads/' + id + '/notes', { method: 'POST', body: JSON.stringify({ content: e.value }) });
    e.disabled = false; if (r.success) { e.value = ''; location.reload(); }
}
async function deleteNote(lid, nid) { if (!confirm('Delete?')) return; const r = await api('/admin/leads/' + lid + '/notes/' + nid, { method: 'DELETE' }); if (r.success) location.reload(); }
async function addTask(id) {
    const t = document.getElementById('task-title'); if (!t.value.trim()) return; t.disabled = true;
    const r = await api('/admin/leads/' + id + '/tasks', { method: 'POST', body: JSON.stringify({ title: t.value, type: document.getElementById('task-type').value, priority: document.getElementById('task-priority').value, due_at: document.getElementById('task-due').value || null }) });
    t.disabled = false; if (r.success) { t.value = ''; location.reload(); }
}
async function toggleTask(lid, tid, cb) { await api('/admin/leads/' + lid + '/tasks/' + tid, { method: 'PATCH', body: JSON.stringify({ status: cb.checked ? 'completed' : 'pending' }) }); }
async function deleteTask(lid, tid) { if (!confirm('Delete task?')) return; const r = await api('/admin/leads/' + lid + '/tasks/' + tid, { method: 'DELETE' }); if (r.success) location.reload(); }
async function attachTag(id) { const s = document.getElementById('tag-select'); if (!s.value) return; const r = await api('/admin/leads/' + id + '/tags/attach', { method: 'POST', body: JSON.stringify({ tag_id: s.value }) }); if (r.success) location.reload(); }
async function detachTag(lid, tid) { const r = await api('/admin/leads/' + lid + '/tags/' + tid, { method: 'DELETE' }); if (r.success) location.reload(); }
async function assignStaff(id) {
    const s = document.getElementById('assign-staff');
    await api('/admin/leads/' + id, { method: 'PUT', body: JSON.stringify({ assigned_to: s.value || null }), headers: { 'X-CSRF-TOKEN': t(), 'Content-Type': 'application/json', 'Accept': 'application/json' } });
    location.reload();
}
async function updateStatus(id) {
    const s = document.getElementById('lead-status');
    await api('/admin/leads/' + id + '/status', { method: 'PATCH', body: JSON.stringify({ status: s.value }), headers: { 'X-CSRF-TOKEN': t(), 'Content-Type': 'application/json', 'Accept': 'application/json' } });
    location.reload();
}
async function recalcScore(id) { const r = await api('/admin/leads/' + id + '/recalculate-score', { method: 'POST' }); if (r.success) location.reload(); }
async function deleteFile(lid, fid) { if (!confirm('Delete file?')) return; const r = await api('/admin/leads/' + lid + '/files/' + fid, { method: 'DELETE' }); if (r.success) location.reload(); }
</script>
@endif
@endsection
