@extends('admin.layouts.app')

@section('content')
<style>
.kanban-container { display:flex; gap:1rem; overflow-x:auto; padding-bottom:1rem; min-height:70vh; padding:2px; }
.kanban-column { flex:0 0 280px; min-width:260px; background:#f1f5f9; border-radius:12px; padding:.75rem; max-height:calc(100vh - 260px); display:flex; flex-direction:column; }
.kanban-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:.75rem; padding:0 .25rem; flex-shrink:0; }
.kanban-header h4 { font-size:.8rem; font-weight:700; text-transform:uppercase; letter-spacing:.03em; }
.kanban-count { font-size:.7rem; font-weight:700; color:#64748b; background:#e2e8f0; padding:.15rem .5rem; border-radius:999px; }
.kanban-body { overflow-y:auto; flex:1; padding-right:4px; }
.kanban-body::-webkit-scrollbar { width:4px; }
.kanban-body::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:4px; }
.kanban-card { background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:.85rem; margin-bottom:.6rem; cursor:grab; transition:all .15s; box-shadow:0 1px 2px rgba(0,0,0,.04); }
.kanban-card:hover { box-shadow:0 4px 12px rgba(0,0,0,.08); border-color:#6366f155; }
.kanban-card:active { cursor:grabbing; }
.kanban-card.dragging { opacity:.5; }
.kanban-card .name { font-weight:600; color:#0f172a; margin-bottom:.15rem; font-size:.85rem; }
.kanban-card .name a { color:inherit; text-decoration:none; }
.kanban-card .name a:hover { color:#6366f1; }
.kanban-card .meta { font-size:.72rem; color:#94a3b8; }
.kanban-card .tags { display:flex; gap:3px; margin-top:.35rem; flex-wrap:wrap; }
.kanban-card .tags span { padding:.1rem .45rem; border-radius:999px; font-size:.65rem; font-weight:600; color:#fff; }
.kanban-card .footer { display:flex; align-items:center; justify-content:space-between; margin-top:.45rem; }
.kanban-card .avatar-mini { width:24px; height:24px; border-radius:50%; font-size:.6rem; font-weight:700; color:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.kanban-card .score-mini { font-size:.7rem; font-weight:700; color:#94a3b8; }
.kanban-column.drag-over { background:#e0e7ff; }
.empty-col { text-align:center; padding:2rem 0; color:#94a3b8; font-size:.8rem; }
</style>

<div class="admin-main__inner">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
        <div>
            <p class="eyebrow">Lead Center</p>
            <h2>Pipeline / Kanban</h2>
        </div>
        <div style="display:flex;gap:.5rem;">
            <a href="{{ route('admin.leads.index', ['view' => 'table']) }}" class="button button--small button--ghost">Table View</a>
            <a href="{{ route('admin.leads.calendar') }}" class="button button--small">Calendar</a>
        </div>
    </div>

    <div class="kanban-container" id="kanban-container">
        @forelse($pipeline as $column)
            <div class="kanban-column" data-status="{{ $column->status }}">
                <div class="kanban-header">
                    <div style="display:flex;align-items:center;gap:.5rem;">
                        <span style="width:10px;height:10px;border-radius:3px;background:{{ $column->color }};display:inline-block;"></span>
                        <h4>{{ $column->label }}</h4>
                    </div>
                    <span class="kanban-count">{{ $column->count }}</span>
                </div>
                <div class="kanban-body">
                    @forelse($column->leads as $lead)
                        <div class="kanban-card" draggable="true" data-lead-id="{{ $lead->id }}" data-status="{{ $column->status }}">
                            <div class="name">
                                <a href="{{ route('admin.leads.show', $lead) }}" onclick="event.preventDefault();openLeadDrawer({{ $lead->id }})">{{ $lead->full_name ?: 'Unknown' }}</a>
                            </div>
                            <div class="meta">{{ $lead->email }} @if($lead->budget) · ${{ number_format($lead->budget) }} @endif</div>
                            @if($lead->tags->isNotEmpty())
                                <div class="tags">
                                    @foreach($lead->tags as $tag)
                                        <span style="background:{{ $tag->color }};">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                            <div class="footer">
                                <div style="display:flex;align-items:center;gap:4px;">
                                    @if($lead->assignedStaff)
                                        <div class="avatar-mini" style="background:#6366f1;">{{ $lead->assignedStaff->name[0] ?? '?' }}</div>
                                    @endif
                                </div>
                                <span class="score-mini">Score: {{ $lead->lead_score }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="empty-col">No leads</div>
                    @endforelse
                </div>
            </div>
        @empty
            <div style="text-align:center;padding:4rem;width:100%;">
                <h3>No pipeline data</h3>
                <p style="color:#94a3b8;">Create some leads to see your pipeline.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- Lead Drawer --}}
<div class="crm-modal-overlay" id="lead-drawer">
    <div class="crm-modal" style="width:min(95vw,640px);">
        <div id="drawer-content" style="min-height:200px;">
            <div style="text-align:center;padding:3rem;">
                <div style="width:36px;height:36px;border:3px solid #6366f1;border-top-color:transparent;border-radius:50%;animation:spin .6s linear infinite;margin:0 auto;"></div>
                <p style="margin-top:1rem;color:#94a3b8;">Loading...</p>
            </div>
        </div>
        <div class="crm-modal-actions">
            <button class="button button--small" onclick="closeDrawer()" style="background:#f1f5f9;color:#475569;border:1px solid #cbd5e1;">Close</button>
        </div>
    </div>
</div>

<style>
@keyframes spin { to { transform:rotate(360deg); } }
</style>

<script>
let dragSrcEl = null;

document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.kanban-card[draggable]');
    const columns = document.querySelectorAll('.kanban-column');

    cards.forEach(card => {
        card.addEventListener('dragstart', handleDragStart);
        card.addEventListener('dragend', handleDragEnd);
    });

    columns.forEach(col => {
        col.addEventListener('dragover', handleDragOver);
        col.addEventListener('dragenter', handleDragEnter);
        col.addEventListener('dragleave', handleDragLeave);
        col.addEventListener('drop', handleDrop);
    });
});

function handleDragStart(e) {
    dragSrcEl = this;
    this.classList.add('dragging');
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/plain', JSON.stringify({
        leadId: this.dataset.leadId,
        fromStatus: this.dataset.status,
    }));
}

function handleDragEnd(e) {
    this.classList.remove('dragging');
    document.querySelectorAll('.kanban-column').forEach(c => c.classList.remove('drag-over'));
}

function handleDragOver(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
}

function handleDragEnter(e) {
    e.preventDefault();
    this.classList.add('drag-over');
}

function handleDragLeave(e) {
    this.classList.remove('drag-over');
}

async function handleDrop(e) {
    e.preventDefault();
    this.classList.remove('drag-over');
    const col = this.closest('.kanban-column');
    if (!col) return;

    const targetStatus = col.dataset.status;
    const data = JSON.parse(e.dataTransfer.getData('text/plain'));
    if (data.fromStatus === targetStatus) return;

    const token = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

    try {
        const res = await fetch('/admin/leads/' + data.leadId + '/status', {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ status: targetStatus }),
        });

        const result = await res.json();
        if (result.success) {
            const card = document.querySelector(`.kanban-card[data-lead-id="${data.leadId}"]`);
            if (card) card.remove();
            location.reload();
        }
    } catch (err) {
        console.error('Drop failed:', err);
    }
}

async function openLeadDrawer(leadId) {
    const overlay = document.getElementById('lead-drawer');
    const content = document.getElementById('drawer-content');
    overlay.classList.add('is-open');
    content.innerHTML = `<div style="text-align:center;padding:3rem;"><div style="width:36px;height:36px;border:3px solid #6366f1;border-top-color:transparent;border-radius:50%;animation:spin .6s linear infinite;margin:0 auto;"></div><p style="margin-top:1rem;color:#94a3b8;">Loading...</p></div>`;

    try {
        const res = await fetch('/admin/leads/' + leadId);
        const html = await res.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const mainContent = doc.querySelector('[data-drawer-content]');
        content.innerHTML = mainContent ? mainContent.innerHTML : html;
    } catch (e) {
        content.innerHTML = `<div style="text-align:center;padding:3rem;"><h3>Error</h3><p style="color:#94a3b8;">${e.message}</p></div>`;
    }
}

function closeDrawer() {
    document.getElementById('lead-drawer').classList.remove('is-open');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDrawer();
});
</script>
@endsection
