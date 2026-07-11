<?php

return [
    'menus' => [
        [
            'label' => 'Dashboard',
            'route' => 'admin.dashboard',
            'icon' => 'layout-dashboard',
            'permission' => 'dashboard.view',
        ],
        [
            'label' => 'Lead Center',
            'icon' => 'users',
            'permission' => 'lead_center.view',
            'children' => [
                ['label' => 'All Leads', 'route' => 'admin.leads.index', 'icon' => 'clipboard-list', 'permission' => 'lead_center.view'],
                ['label' => 'Contact Form', 'route' => 'admin.leads.index', 'params' => ['form_type' => 'contact-page'], 'icon' => 'mail', 'permission' => 'lead_center.view'],
                ['label' => 'Package Bookings', 'route' => 'admin.leads.index', 'params' => ['form_type' => 'package_booking'], 'icon' => 'package', 'permission' => 'lead_center.view'],
                ['label' => 'Roadmap Downloads', 'route' => 'admin.leads.index', 'params' => ['form_type' => 'homepage_roadmap'], 'icon' => 'download', 'permission' => 'lead_center.view'],
                ['label' => 'Ebook Downloads', 'route' => 'admin.leads.index', 'params' => ['form_type' => 'ebook_download'], 'icon' => 'download', 'permission' => 'lead_center.view'],
                ['label' => 'AI Chat Leads', 'route' => 'admin.leads.index', 'params' => ['form_type' => 'ai_chat'], 'icon' => 'bot', 'permission' => 'lead_center.view'],
                ['label' => 'Reports', 'route' => 'admin.leads.reports', 'icon' => 'file-bar-chart', 'permission' => 'lead_center.view'],
                ['label' => 'Calendar', 'route' => 'admin.leads.calendar', 'icon' => 'calendar', 'permission' => 'lead_center.view'],
            ],
        ],
        [
            'label' => 'Content & Core',
            'icon' => 'layers',
            'permission' => 'content_core.view',
            'children' => [
                ['label' => 'Blog Posts', 'route' => 'admin.blog-posts.index', 'icon' => 'newspaper', 'permission' => 'content_core.view'],
                ['label' => 'Directory Listings', 'route' => 'admin.directory-listings.index', 'icon' => 'file-text', 'permission' => 'content_core.view'],
                ['label' => 'Moderator Reviews', 'route' => 'admin.reviews.index', 'icon' => 'message-square-quote', 'permission' => 'content_core.view'],
            ],
        ],
        [
            'label' => 'AI Operations',
            'icon' => 'sparkles',
            'permission' => 'ai_operations.view',
            'children' => [
                ['label' => 'AI Knowledge Base', 'route' => 'admin.ai-knowledge.index', 'icon' => 'brain', 'permission' => 'ai_operations.view'],
                ['label' => 'Admin AI Assistant', 'route' => 'admin.ai-assistant.index', 'icon' => 'bot', 'permission' => 'ai_operations.view'],
            ],
        ],
        [
            'label' => 'Ebook Library',
            'route' => 'admin.ebooks.index',
            'icon' => 'book-open',
            'permission' => 'ebook_library.view',
        ],
        [
            'label' => 'Marketing & Mail',
            'icon' => 'megaphone',
            'permission' => 'marketing_mail.view',
            'children' => [
                ['label' => 'Email Templates', 'route' => 'admin.email-templates.index', 'icon' => 'file-badge', 'permission' => 'marketing_mail.view'],
                ['label' => 'Campaigns', 'route' => 'admin.campaigns.index', 'icon' => 'send', 'permission' => 'marketing_mail.view'],
            ],
        ],
        [
            'label' => 'Reports & Logs',
            'icon' => 'bar-chart-3',
            'permission' => 'reports_logs.view',
            'children' => [
                ['label' => 'Download Logs', 'route' => 'admin.downloads.index', 'icon' => 'scroll-text', 'permission' => 'reports_logs.view'],
                ['label' => 'Download Tokens', 'route' => 'admin.downloads.tokens', 'icon' => 'key-round', 'permission' => 'reports_logs.view'],
                ['label' => 'Ebook Analytics', 'route' => 'admin.ebook-analytics.index', 'icon' => 'chart-column', 'permission' => 'reports_logs.view'],
            ],
        ],
        [
            'label' => 'System',
            'icon' => 'shield',
            'super' => true,
            'children' => [
                ['label' => 'Users', 'route' => 'admin.system.users.index', 'icon' => 'user-cog', 'permission' => 'user_management.view'],
                ['label' => 'Roles', 'route' => 'admin.system.roles.index', 'icon' => 'shield-user', 'permission' => 'roles_management.view'],
                ['label' => 'Permissions', 'route' => 'admin.system.permissions.index', 'icon' => 'key-round', 'permission' => 'permissions_management.view'],
                ['label' => 'Feature Flags', 'route' => 'admin.system.feature-flags.index', 'icon' => 'sliders-horizontal', 'permission' => 'feature_flags.view'],
                ['label' => 'Activity Logs', 'route' => 'admin.system.activity-logs.index', 'icon' => 'history', 'permission' => 'activity_logs.view'],
                ['label' => 'Login History', 'route' => 'admin.system.login-history.index', 'icon' => 'history', 'permission' => 'login_history.view'],
            ],
        ],
        [
            'label' => 'System Settings',
            'icon' => 'settings',
            'permission' => 'settings.view',
            'children' => [
                ['label' => 'General Settings', 'route' => 'admin.settings.edit', 'icon' => 'sliders-horizontal', 'permission' => 'settings.view'],
                ['label' => 'AI Configuration', 'route' => 'admin.ai-settings.api-connection', 'icon' => 'brain', 'permission' => 'settings.view'],
                ['label' => 'SMTP & Mail Themes', 'route' => 'admin.email-settings.index', 'icon' => 'mail', 'permission' => 'smtp_settings.view'],
                ['label' => 'Ebook Defaults', 'route' => 'admin.ebook-settings.index', 'icon' => 'book', 'permission' => 'settings.view'],
                ['label' => 'SEO Manager', 'route' => 'admin.seo.index', 'icon' => 'globe', 'permission' => 'settings.view'],
            ],
        ],
    ],
];
