@props([
    'name',
    'class' => 'w-6 h-6',
    'size' => 24,
    'strokeWidth' => 2
])

@php
    $name = strtolower(trim($name));
@endphp

<svg xmlns="http://www.w3.org/2000/svg" 
     width="{{ $size }}" 
     height="{{ $size }}" 
     viewBox="0 0 24 24" 
     fill="none" 
     stroke="currentColor" 
     stroke-width="{{ $strokeWidth }}" 
     stroke-linecap="round" 
     stroke-linejoin="round" 
     class="lucide-icon lucide-{{ $name }} {{ $class }}" 
     aria-hidden="true">
    @switch($name)
        @case('layout-dashboard')
            <rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="10" rx="1"/><rect width="7" height="5" x="3" y="15" rx="1"/>
            @break

        @case('users')
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            @break

        @case('clipboard-list')
            <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/>
            @break

        @case('mail')
            <rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
            @break

        @case('calendar-check')
            <rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 2v4"/><path d="M16 2v4"/><path d="m9 16 2 2 4-4"/>
            @break

        @case('package')
            <path d="M12 2 2 7l10 5 10-5-10-5Z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/><path d="M12 12v10"/>
            @break

        @case('layers')
            <path d="m12 3-10 5L12 13l10-5-10-5Z"/><path d="m2 17 10 5 10-5"/><path d="m2 12 10 5 10-5"/>
            @break

        @case('file-text')
            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/>
            @break

        @case('monitor-smartphone')
            <rect width="10" height="14" x="3" y="5" rx="2"/><rect width="6" height="10" x="15" y="9" rx="1"/><path d="M10 19h.01"/><path d="M18 19h.01"/>
            @break

        @case('newspaper')
            <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M18 18h-8"/><path d="M16 6H10v4h6V6Z"/>
            @break

        @case('circle-help')
            <circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/>
            @break

        @case('message-square-quote')
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/><path d="M8 12a2 2 0 0 0 2-2V8H8v2h2"/><path d="M14 12a2 2 0 0 0 2-2V8h-2v2h2"/>
            @break

        @case('image')
            <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
            @break

        @case('download')
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/>
            @break

        @case('menu')
            <line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/>
            @break

        @case('sparkles')
            <path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/>
            @break

        @case('bot')
            <path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/>
            @break

        @case('pen-tool')
            <path d="m12 19 7-7 3 3-7 7-3-3Z"/><path d="m18 13-1.5-1.5"/><path d="M12 11 3.5 2.5a2.12 2.12 0 0 0-3 3L9 14l3-3Z"/>
            @break

        @case('brain')
            <path d="M12 5a3 3 0 1 0-5.997.125 4 4 0 0 0-2.526 5.77 4 4 0 0 0 .556 6.588A4 4 0 1 0 12 18Z"/><path d="M12 5a3 3 0 1 1 5.997.125 4 4 0 0 1 2.526 5.77 4 4 0 0 1-.556 6.588A4 4 0 1 1 12 18Z"/><path d="M12 5v14"/>
            @break

        @case('library')
            <path d="m16 6 4 14"/><path d="M12 6v14"/><path d="M8 8v12"/><path d="M4 4v16"/>
            @break

        @case('scroll-text')
            <path d="M15 2H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h9"/><path d="M15 2a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2"/><path d="M17 18a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-2h18v2Z"/><path d="M8 6h4"/><path d="M8 10h6"/>
            @break

        @case('book-open')
            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
            @break

        @case('folder-open')
            <path d="m6 14 1.45-2.9A2 2 0 0 1 9.24 10H20a2 2 0 0 1 1.94 2.5l-1.55 6a2 2 0 0 1-1.94 1.5H4a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2h3.93a2 2 0 0 1 1.66.9l.82 1.2a2 2 0 0 0 1.66.9H18a2 2 0 0 1 2 2v2"/>
            @break

        @case('book')
            <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6 6h10M6 10h10"/>
            @break

        @case('megaphone')
            <path d="m3 11 18-5v12L3 13v-2Z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/>
            @break

        @case('mail-plus')
            <path d="M22 13V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h9"/><path d="m22 7-8.97 5.7a1.9 1.9 0 0 1-2.06 0L2 7"/><path d="M19 16v6"/><path d="M16 19h6"/>
            @break

        @case('send')
            <path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/>
            @break

        @case('users-round')
            <path d="M18 21a6 6 0 0 0-12 0"/><circle cx="12" cy="10" r="4"/><path d="M22 21a5.07 5.07 0 0 0-2.78-4.11"/><path d="M2 21a5.07 5.07 0 0 1 2.78-4.11"/><circle cx="5" cy="11" r="3"/><circle cx="19" cy="11" r="3"/>
            @break

        @case('file-badge')
            <path d="M12 22h6a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v3"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><circle cx="8" cy="16" r="4"/><path d="m11 19 3 3-1-4"/>
            @break

        @case('workflow')
            <rect width="8" height="8" x="3" y="3" rx="2"/><rect width="8" height="8" x="13" y="13" rx="2"/><path d="M14 6h3a2 2 0 0 1 2 2v3"/><path d="M7 11v3a2 2 0 0 0 2 2h3"/><path d="m18 14 3-3-3-3"/><path d="m12 18-3-3 3-3"/>
            @break

        @case('bar-chart-3')
            <path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/>
            @break

        @case('chart-column')
            <path d="M3 3v18h18"/><rect x="7" y="10" width="4" height="7" rx="1"/><rect x="15" y="5" width="4" height="12" rx="1"/>
            @break

        @case('file-bar-chart')
            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M8 18v-4"/><path d="M12 18v-8"/><path d="M16 18v-2"/>
            @break

        @case('history')
            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><polyline points="3 3 3 8 8 8"/><line x1="12" x2="12" y1="7" y2="12"/><line x1="12" x2="16" y1="12" y2="14"/>
            @break

        @case('shield-check')
            <path d="M20 13c0 5-3.5 7.5-7.66 9.7a1 1 0 0 1-.68 0C7.5 20.5 4 18 4 13V6a1 1 0 0 1 .76-.97l8-2a1 1 0 0 1 .48 0l8 2A1 1 0 0 1 20 6Z"/><path d="m9 12 2 2 4-4"/>
            @break

        @case('file-down')
            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M12 18v-6"/><path d="m9 15 3 3 3-3"/>
            @break

        @case('file-up')
            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M12 12v6"/><path d="m15 15-3-3-3 3"/>
            @break

        @case('bell')
            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
            @break

        @case('settings')
            <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.1a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/>
            @break

        @case('sliders-horizontal')
            <line x1="4" x2="14" y1="4" y2="4"/><line x1="20" x2="20" y1="4" y2="4"/><line x1="4" x2="6" y1="12" y2="12"/><line x1="12" x2="20" y1="12" y2="12"/><line x1="4" x2="16" y1="20" y2="20"/><line x1="22" x2="22" y1="20" y2="20"/><circle cx="17" cy="4" r="3"/><circle cx="9" cy="12" r="3"/><circle cx="19" cy="20" r="3"/>
            @break

        @case('globe')
            <circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/>
            @break

        @case('shield')
            <path d="M20 13c0 5-3.5 7.5-7.66 9.7a1 1 0 0 1-.68 0C7.5 20.5 4 18 4 13V6a1 1 0 0 1 .76-.97l8-2a1 1 0 0 1 .48 0l8 2A1 1 0 0 1 20 6Z"/>
            @break

        @case('shield-user')
            <path d="M20 13c0 5-3.5 7.5-7.66 9.7a1 1 0 0 1-.68 0C7.5 20.5 4 18 4 13V6a1 1 0 0 1 .76-.97l8-2a1 1 0 0 1 .48 0l8 2A1 1 0 0 1 20 6Z"/><circle cx="12" cy="10" r="3"/><path d="M18 16a6 6 0 0 0-12 0"/>
            @break

        @case('key-round')
            <path d="m21 2-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0 1.5 1.5M15.5 7.5 14 6"/>
            @break

        @case('user-cog')
            <circle cx="18" cy="15" r="3"/><circle cx="9" cy="7" r="4"/><path d="M10 15H6a4 4 0 0 0-4 4v2"/><path d="m21.7 16.4-.9-.3"/><path d="m15.2 13.9-.9-.3"/><path d="m16.6 18.7.3-.9"/><path d="m19.4 12.2.3-.9"/><path d="m20.9 13.6.7-.7"/><path d="m15.1 16.4.7-.7"/><path d="m19.4 17.8.9.3"/><path d="m12.9 15.3.9.3"/><path d="m17.4 11.3-.3.9"/><path d="m20.2 17.8-.3.9"/><path d="m21.6 15.1-.7.7"/><path d="m15.8 17.9-.7.7"/>
            @break

        @case('user')
            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
            @break

        @case('circle-user')
            <circle cx="12" cy="12" r="10"/><circle cx="12" cy="10" r="3"/><path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662"/>
            @break

        @case('braces')
            <path d="M8 3H7a2 2 0 0 0-2 2v5a2 2 0 0 1-2 2 2 2 0 0 1 2 2v5a2 2 0 0 0 2 2h1"/><path d="M16 21h1a2 2 0 0 0 2-2v-5a2 2 0 0 1 2-2 2 2 0 0 1-2-2V5a2 2 0 0 0-2-2h-1"/>
            @break

        @case('plug')
            <path d="M12 22v-5M9 8V2M15 8V2M18 8H6v6a4 4 0 0 0 4 4h4a4 4 0 0 0 4-4Z"/>
            @break

        @case('database-backup')
            <ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 12a9 9 0 0 1 5-8.1"/><path d="M3 5v14a9 9 0 0 0 5.004 8.1"/><path d="M12 12v3.5a2.5 2.5 0 0 0 5 0V12"/><path d="M12 8a9 9 0 0 0-9 4v4a9 9 0 0 0 5 8.1"/><path d="M19.004 11.2a9 9 0 0 1 .996.8v8c0 1.66-4 3-9 3A9 9 0 0 1 12 23Z"/>
            @break

        @case('hard-drive')
            <rect width="20" height="8" x="2" y="14" rx="2"/><path d="M6 18h.01"/><path d="M10 18h.01"/><path d="M2 10V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5"/><circle cx="6" cy="6" r="1"/><circle cx="10" cy="6" r="1"/>
            @break

        @case('cpu')
            <rect width="16" height="16" x="4" y="4" rx="2"/><path d="M9 9h6v6H9Z"/><path d="M9 1v3"/><path d="M15 1v3"/><path d="M9 20v3"/><path d="M15 20v3"/><path d="M20 9h3"/><path d="M20 15h3"/><path d="M1 9h3"/><path d="M1 15h3"/>
            @break

        @case('clock-3')
            <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
            @break

        @case('wrench')
            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
            @break

        @case('file-search')
            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><circle cx="11.5" cy="13.5" r="2.5"/><path d="M16 18l-2.7-2.7"/>
            @break

        @case('external-link')
            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/>
            @break

        @case('log-out')
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/>
            @break

        @case('plus')
            <path d="M5 12h14M12 5v14"/>
            @break

        @case('pencil')
            <path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/>
            @break

        @case('trash')
            <path d="M3 6h18M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/>
            @break

        @case('eye')
            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>
            @break

        @case('save')
            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
            @break

        @case('upload')
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/>
            @break

        @case('refresh-cw')
            <path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><polyline points="3 3 3 8 8 8"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><polyline points="16 16 21 16 21 21"/>
            @break

        @case('x')
            <path d="M18 6 6 18M6 6l12 12"/>
            @break

        @case('message-circle')
            <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>
            @break

        @case('search')
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
            @break

        @case('chevron-down')
            <path d="m6 9 6 6 6-6"/>
            @break

        @case('chevron-right')
            <path d="m9 18 6-6-6-6"/>
            @break

        @case('chevron-left')
            <path d="m15 18-6-6 6-6"/>
            @break

        @case('chevron-up')
            <path d="m18 15-6-6-6 6"/>
            @break

        @case('copy')
            <rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/>
            @break

        @case('archive')
            <rect width="20" height="5" x="2" y="3" rx="1"/><path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"/><line x1="10" x2="14" y1="12" y2="12"/>
            @break

        @case('rotate-ccw')
            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><polyline points="3 3 3 8 8 8"/>
            @break

        @case('printer')
            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 9V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v5"/><rect width="12" height="8" x="6" y="14" rx="1"/>
            @break

        @case('user-plus')
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/>
            @break

        @case('check')
            <path d="M20 6 9 17l-5-5"/>
            @break

        @case('alert-triangle')
            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/>
            @break

        @case('alert-circle')
            <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>
            @break

        @case('info')
            <circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
            @break

        @default
            <!-- Default placeholder document icon -->
            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/>
    @endswitch
</svg>
