<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        try {
            $site_css_ver = file_exists(public_path('site.css')) ? filemtime(public_path('site.css')) : '1.0';
        } catch (\Throwable $e) {
            $site_css_ver = '1.0';
        }
        try {
            $admin_css_ver = file_exists(public_path('admin.css')) ? filemtime(public_path('admin.css')) : '1.0';
        } catch (\Throwable $e) {
            $admin_css_ver = '1.0';
        }
        try {
            $admin_notif_css_ver = file_exists(public_path('admin-notifications.css')) ? filemtime(public_path('admin-notifications.css')) : '1.0';
        } catch (\Throwable $e) {
            $admin_notif_css_ver = '1.0';
        }
        try {
            $modal_js_ver = file_exists(public_path('js/modal.js')) ? filemtime(public_path('js/modal.js')) : '1.0';
        } catch (\Throwable $e) {
            $modal_js_ver = '1.0';
        }
        try {
            $notif_js_ver = file_exists(public_path('js/notifications.js')) ? filemtime(public_path('js/notifications.js')) : '1.0';
        } catch (\Throwable $e) {
            $notif_js_ver = '1.0';
        }
        try {
            $form_handler_js_ver = file_exists(public_path('js/admin-form-handler.js')) ? filemtime(public_path('js/admin-form-handler.js')) : '1.0';
        } catch (\Throwable $e) {
            $form_handler_js_ver = '1.0';
        }
        $favicon_ver = file_exists(public_path('favicon.ico')) ? filemtime(public_path('favicon.ico')) : '1.0';
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $metaTitle ?? 'Admin | SettleANZ' }}</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico?v={{ $favicon_ver }}">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico?v={{ $favicon_ver }}">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png?v={{ $favicon_ver }}">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v={{ $favicon_ver }}">
    <link rel="icon" type="image/png" sizes="48x48" href="/favicon-48x48.png?v={{ $favicon_ver }}">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png?v={{ $favicon_ver }}">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v={{ $favicon_ver }}">
    <link rel="manifest" href="/site.webmanifest?v={{ $favicon_ver }}">
    <meta name="theme-color" content="#0f8b8d">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('site.css') }}?v={{ $site_css_ver }}">
    <link rel="stylesheet" href="{{ asset('admin.css') }}?v={{ $admin_css_ver }}">
    <link rel="stylesheet" href="{{ asset('admin-notifications.css') }}?v={{ $admin_notif_css_ver }}">
    <style>
        .admin-impersonation-banner { display:flex; align-items:center; justify-content:center; gap:1rem; padding:0.6rem 1rem; background:#e8773a; color:white; font-size:0.88rem; font-weight:500; position:relative; z-index:1000 }
        .admin-alert { padding:0.75rem 1rem; border-radius:10px; margin-bottom:1rem; font-size:0.88rem }
        .admin-alert--success { background:#d4edda; color:#155724; border:1px solid #c3e6cb }
        .admin-alert--error { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb }
        .admin-form-group { margin-bottom:1rem }
        .admin-label { display:block; font-weight:600; font-size:0.85rem; margin-bottom:0.35rem; color:#2c3a47 }
        .admin-input { width:100%; padding:0.65rem 0.85rem; border:1px solid rgba(16,88,98,0.16); border-radius:8px; font-size:0.9rem; background:white; color:#2c3a47; box-sizing:border-box; transition:border-color 0.2s }
        .admin-input:focus { border-color:#14a394; outline:none; box-shadow:0 0 0 3px rgba(20,163,148,0.1) }
        .admin-badge--super { background:linear-gradient(135deg,#f18a42,#d86424); color:white }
        .admin-badge--default { background:#14a394; color:white }
        .admin-badge--success { background:#d4edda; color:#155724 }
        .admin-badge--error { background:#f8d7da; color:#721c24 }
        .admin-badge--muted { background:#e2e8f0; color:#64748b }
        .text-link--danger { color:#dc3545 !important }
        .button--danger { background:#dc3545; border-color:#dc3545; color:white }
        .button--danger:hover { background:#c82333; border-color:#bd2130 }
        .admin-pagination .pagination { display:flex; gap:0.35rem; list-style:none; padding:0; margin:0 }
        .admin-pagination .page-item.active .page-link { background:#14a394; color:white; border-color:#14a394 }
        .admin-pagination .page-link { padding:0.4rem 0.65rem; border:1px solid rgba(16,88,98,0.12); border-radius:6px; color:#2c3a47; text-decoration:none; font-size:0.82rem }
    </style>
</head>
<body class="admin-shell-body" data-route="{{ request()->route()?->getName() ?? '' }}">
    @auth
        @php
            $sidebarMenu = app(\App\Services\MenuBuilderService::class)->getSidebarMenu(auth()->user());
        @endphp
    @endauth

    @if (session()->has('impersonated_by'))
        <div class="admin-impersonation-banner">
            <span>You are impersonating <strong>{{ auth()->user()?->name }}</strong></span>
            <form method="POST" action="{{ route('admin.system.impersonate.leave') }}" style="display:inline">
                @csrf
                <button type="submit" style="background:rgba(255,255,255,0.2);border:1px solid rgba(255,255,255,0.3);color:white;padding:0.35rem 0.75rem;border-radius:6px;cursor:pointer;font-size:0.82rem">Leave Impersonation</button>
            </form>
        </div>
    @endif

    <!-- Toast Notification Container -->
    <div id="notificationContainer" class="notification-container"></div>
    
    <!-- Mobile Sidebar Toggle (floating - hidden when topbar visible) -->
    <button type="button" class="admin-sidebar__toggle" aria-label="Toggle menu">
        @include('admin.partials.icon', ['name' => 'menu', 'size' => 24])
    </button>
    
    <!-- Mobile Overlay -->
    <div class="admin-sidebar__overlay" id="mobileOverlay"></div>

    <div class="admin-shell">
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="admin-sidebar__brand">
                <p class="admin-sidebar__eyebrow">SettleANZ</p>
                <h1>Admin Panel</h1>
            </div>

            <nav class="admin-sidebar__nav" aria-label="Admin navigation">
                @foreach ($sidebarMenu as $item)
                    @if (isset($item['children']))
                        <div @class(['admin-sidebar__dropdown', 'is-open' => $item['children'] && collect($item['children'])->contains(fn($c) => request()->routeIs($c['route'] ?? ''))])>
                            <button type="button" class="admin-sidebar__dropdown-toggle" onclick="this.parentElement.classList.toggle('is-open')" aria-label="{{ $item['label'] }}" aria-expanded="true">
                                <span class="admin-sidebar__dropdown-label-wrapper" style="display: flex; align-items: center; gap: 0.75rem;">
                                    @include('admin.partials.icon', ['name' => $item['icon'] ?? 'file', 'class' => 'admin-sidebar__icon'])
                                    <span class="admin-sidebar__label">{{ $item['label'] }}</span>
                                </span>
                                @include('admin.partials.icon', ['name' => 'chevron-down', 'class' => 'admin-sidebar__chevron', 'size' => 16])
                            </button>
                            <div class="admin-sidebar__dropdown-menu">
                                @foreach ($item['children'] as $child)
                                    @php
                                        $isChildActive = request()->routeIs($child['route'] ?? '');
                                        if ($isChildActive) {
                                            if (isset($child['params']) && count($child['params']) > 0) {
                                                foreach ($child['params'] as $k => $v) {
                                                    if (request()->query($k) != $v) { $isChildActive = false; break; }
                                                }
                                            } else {
                                                $activeSiblingParams = collect($item['children'])->filter(fn($sib) =>
                                                    isset($sib['params']) && collect($sib['params'])->every(fn($v, $k) => request()->query($k) == $v)
                                                );
                                                if ($activeSiblingParams->isNotEmpty()) { $isChildActive = false; }
                                            }
                                        }
                                    @endphp
                                    <a @class(['is-active' => $isChildActive])
                                       href="{{ isset($child['params']) ? route($child['route'], $child['params']) : route($child['route'] ?? '/') }}"
                                       aria-label="{{ $child['label'] }}"
                                       title="{{ $child['label'] }}">
                                        @include('admin.partials.icon', ['name' => $child['icon'] ?? 'file', 'class' => 'admin-sidebar__child-icon', 'size' => 18])
                                        <span class="admin-sidebar__child-label">{{ $child['label'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a @class(['is-active' => request()->routeIs($item['route'] ?? '')])
                           href="{{ route($item['route'] ?? '/') }}"
                           aria-label="{{ $item['label'] }}"
                           title="{{ $item['label'] }}">
                            @include('admin.partials.icon', ['name' => $item['icon'] ?? 'file', 'class' => 'admin-sidebar__icon'])
                            <span class="admin-sidebar__label">{{ $item['label'] }}</span>
                        </a>
                    @endif
                @endforeach

                <a href="/" target="_blank" rel="noreferrer" aria-label="View Website" title="View Website">
                    @include('admin.partials.icon', ['name' => 'external-link', 'class' => 'admin-sidebar__icon'])
                    <span class="admin-sidebar__label">View Website</span>
                </a>
            </nav>
            <div class="admin-sidebar__footer" style="margin-top: auto; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: center; width: 100%;">
                <button type="button" class="admin-sidebar__collapse-btn" id="sidebarCollapseBtn" aria-label="Collapse sidebar" title="Collapse sidebar" style="background: none; border: none; color: rgba(255,255,255,0.6); cursor: pointer; padding: 0.5rem; display: flex; align-items: center; justify-content: center; transition: color 0.2s;">
                    @include('admin.partials.icon', ['name' => 'chevron-left', 'class' => 'admin-sidebar__collapse-icon'])
                </button>
            </div>
        </aside>

        <div class="admin-main">
            <!-- SaaS-style Top Bar -->
            <header class="admin-topbar-saas">
                <div class="admin-topbar-saas__left">
                    <button class="admin-topbar-saas__menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
                        @include('admin.partials.icon', ['name' => 'menu', 'size' => 24])
                    </button>
                    <div class="admin-topbar-saas__breadcrumb">
                        <span class="admin-topbar-saas__page-title">@yield('page-title', 'Dashboard')</span>
                    </div>
                </div>

                <div class="admin-topbar-saas__right">
                    <!-- Notifications -->
                    <div class="admin-notifications" id="notificationsDropdown">
                        <button class="admin-notifications__toggle" id="notificationsToggle" aria-label="Notifications">
                            @include('admin.partials.icon', ['name' => 'bell', 'size' => 20])
                            <span class="admin-notifications__badge" id="notificationBadge" style="display: none;">0</span>
                        </button>
                        <div class="admin-notifications__dropdown" id="notificationsPanel">
                            <div class="admin-notifications__header">
                                <h4>Notifications</h4>
                                <button class="admin-notifications__mark-all" id="markAllRead">Mark all read</button>
                            </div>
                            <div class="admin-notifications__list" id="notificationsList">
                                <div class="admin-notifications__empty">No new notifications</div>
                            </div>
                            <div class="admin-notifications__footer">
                                <a href="{{ route('admin.leads.index') }}">View all leads</a>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="admin-profile" id="profileDropdown">
                        <button class="admin-profile__toggle" id="profileToggle">
                            <div class="admin-profile__avatar">
                                {{ substr(auth()->user()?->name ?? 'A', 0, 1) }}
                            </div>
                            <div class="admin-profile__info">
                                <span class="admin-profile__name">{{ auth()->user()?->name ?? 'Admin' }}</span>
                                <span class="admin-profile__email">{{ auth()->user()?->email ?? '' }}</span>
                            </div>
                            @include('admin.partials.icon', ['name' => 'chevron-down', 'class' => 'admin-profile__chevron', 'size' => 16])
                        </button>
                        <div class="admin-profile__dropdown" id="profilePanel">
                            <div class="admin-profile__header">
                                <div class="admin-profile__avatar admin-profile__avatar--large">
                                    {{ substr(auth()->user()?->name ?? 'A', 0, 1) }}
                                </div>
                                <div>
                                    <div class="admin-profile__name">{{ auth()->user()?->name ?? 'Admin' }}</div>
                                    <div class="admin-profile__email">{{ auth()->user()?->email ?? '' }}</div>
                                </div>
                            </div>
                            <div class="admin-profile__menu">
                                <a href="/" target="_blank" class="admin-profile__menu-item">
                                    @include('admin.partials.icon', ['name' => 'external-link', 'size' => 16])
                                    View Website
                                </a>
                                <form method="POST" action="{{ route('admin.logout') }}" class="admin-profile__logout-form">
                                    @csrf
                                    <button type="submit" class="admin-profile__menu-item admin-profile__logout">
                                        @include('admin.partials.icon', ['name' => 'log-out', 'size' => 16])
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            @yield('content')
        </div>
    </div>

    <script src="{{ asset('js/modal.js') }}?v={{ $modal_js_ver }}"></script>
    
    <!-- Notification System Script -->
    <script src="{{ asset('js/notifications.js') }}?v={{ $notif_js_ver }}"></script>
    <script src="{{ asset('js/admin-form-handler.js') }}?v={{ $form_handler_js_ver }}"></script>

    <!-- Top Bar Dropdowns & Notifications Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sidebar Collapse Logic
        const sidebar = document.getElementById('adminSidebar');
        const mainContent = document.querySelector('.admin-main');
        const collapseBtn = document.getElementById('sidebarCollapseBtn');
        
        // Check localStorage on page load
        if (localStorage.getItem('admin_sidebar_collapsed') === 'true') {
            sidebar?.classList.add('collapsed');
            mainContent?.classList.add('sidebar-collapsed');
        }
        
        if (collapseBtn && sidebar) {
            collapseBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                const isCollapsed = sidebar.classList.contains('collapsed');
                localStorage.setItem('admin_sidebar_collapsed', isCollapsed);
                
                if (mainContent) {
                    if (isCollapsed) {
                        mainContent.classList.add('sidebar-collapsed');
                    } else {
                        mainContent.classList.remove('sidebar-collapsed');
                    }
                }
            });
        }

        // Profile Dropdown
        const profileDropdown = document.getElementById('profileDropdown');
        const profileToggle = document.getElementById('profileToggle');
        const profilePanel = document.getElementById('profilePanel');

        if (profileToggle && profileDropdown) {
            profileToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                profileDropdown.classList.toggle('is-open');
                // Close notifications if open
                document.getElementById('notificationsDropdown')?.classList.remove('is-open');
            });
        }

        // Notifications Dropdown
        const notificationsDropdown = document.getElementById('notificationsDropdown');
        const notificationsToggle = document.getElementById('notificationsToggle');
        const notificationsPanel = document.getElementById('notificationsPanel');
        const notificationBadge = document.getElementById('notificationBadge');
        const notificationsList = document.getElementById('notificationsList');
        const markAllRead = document.getElementById('markAllRead');

        if (notificationsToggle && notificationsDropdown) {
            notificationsToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                notificationsDropdown.classList.toggle('is-open');
                // Close profile if open
                document.getElementById('profileDropdown')?.classList.remove('is-open');
                
                // Fetch notifications when opening
                if (notificationsDropdown.classList.contains('is-open')) {
                    fetchNotifications();
                }
            });
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.admin-profile')) {
                document.getElementById('profileDropdown')?.classList.remove('is-open');
            }
            if (!e.target.closest('.admin-notifications')) {
                document.getElementById('notificationsDropdown')?.classList.remove('is-open');
            }
        });

        // Mobile menu toggle - bind to ALL toggle buttons (handles duplicate IDs)
        const mobileMenuToggles = document.querySelectorAll('#mobileMenuToggle, .admin-sidebar__toggle, .admin-topbar-saas__menu-toggle');
        const adminSidebar = document.getElementById('adminSidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        
        if (adminSidebar && mobileMenuToggles.length) {
            mobileMenuToggles.forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    adminSidebar.classList.toggle('is-open');
                    if (mobileOverlay) {
                        mobileOverlay.classList.toggle('is-open');
                    }
                });
            });
        }
        
        // Close sidebar when clicking overlay
        if (mobileOverlay && adminSidebar) {
            mobileOverlay.addEventListener('click', function() {
                adminSidebar.classList.remove('is-open');
                mobileOverlay.classList.remove('is-open');
            });
        }

        // Fetch and display notifications
        async function fetchNotifications() {
            try {
                const response = await fetch('{{ route("admin.notifications.index") }}');
                const data = await response.json();
                
                updateNotificationBadge(data.unread_count);
                renderNotifications(data.notifications);
            } catch (error) {
                console.error('Error fetching notifications:', error);
            }
        }

        // Update notification badge
        function updateNotificationBadge(count) {
            if (notificationBadge) {
                if (count > 0) {
                    notificationBadge.textContent = count > 99 ? '99+' : count;
                    notificationBadge.style.display = 'flex';
                } else {
                    notificationBadge.style.display = 'none';
                }
            }
        }

        // Render notifications list
        function renderNotifications(notifications) {
            if (!notificationsList) return;
            
            if (!notifications || notifications.length === 0) {
                notificationsList.innerHTML = '<div class="admin-notifications__empty">No new notifications</div>';
                return;
            }

            const icons = {
                lead: '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
                review: '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/><path d="M8 12a2 2 0 0 0 2-2V8H8v2h2"/><path d="M14 12a2 2 0 0 0 2-2V8h-2v2h2"/></svg>',
                system: '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.1a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>'
            };

            notificationsList.innerHTML = notifications.map(notification => {
                const isUnread = !notification.is_read;
                const timeAgo = getTimeAgo(new Date(notification.created_at));
                
                return `
                    <a href="${notification.link || '#'}" 
                       class="admin-notifications__item ${isUnread ? 'admin-notifications__item--unread' : ''}"
                       data-notification-id="${notification.id}">
                        <div class="admin-notifications__icon admin-notifications__icon--${notification.type}">
                            ${icons[notification.type] || icons.system}
                        </div>
                        <div class="admin-notifications__content">
                            <p class="admin-notifications__title">${notification.title}</p>
                            ${notification.message ? `<p class="admin-notifications__message">${notification.message}</p>` : ''}
                            <span class="admin-notifications__time">${timeAgo}</span>
                        </div>
                    </a>
                `;
            }).join('');
            
            // Add click handlers to mark as read
            notificationsList.querySelectorAll('.admin-notifications__item').forEach(item => {
                item.addEventListener('click', async function(e) {
                    const notificationId = this.dataset.notificationId;
                    const targetLink = this.getAttribute('href');
                    if (notificationId) {
                        e.preventDefault();
                        try {
                            await fetch(`{{ url('admin/notifications') }}/${notificationId}/read`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                                    'Accept': 'application/json'
                                }
                            });
                        } catch (error) {
                            console.error('Error marking notification as read:', error);
                        }
                        if (targetLink && targetLink !== '#') {
                            window.location.href = targetLink;
                        } else {
                            fetchNotifications();
                        }
                    }
                });
            });
        }

        // Mark all as read
        if (markAllRead) {
            markAllRead.addEventListener('click', async function(e) {
                e.preventDefault();
                e.stopPropagation();
                try {
                    await fetch('{{ route("admin.notifications.read-all") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                            'Accept': 'application/json'
                        }
                    });
                    fetchNotifications();
                } catch (error) {
                    console.error('Error marking all as read:', error);
                }
            });
        }

        // Helper: Time ago
        function getTimeAgo(date) {
            const seconds = Math.floor((new Date() - date) / 1000);
            let interval = seconds / 31536000;
            if (interval > 1) return Math.floor(interval) + ' years ago';
            interval = seconds / 2592000;
            if (interval > 1) return Math.floor(interval) + ' months ago';
            interval = seconds / 86400;
            if (interval > 1) return Math.floor(interval) + ' days ago';
            interval = seconds / 3600;
            if (interval > 1) return Math.floor(interval) + ' hours ago';
            interval = seconds / 60;
            if (interval > 1) return Math.floor(interval) + ' minutes ago';
            return 'Just now';
        }

        // Initial fetch
        fetchNotifications();
        
        // Poll for new notifications every 30 seconds
        setInterval(fetchNotifications, 30000);
    });
    </script>

    <!-- Pro Select Custom Dropdown -->
    <style>
    .pro-select-wrapper { position: relative; width: 100%; }
    .pro-select-native { position: absolute; opacity: 0; pointer-events: none; width: 100%; height: 100%; top: 0; left: 0; }
    .pro-select-display {
        width: 100%; padding: 0.7rem 2.5rem 0.7rem 1rem;
        border: 1px solid rgba(16, 88, 98, 0.16); border-radius: 6px;
        background: #fff; color: #2c3a47; font-size: 0.9rem;
        cursor: pointer; transition: border-color 0.2s, box-shadow 0.2s;
        position: relative; box-sizing: border-box; min-height: 38px; display: flex; align-items: center;
    }
    .pro-select-display::after {
        content: ''; position: absolute; right: 0.7rem; top: 50%; transform: translateY(-50%);
        width: 16px; height: 16px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23065e5b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: center; background-size: contain; transition: transform 0.2s;
    }
    .pro-select-wrapper.is-open .pro-select-display { border-color: #0b7a75; box-shadow: 0 0 0 3px rgba(11, 122, 117, 0.1); }
    .pro-select-wrapper.is-open .pro-select-display::after { transform: translateY(-50%) rotate(180deg); }
    .pro-select-dropdown {
        position: absolute; top: calc(100% + 6px); left: 0; right: 0;
        background: #fff; border: 1px solid rgba(16, 88, 98, 0.12);
        border-radius: 12px; box-shadow: 0 8px 24px rgba(6, 54, 52, 0.12);
        z-index: 100; opacity: 0; visibility: hidden; transform: translateY(-8px);
        transition: all 0.2s ease; max-height: 280px; overflow-y: auto;
    }
    .pro-select-wrapper.is-open .pro-select-dropdown { opacity: 1; visibility: visible; transform: translateY(0); }
    .pro-select-option { padding: 0.85rem 1.1rem; color: #2c3a47; cursor: pointer; transition: all 0.15s; }
    .pro-select-option:first-child { border-radius: 11px 11px 0 0; }
    .pro-select-option:last-child { border-radius: 0 0 11px 11px; }
    .pro-select-option:hover, .pro-select-option.is-selected { background: #14a394; color: #fff; font-weight: 500; }
    .pro-select-option.is-selected { font-weight: 600; background: #0b7a75; }

    /* AI Settings Sidebar Dropdown */
    .admin-sidebar__dropdown { position: relative; }
    .admin-sidebar__dropdown-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 0.85rem 1.15rem;
        background: none;
        border: none;
        color: rgba(255, 255, 255, 0.88);
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        border-radius: 14px;
        transition: all 0.2s;
    }
    .admin-sidebar__dropdown-toggle:hover { background: rgba(255, 255, 255, 0.12); color: #fff; }
    .admin-sidebar__dropdown-toggle svg { transition: transform 0.2s; }
    .admin-sidebar__dropdown.is-open .admin-sidebar__dropdown-toggle svg { transform: rotate(180deg); }
    .admin-sidebar__dropdown-menu {
        display: none;
        flex-direction: column;
        gap: 0.25rem;
        padding: 0.25rem 0 0.25rem 1.25rem;
    }
    .admin-sidebar__dropdown.is-open .admin-sidebar__dropdown-menu { display: flex; }
    .admin-sidebar__dropdown-menu a {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        padding: 0.5rem 0.75rem;
        color: rgba(255, 255, 255, 0.75);
        text-decoration: none;
        font-size: 0.85rem;
        border-radius: 8px;
        transition: all 0.2s;
    }
    .admin-sidebar__dropdown-menu a:hover { background: rgba(255, 255, 255, 0.12); color: #fff; }
    .admin-sidebar__dropdown-menu a.is-active { background: rgba(255, 255, 255, 0.18); color: #fff; font-weight: 600; }
    </style>
    @include('admin.partials.ai-copilot')

    <script>
    (function() {
        function initProDropdowns() {
            document.querySelectorAll('select.pro-select').forEach(function(select) {
                if (select.dataset.proSelectInitialized) return;
                select.dataset.proSelectInitialized = 'true';

                var wrapper = document.createElement('div');
                wrapper.className = 'pro-select-wrapper';
                select.parentNode.insertBefore(wrapper, select);
                wrapper.appendChild(select);
                select.classList.add('pro-select-native');

                var display = document.createElement('div');
                display.className = 'pro-select-display';
                display.style.color = '#2c3a47';
                var selectedOption = select.options[select.selectedIndex];
                display.textContent = (selectedOption && selectedOption.value) ? selectedOption.text : 'Select option';
                if (selectedOption && !selectedOption.value) {
                    display.style.color = '#999';
                }
                wrapper.appendChild(display);

                var dropdown = document.createElement('div');
                dropdown.className = 'pro-select-dropdown';
                wrapper.appendChild(dropdown);

                Array.from(select.options).forEach(function(option) {
                    var opt = document.createElement('div');
                    opt.className = 'pro-select-option';
                    opt.textContent = option.text;
                    opt.dataset.value = option.value;
                    if (option.selected) opt.classList.add('is-selected');
                    dropdown.appendChild(opt);

                    opt.addEventListener('click', function() {
                        select.value = this.dataset.value;
                        var selectedOpt = select.options[select.selectedIndex];
                        display.textContent = selectedOpt ? selectedOpt.text : this.textContent;
                        display.style.color = '#2c3a47';
                        dropdown.querySelectorAll('.pro-select-option').forEach(function(o) { o.classList.remove('is-selected'); });
                        this.classList.add('is-selected');
                        wrapper.classList.remove('is-open');
                        select.dispatchEvent(new Event('change'));
                    });
                });

                display.addEventListener('click', function(e) {
                    e.stopPropagation();
                    document.querySelectorAll('.pro-select-wrapper.is-open').forEach(function(w) { w.classList.remove('is-open'); });
                    wrapper.classList.toggle('is-open');
                });
            });
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.pro-select-wrapper')) {
                document.querySelectorAll('.pro-select-wrapper.is-open').forEach(function(w) { w.classList.remove('is-open'); });
            }
        });

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initProDropdowns);
        } else {
            initProDropdowns();
        }
    })();
    </script>
</body>
</html>


