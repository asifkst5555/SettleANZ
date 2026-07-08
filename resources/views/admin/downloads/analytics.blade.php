@extends('admin.layouts.app')

@section('page-title', 'Download Analytics')

@section('content')
<style>
    .analytics-stat-card { background:white; border:1px solid #e5e7eb; border-radius:0.75rem; padding:1.5rem; }
    .analytics-stat-value { font-size:2rem; font-weight:700; }
    .analytics-stat-label { font-size:0.875rem; color:#6b7280; margin-top:0.25rem; }
    .analytics-stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1rem; margin-bottom:2rem; }
    .analytics-card { background:white; border:1px solid #e5e7eb; border-radius:0.75rem; padding:1.5rem; }
    .analytics-card h3 { font-size:1rem; font-weight:600; margin-bottom:1rem; }
</style>

<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">Ebook System</p>
            <h2>Download Analytics</h2>
            <p>Overview of ebook download performance and lead conversion</p>
        </div>
    </section>

    <div class="analytics-stats-grid">
        <div class="analytics-stat-card">
            <div class="analytics-stat-value" style="color:#2563eb;">{{ $stats['overview']['total_downloads'] }}</div>
            <div class="analytics-stat-label">Total Downloads</div>
        </div>
        <div class="analytics-stat-card">
            <div class="analytics-stat-value" style="color:#059669;">{{ $stats['overview']['downloads_today'] }}</div>
            <div class="analytics-stat-label">Today</div>
        </div>
        <div class="analytics-stat-card">
            <div class="analytics-stat-value" style="color:#6366f1;">{{ $stats['overview']['total_leads'] }}</div>
            <div class="analytics-stat-label">Total Leads</div>
        </div>
        <div class="analytics-stat-card">
            <div class="analytics-stat-value" style="color:#9333ea;">{{ $stats['overview']['conversion_rate'] }}%</div>
            <div class="analytics-stat-label">Conversion Rate</div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem;">
        <section class="analytics-card">
            <h3>Top Ebooks</h3>
            @if(!empty($stats['top_ebooks']))
            <div style="display:flex;flex-direction:column;gap:0.75rem;">
                @foreach($stats['top_ebooks'] as $ebook)
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <div>
                        <div style="font-weight:500;">{{ $ebook['title'] }}</div>
                        <div style="font-size:0.8125rem;color:#6b7280;">{{ $ebook['file_type'] }} &middot; {{ $ebook['lead_count'] }} leads</div>
                    </div>
                    <div style="font-size:1.25rem;font-weight:700;color:#2563eb;">{{ $ebook['download_count'] }}</div>
                </div>
                @endforeach
            </div>
            @else
            <p style="color:#6b7280;">No data yet.</p>
            @endif
        </section>

        <section class="analytics-card">
            <h3>Device Breakdown</h3>
            <div style="display:flex;flex-direction:column;gap:1rem;">
                @foreach(['desktop', 'mobile', 'tablet'] as $device)
                @php $count = $stats['device_breakdown'][$device] ?? 0; $total = max(1, array_sum($stats['device_breakdown'])); @endphp
                <div>
                    <div style="display:flex;justify-content:space-between;font-size:0.875rem;margin-bottom:0.25rem;">
                        <span style="text-transform:capitalize;">{{ $device }}</span>
                        <span style="font-weight:500;">{{ $count }}</span>
                    </div>
                    <div style="width:100%;background:#e5e7eb;border-radius:999px;height:8px;">
                        <div style="background:#2563eb;height:8px;border-radius:999px;width:{{ ($count / $total) * 100 }}%;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
        <section class="analytics-card">
            <h3>Email Stats</h3>
            <div style="display:flex;flex-direction:column;gap:0.75rem;">
                <div style="display:flex;justify-content:space-between;"><span style="color:#6b7280;">Total Sent</span><span style="font-weight:500;">{{ $stats['email_stats']['total'] }}</span></div>
                <div style="display:flex;justify-content:space-between;"><span style="color:#6b7280;">Delivered</span><span style="font-weight:500;color:#059669;">{{ $stats['email_stats']['sent'] }}</span></div>
                <div style="display:flex;justify-content:space-between;"><span style="color:#6b7280;">Failed</span><span style="font-weight:500;color:#dc2626;">{{ $stats['email_stats']['failed'] }}</span></div>
                <div style="display:flex;justify-content:space-between;"><span style="color:#6b7280;">Open Rate</span><span style="font-weight:500;">{{ $stats['email_stats']['open_rate'] }}%</span></div>
                <div style="display:flex;justify-content:space-between;"><span style="color:#6b7280;">Click Rate</span><span style="font-weight:500;">{{ $stats['email_stats']['click_rate'] }}%</span></div>
            </div>
        </section>

        <section class="analytics-card">
            <h3>Geography</h3>
            @if(!empty($stats['geography']))
            <div style="display:flex;flex-direction:column;gap:0.5rem;">
                @foreach($stats['geography'] as $loc)
                <div style="display:flex;justify-content:space-between;font-size:0.875rem;">
                    <span>{{ $loc['country'] }}</span>
                    <span style="font-weight:500;">{{ $loc['count'] }}</span>
                </div>
                @endforeach
            </div>
            @else
            <p style="color:#6b7280;">No geographic data yet.</p>
            @endif
        </section>
    </div>
</div>
@endsection
