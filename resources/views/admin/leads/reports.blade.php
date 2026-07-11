@extends('admin.layouts.app')

@section('content')
<style>
.rp-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:1.1rem; }
.rp-card { background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:1.15rem 1.25rem;box-shadow:0 1px 3px rgba(0,0,0,.06); }
.rp-card h3 { font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#64748b;margin:0 0 .75rem; }
.rp-row { display:flex;align-items:center;gap:.5rem;padding:.3rem 0;border-bottom:1px solid #f1f5f9;font-size:.82rem; }
.rp-row:last-child { border-bottom:none; }
.rp-bar { height:6px;border-radius:3px;background:#e2e8f0;flex:1;overflow:hidden; }
.rp-bar span { display:block;height:100%;border-radius:3px;background:#14a394; }
.rp-label { font-weight:600;color:#0f172a;min-width:100px; }
.rp-count { color:#64748b;min-width:30px;text-align:right;font-weight:600; }
.rp-metric { text-align:center;padding:1rem; }
.rp-metric-value { font-size:2rem;font-weight:800;color:#0f172a; }
.rp-metric-label { font-size:.72rem;color:#64748b;font-weight:600;text-transform:uppercase;margin-top:.15rem; }
.trend-chart { display:flex;gap:3px;align-items:flex-end;height:120px; }
.trend-col { flex:1;display:flex;flex-direction:column;align-items:center;gap:2px; }
.trend-bar { width:100%;max-width:16px;border-radius:3px 3px 0 0;transition:height .3s;position:relative; }
.trend-bar:hover { opacity:.8; }
.trend-label { font-size:.55rem;color:#94a3b8;writing-mode:vertical-lr;text-orientation:mixed;transform:rotate(180deg); }
.rp-header { display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem;flex-wrap:wrap;gap:.5rem; }
</style>

<div class="admin-main__inner">
    <div class="admin-topbar">
        <div>
            <p class="eyebrow">SettleANZ CRM</p>
            <h2>Lead Reports</h2>
            <p>Analytics across every lead source, page, and visa type.</p>
        </div>
        <div class="admin-topbar__actions">
            <a href="{{ route('admin.leads.index') }}" class="button button--small button--ghost">Back to Leads</a>
            @can('lead_center.export')
            <a href="{{ route('admin.leads.export') }}" class="button button--small">Export CSV</a>
            <a href="{{ route('admin.leads.export', ['format' => 'pdf']) }}" class="button button--small">Export PDF</a>
            @endcan
        </div>
    </div>

    {{-- Top Metrics --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:.75rem;margin-bottom:1.25rem;">
        <div class="rp-metric rp-card"><div class="rp-metric-value">{{ $stats['total'] }}</div><div class="rp-metric-label">Total Leads</div></div>
        <div class="rp-metric rp-card"><div class="rp-metric-value">{{ $stats['today'] }}</div><div class="rp-metric-label">Today</div></div>
        <div class="rp-metric rp-card"><div class="rp-metric-value">{{ $stats['this_month'] }}</div><div class="rp-metric-label">This Month</div></div>
        <div class="rp-metric rp-card"><div class="rp-metric-value">{{ $stats['conversion_rate'] }}%</div><div class="rp-metric-label">Conversion Rate</div></div>
        <div class="rp-metric rp-card"><div class="rp-metric-value">{{ $stats['new_leads'] }}</div><div class="rp-metric-label">New (Unread)</div></div>
        <div class="rp-metric rp-card"><div class="rp-metric-value">{{ $stats['qualified'] }}</div><div class="rp-metric-label">Qualified</div></div>
    </div>

    {{-- Monthly Trend --}}
    <div class="rp-card" style="margin-bottom:1.1rem;">
        <div class="rp-header">
            <h3>Monthly Lead Trend</h3>
            <div style="display:flex;gap:1rem;font-size:.72rem;">
                <span><span style="display:inline-block;width:10px;height:10px;border-radius:2px;background:#14a394;margin-right:4px;"></span> Total</span>
                <span><span style="display:inline-block;width:10px;height:10px;border-radius:2px;background:#10b981;margin-right:4px;"></span> Won</span>
            </div>
        </div>
        @php $maxM = max($monthlyTrend->max('total'), 1); @endphp
        <div class="trend-chart">
            @foreach($monthlyTrend as $m)
                <div class="trend-col">
                    <div class="trend-bar" style="height:{{ max(($m['total']/$maxM)*100, 2) }}px;background:#14a394;" title="{{ $m['month'] }}: {{ $m['total'] }} leads"></div>
                    <div class="trend-bar" style="height:{{ max(($m['won']/$maxM)*100, 2) }}px;background:#10b981;" title="Won: {{ $m['won'] }}"></div>
                    <span class="trend-label">{{ $m['month'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="rp-grid">
        {{-- By Lead Source --}}
        <div class="rp-card">
            <h3>Leads by Source</h3>
            @php $maxS = max($leadsBySource->max('count'), 1); @endphp
            @foreach($leadsBySource as $item)
                @php
                    $labels = \App\Models\Lead::sourceLabels();
                    $label = $labels[$item->form_type] ?? ucfirst(str_replace('_',' ',$item->form_type));
                    $colors = ['contact-page' => '#2563eb', 'package_booking' => '#d97706', 'homepage_roadmap' => '#dc2626', 'ebook_download' => '#7c3aed', 'ai_chat' => '#ca8a04', 'general' => '#64748b'];
                @endphp
                <div class="rp-row">
                    <span style="width:10px;height:10px;border-radius:2px;background:{{ $colors[$item->form_type] ?? '#14a394' }};flex-shrink:0;"></span>
                    <span class="rp-label" style="min-width:120px;">{{ $label }}</span>
                    <div class="rp-bar"><span style="width:{{ ($item->count/$maxS)*100 }}%;background:{{ $colors[$item->form_type] ?? '#14a394' }};"></span></div>
                    <span class="rp-count">{{ $item->count }}</span>
                </div>
            @endforeach
        </div>

        {{-- By Website Page --}}
        <div class="rp-card">
            <h3>Leads by Website Page</h3>
            @php $maxP = max($leadsByPage->max('count'), 1); @endphp
            @foreach($leadsByPage as $item)
                @php
                    $pages = \App\Models\Lead::pageLabels();
                    $label = $pages[$item->source_page] ?? $item->source_page;
                @endphp
                <div class="rp-row">
                    <span style="width:10px;height:10px;border-radius:2px;background:#14a394;flex-shrink:0;"></span>
                    <span class="rp-label" style="min-width:140px;">{{ $label }}</span>
                    <div class="rp-bar"><span style="width:{{ ($item->count/$maxP)*100 }}%;"></span></div>
                    <span class="rp-count">{{ $item->count }}</span>
                </div>
            @endforeach
        </div>

        {{-- By Visa Type --}}
        @if($leadsByVisaType->isNotEmpty())
        <div class="rp-card">
            <h3>Leads by Visa Type</h3>
            @php $maxV = max($leadsByVisaType->max('count'), 1); @endphp
            @foreach($leadsByVisaType as $item)
                @php $vlabel = \App\Models\Lead::visaTypes()[$item->visa_type] ?? $item->visa_type; @endphp
                <div class="rp-row">
                    <span style="width:10px;height:10px;border-radius:2px;background:#8b5cf6;flex-shrink:0;"></span>
                    <span class="rp-label" style="min-width:140px;">{{ $vlabel }}</span>
                    <div class="rp-bar"><span style="width:{{ ($item->count/$maxV)*100 }}%;background:#8b5cf6;"></span></div>
                    <span class="rp-count">{{ $item->count }}</span>
                </div>
            @endforeach
        </div>
        @endif

        {{-- Top Staff --}}
        @if($topStaff->isNotEmpty())
        <div class="rp-card">
            <h3>Top Performing Staff</h3>
            @php $maxT = max($topStaff->max('lead_count'), 1); @endphp
            @foreach($topStaff as $staff)
                <div class="rp-row">
                    <span style="width:10px;height:10px;border-radius:2px;background:#f59e0b;flex-shrink:0;"></span>
                    <span class="rp-label" style="min-width:140px;">{{ $staff->name }}</span>
                    <div class="rp-bar"><span style="width:{{ ($staff->lead_count/$maxT)*100 }}%;background:#f59e0b;"></span></div>
                    <span class="rp-count">{{ $staff->lead_count }}</span>
                </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Period Selector --}}
    <div style="display:flex;gap:.5rem;justify-content:flex-end;margin-top:1rem;padding-top:1rem;border-top:1px solid #e2e8f0;">
        <span style="font-size:.78rem;color:#64748b;margin-right:.5rem;">Chart period:</span>
        @foreach([7, 14, 30, 60, 90] as $p)
            <a href="{{ route('admin.leads.reports', ['period' => $p]) }}" class="button button--small {{ $period == $p ? '' : 'button--ghost' }}" style="{{ $period == $p ? 'background:#14a394;color:#fff;border:none;' : '' }}">{{ $p }} days</a>
        @endforeach
    </div>
</div>
@endsection
