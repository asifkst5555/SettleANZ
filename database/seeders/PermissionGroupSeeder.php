<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionGroupSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            ['name' => 'Dashboard', 'slug' => 'dashboard', 'description' => 'Dashboard access', 'display_order' => 1],
            ['name' => 'Lead Center', 'slug' => 'lead_center', 'description' => 'Lead management', 'display_order' => 2],
            ['name' => 'Content & Core', 'slug' => 'content_core', 'description' => 'Blog, directory, reviews', 'display_order' => 3],
            ['name' => 'AI Operations', 'slug' => 'ai_operations', 'description' => 'AI features', 'display_order' => 4],
            ['name' => 'Ebook Library', 'slug' => 'ebook_library', 'description' => 'Ebook management', 'display_order' => 5],
            ['name' => 'Marketing & Mail', 'slug' => 'marketing_mail', 'description' => 'Email campaigns', 'display_order' => 6],
            ['name' => 'Reports & Logs', 'slug' => 'reports_logs', 'description' => 'Reports and analytics', 'display_order' => 7],
            ['name' => 'System Settings', 'slug' => 'settings', 'description' => 'System configuration', 'display_order' => 8],
            ['name' => 'Role Management', 'slug' => 'roles_management', 'description' => 'Role CRUD', 'display_order' => 9],
            ['name' => 'Permission Management', 'slug' => 'permissions_management', 'description' => 'Permission CRUD', 'display_order' => 10],
            ['name' => 'User Management', 'slug' => 'user_management', 'description' => 'User admin', 'display_order' => 11],
            ['name' => 'Activity Logs', 'slug' => 'activity_logs', 'description' => 'Audit trail', 'display_order' => 12],
            ['name' => 'Login History', 'slug' => 'login_history', 'description' => 'Login tracking', 'display_order' => 13],
            ['name' => 'Feature Flags', 'slug' => 'feature_flags', 'description' => 'Feature toggles', 'display_order' => 14],
            ['name' => 'Security', 'slug' => 'security', 'description' => 'Security settings', 'display_order' => 15],
            ['name' => 'API Keys', 'slug' => 'api_keys', 'description' => 'API key management', 'display_order' => 16],
            ['name' => 'SMTP Settings', 'slug' => 'smtp_settings', 'description' => 'Mail configuration', 'display_order' => 17],
            ['name' => 'Database Settings', 'slug' => 'database_settings', 'description' => 'Database configuration', 'display_order' => 18],
            ['name' => 'System Configuration', 'slug' => 'system_config', 'description' => 'System config', 'display_order' => 19],
        ];

        foreach ($groups as $group) {
            $existing = PermissionGroup::where('slug', $group['slug'])->first();
            if ($existing) {
                $existing->update($group);
            } else {
                $group['id'] = (string) Str::uuid();
                PermissionGroup::create($group);
            }
        }
    }
}
