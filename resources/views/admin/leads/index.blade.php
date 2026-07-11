@extends('admin.layouts.app')

@section('content')
<style>
:root {
    --sz-primary: #14a394;
    --sz-primary-dark: #0f766e;
    --sz-accent: #e8773a;
    --sz-bg: #f8fafc;
    --sz-card: #ffffff;
    --sz-border: #e2e8f0;
    --sz-text: #1e293b;
    --sz-muted: #94a3b8;
    --sz-radius: 12px;
    --sz-shadow: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
}

.sz-layout { display:flex; flex-direction:column; gap:1.25rem; }

.sz-stats { display:grid; grid-template-columns:repeat(auto-fill,minmax(170px,1fr)); gap:.85rem; }
.sz-stat { background:var(--sz-card); border-radius:var(--sz-radius); padding:1rem 1.15rem; box-shadow:var(--sz-shadow); border:1px solid var(--sz-border); transition:all .2s; }
.sz-stat:hover { box-shadow:0 4px 12px rgba(0,0,0,.08); transform:translateY(-2px); }
.sz-stat-label { font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.04em; color:var(--sz-muted); margin-bottom:.15rem; }
.sz-stat-value { font-size:1.6rem; font-weight:800; color:var(--sz-text); line-height:1.2; }
.sz-stat-sub { font-size:.7rem; font-weight:600; margin-top:.2rem; }
.sz-stat-sub.up { color:#10b981; }
.sz-stat-sub.down { color:#ef4444; }
.sz-stat-icon { float:right; width:36px; height:36px; border-radius:10px; display:grid; place-items:center; font-size:1.1rem; }

.sz-toolbar { display:flex; gap:.65rem; align-items:center; flex-wrap:wrap; background:var(--sz-card); border-radius:var(--sz-radius); padding:.85rem 1.1rem; box-shadow:var(--sz-shadow); border:1px solid var(--sz-border); }
.sz-toolbar .quick-filter { display:flex; gap:2px; background:#f1f5f9; border-radius:8px; padding:2px; }
.sz-toolbar .quick-filter a { padding:.35rem .75rem; border-radius:6px; font-size:.75rem; font-weight:600; color:#64748b; text-decoration:none; transition:all .12s; white-space:nowrap; }
.sz-toolbar .quick-filter a.is-active { background:#fff; color:var(--sz-primary); box-shadow:0 1px 3px rgba(0,0,0,.08); }
.sz-toolbar .quick-filter a:hover:not(.is-active) { color:#334155; }
.sz-search { flex:1; min-width:160px; padding:.4rem .7rem; border:1px solid var(--sz-border); border-radius:8px; font-size:.82rem; background:#fff; }
.sz-search:focus { outline:2px solid var(--sz-primary); outline-offset:-1px; border-color:transparent; }
.sz-filter-group { display:flex; gap:.35rem; align-items:center; flex-wrap:wrap; }
.sz-select { padding:.4rem .7rem; border:1px solid var(--sz-border); border-radius:8px; font-size:.8rem; background:#fff; }

.sz-table-wrap { overflow-x:auto; background:var(--sz-card); border-radius:var(--sz-radius); box-shadow:var(--sz-shadow); border:1px solid var(--sz-border); }
.sz-table { width:100%; border-collapse:collapse; }
.sz-table thead { position:sticky; top:0; z-index:2; }
.sz-table th { background:#f8fafc; padding:.55rem .65rem; font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.04em; color:#475569; text-align:left; border-bottom:2px solid var(--sz-border); white-space:nowrap; cursor:pointer; user-select:none; }
.sz-table th:hover { color:var(--sz-primary); }
.sz-table th .sorter { opacity:.3; margin-left:3px; font-size:.6rem; }
.sz-table th.is-sorted .sorter { opacity:1; color:var(--sz-primary); }
.sz-table td { padding:.5rem .65rem; border-bottom:1px solid #f1f5f9; font-size:.82rem; color:var(--sz-text); vertical-align:middle; }
.sz-table tr:hover td { background:#f8fafc; }
.sz-table .sz-lead-info { display:flex; align-items:center; gap:.55rem; }
.sz-table .sz-avatar { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:.7rem; color:#fff; flex-shrink:0; }
.sz-table .sz-name { font-weight:600; color:#0f172a; text-decoration:none; }
.sz-table .sz-name:hover { color:var(--sz-primary); }
.sz-table .sz-email { font-size:.7rem; color:var(--sz-muted); }

.sz-badge { display:inline-flex; align-items:center; padding:.15rem .5rem; border-radius:999px; font-size:.67rem; font-weight:600; white-space:nowrap; }
.sz-badge.status { color:#fff; }
.sz-badge.priority { background:#f1f5f9; color:#475569; border-left:3px solid; }

.sz-score { display:inline-flex; align-items:center; gap:3px; }
.sz-score-bar { height:5px; border-radius:3px; background:#e2e8f0; width:40px; overflow:hidden; display:inline-block; vertical-align:middle; }
.sz-score-bar span { display:block; height:100%; border-radius:3px; transition:width .5s; }
.sz-score-val { font-size:.68rem; font-weight:700; width:20px; text-align:right; display:inline-block; }

.sz-checkbox { width:15px; height:15px; border-radius:4px; border:2px solid #cbd5e1; cursor:pointer; accent-color:var(--sz-primary); }

.sz-pagination { display:flex; align-items:center; justify-content:space-between; padding:.65rem 1.1rem; background:var(--sz-card); border-radius:var(--sz-radius); border:1px solid var(--sz-border); }
.sz-pagination .info { font-size:.78rem; color:var(--sz-muted); }
.sz-pagination .links { display:flex; gap:2px; }
.sz-pagination .links a, .sz-pagination .links span { padding:.3rem .6rem; border-radius:6px; font-size:.78rem; font-weight:600; text-decoration:none; }
.sz-pagination .links a { color:var(--sz-primary); border:1px solid var(--sz-border); }
.sz-pagination .links a:hover { background:var(--sz-primary); color:#fff; border-color:var(--sz-primary); }
.sz-pagination .links span.current { background:var(--sz-primary); color:#fff; border:1px solid var(--sz-primary); }

.sz-modal { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); backdrop-filter:blur(4px); z-index:1000; align-items:center; justify-content:center; }
.sz-modal.is-open { display:flex; }
.sz-modal-box { background:#fff; border-radius:16px; padding:1.5rem; width:min(90vw,520px); max-height:85vh; overflow-y:auto; box-shadow:0 25px 50px rgba(0,0,0,.2); }
.sz-modal-box h3 { font-size:1.05rem; font-weight:700; margin-bottom:.15rem; }
.sz-modal-box p.sub { font-size:.8rem; color:var(--sz-muted); margin-bottom:1rem; }

.sz-toast { position:fixed; bottom:1.5rem; right:1.5rem; background:#1e293b; color:#fff; padding:.65rem 1.15rem; border-radius:10px; font-size:.82rem; font-weight:600; z-index:2000; transform:translateY(calc(100% + 2rem)); transition:transform .3s cubic-bezier(.4,0,.2,1); box-shadow:0 4px 12px rgba(0,0,0,.2); }
.sz-toast.show { transform:translateY(0); }

.sz-bulk-bar { display:none; position:fixed; bottom:0; left:0; right:0; background:#fff; border-top:2px solid var(--sz-primary); padding:.65rem 1.5rem; z-index:999; box-shadow:0 -4px 20px rgba(0,0,0,.1); }

.sz-empty { text-align:center; padding:4rem 2rem; }
.sz-empty-icon { font-size:2.5rem; margin-bottom:.5rem; }
.sz-empty h3 { color:var(--sz-text); }
.sz-empty p { color:var(--sz-muted); font-size:.82rem; }

@media(max-width:768px){
    .sz-stats { grid-template-columns:repeat(2,1fr); }
}
</style>

<div class="admin-main__inner">
    @if(session('status'))
        <div class="sz-toast show" id="status-toast">{{ session('status') }}</div>
    @endif

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.15rem;">
        <div>
            <p class="eyebrow">SettleANZ CRM</p>
            <h2>Lead Center</h2>
            <p style="font-size:.85rem;color:#64748b;">Every lead traced to its exact website page and form.</p>
        </div>
        <div style="display:flex;gap:.5rem;">
            @can('lead_center.export')
            <a href="{{ route('admin.leads.export', request()->only(['form_type','source_page','status'])) }}" class="button button--small" style="background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;display:inline-flex;align-items:center;gap:0.35rem;" aria-label="Export leads to CSV" title="CSV Export">
                @include('admin.partials.icon', ['name' => 'file-down', 'size' => 15])
                <span>CSV Export</span>
            </a>
            @endcan
            <a href="{{ route('admin.leads.reports') }}" class="button button--small" style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;display:inline-flex;align-items:center;gap:0.35rem;" aria-label="View Reports" title="Reports">
                @include('admin.partials.icon', ['name' => 'file-bar-chart', 'size' => 15])
                <span>Reports</span>
            </a>
            <a href="{{ route('admin.leads.calendar') }}" class="button button--small" style="background:#f5f3ff;color:#6d28d9;border:1px solid #ddd6fe;display:inline-flex;align-items:center;gap:0.35rem;" aria-label="View Calendar" title="Calendar">
                @include('admin.partials.icon', ['name' => 'calendar', 'size' => 15])
                <span>Calendar</span>
            </a>
        </div>
    </div>

    <div class="sz-layout">

        {{-- Dashboard Stats --}}
        <div class="sz-stats">
            <div class="sz-stat">
                <div class="sz-stat-icon" style="background:#eef2ff;color:#6366f1;display:flex;align-items:center;justify-content:center;">
                    @include('admin.partials.icon', ['name' => 'bar-chart-3', 'size' => 20])
                </div>
                <div class="sz-stat-label">Total Leads</div>
                <div class="sz-stat-value">{{ number_format($stats['total']) }}</div>
                <div class="sz-stat-sub {{ $stats['this_month'] >= $stats['last_month'] ? 'up' : 'down' }}">
                    {{ $stats['this_month'] }} this month
                </div>
            </div>
            <div class="sz-stat">
                <div class="sz-stat-icon" style="background:#f0fdf4;color:#10b981;display:flex;align-items:center;justify-content:center;">
                    @include('admin.partials.icon', ['name' => 'plus', 'size' => 20])
                </div>
                <div class="sz-stat-label">Today</div>
                <div class="sz-stat-value">{{ $stats['today'] }}</div>
                <div class="sz-stat-sub up">New today</div>
            </div>
            <div class="sz-stat">
                <div class="sz-stat-icon" style="background:#eff6ff;color:#2563eb;display:flex;align-items:center;justify-content:center;">
                    @include('admin.partials.icon', ['name' => 'mail', 'size' => 20])
                </div>
                <div class="sz-stat-label">Contact Messages</div>
                <div class="sz-stat-value">{{ $stats['contact_messages'] }}</div>
                <div class="sz-stat-sub">From Contact page</div>
            </div>
            <div class="sz-stat">
                <div class="sz-stat-icon" style="background:#fffbeb;color:#d97706;display:flex;align-items:center;justify-content:center;">
                    @include('admin.partials.icon', ['name' => 'package', 'size' => 20])
                </div>
                <div class="sz-stat-label">Package Bookings</div>
                <div class="sz-stat-value">{{ $stats['package_requests'] }}</div>
                <div class="sz-stat-sub">Settlement packages</div>
            </div>
            <div class="sz-stat">
                <div class="sz-stat-icon" style="background:#fef2f2;color:#dc2626;display:flex;align-items:center;justify-content:center;">
                    @include('admin.partials.icon', ['name' => 'download', 'size' => 20])
                </div>
                <div class="sz-stat-label">Roadmap Downloads</div>
                <div class="sz-stat-value">{{ $stats['roadmap_downloads'] }}</div>
                <div class="sz-stat-sub">Homepage leads</div>
            </div>
            <div class="sz-stat">
                <div class="sz-stat-icon" style="background:#f5f3ff;color:#7c3aed;display:flex;align-items:center;justify-content:center;">
                    @include('admin.partials.icon', ['name' => 'book-open', 'size' => 20])
                </div>
                <div class="sz-stat-label">Ebook Downloads</div>
                <div class="sz-stat-value">{{ $stats['ebook_downloads'] }}</div>
                <div class="sz-stat-sub">From landing pages</div>
            </div>
            <div class="sz-stat">
                <div class="sz-stat-icon" style="background:#fefce8;color:#ca8a04;display:flex;align-items:center;justify-content:center;">
                    @include('admin.partials.icon', ['name' => 'bot', 'size' => 20])
                </div>
                <div class="sz-stat-label">AI Chat Leads</div>
                <div class="sz-stat-value">{{ $stats['ai_requests'] }}</div>
                <div class="sz-stat-sub">Captured via chat</div>
            </div>
            <div class="sz-stat">
                <div class="sz-stat-icon" style="background:#f1f5f9;color:#475569;display:flex;align-items:center;justify-content:center;">
                    @include('admin.partials.icon', ['name' => 'check-circle', 'size' => 20])
                </div>
                <div class="sz-stat-label">My Tasks</div>
                <div class="sz-stat-value">{{ $stats['pending_tasks'] }}</div>
                <div class="sz-stat-sub">Pending follow-ups</div>
            </div>
        </div>

        {{-- Trend Chart --}}
        @if($chartData->isNotEmpty())
        <div style="background:var(--sz-card);border-radius:var(--sz-radius);padding:.85rem 1.1rem;box-shadow:var(--sz-shadow);border:1px solid var(--sz-border);">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.65rem;">
                <h4 style="font-size:.8rem;font-weight:700;color:var(--sz-text);">Lead Trend</h4>
                <div style="display:flex;gap:.3rem;">
                    @foreach([7,14,30,60] as $p)
                        <a href="{{ route('admin.leads.index', array_merge(request()->except('period'), ['period' => $p])) }}"
                           style="padding:.2rem .5rem;border-radius:6px;font-size:.68rem;font-weight:600;text-decoration:none;
                                  {{ (request('period', '30') == $p) ? 'background:var(--sz-primary);color:#fff;' : 'background:#f1f5f9;color:#64748b;' }}">
                            {{ $p }}d
                        </a>
                    @endforeach
                </div>
            </div>
            <div style="display:flex;gap:2px;align-items:flex-end;height:100px;">
                @php $maxVal = max($chartData->max('new'), 1); @endphp
                @foreach($chartData as $point)
                    <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:1px;">
                        <div title="{{ $point['date'] }}: {{ $point['new'] }} new"
                             style="width:100%;max-width:10px;height:{{ max(($point['new']/$maxVal)*80, 2) }}px;background:var(--sz-primary);border-radius:2px 2px 0 0;transition:height .3s;"></div>
                        @if($loop->iteration % 5 === 0 || $loop->last || $loop->first)
                        <span style="font-size:.5rem;color:var(--sz-muted);">{{ $point['label'] }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Toolbar --}}
        <form method="GET" action="{{ route('admin.leads.index') }}" class="sz-toolbar">
            <div class="quick-filter">
                <a href="{{ route('admin.leads.index') }}" class="{{ !request('form_type') ? 'is-active' : '' }}">All</a>
                <a href="{{ route('admin.leads.index', array_merge(request()->except('form_type'), ['form_type' => 'contact-page'])) }}" class="{{ request('form_type') === 'contact-page' ? 'is-active' : '' }}">Contact</a>
                <a href="{{ route('admin.leads.index', array_merge(request()->except('form_type'), ['form_type' => 'package_booking'])) }}" class="{{ request('form_type') === 'package_booking' ? 'is-active' : '' }}">Packages</a>
                <a href="{{ route('admin.leads.index', array_merge(request()->except('form_type'), ['form_type' => 'ebook_download'])) }}" class="{{ request('form_type') === 'ebook_download' ? 'is-active' : '' }}">Ebooks</a>
                <a href="{{ route('admin.leads.index', array_merge(request()->except('form_type'), ['form_type' => 'homepage_roadmap'])) }}" class="{{ request('form_type') === 'homepage_roadmap' ? 'is-active' : '' }}">Roadmap</a>
                <a href="{{ route('admin.leads.index', array_merge(request()->except('form_type'), ['form_type' => 'ai_chat'])) }}" class="{{ request('form_type') === 'ai_chat' ? 'is-active' : '' }}">AI Chat</a>
            </div>

            <input type="text" name="search" class="sz-search" placeholder="Search name, email, phone..." value="{{ $filters['search'] ?? '' }}">

            <select name="status" class="sz-select" onchange="this.form.submit()">
                <option value="">All Status</option>
                @foreach(array_keys($statusColors) as $s)
                    <option value="{{ $s }}" @selected(($filters['status'] ?? '') === $s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                @endforeach
            </select>

            <select name="visa_type" class="sz-select" onchange="this.form.submit()">
                <option value="">All Visa Types</option>
                @foreach($visaTypes as $k => $v)
                    <option value="{{ $k }}" @selected(($filters['visa_type'] ?? '') === $k)>{{ $v }}</option>
                @endforeach
            </select>

            <select name="assigned_to" class="sz-select" onchange="this.form.submit()">
                <option value="">All Staff</option>
                @foreach($staff as $s)
                    <option value="{{ $s->id }}" @selected(($filters['assigned_to'] ?? '') == $s->id)>{{ $s->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="button button--small" style="display:inline-flex;align-items:center;gap:0.35rem;">
                @include('admin.partials.icon', ['name' => 'sliders-horizontal', 'size' => 14])
                <span>Filter</span>
            </button>
            @if(array_filter($filters))
                <a href="{{ route('admin.leads.index') }}" class="button button--small button--ghost" style="display:inline-flex;align-items:center;gap:0.35rem;">
                    @include('admin.partials.icon', ['name' => 'x', 'size' => 14])
                    <span>Clear</span>
                </a>
            @endif
        </form>

        {{-- Active Filters --}}
        @if(array_filter($filters))
        <div style="display:flex;gap:.3rem;flex-wrap:wrap;">
            @foreach($filters as $key => $value)
                @if($value)
                    <span style="display:inline-flex;align-items:center;gap:3px;padding:.15rem .5rem;border-radius:999px;font-size:.68rem;font-weight:600;background:#e0e7ff;color:#4338ca;">
                        {{ ucwords(str_replace('_',' ',$key)) }}: {{ $value }}
                        <a href="{{ route('admin.leads.index', request()->except($key)) }}" style="text-decoration:none;color:inherit;opacity:.6;">&times;</a>
                    </span>
                @endif
            @endforeach
        </div>
        @endif

        {{-- Data Table --}}
        <div class="sz-table-wrap">
            <table class="sz-table">
                <thead>
                    <tr>
                        <th style="width:28px;"><input type="checkbox" class="sz-checkbox" id="select-all"></th>
                        <th>Lead</th>
                        <th>Lead Source</th>
                        <th>Website Page</th>
                        <th>Visa / Service</th>
                        <th>Status</th>
                        <th>Staff</th>
                        <th>Created</th>
                        <th style="width:70px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leads as $lead)
                        <tr>
                            <td><input type="checkbox" class="sz-checkbox lead-checkbox" value="{{ $lead->id }}"></td>
                            <td>
                                <div class="sz-lead-info">
                                    <div class="sz-avatar" style="background:{{ $lead->avatar_color }};">{{ $lead->initials }}</div>
                                    <div>
                                        <a href="{{ route('admin.leads.show', $lead) }}" class="sz-name" onclick="openDrawer(event, {{ $lead->id }})">{{ $lead->full_name ?: ($lead->first_name ?: 'Unknown') }}</a>
                                        <div class="sz-email">{{ $lead->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span style="font-size:.75rem;font-weight:600;">{{ $lead->lead_source_label }}</span>
                                @if($lead->form_name)
                                    <div style="font-size:.68rem;color:var(--sz-muted);">{{ $lead->form_name }}</div>
                                @endif
                            </td>
                            <td>
                                <span style="font-size:.75rem;">{{ $lead->website_page_label }}</span>
                                @if($lead->landing_page_name)
                                    <div style="font-size:.68rem;color:var(--sz-muted);">{{ $lead->landing_page_name }}</div>
                                @endif
                            </td>
                            <td>
                                @if($lead->visa_type)
                                    <span style="font-size:.72rem;font-weight:600;">{{ $visaTypes[$lead->visa_type] ?? $lead->visa_type }}</span>
                                @elseif($lead->interested_service)
                                    <span style="font-size:.72rem;">{{ $lead->interested_service }}</span>
                                @elseif($lead->package_name)
                                    <span style="font-size:.72rem;">{{ $lead->package_name }}</span>
                                @else
                                    <span style="font-size:.72rem;color:var(--sz-muted);">-</span>
                                @endif
                            </td>
                            <td>
                                @php 
                                    $sc = $statusColors[$lead->status] ?? '#94a3b8'; 
                                    $statusIcon = match($lead->status) {
                                        'new' => 'bell',
                                        'contacted' => 'mail',
                                        'converted' => 'check',
                                        'lost' => 'x',
                                        default => 'clock-3'
                                    };
                                @endphp
                                <span class="sz-badge status badge-with-icon" style="background:{{ $sc }};">
                                    @include('admin.partials.icon', ['name' => $statusIcon, 'size' => 12])
                                    <span>{{ ucfirst(str_replace('_',' ',$lead->status)) }}</span>
                                </span>
                            </td>
                            <td>
                                @if($lead->assignedStaff)
                                    <div style="display:flex;align-items:center;gap:4px;">
                                        <div class="sz-avatar" style="width:22px;height:22px;font-size:.55rem;background:#6366f1;">{{ $lead->assignedStaff->name[0] ?? '?' }}</div>
                                        <span style="font-size:.72rem;">{{ $lead->assignedStaff->name }}</span>
                                    </div>
                                @else
                                    <span style="font-size:.72rem;color:var(--sz-muted);">Unassigned</span>
                                @endif
                            </td>
                            <td>
                                <div style="font-size:.75rem;">{{ $lead->created_at?->format('d M Y') }}</div>
                                <div style="font-size:.65rem;color:var(--sz-muted);">{{ $lead->created_at?->diffForHumans() }}</div>
                            </td>
                            <td>
                                <div style="display:flex;gap:.2rem;">
                                    <a href="{{ route('admin.leads.show', $lead) }}" class="button button--small" style="padding:.2rem .45rem;font-size:.67rem;background:#e0f2fe;color:#0369a1;border:1px solid #bae6fd;text-decoration:none;display:inline-flex;align-items:center;gap:0.25rem;" onclick="openDrawer(event, {{ $lead->id }})" aria-label="View Lead Details" title="View">
                                        @include('admin.partials.icon', ['name' => 'eye', 'size' => 12])
                                        <span>View</span>
                                    </a>
                                    @can('lead_center.edit')
                                    <a href="{{ route('admin.leads.edit', $lead) }}" class="button button--small" style="padding:.2rem .45rem;font-size:.67rem;background:#f1f5f9;color:#334155;border:1px solid #cbd5e1;text-decoration:none;display:inline-flex;align-items:center;gap:0.25rem;" aria-label="Edit Lead Details" title="Edit">
                                        @include('admin.partials.icon', ['name' => 'pencil', 'size' => 12])
                                        <span>Edit</span>
                                    </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="sz-empty">
                                    <div class="sz-empty-icon" style="color: var(--sz-muted); display: flex; justify-content: center; margin-bottom: 0.5rem;">
                                        @include('admin.partials.icon', ['name' => 'folder-open', 'size' => 48, 'strokeWidth' => 1.5])
                                    </div>
                                    <h3>No leads found</h3>
                                    <p>Try adjusting your filters or search terms.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($leads->hasPages())
        <div class="sz-pagination">
            <div class="info">Showing {{ $leads->firstItem() }}–{{ $leads->lastItem() }} of {{ $leads->total() }}</div>
            <div class="links">{{ $leads->onEachSide(1)->links('pagination::bootstrap-4') }}</div>
        </div>
        @endif

        {{-- Source Distribution --}}
        @if($leadsBySource->count() > 1)
        <div style="background:var(--sz-card);border-radius:var(--sz-radius);padding:.85rem 1.1rem;box-shadow:var(--sz-shadow);border:1px solid var(--sz-border);">
            <h4 style="font-size:.78rem;font-weight:700;color:var(--sz-text);margin-bottom:.65rem;">Leads by Source</h4>
            <div style="display:flex;gap:.75rem;flex-wrap:wrap;">
                @foreach($leadsBySource as $dist)
                    @php $label = $formTypes[$dist->form_type] ?? ucfirst(str_replace('_',' ',$dist->form_type)); @endphp
                    <div style="display:flex;align-items:center;gap:.35rem;">
                        <span style="width:10px;height:10px;border-radius:2px;background:{{ \App\Models\Lead::statusColors()[$dist->form_type] ?? '#6366f1' }};"></span>
                        <span style="font-size:.76rem;font-weight:600;">{{ $label }}</span>
                        <span style="font-size:.7rem;color:var(--sz-muted);">{{ $dist->count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Page Distribution --}}
        @if($leadsByPage->count() > 1)
        <div style="background:var(--sz-card);border-radius:var(--sz-radius);padding:.85rem 1.1rem;box-shadow:var(--sz-shadow);border:1px solid var(--sz-border);">
            <h4 style="font-size:.78rem;font-weight:700;color:var(--sz-text);margin-bottom:.65rem;">Leads by Website Page</h4>
            <div style="display:flex;gap:.75rem;flex-wrap:wrap;">
                @foreach($leadsByPage as $dist)
                    @php $label = $sourcePages[$dist->source_page] ?? $dist->source_page; @endphp
                    <div style="display:flex;align-items:center;gap:.35rem;">
                        <span style="width:10px;height:10px;border-radius:2px;background:#14a394;"></span>
                        <span style="font-size:.76rem;font-weight:600;">{{ $label }}</span>
                        <span style="font-size:.7rem;color:var(--sz-muted);">{{ $dist->count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Bulk Action Bar --}}
<div class="sz-bulk-bar" id="bulk-bar">
    <form method="POST" action="{{ route('admin.leads.bulk-action') }}" style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
        @csrf
        <input type="hidden" name="lead_ids" id="bulk-lead-ids">
        <span style="font-size:.8rem;font-weight:600;"><span id="bulk-count">0</span> selected</span>
        <select name="action" class="sz-select" id="bulk-action">
            <option value="">Action...</option>
            <option value="assign">Assign to Staff</option>
            <option value="status">Change Status</option>
            <option value="archive">Archive</option>
            @can('lead_center.delete')
            <option value="delete">Delete</option>
            @endcan
        </select>
        <div id="bulk-value-wrap" style="display:none;">
            <select name="value" class="sz-select" id="bulk-value"></select>
        </div>
        <button type="submit" class="button button--small" style="background:var(--sz-primary);color:#fff;border:none;" onclick="return confirm('Apply bulk action?')">Apply</button>
        <button type="button" class="button button--small" onclick="clearSelection()" style="background:#f1f5f9;color:#475569;border:1px solid #cbd5e1;">Cancel</button>
    </form>
</div>

{{-- Lead Drawer --}}
<div class="sz-modal" id="lead-drawer">
    <div class="sz-modal-box" style="width:min(95vw,640px);">
        <div id="drawer-content" style="min-height:150px;">
            <div style="text-align:center;padding:2rem;">
                <div style="width:30px;height:30px;border:3px solid var(--sz-primary);border-top-color:transparent;border-radius:50%;animation:spin .6s linear infinite;margin:0 auto;"></div>
                <p style="margin-top:.75rem;color:var(--sz-muted);font-size:.85rem;">Loading lead...</p>
            </div>
        </div>
        <div class="sz-modal-actions" style="display:flex;gap:.5rem;justify-content:flex-end;margin-top:1rem;padding-top:1rem;border-top:1px solid var(--sz-border);">
            <button class="button button--small" onclick="closeDrawer()" style="background:#f1f5f9;color:#475569;border:1px solid #cbd5e1;">Close</button>
        </div>
    </div>
</div>

<style>
@keyframes spin { to { transform:rotate(360deg); } }
</style>

<script>
let selected = new Set();

document.addEventListener('DOMContentLoaded', function() {
    const selAll = document.getElementById('select-all');
    if (selAll) {
        selAll.addEventListener('change', function() {
            document.querySelectorAll('.lead-checkbox').forEach(cb => {
                cb.checked = this.checked;
                this.checked ? selected.add(cb.value) : selected.delete(cb.value);
            });
            updateBulk();
        });
    }
    document.querySelectorAll('.lead-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            this.checked ? selected.add(this.value) : selected.delete(this.value);
            updateBulk();
        });
    });

    const toast = document.getElementById('status-toast');
    if (toast) { setTimeout(() => toast.classList.remove('show'), 3000); setTimeout(() => toast.remove(), 4000); }

    document.getElementById('bulk-action')?.addEventListener('change', function() {
        const wrap = document.getElementById('bulk-value-wrap');
        const val = document.getElementById('bulk-value');
        if (this.value === 'assign') {
            wrap.style.display = 'block';
            val.outerHTML = `<select name="value" class="sz-select" id="bulk-value">
                @foreach($staff as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach
            </select>`;
        } else if (this.value === 'status') {
            wrap.style.display = 'block';
            val.outerHTML = `<select name="value" class="sz-select" id="bulk-value">
                @foreach(array_keys($statusColors) as $s)<option value="{{ $s }}">{{ ucfirst(str_replace('_',' ',$s)) }}</option>@endforeach
            </select>`;
        } else { wrap.style.display = 'none'; }
    });
});

function updateBulk() {
    const bar = document.getElementById('bulk-bar');
    if (selected.size > 0) {
        bar.style.display = 'flex';
        document.getElementById('bulk-count').textContent = selected.size;
        document.getElementById('bulk-lead-ids').value = JSON.stringify([...selected]);
    } else { bar.style.display = 'none'; }
}

function clearSelection() {
    selected.clear();
    document.querySelectorAll('.lead-checkbox').forEach(cb => cb.checked = false);
    const sa = document.getElementById('select-all');
    if (sa) sa.checked = false;
    updateBulk();
}

async function openDrawer(event, leadId) {
    event?.preventDefault();
    const overlay = document.getElementById('lead-drawer');
    const content = document.getElementById('drawer-content');
    overlay.classList.add('is-open');
    content.innerHTML = `<div style="text-align:center;padding:2rem;"><div style="width:30px;height:30px;border:3px solid #14a394;border-top-color:transparent;border-radius:50%;animation:spin .6s linear infinite;margin:0 auto;"></div><p style="margin-top:.75rem;color:#94a3b8;font-size:.85rem;">Loading lead...</p></div>`;
    try {
        const res = await fetch('/admin/leads/' + leadId);
        const html = await res.text();
        const doc = new DOMParser().parseFromString(html, 'text/html');
        const main = doc.querySelector('[data-drawer-content]');
        content.innerHTML = main ? main.innerHTML : html;
    } catch(e) {
        content.innerHTML = `<div style="text-align:center;padding:2rem;"><h3>Error loading lead</h3><p style="color:#94a3b8;">${e.message}</p></div>`;
    }
}

function closeDrawer() { document.getElementById('lead-drawer').classList.remove('is-open'); }
document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeDrawer(); });
</script>
@endsection
