<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $rolesData = [
            [
                'slug' => 'super-admin',
                'name' => 'Super Admin',
                'description' => 'Full unrestricted access to the entire system.',
                'is_super' => true, 'is_default' => true, 'is_active' => true,
                'color' => '#d86424', 'icon' => 'crown', 'priority' => 999,
            ],
            [
                'slug' => 'admin',
                'name' => 'Admin',
                'description' => 'General administrator with configurable permissions.',
                'is_super' => false, 'is_default' => true, 'is_active' => true,
                'color' => '#14a394', 'icon' => 'shield', 'priority' => 100,
            ],
            [
                'slug' => 'content-manager',
                'name' => 'Content Manager',
                'description' => 'Manages blog posts, directory listings, and SEO.',
                'is_super' => false, 'is_default' => true, 'is_active' => true,
                'color' => '#4f46e5', 'icon' => 'document', 'priority' => 80,
            ],
            [
                'slug' => 'marketing-manager',
                'name' => 'Marketing Manager',
                'description' => 'Manages email campaigns, templates, and marketing content.',
                'is_super' => false, 'is_default' => true, 'is_active' => true,
                'color' => '#e8773a', 'icon' => 'megaphone', 'priority' => 70,
            ],
            [
                'slug' => 'lead-manager',
                'name' => 'Lead Manager',
                'description' => 'Handles incoming leads, consultations, and follow-ups.',
                'is_super' => false, 'is_default' => true, 'is_active' => true,
                'color' => '#0b7a75', 'icon' => 'users', 'priority' => 60,
            ],
            [
                'slug' => 'ai-operator',
                'name' => 'AI Operator',
                'description' => 'Manages AI knowledge base, generates content, and uses AI tools.',
                'is_super' => false, 'is_default' => true, 'is_active' => true,
                'color' => '#7c3aed', 'icon' => 'sparkles', 'priority' => 50,
            ],
            [
                'slug' => 'support',
                'name' => 'Support',
                'description' => 'View and respond to inquiries with limited access.',
                'is_super' => false, 'is_default' => true, 'is_active' => true,
                'color' => '#64748b', 'icon' => 'headset', 'priority' => 40,
            ],
            [
                'slug' => 'viewer',
                'name' => 'Viewer',
                'description' => 'Read-only access to dashboards and reports.',
                'is_super' => false, 'is_default' => true, 'is_active' => true,
                'color' => '#94a3b8', 'icon' => 'eye', 'priority' => 30,
            ],
        ];

        $createdRoles = [];
        foreach ($rolesData as $data) {
            $existing = Role::where('slug', $data['slug'])->first();
            if ($existing) {
                $existing->update($data);
                $createdRoles[$data['slug']] = $existing;
            } else {
                $data['id'] = (string) Str::uuid();
                $createdRoles[$data['slug']] = Role::create($data);
            }
        }

        $superAdmin = $createdRoles['super-admin'];
        $admin = $createdRoles['admin'];
        $contentManager = $createdRoles['content-manager'];
        $marketingManager = $createdRoles['marketing-manager'];
        $leadManager = $createdRoles['lead-manager'];
        $aiOperator = $createdRoles['ai-operator'];
        $support = $createdRoles['support'];
        $viewer = $createdRoles['viewer'];

        $allPermissions = Permission::pluck('id');

        $superAdmin->permissions()->sync(
            $allPermissions->mapWithKeys(fn($id) => [$id => ['is_allowed' => true]])
        );

        $adminPerms = Permission::whereIn('slug', [
            'dashboard.view',
            'lead_center.view', 'lead_center.create', 'lead_center.edit', 'lead_center.delete', 'lead_center.export',
            'content_core.view', 'content_core.create', 'content_core.edit', 'content_core.publish', 'content_core.delete',
            'ai_operations.view', 'ai_operations.generate',
            'ebook_library.view', 'ebook_library.create', 'ebook_library.edit', 'ebook_library.delete', 'ebook_library.publish',
            'marketing_mail.view', 'marketing_mail.send_campaign', 'marketing_mail.edit_templates',
            'reports_logs.view', 'reports_logs.export',
        ])->pluck('id');

        $admin->permissions()->sync(
            $adminPerms->mapWithKeys(fn($id) => [$id => ['is_allowed' => true]])
        );

        $contentPerms = Permission::whereIn('slug', [
            'dashboard.view',
            'content_core.view', 'content_core.create', 'content_core.edit', 'content_core.publish', 'content_core.delete',
            'reports_logs.view',
        ])->pluck('id');

        $contentManager->permissions()->sync(
            $contentPerms->mapWithKeys(fn($id) => [$id => ['is_allowed' => true]])
        );

        $marketingPerms = Permission::whereIn('slug', [
            'dashboard.view',
            'marketing_mail.view', 'marketing_mail.send_campaign', 'marketing_mail.edit_templates',
            'reports_logs.view',
        ])->pluck('id');

        $marketingManager->permissions()->sync(
            $marketingPerms->mapWithKeys(fn($id) => [$id => ['is_allowed' => true]])
        );

        $leadPerms = Permission::whereIn('slug', [
            'dashboard.view',
            'lead_center.view', 'lead_center.create', 'lead_center.edit', 'lead_center.export',
            'reports_logs.view',
        ])->pluck('id');

        $leadManager->permissions()->sync(
            $leadPerms->mapWithKeys(fn($id) => [$id => ['is_allowed' => true]])
        );

        $aiPerms = Permission::whereIn('slug', [
            'dashboard.view',
            'ai_operations.view', 'ai_operations.generate',
            'reports_logs.view',
        ])->pluck('id');

        $aiOperator->permissions()->sync(
            $aiPerms->mapWithKeys(fn($id) => [$id => ['is_allowed' => true]])
        );

        $supportPerms = Permission::whereIn('slug', [
            'dashboard.view',
            'lead_center.view',
        ])->pluck('id');

        $support->permissions()->sync(
            $supportPerms->mapWithKeys(fn($id) => [$id => ['is_allowed' => true]])
        );

        $viewerPerms = Permission::whereIn('slug', [
            'dashboard.view',
        ])->pluck('id');

        $viewer->permissions()->sync(
            $viewerPerms->mapWithKeys(fn($id) => [$id => ['is_allowed' => true]])
        );
    }
}
