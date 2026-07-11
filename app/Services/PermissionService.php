<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\Role;
use Illuminate\Support\Collection;

class PermissionService
{
    public function getAllPermissionsGrouped(): Collection
    {
        return PermissionGroup::with(['permissions' => function ($query) {
            $query->orderBy('name');
        }])
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();
    }

    public function getRolePermissions(Role $role): Collection
    {
        return $role->permissions()->pluck('role_permissions.is_allowed', 'permission_id');
    }

    public function getPermissionMatrix(): array
    {
        $groups = $this->getAllPermissionsGrouped();
        $matrix = [];

        foreach ($groups as $group) {
            $matrix[$group->slug] = [
                'name' => $group->name,
                'permissions' => $group->permissions->map(function ($perm) {
                    return [
                        'id' => $perm->id,
                        'name' => $perm->name,
                        'slug' => $perm->slug,
                    ];
                })->toArray(),
            ];
        }

        return $matrix;
    }

    public function syncRolePermissions(Role $role, array $permissions): void
    {
        $syncData = [];
        foreach ($permissions as $permissionId => $isAllowed) {
            $syncData[$permissionId] = ['is_allowed' => filter_var($isAllowed, FILTER_VALIDATE_BOOLEAN)];
        }
        $role->permissions()->sync($syncData);
    }

    public function cloneRolePermissions(Role $sourceRole, Role $targetRole): void
    {
        $permissions = $sourceRole->permissions()->pluck('role_permissions.is_allowed', 'permission_id')->toArray();
        $this->syncRolePermissions($targetRole, $permissions);
    }

    public function getModulePermissionMap(): array
    {
        return [
            'dashboard' => [
                'group' => 'Dashboard',
                'permissions' => ['dashboard.view' => 'View Dashboard'],
            ],
            'lead_center' => [
                'group' => 'Lead Center',
                'permissions' => [
                    'lead_center.view' => 'View Leads',
                    'lead_center.create' => 'Create Lead',
                    'lead_center.edit' => 'Edit Lead',
                    'lead_center.delete' => 'Delete Lead',
                    'lead_center.export' => 'Export Leads',
                ],
            ],
            'content_core' => [
                'group' => 'Content & Core',
                'permissions' => [
                    'content_core.view' => 'View Content',
                    'content_core.create' => 'Create Content',
                    'content_core.edit' => 'Edit Content',
                    'content_core.publish' => 'Publish Content',
                    'content_core.delete' => 'Delete Content',
                ],
            ],
            'ai_operations' => [
                'group' => 'AI Operations',
                'permissions' => [
                    'ai_operations.view' => 'View AI',
                    'ai_operations.generate' => 'Generate AI Content',
                    'ai_operations.delete' => 'Delete AI Content',
                ],
            ],
            'ebook_library' => [
                'group' => 'Ebook Library',
                'permissions' => [
                    'ebook_library.view' => 'View Ebooks',
                    'ebook_library.create' => 'Create Ebook',
                    'ebook_library.edit' => 'Edit Ebook',
                    'ebook_library.delete' => 'Delete Ebook',
                    'ebook_library.publish' => 'Publish Ebook',
                ],
            ],
            'marketing_mail' => [
                'group' => 'Marketing & Mail',
                'permissions' => [
                    'marketing_mail.view' => 'View Marketing',
                    'marketing_mail.send_campaign' => 'Send Campaign',
                    'marketing_mail.edit_templates' => 'Edit Templates',
                ],
            ],
            'reports_logs' => [
                'group' => 'Reports & Logs',
                'permissions' => [
                    'reports_logs.view' => 'View Reports',
                    'reports_logs.export' => 'Export Reports',
                ],
            ],
            'settings' => [
                'group' => 'System Settings',
                'permissions' => [
                    'settings.view' => 'View Settings',
                    'settings.edit' => 'Edit Settings',
                ],
            ],
            'roles_management' => [
                'group' => 'Role Management',
                'permissions' => [
                    'roles_management.view' => 'View Roles',
                    'roles_management.create' => 'Create Role',
                    'roles_management.edit' => 'Edit Role',
                    'roles_management.delete' => 'Delete Role',
                    'roles_management.clone' => 'Clone Role',
                    'roles_management.assign_users' => 'Assign Users',
                ],
            ],
            'permissions_management' => [
                'group' => 'Permission Management',
                'permissions' => [
                    'permissions_management.view' => 'View Permissions',
                    'permissions_management.create' => 'Create Permission',
                    'permissions_management.edit' => 'Edit Permission',
                ],
            ],
            'user_management' => [
                'group' => 'User Management',
                'permissions' => [
                    'user_management.view' => 'View Users',
                    'user_management.create' => 'Create User',
                    'user_management.edit' => 'Edit User',
                    'user_management.delete' => 'Delete User',
                    'user_management.suspend' => 'Suspend User',
                    'user_management.activate' => 'Activate User',
                    'user_management.reset_password' => 'Reset Password',
                    'user_management.force_logout' => 'Force Logout',
                    'user_management.impersonate' => 'Impersonate User',
                ],
            ],
            'activity_logs' => [
                'group' => 'Activity Logs',
                'permissions' => [
                    'activity_logs.view' => 'View Activity Logs',
                    'activity_logs.export' => 'Export Activity Logs',
                ],
            ],
            'login_history' => [
                'group' => 'Login History',
                'permissions' => [
                    'login_history.view' => 'View Login History',
                ],
            ],
            'feature_flags' => [
                'group' => 'Feature Flags',
                'permissions' => [
                    'feature_flags.view' => 'View Feature Flags',
                    'feature_flags.toggle' => 'Toggle Feature Flags',
                ],
            ],
            'security' => [
                'group' => 'Security',
                'permissions' => [
                    'security.view' => 'View Security',
                    'security.edit' => 'Edit Security',
                ],
            ],
            'api_keys' => [
                'group' => 'API Keys',
                'permissions' => [
                    'api_keys.view' => 'View API Keys',
                    'api_keys.manage' => 'Manage API Keys',
                ],
            ],
            'smtp_settings' => [
                'group' => 'SMTP Settings',
                'permissions' => [
                    'smtp_settings.view' => 'View SMTP Settings',
                    'smtp_settings.edit' => 'Edit SMTP Settings',
                ],
            ],
            'database_settings' => [
                'group' => 'Database Settings',
                'permissions' => [
                    'database_settings.view' => 'View Database Settings',
                    'database_settings.edit' => 'Edit Database Settings',
                ],
            ],
            'system_config' => [
                'group' => 'System Configuration',
                'permissions' => [
                    'system_config.view' => 'View Configuration',
                    'system_config.edit' => 'Edit Configuration',
                ],
            ],
        ];
    }
}
