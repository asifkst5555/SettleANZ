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

.sz-toast { position:fixed; bottom:1.5rem; right:1.5rem; background:#1e293b; color:#fff; padding:.65rem 1.15rem; border-radius:10px; font-size:.82rem; font-weight:600; z-index:2000; transform:translateY(calc(100% + 2rem)); transition:transform .3s cubic-bezier(.4,0,.2,1); box-shadow:0 4px 12px rgba(0,0,0,.2); }
.sz-toast.show { transform:translateY(0); }

.sz-empty { text-align:center; padding:4rem 2rem; }
.sz-empty-icon { font-size:2.5rem; margin-bottom:.5rem; }
.sz-empty h3 { color:var(--sz-text); }
.sz-empty p { color:var(--sz-muted); font-size:.82rem; }

@media(max-width:768px){
    .sz-stats { grid-template-columns:repeat(2,1fr); }
}
</style>

@php
    $sortField = request('sort', 'created_at');
    $sortDir = request('direction', 'desc');
    $nextDir = $sortDir === 'asc' ? 'desc' : 'asc';
    function sortUrl($field, $currentField, $currentDir, $nextDir) {
        return route('admin.leads.index', array_merge(request()->query(), ['sort' => $field, 'direction' => ($field === $currentField ? $nextDir : 'desc')]));
    }
@endphp

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
            <button type="button" onclick="openBulkExportModal()" class="button button--small" style="background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;display:inline-flex;align-items:center;gap:0.35rem;" aria-label="Export leads menu" title="Export Menu">
                @include('admin.partials.icon', ['name' => 'file-down', 'size' => 15])
                <span>Export Menu</span>
            </button>
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

        {{-- Toolbar / Filter section --}}
        <form method="GET" action="{{ route('admin.leads.index') }}" class="sz-toolbar" id="filter-form">
            <div class="quick-filter">
                <a href="{{ route('admin.leads.index') }}" class="{{ !request('form_type') ? 'is-active' : '' }}">All</a>
                <a href="{{ route('admin.leads.index', array_merge(request()->except('form_type'), ['form_type' => 'contact-page'])) }}" class="{{ request('form_type') === 'contact-page' ? 'is-active' : '' }}">Contact</a>
                <a href="{{ route('admin.leads.index', array_merge(request()->except('form_type'), ['form_type' => 'package_booking'])) }}" class="{{ request('form_type') === 'package_booking' ? 'is-active' : '' }}">Packages</a>
                <a href="{{ route('admin.leads.index', array_merge(request()->except('form_type'), ['form_type' => 'ebook_download'])) }}" class="{{ request('form_type') === 'ebook_download' ? 'is-active' : '' }}">Ebooks</a>
                <a href="{{ route('admin.leads.index', array_merge(request()->except('form_type'), ['form_type' => 'homepage_roadmap'])) }}" class="{{ request('form_type') === 'homepage_roadmap' ? 'is-active' : '' }}">Roadmap</a>
                <a href="{{ route('admin.leads.index', array_merge(request()->except('form_type'), ['form_type' => 'ai_chat'])) }}" class="{{ request('form_type') === 'ai_chat' ? 'is-active' : '' }}">AI Chat</a>
            </div>

            @if(request('form_type'))
                <input type="hidden" name="form_type" value="{{ request('form_type') }}">
            @endif

            @if(request('per_page'))
                <input type="hidden" name="per_page" id="per_page_input" value="{{ request('per_page') }}">
            @endif

            <input type="text" name="search" class="sz-search" placeholder="Search name, email, phone..." value="{{ $filters['search'] ?? '' }}">

            {{-- Custom Dropdown: Status --}}
            <div class="custom-dropdown" data-dropdown id="status-filter-wrapper">
                <button type="button" class="dropdown-trigger" id="status-filter-trigger" aria-haspopup="listbox" aria-expanded="false" aria-label="Status filter">
                    <span>
                        @if(request('status'))
                            Status: {{ ucfirst(str_replace('_',' ',request('status'))) }}
                        @else
                            All Status
                        @endif
                    </span>
                    <svg class="chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div class="dropdown-menu" role="listbox">
                    <ul class="dropdown-options">
                        <li class="dropdown-option {{ !request('status') ? 'selected' : '' }}" data-value="" role="option" aria-selected="{{ !request('status') ? 'true' : 'false' }}">All Status</li>
                        @foreach(array_keys($statusColors) as $s)
                            <li class="dropdown-option {{ request('status') === $s ? 'selected' : '' }}" data-value="{{ $s }}" role="option" aria-selected="{{ request('status') === $s ? 'true' : 'false' }}">
                                {{ ucfirst(str_replace('_',' ',$s)) }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                <input type="hidden" name="status" value="{{ request('status') }}">
            </div>

            {{-- Custom Dropdown: Visa Types --}}
            <div class="custom-dropdown" data-dropdown id="visa-filter-wrapper">
                <button type="button" class="dropdown-trigger" id="visa-filter-trigger" aria-haspopup="listbox" aria-expanded="false" aria-label="Visa type filter">
                    <span>
                        @if(request('visa_type'))
                            Visa: {{ $visaTypes[request('visa_type')] ?? request('visa_type') }}
                        @else
                            All Visa Types
                        @endif
                    </span>
                    <svg class="chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div class="dropdown-menu" role="listbox">
                    <div class="dropdown-search-wrap">
                        <input type="text" class="dropdown-search-input" placeholder="Search visas..." onkeyup="filterDropdownOptions(this)">
                    </div>
                    <ul class="dropdown-options">
                        <li class="dropdown-option {{ !request('visa_type') ? 'selected' : '' }}" data-value="" role="option" aria-selected="{{ !request('visa_type') ? 'true' : 'false' }}">All Visa Types</li>
                        @foreach($visaTypes as $k => $v)
                            <li class="dropdown-option {{ request('visa_type') === $k ? 'selected' : '' }}" data-value="{{ $k }}" role="option" aria-selected="{{ request('visa_type') === $k ? 'true' : 'false' }}">
                                {{ $v }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                <input type="hidden" name="visa_type" value="{{ request('visa_type') }}">
            </div>

            {{-- Custom Dropdown: Assigned Staff --}}
            <div class="custom-dropdown" data-dropdown id="staff-filter-wrapper">
                <button type="button" class="dropdown-trigger" id="staff-filter-trigger" aria-haspopup="listbox" aria-expanded="false" aria-label="Staff filter">
                    <span>
                        @if(request('assigned_to'))
                            Staff: {{ $staff->firstWhere('id', request('assigned_to'))->name ?? 'Unknown' }}
                        @else
                            All Staff
                        @endif
                    </span>
                    <svg class="chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div class="dropdown-menu" role="listbox">
                    <ul class="dropdown-options">
                        <li class="dropdown-option {{ !request('assigned_to') ? 'selected' : '' }}" data-value="" role="option" aria-selected="{{ !request('assigned_to') ? 'true' : 'false' }}">All Staff</li>
                        @foreach($staff as $s)
                            <li class="dropdown-option {{ request('assigned_to') == $s->id ? 'selected' : '' }}" data-value="{{ $s->id }}" role="option" aria-selected="{{ request('assigned_to') == $s->id ? 'true' : 'false' }}">
                                {{ $s->name }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                <input type="hidden" name="assigned_to" value="{{ request('assigned_to') }}">
            </div>

            <button type="submit" class="button button--small" style="display:inline-flex;align-items:center;gap:0.35rem;">
                @include('admin.partials.icon', ['name' => 'sliders-horizontal', 'size' => 14])
                <span>Filter</span>
            </button>
            @if(array_filter($filters) || request('form_type'))
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
                @if($value && $key !== 'per_page')
                    <span style="display:inline-flex;align-items:center;gap:3px;padding:.15rem .5rem;border-radius:999px;font-size:.68rem;font-weight:600;background:#e0e7ff;color:#4338ca;">
                        {{ ucwords(str_replace('_',' ',$key)) }}: {{ $value }}
                        <a href="{{ route('admin.leads.index', request()->except($key)) }}" style="text-decoration:none;color:inherit;opacity:.6;">&times;</a>
                    </span>
                @endif
            @endforeach
        </div>
        @endif

        {{-- Gmail Style Banner --}}
        <div class="sz-selection-banner" id="selection-banner">
            <span id="selection-banner-text">All {{ $leads->count() }} leads on this page are selected.</span>
            <button type="button" id="select-all-matching-btn" onclick="toggleSelectAllMatching(true)">Select all <strong class="total-matching-count">{{ $leads->total() }}</strong> matching leads</button>
            <button type="button" id="clear-all-matching-btn" onclick="toggleSelectAllMatching(false)" style="display:none;">Clear selection</button>
        </div>

        {{-- Data Table --}}
        <div class="sz-table-wrap">
            <table class="sz-table" id="leads-table">
                <thead>
                    <tr>
                        <th style="width:28px;"><input type="checkbox" class="sz-checkbox" id="select-all" aria-label="Select all leads on current page"></th>
                        
                        <th class="sortable-header" onclick="window.location='{{ sortUrl('full_name', $sortField, $sortDir, $nextDir) }}'">
                            Lead
                            <span class="sort-indicator">{!! $sortField === 'full_name' ? ($sortDir === 'asc' ? '▲' : '▼') : '⇅' !!}</span>
                        </th>
                        
                        <th class="sortable-header" onclick="window.location='{{ sortUrl('form_type', $sortField, $sortDir, $nextDir) }}'">
                            Lead Source
                            <span class="sort-indicator">{!! $sortField === 'form_type' ? ($sortDir === 'asc' ? '▲' : '▼') : '⇅' !!}</span>
                        </th>
                        
                        <th class="sortable-header" onclick="window.location='{{ sortUrl('source_page', $sortField, $sortDir, $nextDir) }}'">
                            Website Page
                            <span class="sort-indicator">{!! $sortField === 'source_page' ? ($sortDir === 'asc' ? '▲' : '▼') : '⇅' !!}</span>
                        </th>
                        
                        <th class="sortable-header" onclick="window.location='{{ sortUrl('visa_type', $sortField, $sortDir, $nextDir) }}'">
                            Visa / Service
                            <span class="sort-indicator">{!! $sortField === 'visa_type' ? ($sortDir === 'asc' ? '▲' : '▼') : '⇅' !!}</span>
                        </th>
                        
                        <th class="sortable-header" onclick="window.location='{{ sortUrl('status', $sortField, $sortDir, $nextDir) }}'">
                            Status
                            <span class="sort-indicator">{!! $sortField === 'status' ? ($sortDir === 'asc' ? '▲' : '▼') : '⇅' !!}</span>
                        </th>
                        
                        <th>Staff</th>
                        
                        <th class="sortable-header" onclick="window.location='{{ sortUrl('created_at', $sortField, $sortDir, $nextDir) }}'">
                            Created
                            <span class="sort-indicator">{!! $sortField === 'created_at' ? ($sortDir === 'asc' ? '▲' : '▼') : '⇅' !!}</span>
                        </th>
                        
                        <th style="width:70px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leads as $lead)
                        <tr id="lead-row-{{ $lead->id }}">
                            <td><input type="checkbox" class="sz-checkbox lead-checkbox" value="{{ $lead->id }}" aria-label="Select lead: {{ $lead->full_name }}"></td>
                             <td>
                                @php
                                    $baseColor = $lead->avatar_color;
                                    $gradientMap = [
                                        '#6366f1' => 'linear-gradient(135deg, #818cf8 0%, #6366f1 100%)',
                                        '#14a394' => 'linear-gradient(135deg, #2dd4bf 0%, #14a394 100%)',
                                        '#e8773a' => 'linear-gradient(135deg, #fb923c 0%, #e8773a 100%)',
                                        '#7c3aed' => 'linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%)',
                                        '#dc3545' => 'linear-gradient(135deg, #f87171 0%, #dc3545 100%)',
                                        '#0ea5e9' => 'linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%)',
                                        '#f59e0b' => 'linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%)',
                                        '#10b981' => 'linear-gradient(135deg, #34d399 0%, #10b981 100%)',
                                        '#8b5cf6' => 'linear-gradient(135deg, #a78bfa 0%, #8b5cf6 100%)',
                                        '#ec4899' => 'linear-gradient(135deg, #f472b6 0%, #ec4899 100%)',
                                    ];
                                    $gradient = $gradientMap[$baseColor] ?? "linear-gradient(135deg, #94a3b8 0%, #64748b 100%)";
                                @endphp
                                <div class="sz-profile-cell">
                                    <div class="sz-avatar-container" style="background: {{ $gradient }};">
                                        @if(!empty($lead->photo_url))
                                            <img src="{{ $lead->photo_url }}" alt="{{ $lead->full_name }}" class="sz-avatar-image">
                                        @elseif(!empty($lead->avatar_url))
                                            <img src="{{ $lead->avatar_url }}" alt="{{ $lead->full_name }}" class="sz-avatar-image">
                                        @else
                                            <span>{{ $lead->initials }}</span>
                                        @endif
                                    </div>
                                    <div class="sz-profile-info">
                                        <a href="{{ route('admin.leads.show', $lead) }}" class="sz-profile-name" onclick="openLeadDrawer(event, {{ $lead->id }})">{{ $lead->full_name ?: ($lead->first_name ?: 'Unknown') }}</a>
                                        <div class="sz-profile-email" title="{{ $lead->email }}">{{ $lead->email }}</div>
                                        <div class="sz-profile-meta" title="Source: {{ $lead->lead_source_label }} • Created: {{ $lead->created_at?->toDayDateTimeString() }}">{{ $lead->lead_source_label }} &bull; {{ $lead->created_at?->diffForHumans() }}</div>
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
                                    $statusMap = [
                                        'new' => ['label' => 'New', 'class' => 'status-new', 'icon' => 'bell'],
                                        'contacted' => ['label' => 'Contacted', 'class' => 'status-contacted', 'icon' => 'mail'],
                                        'qualified' => ['label' => 'Qualified', 'class' => 'status-downloaded', 'icon' => 'check-circle'],
                                        'follow_up' => ['label' => 'Follow Up', 'class' => 'status-assigned', 'icon' => 'user'],
                                        'consultation_booked' => ['label' => 'Consultation Booked', 'class' => 'status-contacted', 'icon' => 'calendar'],
                                        'proposal_sent' => ['label' => 'Proposal Sent', 'class' => 'status-downloaded', 'icon' => 'download'],
                                        'negotiating' => ['label' => 'Negotiating', 'class' => 'status-assigned', 'icon' => 'user'],
                                        'won' => ['label' => 'Won', 'class' => 'status-closed', 'icon' => 'check-circle'],
                                        'lost' => ['label' => 'Lost', 'class' => 'status-spam', 'icon' => 'shield-alert'],
                                    ];

                                    $statusData = $statusMap[$lead->status] ?? ['label' => ucfirst($lead->status), 'class' => 'status-archived', 'icon' => 'clock-3'];
                                    
                                    if ($lead->is_archived) {
                                        $statusData = ['label' => 'Archived', 'class' => 'status-archived', 'icon' => 'archive'];
                                    }
                                @endphp
                                <span class="sz-status-badge {{ $statusData['class'] }}">
                                    @include('admin.partials.icon', ['name' => $statusData['icon'], 'size' => 12])
                                    <span>{{ $statusData['label'] }}</span>
                                </span>
                            </td>
                            <td>
                                @if($lead->assignedStaff)
                                    <div class="sz-staff-cell">
                                        <div class="sz-avatar-staff" style="background:#6366f1;">{{ $lead->assignedStaff->name[0] ?? '?' }}</div>
                                        <div class="sz-staff-info">
                                            <span class="sz-staff-name">{{ $lead->assignedStaff->name }}</span>
                                            <span class="sz-staff-role">Staff</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="sz-staff-cell unassigned">
                                        <div class="sz-avatar-staff">?</div>
                                        <div class="sz-staff-info">
                                            <span class="sz-staff-name">Unassigned</span>
                                            <button type="button" class="sz-staff-assign-btn" onclick="clearSelection(); selected.add('{{ $lead->id }}'); saveSelection(); updateBulkBar(); openBulkModal('assign');">Assign</button>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-size:0.825rem; font-weight:600; color:#334155;">{{ $lead->created_at?->diffForHumans() }}</div>
                                <div style="font-size:0.7rem; color:#94a3b8; margin-top:2px;">{{ $lead->created_at?->format('d M Y • g:i A') }}</div>
                            </td>
                            <td>
                                <div class="sz-row-actions" data-row-dropdown>
                                    <button type="button" class="sz-action-trigger" aria-label="Lead Actions" onclick="toggleRowDropdown(event, {{ $lead->id }})">
                                        @include('admin.partials.icon', ['name' => 'more-vertical', 'size' => 14])
                                    </button>
                                    <div class="sz-action-menu" id="row-dropdown-{{ $lead->id }}">
                                        <a href="{{ route('admin.leads.show', $lead) }}" onclick="openLeadDrawer(event, {{ $lead->id }}); closeAllRowDropdowns();">
                                            @include('admin.partials.icon', ['name' => 'eye', 'size' => 14])
                                            <span>View Details</span>
                                        </a>
                                        @can('lead_center.edit')
                                        <a href="{{ route('admin.leads.edit', $lead) }}">
                                            @include('admin.partials.icon', ['name' => 'pencil', 'size' => 14])
                                            <span>Edit Lead</span>
                                        </a>
                                        @endcan
                                        <a href="javascript:void(0)" onclick="clearSelection(); selected.add('{{ $lead->id }}'); saveSelection(); updateBulkBar(); openBulkModal('status'); closeAllRowDropdowns();">
                                            @include('admin.partials.icon', ['name' => 'tag', 'size' => 14])
                                            <span>Update Status</span>
                                        </a>
                                        <a href="javascript:void(0)" onclick="clearSelection(); selected.add('{{ $lead->id }}'); saveSelection(); updateBulkBar(); openBulkModal('archive'); closeAllRowDropdowns();">
                                            @include('admin.partials.icon', ['name' => 'archive', 'size' => 14])
                                            <span>Archive Lead</span>
                                        </a>
                                        @can('lead_center.delete')
                                        <a href="javascript:void(0)" onclick="clearSelection(); selected.add('{{ $lead->id }}'); saveSelection(); updateBulkBar(); openBulkModal('delete'); closeAllRowDropdowns();" style="color:#ef4444;">
                                            @include('admin.partials.icon', ['name' => 'trash-2', 'size' => 14])
                                            <span>Delete Lead</span>
                                        </a>
                                        @endcan
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="sz-empty">
                                    <div class="sz-empty-icon" style="color: var(--sz-primary); display: flex; justify-content: center; margin-bottom: 1rem;">
                                        @include('admin.partials.icon', ['name' => 'inbox', 'size' => 64, 'strokeWidth' => 1.2])
                                    </div>
                                    <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--sz-text); margin-bottom: 0.5rem;">No Leads Found</h3>
                                    <p style="color: var(--sz-muted); font-size: 0.875rem; max-width: 320px; margin: 0 auto 1.5rem;">We couldn't find any lead matching your search query or filters in SettleANZ workspace.</p>
                                    <a href="{{ route('admin.leads.index') }}" class="button button--small" style="background: var(--sz-primary); color: #fff; border-color: var(--sz-primary); display: inline-flex; align-items: center; gap: 0.35rem;">
                                        @include('admin.partials.icon', ['name' => 'rotate-ccw', 'size' => 14])
                                        <span>Reset Filters</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Custom Premium SaaS Pagination --}}
        @if($leads->hasPages())
        <div class="sz-pagination">
            <div style="display:flex;align-items:center;gap:1.5rem;">
                <div class="info">Showing {{ $leads->firstItem() }}–{{ $leads->lastItem() }} of {{ $leads->total() }} Leads</div>
                <div class="rows-selector">
                    <span>Rows per page:</span>
                    <select class="rows-select" onchange="changePerPage(this.value)">
                        <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        <option value="250" {{ request('per_page') == 250 ? 'selected' : '' }}>250</option>
                    </select>
                </div>
            </div>
            <div class="links">
                @if ($leads->onFirstPage())
                    <span class="pg-btn disabled">Previous</span>
                @else
                    <a href="{{ $leads->previousPageUrl() }}" class="pg-btn">Previous</a>
                @endif

                @php
                    $currentPage = $leads->currentPage();
                    $lastPage = $leads->lastPage();
                    $start = max(1, $currentPage - 2);
                    $end = min($lastPage, $currentPage + 2);
                @endphp

                @if ($start > 1)
                    <a href="{{ $leads->url(1) }}" class="pg-btn">1</a>
                    @if ($start > 2)
                        <span class="pg-dots">...</span>
                    @endif
                @endif

                @for ($page = $start; $page <= $end; $page++)
                    @if ($page == $currentPage)
                        <span class="pg-btn current">{{ $page }}</span>
                    @else
                        <a href="{{ $leads->url($page) }}" class="pg-btn">{{ $page }}</a>
                    @endif
                @endfor

                @if ($end < $lastPage)
                    @if ($end < $lastPage - 1)
                        <span class="pg-dots">...</span>
                    @endif
                    <a href="{{ $leads->url($lastPage) }}" class="pg-btn">{{ $lastPage }}</a>
                @endif

                @if ($leads->hasMorePages())
                    <a href="{{ $leads->nextPageUrl() }}" class="pg-btn">Next</a>
                @else
                    <span class="pg-btn disabled">Next</span>
                @endif
            </div>
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

{{-- Premium Floating Action Bar --}}
<div class="sz-bulk-bar" id="bulk-bar">
    <span class="bulk-info-text"><span id="bulk-count">0</span> Leads Selected</span>
    <div class="bulk-actions-group">
        <button type="button" class="bulk-btn bulk-btn-secondary" onclick="openBulkModal('assign')" aria-label="Assign selected leads to staff">
            @include('admin.partials.icon', ['name' => 'user-plus', 'size' => 13])
            <span>Assign Staff</span>
        </button>
        <button type="button" class="bulk-btn bulk-btn-secondary" onclick="openBulkModal('status')" aria-label="Change status of selected leads">
            @include('admin.partials.icon', ['name' => 'tag', 'size' => 13])
            <span>Change Status</span>
        </button>
        <button type="button" class="bulk-btn bulk-btn-secondary" onclick="openBulkModal('archive')" aria-label="Archive selected leads">
            @include('admin.partials.icon', ['name' => 'archive', 'size' => 13])
            <span>Archive</span>
        </button>
        @can('lead_center.delete')
        <button type="button" class="bulk-btn bulk-btn-danger" onclick="openBulkModal('delete')" aria-label="Delete selected leads">
            @include('admin.partials.icon', ['name' => 'trash-2', 'size' => 13])
            <span>Delete</span>
        </button>
        @endcan
        @can('lead_center.export')
        <button type="button" class="bulk-btn bulk-btn-secondary" onclick="openBulkExportModal()" aria-label="Export selected leads">
            @include('admin.partials.icon', ['name' => 'download', 'size' => 13])
            <span>Export</span>
        </button>
        @endcan
        <button type="button" class="bulk-btn bulk-btn-secondary" onclick="clearSelection()" style="background:transparent;border:none;color:#94a3b8;font-weight:500;">
            Cancel
        </button>
    </div>
</div>

{{-- Modal: Assign Staff --}}
<div class="custom-modal" id="modal-bulk-assign" role="dialog" aria-modal="true" aria-labelledby="modal-assign-title" hidden>
    <div class="custom-modal__backdrop" onclick="closeBulkModal('assign')"></div>
    <div class="custom-modal__content">
        <h3 class="modal-title" id="modal-assign-title">Assign Staff</h3>
        <p class="modal-desc">Assign the selected leads to a team member.</p>
        <div class="modal-body">
            <div class="custom-dropdown" data-dropdown style="width: 100%;">
                <button type="button" class="dropdown-trigger" style="width: 100%;" aria-haspopup="listbox" aria-expanded="false" id="bulk-assign-trigger">
                    <span>Select Staff...</span>
                    <svg class="chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div class="dropdown-menu" role="listbox">
                    <ul class="dropdown-options" id="modal-assign-options">
                        @foreach($staff as $s)
                            <li class="dropdown-option" data-value="{{ $s->id }}" role="option" aria-selected="false">{{ $s->name }}</li>
                        @endforeach
                    </ul>
                </div>
                <input type="hidden" id="bulk-assign-value" value="">
            </div>
            
            {{-- Action progress --}}
            <div class="progress-container" id="progress-container-assign" style="display:none;">
                <div class="progress-bar-fill" id="progress-bar-fill-assign"></div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="pg-btn" onclick="closeBulkModal('assign')">Cancel</button>
            <button type="button" class="pg-btn current" onclick="submitBulkAction('assign')">Assign Staff</button>
        </div>
    </div>
</div>

{{-- Modal: Change Status --}}
<div class="custom-modal" id="modal-bulk-status" role="dialog" aria-modal="true" aria-labelledby="modal-status-title" hidden>
    <div class="custom-modal__backdrop" onclick="closeBulkModal('status')"></div>
    <div class="custom-modal__content">
        <h3 class="modal-title" id="modal-status-title">Change Status</h3>
        <p class="modal-desc">Update the status of the selected leads.</p>
        <div class="modal-body">
            <div class="custom-dropdown" data-dropdown style="width: 100%;">
                <button type="button" class="dropdown-trigger" style="width: 100%;" aria-haspopup="listbox" aria-expanded="false" id="bulk-status-trigger">
                    <span>Select Status...</span>
                    <svg class="chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div class="dropdown-menu" role="listbox">
                    <ul class="dropdown-options" id="modal-status-options">
                        @foreach(array_keys($statusColors) as $s)
                            <li class="dropdown-option" data-value="{{ $s }}" role="option" aria-selected="false">{{ ucfirst(str_replace('_',' ',$s)) }}</li>
                        @endforeach
                    </ul>
                </div>
                <input type="hidden" id="bulk-status-value" value="">
            </div>
            
            {{-- Action progress --}}
            <div class="progress-container" id="progress-container-status" style="display:none;">
                <div class="progress-bar-fill" id="progress-bar-fill-status"></div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="pg-btn" onclick="closeBulkModal('status')">Cancel</button>
            <button type="button" class="pg-btn current" onclick="submitBulkAction('status')">Update Status</button>
        </div>
    </div>
</div>

{{-- Modal: Archive Confirmation --}}
<div class="custom-modal" id="modal-bulk-archive" role="dialog" aria-modal="true" aria-labelledby="modal-archive-title" hidden>
    <div class="custom-modal__backdrop" onclick="closeBulkModal('archive')"></div>
    <div class="custom-modal__content">
        <h3 class="modal-title" id="modal-archive-title">Archive Leads</h3>
        <p class="modal-desc">Are you sure you want to archive the selected <strong class="bulk-selected-count-label">0</strong> leads? They will be removed from your active workspace.</p>
        <div class="modal-body">
            <div class="progress-container" id="progress-container-archive" style="display:none;">
                <div class="progress-bar-fill" id="progress-bar-fill-archive"></div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="pg-btn" onclick="closeBulkModal('archive')">Cancel</button>
            <button type="button" class="pg-btn current" onclick="submitBulkAction('archive')">Archive Leads</button>
        </div>
    </div>
</div>

{{-- Modal: Delete Confirmation --}}
<div class="custom-modal" id="modal-bulk-delete" role="dialog" aria-modal="true" aria-labelledby="modal-delete-title" hidden>
    <div class="custom-modal__backdrop" onclick="closeBulkModal('delete')"></div>
    <div class="custom-modal__content">
        <h3 class="modal-title" id="modal-delete-title" style="color: #dc2626;">Delete Leads</h3>
        <p class="modal-desc">Warning: This action is permanent and cannot be undone. Are you sure you want to delete the selected <strong class="bulk-selected-count-label">0</strong> leads?</p>
        <div class="modal-body">
            <div class="progress-container" id="progress-container-delete" style="display:none;">
                <div class="progress-bar-fill" id="progress-bar-fill-delete"></div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="pg-btn" onclick="closeBulkModal('delete')">Cancel</button>
            <button type="button" class="pg-btn" onclick="submitBulkAction('delete')" style="background:#dc2626;color:#fff;border-color:#dc2626;">Delete Leads</button>
        </div>
    </div>
</div>

{{-- Modal: Export Options --}}
<div class="custom-modal" id="modal-bulk-export" role="dialog" aria-modal="true" aria-labelledby="modal-export-title" hidden>
    <div class="custom-modal__backdrop" onclick="closeBulkExportModal()"></div>
    <div class="custom-modal__content">
        <h3 class="modal-title" id="modal-export-title">Export Leads</h3>
        <p class="modal-desc">Configure the export scope and format.</p>
        <div class="modal-body" style="display:flex; flex-direction:column; gap:1rem;">
            <div>
                <label style="font-size:0.8rem; font-weight:600; color:#475569; display:block; margin-bottom:0.35rem;">Scope</label>
                <select id="export-scope" class="rows-select" style="width:100%; height:36px; padding:0 0.5rem;">
                    <option value="selected">Selected Leads (<span class="export-selected-count">0</span>)</option>
                    <option value="filtered">All Filtered Leads ({{ $leads->total() }})</option>
                    <option value="page">Current Page Leads ({{ $leads->count() }})</option>
                </select>
            </div>
            <div>
                <label style="font-size:0.8rem; font-weight:600; color:#475569; display:block; margin-bottom:0.35rem;">Format</label>
                <select id="export-format" class="rows-select" style="width:100%; height:36px; padding:0 0.5rem;">
                    <option value="csv">CSV (Comma Separated Values)</option>
                    <option value="xls">Excel (Spreadsheet Format)</option>
                    <option value="pdf">PDF (Printable document)</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="pg-btn" onclick="closeBulkExportModal()">Cancel</button>
            <button type="button" class="pg-btn current" onclick="submitBulkExport()">Export Now</button>
        </div>
    </div>
</div>
{{-- Lead Details Slide Drawer --}}
<div class="sz-drawer" id="lead-drawer" role="dialog" aria-modal="true" aria-label="Lead details drawer" hidden>
    <div class="sz-drawer-overlay" onclick="closeLeadDrawer()"></div>
    <div class="sz-drawer-box">
        <div class="sz-drawer-header">
            <div>
                <h3 class="sz-drawer-title">Lead Profile</h3>
                <p style="margin: 0; font-size: 0.775rem; color: var(--sz-text-muted);">Detailed history and timeline activity.</p>
            </div>
            <button type="button" class="sz-drawer-close" onclick="closeLeadDrawer()" aria-label="Close panel">
                @include('admin.partials.icon', ['name' => 'x', 'size' => 20])
            </button>
        </div>
        <div class="sz-drawer-body" id="drawer-content" style="overflow-y: auto; display: flex; flex-direction: column;">
            <div style="text-align:center;padding:2rem;">
                <div style="width:30px;height:30px;border:3px solid var(--sz-primary);border-top-color:transparent;border-radius:50%;animation:spin .6s linear infinite;margin:0 auto;"></div>
                <p style="margin-top:.75rem;color:var(--sz-muted);font-size:.85rem;">Loading lead...</p>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes spin { to { transform:rotate(360deg); } }

/* Allow row dropdown menu to escape container clipping */
.sz-action-menu {
    position: fixed !important;
    z-index: 9999 !important;
    top: auto !important;
    left: auto !important;
    right: auto !important;
    margin-top: 0 !important;
}

/* Promote stacking context of sticky table cell when actions dropdown is open to float over header */
td:has(.sz-row-actions.open) {
    z-index: 100 !important;
}
</style>

<script>
// Checkbox selection set
let selected = new Set();
const STORAGE_KEY = 'settleanz_selected_leads';
let selectAllMatching = false;

// Load initial selection from sessionStorage
try {
    const stored = sessionStorage.getItem(STORAGE_KEY);
    if (stored) {
        const parsed = JSON.parse(stored);
        if (Array.isArray(parsed)) {
            parsed.forEach(id => selected.add(id.toString()));
        }
    }
    selectAllMatching = sessionStorage.getItem(STORAGE_KEY + '_all_matching') === 'true';
} catch (e) {
    console.error('Error loading selection from storage:', e);
}

document.addEventListener('DOMContentLoaded', function() {
    // Save current visible page lead IDs for client-side details navigation
    const visibleLeadIds = Array.from(document.querySelectorAll('.lead-checkbox')).map(cb => cb.value);
    sessionStorage.setItem('settleanz_lead_ids', JSON.stringify(visibleLeadIds));

    // 1. Initialize Checkboxes from stored state
    document.querySelectorAll('.lead-checkbox').forEach(cb => {
        if (selected.has(cb.value.toString()) || selectAllMatching) {
            cb.checked = true;
            const row = document.getElementById('lead-row-' + cb.value);
            if (row) row.classList.add('selected');
        }
        
        cb.addEventListener('change', function() {
            const valStr = this.value.toString();
            const row = document.getElementById('lead-row-' + this.value);
            
            // Any individual deselect breaks "Select All Matching"
            if (!this.checked) {
                selectAllMatching = false;
                sessionStorage.setItem(STORAGE_KEY + '_all_matching', 'false');
            }

            if (this.checked) {
                selected.add(valStr);
                if (row) row.classList.add('selected');
            } else {
                selected.delete(valStr);
                if (row) row.classList.remove('selected');
            }
            saveSelection();
            updateBulkBar();
            updateSelectAllState();
        });
    });

    const selectAllCb = document.getElementById('select-all');
    if (selectAllCb) {
        selectAllCb.addEventListener('change', function() {
            const currentCbs = document.querySelectorAll('.lead-checkbox');
            currentCbs.forEach(cb => {
                cb.checked = this.checked;
                const valStr = cb.value.toString();
                const row = document.getElementById('lead-row-' + cb.value);
                if (this.checked) {
                    selected.add(valStr);
                    if (row) row.classList.add('selected');
                } else {
                    selected.delete(valStr);
                    if (row) row.classList.remove('selected');
                }
            });
            
            // Gmail behavior: when current page select-all is checked, show the banner option
            if (this.checked) {
                const banner = document.getElementById('selection-banner');
                if (banner && {{ $leads->total() }} > {{ $leads->count() }}) {
                    banner.classList.add('show');
                    document.getElementById('select-all-matching-btn').style.display = 'inline-block';
                    document.getElementById('clear-all-matching-btn').style.display = 'none';
                    document.getElementById('selection-banner-text').textContent = `All ${currentCbs.length} leads on this page are selected.`;
                }
            } else {
                selectAllMatching = false;
                sessionStorage.setItem(STORAGE_KEY + '_all_matching', 'false');
                const banner = document.getElementById('selection-banner');
                if (banner) banner.classList.remove('show');
            }

            saveSelection();
            updateBulkBar();
        });
    }

    // Restore banner if selectAllMatching was previously loaded
    if (selectAllMatching) {
        toggleSelectAllMatching(true, false);
    } else {
        updateBulkBar();
        updateSelectAllState();
    }

    // 2. Custom Dropdowns toggle logic
    document.querySelectorAll('[data-dropdown]').forEach(dd => {
        const trigger = dd.querySelector('.dropdown-trigger');
        const input = dd.querySelector('input[type="hidden"]');
        const menu = dd.querySelector('.dropdown-menu');
        const options = dd.querySelectorAll('.dropdown-option');

        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            const isCurrentlyOpen = dd.classList.contains('open');
            // Close all dropdowns
            document.querySelectorAll('[data-dropdown].open').forEach(opened => {
                opened.classList.remove('open');
                opened.querySelector('.dropdown-trigger').setAttribute('aria-expanded', 'false');
            });
            
            if (!isCurrentlyOpen) {
                dd.classList.add('open');
                trigger.setAttribute('aria-expanded', 'true');
                // Clear any searches inside the dropdown
                const search = dd.querySelector('.dropdown-search-input');
                if (search) {
                    search.value = '';
                    dd.querySelectorAll('.dropdown-option.hidden').forEach(opt => opt.classList.remove('hidden'));
                    search.focus();
                }
            }
        });

        options.forEach(opt => {
            opt.addEventListener('click', function() {
                const val = this.getAttribute('data-value');
                const text = this.textContent.trim();
                
                // Update selected styles
                options.forEach(o => {
                    o.classList.remove('selected');
                    o.setAttribute('aria-selected', 'false');
                });
                this.classList.add('selected');
                this.setAttribute('aria-selected', 'true');

                // Update text on trigger button
                const labelText = dd.id === 'status-filter-wrapper' ? 'Status: ' + text :
                                  dd.id === 'visa-filter-wrapper' ? 'Visa: ' + text :
                                  dd.id === 'staff-filter-wrapper' ? 'Staff: ' + text : text;
                trigger.querySelector('span').textContent = labelText;

                // Close and set input value
                dd.classList.remove('open');
                trigger.setAttribute('aria-expanded', 'false');
                
                if (input.value !== val) {
                    input.value = val;
                    // Trigger native change to submit filters form if applicable
                    if (input.getAttribute('onchange')) {
                        input.dispatchEvent(new Event('change'));
                    } else if (input.id.startsWith('bulk-')) {
                        // Keep value synced for bulk modals
                    }
                }
            });
        });
    });

    // Clickable table rows: clicking a row opens details drawer (except checkbox, trigger, or actions menu)
    document.querySelectorAll('#leads-table tbody tr').forEach(tr => {
        tr.addEventListener('click', function(e) {
            if (e.target.closest('.sz-checkbox') || e.target.closest('.sz-row-actions') || e.target.closest('a') || e.target.closest('button')) {
                return;
            }
            const leadId = this.id.replace('lead-row-', '');
            openLeadDrawer(e, leadId);
        });
    });

    // Close dropdowns on clicking outside
    document.addEventListener('click', function(e) {
        document.querySelectorAll('[data-dropdown].open').forEach(dd => {
            dd.classList.remove('open');
            dd.querySelector('.dropdown-trigger').setAttribute('aria-expanded', 'false');
        });
        if (!e.target.closest('[data-row-dropdown]')) {
            closeAllRowDropdowns();
        }
    });

    // Close active dropdowns on scroll or resize to prevent floating menus
    window.addEventListener('scroll', closeAllRowDropdowns, { passive: true });
    window.addEventListener('resize', closeAllRowDropdowns, { passive: true });
    document.querySelectorAll('.sz-table-wrap').forEach(el => {
        el.addEventListener('scroll', closeAllRowDropdowns, { passive: true });
    });

    // Close modals on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDrawer();
            closeAllRowDropdowns();
            document.querySelectorAll('.custom-modal.open').forEach(modal => {
                modal.classList.remove('open');
            });
        }
    });

    // Toast alert auto dismiss
    const toast = document.getElementById('status-toast');
    if (toast) {
        setTimeout(() => toast.classList.remove('show'), 3000);
        setTimeout(() => toast.remove(), 4000);
    }
});

// Helper: Save selection to sessionStorage
function saveSelection() {
    sessionStorage.setItem(STORAGE_KEY, JSON.stringify(Array.from(selected)));
}

// Helper: Update floating bar visibility & count
function updateBulkBar() {
    const bar = document.getElementById('bulk-bar');
    const countLabel = document.getElementById('bulk-count');
    const totalCount = selectAllMatching ? {{ $leads->total() }} : selected.size;
    
    if (totalCount > 0) {
        bar.classList.add('show');
        countLabel.textContent = totalCount;
    } else {
        bar.classList.remove('show');
    }
}

// Helper: Select all checkbox indeterminate state
function updateSelectAllState() {
    if (selectAllMatching) return;
    
    const selectAllCb = document.getElementById('select-all');
    if (!selectAllCb) return;
    const currentCbs = document.querySelectorAll('.lead-checkbox');
    if (currentCbs.length === 0) return;
    
    let checkedCount = 0;
    currentCbs.forEach(cb => {
        if (cb.checked) checkedCount++;
    });

    if (checkedCount === 0) {
        selectAllCb.checked = false;
        selectAllCb.indeterminate = false;
    } else if (checkedCount === currentCbs.length) {
        selectAllCb.checked = true;
        selectAllCb.indeterminate = false;
    } else {
        selectAllCb.checked = false;
        selectAllCb.indeterminate = true;
    }
}

// Gmail: toggle select all matching leads
function toggleSelectAllMatching(activate, syncCheckboxes = true) {
    selectAllMatching = activate;
    sessionStorage.setItem(STORAGE_KEY + '_all_matching', activate ? 'true' : 'false');
    
    const banner = document.getElementById('selection-banner');
    if (activate) {
        if (banner) {
            banner.classList.add('show');
            document.getElementById('select-all-matching-btn').style.display = 'none';
            document.getElementById('clear-all-matching-btn').style.display = 'inline-block';
            document.getElementById('selection-banner-text').textContent = `All {{ $leads->total() }} matching leads in SettleANZ are selected.`;
        }
        
        if (syncCheckboxes) {
            document.querySelectorAll('.lead-checkbox').forEach(cb => {
                cb.checked = true;
                const row = document.getElementById('lead-row-' + cb.value);
                if (row) row.classList.add('selected');
            });
            const sa = document.getElementById('select-all');
            if (sa) {
                sa.checked = true;
                sa.indeterminate = false;
            }
        }
    } else {
        if (banner) banner.classList.remove('show');
        clearSelection();
    }
    updateBulkBar();
}

// Search options inside dropdown menu (e.g. Visa)
function filterDropdownOptions(inputEl) {
    const term = inputEl.value.toLowerCase().trim();
    const options = inputEl.closest('.dropdown-menu').querySelectorAll('.dropdown-options .dropdown-option');
    options.forEach(opt => {
        const text = opt.textContent.toLowerCase();
        if (opt.getAttribute('data-value') === "" || text.includes(term)) {
            opt.classList.remove('hidden');
        } else {
            opt.classList.add('hidden');
        }
    });
}

// Change pagination rows limit
function changePerPage(perPage) {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', perPage);
    // Reset to page 1 on per_page shift
    url.searchParams.set('page', 1);
    window.location.href = url.toString();
}

// Clear all checkboxes
function clearSelection() {
    selected.clear();
    selectAllMatching = false;
    sessionStorage.removeItem(STORAGE_KEY);
    sessionStorage.removeItem(STORAGE_KEY + '_all_matching');
    
    document.querySelectorAll('.lead-checkbox').forEach(cb => {
        cb.checked = false;
        const row = document.getElementById('lead-row-' + cb.value);
        if (row) row.classList.remove('selected');
    });
    const sa = document.getElementById('select-all');
    if (sa) {
        sa.checked = false;
        sa.indeterminate = false;
    }
    const banner = document.getElementById('selection-banner');
    if (banner) banner.classList.remove('show');
    updateBulkBar();
}

// Bulk action modals toggle
function openBulkModal(action) {
    const totalCount = selectAllMatching ? {{ $leads->total() }} : selected.size;
    document.querySelectorAll('.bulk-selected-count-label').forEach(el => {
        el.textContent = totalCount;
    });

    const modal = document.getElementById('modal-bulk-' + action);
    if (modal) {
        modal.classList.add('open');
        modal.removeAttribute('hidden');
        // Hide progress bar on initial open
        const progress = modal.querySelector('.progress-container');
        if (progress) progress.style.display = 'none';
        modal.querySelectorAll('.modal-footer button').forEach(btn => btn.disabled = false);
    }
}

function closeBulkModal(action) {
    const modal = document.getElementById('modal-bulk-' + action);
    if (modal) {
        modal.classList.remove('open');
        modal.setAttribute('hidden', 'true');
    }
}

// Bulk export modal triggers
function openBulkExportModal() {
    const count = selectAllMatching ? {{ $leads->total() }} : selected.size;
    document.querySelectorAll('.export-selected-count').forEach(el => {
        el.textContent = count;
    });
    
    // Set scope options depending on whether items are checked
    const scopeSelect = document.getElementById('export-scope');
    if (count === 0) {
        scopeSelect.value = 'filtered';
        scopeSelect.querySelector('option[value="selected"]').disabled = true;
    } else {
        scopeSelect.value = 'selected';
        scopeSelect.querySelector('option[value="selected"]').disabled = false;
    }

    const modal = document.getElementById('modal-bulk-export');
    if (modal) {
        modal.classList.add('open');
        modal.removeAttribute('hidden');
    }
}

function closeBulkExportModal() {
    const modal = document.getElementById('modal-bulk-export');
    if (modal) {
        modal.classList.remove('open');
        modal.setAttribute('hidden', 'true');
    }
}

// Submit bulk actions form dynamically with visual progress updates
function submitBulkAction(action) {
    let value = null;
    if (action === 'assign') {
        value = document.getElementById('bulk-assign-value').value;
        if (!value) {
            alert('Please select a staff member to assign.');
            return;
        }
    } else if (action === 'status') {
        value = document.getElementById('bulk-status-value').value;
        if (!value) {
            alert('Please select a status to apply.');
            return;
        }
    }

    const modal = document.getElementById('modal-bulk-' + action);
    const progressContainer = document.getElementById('progress-container-' + action);
    const progressBarFill = document.getElementById('progress-bar-fill-' + action);
    const footerButtons = modal.querySelectorAll('.modal-footer button');
    
    // Disable form to prevent duplicate clicks and show premium progress loaders
    footerButtons.forEach(btn => btn.disabled = true);
    if (progressContainer && progressBarFill) {
        progressContainer.style.display = 'block';
        let progress = 0;
        progressBarFill.style.width = '0%';
        const interval = setInterval(() => {
            progress += 10;
            if (progress >= 95) {
                clearInterval(interval);
            } else {
                progressBarFill.style.width = progress + '%';
            }
        }, 80);
    }

    // Build form post
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = "{{ route('admin.leads.bulk-action') }}";

    // CSRF
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = "{{ csrf_token() }}";
    form.appendChild(csrf);

    // If selectAllMatching is active, pass filter parameters
    if (selectAllMatching) {
        const samInput = document.createElement('input');
        samInput.type = 'hidden';
        samInput.name = 'select_all_matching';
        samInput.value = 'true';
        form.appendChild(samInput);

        // Get filter inputs from filters form
        const filterForm = document.getElementById('filter-form');
        const formData = new FormData(filterForm);
        for (let [key, val] of formData.entries()) {
            if (val && key !== '_token') {
                const hiddenFilter = document.createElement('input');
                hiddenFilter.type = 'hidden';
                hiddenFilter.name = key;
                hiddenFilter.value = val;
                form.appendChild(hiddenFilter);
            }
        }
    } else {
        // Pass specific selected lead IDs
        Array.from(selected).forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'lead_ids[]';
            input.value = id;
            form.appendChild(input);
        });
    }

    // Action name
    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = action;
    form.appendChild(actionInput);

    // Assigned value (status key, or staff user ID)
    if (value !== null) {
        const valInput = document.createElement('input');
        valInput.type = 'hidden';
        valInput.name = 'value';
        valInput.value = value;
        form.appendChild(valInput);
    }

    document.body.appendChild(form);
    
    // Clear selection so the operation doesn't carry selected boxes to refreshed page
    sessionStorage.removeItem(STORAGE_KEY);
    sessionStorage.removeItem(STORAGE_KEY + '_all_matching');
    
    setTimeout(() => {
        form.submit();
    }, 450); // Pause briefly to let user register progress animation
}

// Submit bulk export
function submitBulkExport() {
    const scope = document.getElementById('export-scope').value;
    const format = document.getElementById('export-format').value;
    
    const url = new URL("{{ route('admin.leads.export') }}");
    url.searchParams.set('format', format);
    
    // Apply current filter states
    const filterForm = document.getElementById('filter-form');
    const formData = new FormData(filterForm);
    for (let [key, val] of formData.entries()) {
        if (val && key !== '_token') {
            url.searchParams.set(key, val);
        }
    }
    
    if (scope === 'selected' && !selectAllMatching) {
        url.searchParams.set('lead_ids', JSON.stringify(Array.from(selected)));
    } else if (scope === 'page') {
        // Grab IDs only from the visible checkboxes
        const pageIds = Array.from(document.querySelectorAll('.lead-checkbox')).map(cb => cb.value);
        url.searchParams.set('lead_ids', JSON.stringify(pageIds));
    }
    
    closeBulkExportModal();
    window.location.href = url.toString();
}

// Lead Details Drawer functions are now loaded globally from js/modal.js

function toggleRowDropdown(event, leadId) {
    event.stopPropagation();
    const trigger = event.currentTarget;
    const dropdown = document.getElementById('row-dropdown-' + leadId);
    const wrapper = dropdown.closest('.sz-row-actions');
    const isOpen = wrapper.classList.contains('open');
    
    closeAllRowDropdowns();
    
    if (!isOpen) {
        wrapper.classList.add('open');
        
        // Calculate coordinates dynamically
        const rect = trigger.getBoundingClientRect();
        const viewportHeight = window.innerHeight;
        const dropdownHeight = 150; // approximate height of the menu
        
        let top = rect.bottom + 6;
        // If it overflows the bottom of the viewport, display it above the trigger instead
        if (top + dropdownHeight > viewportHeight && rect.top - dropdownHeight > 10) {
            top = rect.top - dropdownHeight - 6;
        }
        
        let left = rect.right - 170; // align right edges
        if (left < 10) left = 10; // keep it at least 10px from the left edge of viewport
        
        dropdown.style.setProperty('top', `${top}px`, 'important');
        dropdown.style.setProperty('left', `${left}px`, 'important');
    }
}

function closeAllRowDropdowns() {
    document.querySelectorAll('.sz-row-actions.open').forEach(el => {
        el.classList.remove('open');
        const menu = el.querySelector('.sz-action-menu');
        if (menu) {
            menu.style.removeProperty('top');
            menu.style.removeProperty('left');
        }
    });
}
</script>
@endsection
