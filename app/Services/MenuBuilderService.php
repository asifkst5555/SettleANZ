<?php

namespace App\Services;

use App\Models\User;

class MenuBuilderService
{
    public function getSidebarMenu(?User $user): array
    {
        if (!$user) {
            return [];
        }

        $menus = config('admin.menus', $this->getDefaultMenus());
        $filtered = [];

        foreach ($menus as $menu) {
            $item = $menu;

            if (isset($item['permission']) && !$user->hasPermission($item['permission'])) {
                continue;
            }

            if (isset($item['children'])) {
                $children = [];
                foreach ($item['children'] as $child) {
                    if (isset($child['permission']) && !$user->hasPermission($child['permission'])) {
                        continue;
                    }
                    $children[] = $child;
                }
                if (empty($children)) {
                    continue;
                }
                $item['children'] = $children;
            }

            $filtered[] = $item;
        }

        return $filtered;
    }

    public function getDefaultMenus(): array
    {
        return [
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
                    ['label' => 'All Inquiries', 'route' => 'admin.leads.index', 'icon' => 'clipboard-list', 'permission' => 'lead_center.view'],
                    ['label' => 'Contact Form', 'route' => 'admin.leads.index', 'params' => ['type' => 'contact-page'], 'icon' => 'mail', 'permission' => 'lead_center.view'],
                    ['label' => 'Bookings & Packages', 'route' => 'admin.leads.index', 'params' => ['type' => 'consultation-booking'], 'icon' => 'package', 'permission' => 'lead_center.view'],
                    ['label' => 'Ebook Downloads', 'route' => 'admin.ebook-leads.index', 'icon' => 'download', 'permission' => 'lead_center.view'],
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
        ];
    }

    public function getBreadcrumbs(?User $user, string $currentRoute): array
    {
        $breadcrumbs = [['label' => 'Home', 'route' => 'admin.dashboard']];

        $menu = $this->getSidebarMenu($user);
        $this->findBreadcrumbs($menu, $currentRoute, $breadcrumbs);

        return $breadcrumbs;
    }

    private function findBreadcrumbs(array $items, string $currentRoute, array &$breadcrumbs): bool
    {
        foreach ($items as $item) {
            if (isset($item['route']) && $item['route'] === $currentRoute) {
                $breadcrumbs[] = ['label' => $item['label']];
                return true;
            }
            if (isset($item['children'])) {
                foreach ($item['children'] as $child) {
                    if (isset($child['route']) && $child['route'] === $currentRoute) {
                        $breadcrumbs[] = ['label' => $item['label']];
                        $breadcrumbs[] = ['label' => $child['label']];
                        return true;
                    }
                }
            }
        }
        return false;
    }
}
