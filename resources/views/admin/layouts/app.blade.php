<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>{{ $metaTitle ?? 'Admin | SettleANZ' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('site.css') }}">
    <link rel="stylesheet" href="{{ asset('admin.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-notifications.css') }}">
</head>
<body class="admin-shell-body">
    <!-- Toast Notification Container -->
    <div id="notificationContainer" class="notification-container"></div>
    
    <!-- Mobile Sidebar Toggle (floating - hidden when topbar visible) -->
    <button type="button" class="admin-sidebar__toggle" aria-label="Toggle menu">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
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
                <a @class(['is-active' => request()->routeIs('admin.dashboard')]) href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a @class(['is-active' => request()->routeIs('admin.leads.*') && !request('type')]) href="{{ route('admin.leads.index') }}">All Leads</a>
                <a @class(['is-active' => request()->routeIs('admin.leads.index') && request('type') === 'contact-page']) href="{{ route('admin.leads.index', ['type' => 'contact-page']) }}">Contact Submissions</a>
                <a @class(['is-active' => request()->routeIs('admin.leads.index') && request('type') === 'consultation-booking']) href="{{ route('admin.leads.index', ['type' => 'consultation-booking']) }}">Book Consultations</a>
                <a @class(['is-active' => request()->routeIs('admin.leads.index') && request('type') === 'package_booking']) href="{{ route('admin.leads.index', ['type' => 'package_booking']) }}">Package Requests</a>
                <a @class(['is-active' => request()->routeIs('admin.blog-posts.*')]) href="{{ route('admin.blog-posts.index') }}">Blog Posts</a>
                <a @class(['is-active' => request()->routeIs('admin.directory-listings.*')]) href="{{ route('admin.directory-listings.index') }}">Directory Listings</a>
                <a @class(['is-active' => request()->routeIs('admin.reviews.*')]) href="{{ route('admin.reviews.index') }}">Reviews</a>
                <a @class(['is-active' => request()->routeIs('admin.settings.*')]) href="{{ route('admin.settings.edit') }}">AI & Settings</a>
                <a @class(['is-active' => request()->routeIs('admin.seo.*')]) href="{{ route('admin.seo.index') }}">SEO Manager</a>
                <a href="/" target="_blank" rel="noreferrer">View Website</a>
            </nav>
        </aside>

        <div class="admin-main">
            <!-- SaaS-style Top Bar -->
            <header class="admin-topbar-saas">
                <div class="admin-topbar-saas__left">
                    <button class="admin-topbar-saas__menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 12h18M3 6h18M3 18h18"/>
                        </svg>
                    </button>
                    <div class="admin-topbar-saas__breadcrumb">
                        <span class="admin-topbar-saas__page-title">@yield('page-title', 'Dashboard')</span>
                    </div>
                </div>

                <div class="admin-topbar-saas__right">
                    <!-- Notifications -->
                    <div class="admin-notifications" id="notificationsDropdown">
                        <button class="admin-notifications__toggle" id="notificationsToggle" aria-label="Notifications">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                                <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                            </svg>
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
                            <svg class="admin-profile__chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
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
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                        <path d="M15 3h6v6"/>
                                        <path d="M10 14 21 3"/>
                                    </svg>
                                    View Website
                                </a>
                                <form method="POST" action="{{ route('admin.logout') }}" class="admin-profile__logout-form">
                                    @csrf
                                    <button type="submit" class="admin-profile__menu-item admin-profile__logout">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                            <path d="M16 17l5-5-5-5"/>
                                            <path d="M21 12H9"/>
                                        </svg>
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

    <!-- Modal System Script -->
    <script src="{{ asset('js/modal.js') }}"></script>
    
    <!-- Notification System Script -->
    <script src="{{ asset('js/notifications.js') }}"></script>
    <script src="{{ asset('js/admin-form-handler.js') }}"></script>

    <!-- Top Bar Dropdowns & Notifications Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
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
                lead: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="m22 21-3-3"/></svg>',
                review: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
                system: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>'
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
                    if (notificationId) {
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
    </style>
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


