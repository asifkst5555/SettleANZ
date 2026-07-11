@extends('admin.layouts.app')

@section('content')
<style>
.crm-calendar-legend { display:flex;gap:.75rem;flex-wrap:wrap;margin-bottom:1rem;padding:.75rem 1rem;background:#fff;border:1px solid #e2e8f0;border-radius:10px; }
.crm-calendar-legend-item { display:flex;align-items:center;gap:.35rem;font-size:.75rem;font-weight:600; }
.crm-calendar-legend-dot { width:10px;height:10px;border-radius:3px;display:inline-block; }
#calendar { background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:1rem;min-height:500px; }
.fc { font-family:inherit !important; }
.fc .fc-toolbar-title { font-size:1.15rem !important;font-weight:700 !important;color:#0f172a !important; }
.fc .fc-button { background:#f1f5f9 !important;border:1px solid #e2e8f0 !important;color:#475569 !important;font-weight:600 !important;font-size:.8rem !important;padding:.35rem .75rem !important;border-radius:8px !important; }
.fc .fc-button-primary:not(:disabled).fc-button-active, .fc .fc-button-primary:not(:disabled):active { background:#6366f1 !important;border-color:#6366f1 !important;color:#fff !important; }
.fc .fc-daygrid-day-number { font-size:.82rem;font-weight:600;color:#475569;padding:4px 6px !important; }
.fc .fc-daygrid-day.fc-day-today { background:#eef2ff !important; }
.fc .fc-daygrid-day.fc-day-today .fc-daygrid-day-number { background:#6366f1;color:#fff;border-radius:50%;width:26px;height:26px;display:flex;align-items:center;justify-content:center; }
.fc .fc-event { border:none !important;border-radius:6px !important;padding:2px 6px !important;font-size:.72rem !important;font-weight:600 !important;cursor:pointer; }
.fc .fc-event:hover { opacity:.85; }
.fc .fc-col-header-cell-cushion { font-size:.75rem;font-weight:700;text-transform:uppercase;color:#64748b;text-decoration:none; }
.fc .fc-scrollgrid { border-color:#e2e8f0 !important;border-radius:8px;overflow:hidden; }
.fc .fc-scrollgrid-section > td { border-color:#e2e8f0 !important; }
</style>

<div class="admin-main__inner">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.25rem;">
        <div>
            <p class="eyebrow">Lead Center</p>
            <h2>Lead Calendar</h2>
            <p>Visual timeline of lead acquisition and activity.</p>
        </div>
        <div style="display:flex;gap:.5rem;">
            <a href="{{ route('admin.leads.index', ['view' => 'table']) }}" class="button button--small button--ghost">Table View</a>
            <a href="{{ route('admin.leads.reports') }}" class="button button--small">Reports</a>
        </div>
    </div>

    <div class="crm-calendar-legend">
        @foreach(\App\Models\Lead::statusColors() as $status => $color)
            <div class="crm-calendar-legend-item">
                <span class="crm-calendar-legend-dot" style="background:{{ $color }};"></span>
                {{ str_replace('_', ' ', ucfirst($status)) }}
            </div>
        @endforeach
    </div>

    <div id="calendar"></div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek,dayGridDay'
        },
        height: 'auto',
        events: {
            url: '{{ route('admin.leads.calendar-events') }}',
            method: 'GET',
            extraParams: function() {
                return {
                    _token: '{{ csrf_token() }}'
                };
            },
            failure: function() {
                console.error('Failed to load calendar events');
            }
        },
        eventClick: function(info) {
            var leadId = info.event.id;
            if (leadId) {
                window.location.href = '/admin/leads/' + leadId;
            }
        },
        loading: function(isLoading) {
            if (isLoading) {
                calendarEl.style.opacity = '0.5';
            } else {
                calendarEl.style.opacity = '1';
            }
        }
    });
    calendar.render();
});
</script>
@endsection
