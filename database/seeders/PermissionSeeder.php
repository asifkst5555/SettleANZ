<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'dashboard' => [
                ['name' => 'View Dashboard', 'slug' => 'dashboard.view'],
            ],
            'lead_center' => [
                ['name' => 'View Leads', 'slug' => 'lead_center.view'],
                ['name' => 'Create Lead', 'slug' => 'lead_center.create'],
                ['name' => 'Edit Lead', 'slug' => 'lead_center.edit'],
                ['name' => 'Delete Lead', 'slug' => 'lead_center.delete'],
                ['name' => 'Export Leads', 'slug' => 'lead_center.export'],
            ],
            'content_core' => [
                ['name' => 'View Content', 'slug' => 'content_core.view'],
                ['name' => 'Create Content', 'slug' => 'content_core.create'],
                ['name' => 'Edit Content', 'slug' => 'content_core.edit'],
                ['name' => 'Publish Content', 'slug' => 'content_core.publish'],
                ['name' => 'Delete Content', 'slug' => 'content_core.delete'],
            ],
            'ai_operations' => [
                ['name' => 'View AI', 'slug' => 'ai_operations.view'],
                ['name' => 'Generate AI Content', 'slug' => 'ai_operations.generate'],
                ['name' => 'Delete AI Content', 'slug' => 'ai_operations.delete'],
            ],
            'ebook_library' => [
                ['name' => 'View Ebooks', 'slug' => 'ebook_library.view'],
                ['name' => 'Create Ebook', 'slug' => 'ebook_library.create'],
                ['name' => 'Edit Ebook', 'slug' => 'ebook_library.edit'],
                ['name' => 'Delete Ebook', 'slug' => 'ebook_library.delete'],
                ['name' => 'Publish Ebook', 'slug' => 'ebook_library.publish'],
            ],
            'marketing_mail' => [
                ['name' => 'View Marketing', 'slug' => 'marketing_mail.view'],
                ['name' => 'Send Campaign', 'slug' => 'marketing_mail.send_campaign'],
                ['name' => 'Edit Templates', 'slug' => 'marketing_mail.edit_templates'],
            ],
            'reports_logs' => [
                ['name' => 'View Reports', 'slug' => 'reports_logs.view'],
                ['name' => 'Export Reports', 'slug' => 'reports_logs.export'],
            ],
            'settings' => [
                ['name' => 'View Settings', 'slug' => 'settings.view'],
                ['name' => 'Edit Settings', 'slug' => 'settings.edit'],
            ],
            'roles_management' => [
                ['name' => 'View Roles', 'slug' => 'roles_management.view'],
                ['name' => 'Create Role', 'slug' => 'roles_management.create'],
                ['name' => 'Edit Role', 'slug' => 'roles_management.edit'],
                ['name' => 'Delete Role', 'slug' => 'roles_management.delete'],
                ['name' => 'Clone Role', 'slug' => 'roles_management.clone'],
                ['name' => 'Assign Users', 'slug' => 'roles_management.assign_users'],
            ],
            'permissions_management' => [
                ['name' => 'View Permissions', 'slug' => 'permissions_management.view'],
                ['name' => 'Create Permission', 'slug' => 'permissions_management.create'],
                ['name' => 'Edit Permission', 'slug' => 'permissions_management.edit'],
            ],
            'user_management' => [
                ['name' => 'View Users', 'slug' => 'user_management.view'],
                ['name' => 'Create User', 'slug' => 'user_management.create'],
                ['name' => 'Edit User', 'slug' => 'user_management.edit'],
                ['name' => 'Delete User', 'slug' => 'user_management.delete'],
                ['name' => 'Suspend User', 'slug' => 'user_management.suspend'],
                ['name' => 'Activate User', 'slug' => 'user_management.activate'],
                ['name' => 'Reset Password', 'slug' => 'user_management.reset_password'],
                ['name' => 'Force Logout', 'slug' => 'user_management.force_logout'],
                ['name' => 'Impersonate User', 'slug' => 'user_management.impersonate'],
            ],
            'activity_logs' => [
                ['name' => 'View Activity Logs', 'slug' => 'activity_logs.view'],
                ['name' => 'Export Activity Logs', 'slug' => 'activity_logs.export'],
            ],
            'login_history' => [
                ['name' => 'View Login History', 'slug' => 'login_history.view'],
            ],
            'feature_flags' => [
                ['name' => 'View Feature Flags', 'slug' => 'feature_flags.view'],
                ['name' => 'Toggle Feature Flags', 'slug' => 'feature_flags.toggle'],
            ],
            'security' => [
                ['name' => 'View Security', 'slug' => 'security.view'],
                ['name' => 'Edit Security', 'slug' => 'security.edit'],
            ],
            'api_keys' => [
                ['name' => 'View API Keys', 'slug' => 'api_keys.view'],
                ['name' => 'Manage API Keys', 'slug' => 'api_keys.manage'],
            ],
            'smtp_settings' => [
                ['name' => 'View SMTP Settings', 'slug' => 'smtp_settings.view'],
                ['name' => 'Edit SMTP Settings', 'slug' => 'smtp_settings.edit'],
            ],
            'database_settings' => [
                ['name' => 'View Database Settings', 'slug' => 'database_settings.view'],
                ['name' => 'Edit Database Settings', 'slug' => 'database_settings.edit'],
            ],
            'system_config' => [
                ['name' => 'View Configuration', 'slug' => 'system_config.view'],
                ['name' => 'Edit Configuration', 'slug' => 'system_config.edit'],
            ],
        ];

        foreach ($permissions as $groupSlug => $perms) {
            $group = PermissionGroup::where('slug', $groupSlug)->first();
            if (!$group) {
                continue;
            }

            foreach ($perms as $perm) {
                $existing = Permission::where('slug', $perm['slug'])->first();
                if ($existing) {
                    $existing->update($perm + ['group_id' => $group->id]);
                } else {
                    $perm['id'] = (string) Str::uuid();
                    $perm['group_id'] = $group->id;
                    Permission::create($perm);
                }
            }
        }
    }
}
