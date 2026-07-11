<?php

namespace Database\Seeders;

use App\Models\FeatureFlag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FeatureFlagSeeder extends Seeder
{
    public function run(): void
    {
        $flags = [
            ['module_key' => 'dashboard', 'name' => 'Dashboard', 'group' => 'General', 'is_enabled' => true, 'is_visible' => true],
            ['module_key' => 'lead_center', 'name' => 'Lead Center', 'group' => 'CRM', 'is_enabled' => true, 'is_visible' => true],
            ['module_key' => 'website_builder', 'name' => 'Website Builder', 'group' => 'General', 'is_enabled' => false, 'is_visible' => false],
            ['module_key' => 'email_templates', 'name' => 'Email Templates', 'group' => 'Marketing', 'is_enabled' => true, 'is_visible' => true],
            ['module_key' => 'ai_writer', 'name' => 'AI Writer', 'group' => 'AI', 'is_enabled' => true, 'is_visible' => true],
            ['module_key' => 'ai_chat', 'name' => 'AI Chat', 'group' => 'AI', 'is_enabled' => true, 'is_visible' => true],
            ['module_key' => 'ai_translation', 'name' => 'AI Translation', 'group' => 'AI', 'is_enabled' => true, 'is_visible' => true],
            ['module_key' => 'reports', 'name' => 'Reports', 'group' => 'Analytics', 'is_enabled' => true, 'is_visible' => true],
            ['module_key' => 'analytics', 'name' => 'Analytics', 'group' => 'Analytics', 'is_enabled' => true, 'is_visible' => true],
            ['module_key' => 'notifications', 'name' => 'Notifications', 'group' => 'General', 'is_enabled' => true, 'is_visible' => true],
            ['module_key' => 'media_library', 'name' => 'Media Library', 'group' => 'Content', 'is_enabled' => true, 'is_visible' => true],
            ['module_key' => 'blog', 'name' => 'Blog', 'group' => 'Content', 'is_enabled' => true, 'is_visible' => true],
            ['module_key' => 'faq', 'name' => 'FAQ', 'group' => 'Content', 'is_enabled' => true, 'is_visible' => true],
            ['module_key' => 'testimonials', 'name' => 'Testimonials', 'group' => 'Content', 'is_enabled' => true, 'is_visible' => true],
            ['module_key' => 'team', 'name' => 'Team', 'group' => 'Content', 'is_enabled' => true, 'is_visible' => true],
            ['module_key' => 'migration_packages', 'name' => 'Migration Packages', 'group' => 'Services', 'is_enabled' => true, 'is_visible' => true],
            ['module_key' => 'consultation', 'name' => 'Consultation', 'group' => 'Services', 'is_enabled' => true, 'is_visible' => true],
            ['module_key' => 'ebook', 'name' => 'Ebook Library', 'group' => 'Marketing', 'is_enabled' => true, 'is_visible' => false],
            ['module_key' => 'landing_pages', 'name' => 'Landing Pages', 'group' => 'Marketing', 'is_enabled' => true, 'is_visible' => true],
            ['module_key' => 'popup_builder', 'name' => 'Popup Builder', 'group' => 'Marketing', 'is_enabled' => false, 'is_visible' => false],
            ['module_key' => 'seo', 'name' => 'SEO', 'group' => 'Marketing', 'is_enabled' => true, 'is_visible' => true],
            ['module_key' => 'forms', 'name' => 'Forms', 'group' => 'General', 'is_enabled' => true, 'is_visible' => true],
            ['module_key' => 'crm', 'name' => 'CRM', 'group' => 'CRM', 'is_enabled' => true, 'is_visible' => true],
            ['module_key' => 'export', 'name' => 'Export', 'group' => 'General', 'is_enabled' => true, 'is_visible' => true],
            ['module_key' => 'import', 'name' => 'Import', 'group' => 'General', 'is_enabled' => true, 'is_visible' => true],
        ];

        foreach ($flags as $flag) {
            $existing = FeatureFlag::where('module_key', $flag['module_key'])->first();
            if ($existing) {
                $existing->update($flag);
            } else {
                $flag['id'] = (string) Str::uuid();
                FeatureFlag::create($flag);
            }
        }
    }
}
