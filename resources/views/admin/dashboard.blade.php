@extends('admin.layouts.app')

@section('content')
<div class="admin-main__inner">
    <!-- Welcome Header -->
    <section class="db-header">
        <div class="db-header__title">
            <p class="eyebrow" style="margin: 0; color: #14a394; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.75rem;">Enterprise Operations Control</p>
            <h2>Welcome back, {{ auth()->user()->name }}</h2>
            <p>Here is your business performance snapshot for {{ now()->format('l, jS F Y') }}.</p>
        </div>
        <div class="db-header__actions">
            <a class="button button--small button--ghost" href="/" target="_blank" rel="noreferrer" style="display: inline-flex; align-items: center; gap: 0.35rem;">
                @include('admin.partials.icon', ['name' => 'external-link', 'size' => 14])
                <span>Open Site</span>
            </a>
            <a href="{{ route('admin.leads.export') }}" class="button button--small" style="background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; display: inline-flex; align-items: center; gap: 0.35rem;" title="Export Leads to CSV">
                @include('admin.partials.icon', ['name' => 'file-down', 'size' => 14])
                <span>Export Report</span>
            </a>
        </div>
    </section>

    <!-- Top KPI Cards Grid -->
    <section class="kpi-grid">
        <!-- KPI 1: Total Leads -->
        <div class="kpi-card">
            <div class="kpi-card__header">
                <span class="kpi-card__label">Total Leads</span>
                <span class="kpi-card__icon" style="background: rgba(20, 163, 148, 0.08); color: #14a394;">
                    @include('admin.partials.icon', ['name' => 'users', 'size' => 18])
                </span>
            </div>
            <div class="kpi-card__body">
                <div class="kpi-card__value">{{ number_format($leadServiceStats['total'] ?? 0) }}</div>
                <div class="kpi-card__trend {{ ($leadServiceStats['this_month'] ?? 0) >= ($leadServiceStats['last_month'] ?? 0) ? 'up' : 'down' }}">
                    @include('admin.partials.icon', ['name' => ($leadServiceStats['this_month'] ?? 0) >= ($leadServiceStats['last_month'] ?? 0) ? 'trending-up' : 'trending-down', 'size' => 14])
                    <span>{{ $leadServiceStats['this_month'] ?? 0 }} this month</span>
                </div>
            </div>
        </div>

        <!-- KPI 2: Today's Leads -->
        <div class="kpi-card">
            <div class="kpi-card__header">
                <span class="kpi-card__label">Today's Leads</span>
                <span class="kpi-card__icon" style="background: rgba(16, 185, 129, 0.08); color: #10b981;">
                    @include('admin.partials.icon', ['name' => 'user-plus', 'size' => 18])
                </span>
            </div>
            <div class="kpi-card__body">
                <div class="kpi-card__value">{{ $leadServiceStats['today'] ?? 0 }}</div>
                <div class="kpi-card__trend up">
                    @include('admin.partials.icon', ['name' => 'clock', 'size' => 14])
                    <span>New today</span>
                </div>
            </div>
        </div>

        <!-- KPI 3: Consultation Requests -->
        <div class="kpi-card">
            <div class="kpi-card__header">
                <span class="kpi-card__label">Consultations</span>
                <span class="kpi-card__icon" style="background: rgba(99, 102, 241, 0.08); color: #6366f1;">
                    @include('admin.partials.icon', ['name' => 'calendar', 'size' => 18])
                </span>
            </div>
            <div class="kpi-card__body">
                <div class="kpi-card__value">{{ $consultationBookingCount ?? 0 }}</div>
                <div class="kpi-card__trend neutral">
                    @include('admin.partials.icon', ['name' => 'info', 'size' => 14])
                    <span>Bookings logged</span>
                </div>
            </div>
        </div>

        <!-- KPI 4: Package Requests -->
        <div class="kpi-card">
            <div class="kpi-card__header">
                <span class="kpi-card__label">Package Bookings</span>
                <span class="kpi-card__icon" style="background: rgba(245, 158, 11, 0.08); color: #f59e0b;">
                    @include('admin.partials.icon', ['name' => 'package', 'size' => 18])
                </span>
            </div>
            <div class="kpi-card__body">
                <div class="kpi-card__value">{{ $packageBookingCount ?? 0 }}</div>
                <div class="kpi-card__trend up">
                    @include('admin.partials.icon', ['name' => 'package', 'size' => 14])
                    <span>Settlement packs</span>
                </div>
            </div>
        </div>

        <!-- KPI 5: Ebook Downloads -->
        <div class="kpi-card">
            <div class="kpi-card__header">
                <span class="kpi-card__label">Ebook Downloads</span>
                <span class="kpi-card__icon" style="background: rgba(139, 92, 246, 0.08); color: #8b5cf6;">
                    @include('admin.partials.icon', ['name' => 'download', 'size' => 18])
                </span>
            </div>
            <div class="kpi-card__body">
                <div class="kpi-card__value">{{ $ebookStats['overview']['total_downloads'] ?? 0 }}</div>
                <div class="kpi-card__trend up">
                    @include('admin.partials.icon', ['name' => 'book-open', 'size' => 14])
                    <span>Magnet conversions</span>
                </div>
            </div>
        </div>

        <!-- KPI 6: Contact Messages -->
        <div class="kpi-card">
            <div class="kpi-card__header">
                <span class="kpi-card__label">Contact Messages</span>
                <span class="kpi-card__icon" style="background: rgba(236, 72, 153, 0.08); color: #ec4899;">
                    @include('admin.partials.icon', ['name' => 'mail', 'size' => 18])
                </span>
            </div>
            <div class="kpi-card__body">
                <div class="kpi-card__value">{{ $contactLeadCount ?? 0 }}</div>
                <div class="kpi-card__trend neutral">
                    @include('admin.partials.icon', ['name' => 'inbox', 'size' => 14])
                    <span>Direct messages</span>
                </div>
            </div>
        </div>

        <!-- KPI 7: Newsletter Subscribers -->
        <div class="kpi-card">
            <div class="kpi-card__header">
                <span class="kpi-card__label">Newsletter Signups</span>
                <span class="kpi-card__icon" style="background: rgba(16, 185, 129, 0.08); color: #10b981;">
                    @include('admin.partials.icon', ['name' => 'megaphone', 'size' => 18])
                </span>
            </div>
            <div class="kpi-card__body">
                <div class="kpi-card__value">{{ $leadServiceStats['newsletter_subscribers'] ?? 0 }}</div>
                <div class="kpi-card__trend up">
                    @include('admin.partials.icon', ['name' => 'users', 'size' => 14])
                    <span>Subscribed</span>
                </div>
            </div>
        </div>

        <!-- KPI 8: Open Cases -->
        <div class="kpi-card">
            <div class="kpi-card__header">
                <span class="kpi-card__label">Open/New Cases</span>
                <span class="kpi-card__icon" style="background: rgba(239, 68, 68, 0.08); color: #ef4444;">
                    @include('admin.partials.icon', ['name' => 'clipboard-list', 'size' => 18])
                </span>
            </div>
            <div class="kpi-card__body">
                <div class="kpi-card__value">{{ $leadServiceStats['new_leads'] ?? 0 }}</div>
                <div class="kpi-card__trend down">
                    @include('admin.partials.icon', ['name' => 'alert-circle', 'size' => 14])
                    <span>Awaiting action</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Charts Row Grid -->
    <section class="charts-grid">
        <!-- Chart 1: Lead Trend -->
        <div class="chart-card">
            <div class="chart-card__header">
                <span class="chart-card__title">Monthly Lead Trend (Last 12 Months)</span>
            </div>
            <div id="chart-monthly-trend"></div>
        </div>

        <!-- Chart 2: Lead Sources -->
        <div class="chart-card">
            <div class="chart-card__header">
                <span class="chart-card__title">Lead Acquisition Sources</span>
            </div>
            <div id="chart-lead-sources"></div>
        </div>

        <!-- Chart 3: Interested Services -->
        <div class="chart-card">
            <div class="chart-card__header">
                <span class="chart-card__title">Demanded Services & Visas</span>
            </div>
            <div id="chart-interested-services"></div>
        </div>

        <!-- Chart 4: Lead Status Stacked Bar -->
        <div class="chart-card">
            <div class="chart-card__header">
                <span class="chart-card__title">Leads Conversion Funnel</span>
            </div>
            <div id="chart-lead-status"></div>
        </div>
    </section>

    <!-- Secondary Row: Country & Upcoming Consultations -->
    <section class="dashboard-secondary-grid">
        <!-- Country Distribution Chart -->
        <div class="chart-card">
            <div class="chart-card__header">
                <span class="chart-card__title">Geographical Distribution (Top Countries)</span>
            </div>
            <div id="chart-country-distribution"></div>
        </div>

        <!-- Upcoming Consultations Schedule Widget -->
        <section class="admin-panel-card" style="box-sizing: border-box; display: flex; flex-direction: column;">
            <div class="admin-section-head">
                <div>
                    <h3>Upcoming consultations schedule</h3>
                    <p>Planned advisor-client meetings and bookings.</p>
                </div>
                <a class="text-link" href="{{ route('admin.leads.index', ['form_type' => 'consultation-booking']) }}">All Consultations</a>
            </div>
            <div class="schedule-widget" style="margin-top: 1rem; flex-grow: 1; overflow-y: auto; max-height: 330px;">
                @forelse ($upcomingConsultations as $consult)
                    <div class="schedule-item">
                        <div class="schedule-item__time-box">
                            <span style="text-transform: uppercase;">{{ \Carbon\Carbon::parse($consult->preferred_date)->format('M') }}</span>
                            <span style="font-size: 1.1rem; line-height: 1;">{{ \Carbon\Carbon::parse($consult->preferred_date)->format('d') }}</span>
                        </div>
                        <div class="schedule-item__details">
                            <div class="schedule-item__title">{{ $consult->full_name }}</div>
                            <div class="schedule-item__meta">
                                <span style="display: flex; align-items: center; gap: 0.2rem;">
                                    @include('admin.partials.icon', ['name' => 'clock', 'size' => 12])
                                    {{ $consult->preferred_time ?: 'TBD' }}
                                </span>
                                <span>&bull;</span>
                                <span style="text-transform: capitalize;">{{ str_replace('_', ' ', $consult->visa_type ?: 'General') }}</span>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center;">
                            <a href="{{ route('admin.leads.show', $consult) }}" class="button button--small" style="padding: 0.25rem 0.5rem; text-decoration: none; display: flex; align-items: center; gap: 0.25rem;" onclick="openDrawer(event, {{ $consult->id }})">
                                @include('admin.partials.icon', ['name' => 'eye', 'size' => 12])
                                <span>Open</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-state__icon">
                            @include('admin.partials.icon', ['name' => 'calendar-x', 'size' => 40])
                        </div>
                        <div class="empty-state__title">No bookings scheduled</div>
                        <div class="empty-state__desc">There are no consultations scheduled for the coming period.</div>
                    </div>
                @endforelse
            </div>
        </section>
    </section>

    <!-- Third Grid Row: Activities, Recent Tables & System Health -->
    <section class="admin-two-column-grid">
        <!-- Unified Activities Timeline -->
        <section class="admin-panel-card">
            <div class="admin-section-head">
                <div>
                    <h3>Operations activity feed</h3>
                    <p>Recent events, system changes, and lead status logs.</p>
                </div>
            </div>
            <div class="timeline-widget" style="margin-top: 1.5rem; max-height: 400px; overflow-y: auto;">
                @forelse ($activities as $act)
                    <div class="timeline-item {{ $act['type'] }}">
                        <span class="timeline-item__time">{{ \Carbon\Carbon::parse($act['time'])->diffForHumans() }}</span>
                        <div class="timeline-item__title">{{ $act['label'] }}</div>
                        <div class="timeline-item__desc">{{ $act['description'] }} &bull; <small style="color: var(--admin-muted);">by {{ $act['user'] }}</small></div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-state__icon">
                            @include('admin.partials.icon', ['name' => 'activity', 'size' => 40])
                        </div>
                        <div class="empty-state__title">No activity logged yet</div>
                        <div class="empty-state__desc">System logs and lead movements will display here.</div>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- System Health Widget & Quick Actions -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- System Health Dashboard -->
            <section class="admin-panel-card">
                <div class="admin-section-head">
                    <div>
                        <h3>System nodes health</h3>
                        <p>Real-time checks on dependencies and host performance.</p>
                    </div>
                </div>
                <div class="health-grid" style="margin-top: 1rem;">
                    @foreach ($systemHealth as $node => $status)
                        <div class="health-item">
                            <span class="health-dot {{ $status }}"></span>
                            <span style="text-transform: capitalize;">{{ $node }}</span>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- Quick Shortcuts Dashboard -->
            <section class="admin-panel-card">
                <div class="admin-section-head">
                    <div>
                        <h3>Operational shortcuts</h3>
                        <p>Perform quick actions and access specific utilities.</p>
                    </div>
                </div>
                <div class="admin-action-grid" style="grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-top: 1rem;">
                    <a class="admin-action-card" href="{{ route('admin.leads.index') }}?status=new" style="padding: 1rem; border-radius: 12px; text-decoration: none;">
                        <strong style="display: flex; align-items: center; gap: 0.35rem; color: #1e293b;">
                            @include('admin.partials.icon', ['name' => 'plus-circle', 'size' => 14])
                            Manage Leads
                        </strong>
                        <span style="font-size: 0.75rem; color: #64748b;">Review new incoming CRM entries</span>
                    </a>
                    <a class="admin-action-card" href="{{ route('admin.ebooks.index') }}" style="padding: 1rem; border-radius: 12px; text-decoration: none;">
                        <strong style="display: flex; align-items: center; gap: 0.35rem; color: #1e293b;">
                            @include('admin.partials.icon', ['name' => 'book-open', 'size' => 14])
                            Manage Ebooks
                        </strong>
                        <span style="font-size: 0.75rem; color: #64748b;">Upload and view download magnets</span>
                    </a>
                    <a class="admin-action-card" href="{{ route('admin.ai-settings.api-connection') }}" style="padding: 1rem; border-radius: 12px; text-decoration: none;">
                        <strong style="display: flex; align-items: center; gap: 0.35rem; color: #1e293b;">
                            @include('admin.partials.icon', ['name' => 'bot', 'size' => 14])
                            AI Copilot Settings
                        </strong>
                        <span style="font-size: 0.75rem; color: #64748b;">Manage keys, system prompts, knowledge</span>
                    </a>
                    <a class="admin-action-card" href="{{ route('admin.settings.edit') }}" style="padding: 1rem; border-radius: 12px; text-decoration: none;">
                        <strong style="display: flex; align-items: center; gap: 0.35rem; color: #1e293b;">
                            @include('admin.partials.icon', ['name' => 'settings', 'size' => 14])
                            Global Settings
                        </strong>
                        <span style="font-size: 0.75rem; color: #64748b;">Configure brand, mails, and profiles</span>
                    </a>
                </div>
            </section>
        </div>
    </section>

    <!-- Latest Data Tables Rows -->
    <section class="admin-two-column-grid" style="margin-top: 1.5rem;">
        <!-- Latest 10 Leads -->
        <section class="admin-panel-card">
            <div class="admin-section-head">
                <div>
                    <h3>Latest 10 leads</h3>
                    <p>Newest entries logged across site portals.</p>
                </div>
                <a class="text-link" href="{{ route('admin.leads.index') }}">Manage leads</a>
            </div>
            <div class="admin-table-wrap" style="margin-top: 1rem; border: none; box-shadow: none; overflow-x: auto;">
                <table class="admin-table" style="font-size: 0.85rem; width: 100%;">
                    <thead>
                        <tr>
                            <th style="padding: 0.5rem 0.75rem;">Lead</th>
                            <th style="padding: 0.5rem 0.75rem;">Source</th>
                            <th style="padding: 0.5rem 0.75rem;">Status</th>
                            <th style="padding: 0.5rem 0.75rem; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentLeads as $lead)
                            <tr>
                                <td style="padding: 0.65rem 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                                    @php
                                        $initials = substr($lead->full_name ?: ($lead->first_name ?: 'U'), 0, 2);
                                        $colors = ['#14a394', '#3b82f6', '#f59e0b', '#8b5cf6', '#ec4899', '#10b981'];
                                        $bg = $colors[abs(crc32($lead->email)) % count($colors)];
                                    @endphp
                                    <span class="avatar-initials" style="background: {{ $bg }};">{{ $initials }}</span>
                                    <div>
                                        <strong style="color: #1e293b; display: block;">{{ $lead->full_name ?: $lead->first_name ?: 'Unknown' }}</strong>
                                        <small style="color: var(--admin-muted); display: block; font-size: 0.75rem;">{{ $lead->email }}</small>
                                    </div>
                                </td>
                                <td style="padding: 0.65rem 0.75rem;">
                                    <span class="admin-badge" style="font-size: 0.75rem;">{{ str_replace('_', ' ', $lead->form_type) }}</span>
                                </td>
                                <td style="padding: 0.65rem 0.75rem;">
                                    <small style="text-transform: uppercase; font-weight: 700; font-size: 0.7rem; color: {{ $lead->status === 'new' ? '#ef4444' : '#64748b' }}">{{ $lead->status }}</small>
                                </td>
                                <td style="padding: 0.65rem 0.75rem; text-align: right;">
                                    <a href="{{ route('admin.leads.show', $lead) }}" class="button button--small" style="padding: 0.2rem 0.45rem; font-size: 0.67rem; background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; text-decoration: none;" onclick="openDrawer(event, {{ $lead->id }})">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--admin-muted); padding: 2rem 0;">
                                    @include('admin.partials.icon', ['name' => 'folder-open', 'size' => 32, 'class' => 'empty-state__icon'])
                                    <div style="font-weight: 600; margin-top: 0.5rem;">No leads logged yet</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Recent Ebook Downloads & Contacts Tabular Data -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Recent Downloads -->
            <section class="admin-panel-card">
                <div class="admin-section-head">
                    <div>
                        <h3>Latest ebook downloads</h3>
                        <p>Track magnet leads who downloaded guidebooks.</p>
                    </div>
                </div>
                <div class="admin-table-wrap" style="margin-top: 1rem; border: none; box-shadow: none; overflow-x: auto;">
                    <table class="admin-table" style="font-size: 0.85rem; width: 100%;">
                        <thead>
                            <tr>
                                <th style="padding: 0.5rem 0.75rem;">Downloader</th>
                                <th style="padding: 0.5rem 0.75rem;">Document</th>
                                <th style="padding: 0.5rem 0.75rem; text-align: right;">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentDownloads as $dl)
                                <tr>
                                    <td style="padding: 0.65rem 0.75rem;">
                                        <strong style="color: #1e293b; display: block;">{{ $dl->full_name }}</strong>
                                        <small style="color: var(--admin-muted); display: block; font-size: 0.75rem;">{{ $dl->email }}</small>
                                    </td>
                                    <td style="padding: 0.65rem 0.75rem;">
                                        <span style="font-weight: 500;">{{ $dl->ebook_title ?: 'Migration Guide' }}</span>
                                    </td>
                                    <td style="padding: 0.65rem 0.75rem; text-align: right; color: var(--admin-muted); font-size: 0.75rem;">
                                        {{ $dl->created_at?->format('d M Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align: center; color: var(--admin-muted); padding: 2rem 0;">
                                        No recent downloads logged.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Latest Migration Package Requests -->
            <section class="admin-panel-card">
                <div class="admin-section-head">
                    <div>
                        <h3>Latest package requests</h3>
                        <p>Submissions for visa and settlement packages.</p>
                    </div>
                </div>
                <div class="admin-table-wrap" style="margin-top: 1rem; border: none; box-shadow: none; overflow-x: auto;">
                    <table class="admin-table" style="font-size: 0.85rem; width: 100%;">
                        <thead>
                            <tr>
                                <th style="padding: 0.5rem 0.75rem;">Lead</th>
                                <th style="padding: 0.5rem 0.75rem;">Service Requested</th>
                                <th style="padding: 0.5rem 0.75rem; text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentPackageBookings as $pkg)
                                <tr>
                                    <td style="padding: 0.65rem 0.75rem;">
                                        <strong style="color: #1e293b; display: block;">{{ $pkg->full_name }}</strong>
                                        <small style="color: var(--admin-muted); display: block; font-size: 0.75rem;">{{ $pkg->email }}</small>
                                    </td>
                                    <td style="padding: 0.65rem 0.75rem;">
                                        <span style="font-weight: 500;">{{ data_get($pkg->metadata, 'subject') ?: 'Settlement Package' }}</span>
                                    </td>
                                    <td style="padding: 0.65rem 0.75rem; text-align: right;">
                                        <a href="{{ route('admin.leads.show', $pkg) }}" class="button button--small" style="padding: 0.2rem 0.45rem; font-size: 0.67rem; background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; text-decoration: none;" onclick="openDrawer(event, {{ $pkg->id }})">Open</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align: center; color: var(--admin-muted); padding: 2rem 0;">
                                        No recent package requests logged.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </section>
</div>

<!-- Load ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // 1. Monthly Lead Trend Line Chart
    var monthlyTrendData = @json($monthlyTrend);
    var monthlyOptions = {
        chart: {
            type: 'line',
            height: 300,
            fontFamily: 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
            toolbar: { show: false },
            zoom: { enabled: false }
        },
        series: [{
            name: 'Total Leads',
            data: monthlyTrendData.map(item => item.total)
        }, {
            name: 'Converted',
            data: monthlyTrendData.map(item => item.won)
        }],
        xaxis: {
            categories: monthlyTrendData.map(item => item.month),
            labels: { style: { colors: '#64748b', fontSize: '11px' } }
        },
        yaxis: {
            labels: { style: { colors: '#64748b', fontSize: '11px' } }
        },
        colors: ['#14a394', '#6366f1'],
        stroke: { curve: 'smooth', width: 3 },
        markers: { size: 4 },
        grid: { borderColor: '#f1f5f9' },
        tooltip: { theme: 'light' }
    };
    new ApexCharts(document.querySelector("#chart-monthly-trend"), monthlyOptions).render();

    // 2. Lead Sources Donut Chart
    var sourcesData = @json($leadsBySource);
    var sourcesOptions = {
        chart: { 
            type: 'donut', 
            height: 300,
            fontFamily: 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif'
        },
        series: sourcesData.map(item => item.count),
        labels: sourcesData.map(item => {
            return (item.form_type || 'Unknown').replace('_', ' ').replace('-', ' ').toUpperCase();
        }),
        colors: ['#14a394', '#3b82f6', '#f59e0b', '#8b5cf6', '#ec4899', '#10b981', '#64748b'],
        legend: { position: 'bottom', labels: { colors: '#64748b' } },
        dataLabels: { enabled: false },
        tooltip: { theme: 'light' }
    };
    new ApexCharts(document.querySelector("#chart-lead-sources"), sourcesOptions).render();

    // 3. Interested Services Horizontal Bar Chart
    var servicesData = @json($leadsByVisaType);
    var servicesOptions = {
        chart: { 
            type: 'bar', 
            height: 300, 
            fontFamily: 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
            toolbar: { show: false } 
        },
        plotOptions: { bar: { horizontal: true, barHeight: '55%', borderRadius: 4 } },
        series: [{
            name: 'Leads',
            data: servicesData.map(item => item.count)
        }],
        xaxis: {
            categories: servicesData.map(item => {
                var mapping = {
                    'student_visa': 'Student Visa',
                    'partner_visa': 'Partner Visa',
                    'work_visa': 'Work Visa',
                    'visitor_visa': 'Visitor Visa',
                    'migration_package': 'Migration Package'
                };
                return mapping[item.visa_type] || (item.visa_type || 'Other').replace('_', ' ').toUpperCase();
            }),
            labels: { style: { colors: '#64748b', fontSize: '11px' } }
        },
        yaxis: {
            labels: { style: { colors: '#64748b', fontSize: '11px' } }
        },
        colors: ['#14a394'],
        grid: { borderColor: '#f1f5f9' },
        tooltip: { theme: 'light' }
    };
    new ApexCharts(document.querySelector("#chart-interested-services"), servicesOptions).render();

    // 4. Lead Status Chart
    var statusData = @json($leadsByStatus);
    var statusOptions = {
        chart: { 
            type: 'bar', 
            height: 300, 
            fontFamily: 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
            toolbar: { show: false } 
        },
        plotOptions: { bar: { borderRadius: 4, columnWidth: '50%' } },
        series: [{
            name: 'Leads',
            data: statusData.map(item => item.count)
        }],
        xaxis: {
            categories: statusData.map(item => (item.status || 'unknown').toUpperCase()),
            labels: { style: { colors: '#64748b', fontSize: '11px' } }
        },
        yaxis: {
            labels: { style: { colors: '#64748b', fontSize: '11px' } }
        },
        colors: ['#6366f1'],
        grid: { borderColor: '#f1f5f9' },
        tooltip: { theme: 'light' }
    };
    new ApexCharts(document.querySelector("#chart-lead-status"), statusOptions).render();

    // 5. Country Distribution Bar Chart
    var countryData = @json($countryDistribution);
    var countryOptions = {
        chart: { 
            type: 'bar', 
            height: 300, 
            fontFamily: 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
            toolbar: { show: false } 
        },
        plotOptions: { bar: { borderRadius: 4, columnWidth: '50%' } },
        series: [{
            name: 'Leads',
            data: countryData.map(item => item.count)
        }],
        xaxis: {
            categories: countryData.map(item => (item.country || 'Other').toUpperCase()),
            labels: { style: { colors: '#64748b', fontSize: '11px' } }
        },
        yaxis: {
            labels: { style: { colors: '#64748b', fontSize: '11px' } }
        },
        colors: ['#14a394'],
        grid: { borderColor: '#f1f5f9' },
        tooltip: { theme: 'light' }
    };
    new ApexCharts(document.querySelector("#chart-country-distribution"), countryOptions).render();
});
</script>

<style>
    /* Executive Operations Dashboard Stylesheet */
    .db-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .db-header__title h2 {
        margin: 0;
        font-size: 1.75rem;
        font-weight: 700;
        color: #1e293b;
    }
    .db-header__title p {
        margin: 0.25rem 0 0;
        color: #64748b;
        font-size: 0.9rem;
    }
    .db-header__actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    /* KPI Grid */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }
    .kpi-card {
        background: #ffffff;
        border: 1px solid rgba(16, 88, 98, 0.08);
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02), 0 4px 12px rgba(0, 0, 0, 0.01);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 110px;
    }
    .kpi-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.03), 0 6px 12px rgba(16, 88, 98, 0.02);
        border-color: rgba(20, 163, 148, 0.16);
    }
    .kpi-card__header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.25rem;
    }
    .kpi-card__label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
    }
    .kpi-card__icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .kpi-card__body {
        display: flex;
        flex-direction: column;
        margin-top: auto;
    }
    .kpi-card__value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.15;
    }
    .kpi-card__trend {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 0.15rem;
    }
    .kpi-card__trend.up { color: #10b981; }
    .kpi-card__trend.down { color: #ef4444; }
    .kpi-card__trend.neutral { color: #64748b; }

    /* Charts Grid */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    @media (max-width: 1024px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }
    .chart-card {
        background: #ffffff;
        border: 1px solid rgba(16, 88, 98, 0.08);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }
    .chart-card__header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }
    .chart-card__title {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
    }

    /* Secondary Grid */
    .dashboard-secondary-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    @media (max-width: 968px) {
        .dashboard-secondary-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Schedule Widget */
    .schedule-widget {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .schedule-item {
        display: flex;
        gap: 1rem;
        padding: 0.85rem;
        border-radius: 12px;
        border: 1px solid #f1f5f9;
        background: #fafafb;
        transition: all 0.2s;
    }
    .schedule-item:hover {
        border-color: rgba(20, 163, 148, 0.2);
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }
    .schedule-item__time-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 0.35rem 0.65rem;
        background: rgba(20, 163, 148, 0.08);
        color: #0b7a75;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        min-width: 55px;
    }
    .schedule-item__details {
        display: flex;
        flex-direction: column;
        justify-content: center;
        flex-grow: 1;
    }
    .schedule-item__title {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.85rem;
        margin-bottom: 0.15rem;
    }
    .schedule-item__meta {
        font-size: 0.75rem;
        color: #64748b;
        display: flex;
        gap: 0.5rem;
    }

    /* Activities timeline */
    .timeline-widget {
        display: flex;
        flex-direction: column;
        position: relative;
        padding-left: 1.25rem;
        margin-left: 0.5rem;
        border-left: 2px solid #f1f5f9;
        gap: 1.25rem;
    }
    .timeline-item {
        position: relative;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: calc(-1.25rem - 6px);
        top: 4px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #cbd5e1;
        border: 2px solid #ffffff;
        box-shadow: 0 0 0 2px #f1f5f9;
        transition: all 0.2s;
    }
    
    /* Dynamic timeline dot colors */
    .timeline-item.created::before, .timeline-item.new_lead::before { background-color: #10b981; }
    .timeline-item.status_changed::before { background-color: #3b82f6; }
    .timeline-item.assigned::before { background-color: #8b5cf6; }
    .timeline-item.login::before { background-color: #f59e0b; }
    .timeline-item.settings_changed::before { background-color: #ef4444; }

    .timeline-item__time {
        font-size: 0.7rem;
        color: #94a3b8;
        display: block;
        margin-bottom: 0.15rem;
    }
    .timeline-item__title {
        font-weight: 600;
        font-size: 0.85rem;
        color: #1e293b;
    }
    .timeline-item__desc {
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 0.1rem;
    }

    /* System Health grid */
    .health-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem;
    }
    .health-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        font-size: 0.8rem;
        font-weight: 600;
        color: #334155;
    }
    .health-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }
    .health-dot.green { background-color: #10b981; box-shadow: 0 0 8px rgba(16, 185, 129, 0.4); }
    .health-dot.yellow { background-color: #f59e0b; box-shadow: 0 0 8px rgba(245, 158, 11, 0.4); }
    .health-dot.red { background-color: #ef4444; box-shadow: 0 0 8px rgba(239, 68, 68, 0.4); }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 2.5rem 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .empty-state__icon {
        color: #94a3b8;
        margin-bottom: 1rem;
        display: flex;
        justify-content: center;
    }
    .empty-state__title {
        font-weight: 700;
        font-size: 0.95rem;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }
    .empty-state__desc {
        font-size: 0.8rem;
        color: #64748b;
        max-width: 250px;
        margin: 0 auto;
    }

    /* Avatars */
    .avatar-initials {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        color: #ffffff;
        text-transform: uppercase;
        flex-shrink: 0;
    }
</style>
@endsection
