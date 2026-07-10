<?php

namespace Tests\Feature;

use App\Models\EmailTemplate;
use App\Models\EmailTemplateRevision;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\EmailTemplateRenderer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailTemplateBuilderTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private array $sampleBuilderJson;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['is_admin' => true]);

        $this->sampleBuilderJson = [
            'settings' => [
                'preheader' => 'Check this out!',
                'theme' => [
                    'primaryColor' => '#065e5b',
                    'secondaryColor' => '#e8773a',
                    'backgroundColor' => '#f5f0e8',
                ]
            ],
            'blocks' => [
                ['type' => 'logo', 'properties' => ['alignment' => 'center', 'width' => 150]],
                ['type' => 'heading', 'properties' => ['text' => 'Welcome to Australia!', 'fontSize' => '24px']],
                ['type' => 'text', 'properties' => ['text' => 'Hi {{name}}, here is your ebook download: {{download_url}}']],
                ['type' => 'button', 'properties' => ['text' => 'Download Now', 'url' => '{{download_url}}']],
                ['type' => 'footer', 'properties' => []],
            ]
        ];
    }

    public function test_admin_can_save_visual_builder_json_and_it_compiles_html(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.email-templates.store'), [
                'name' => 'Visual Test Template',
                'type' => 'download',
                'subject' => 'Download eBook {{ebook_name}}',
                'builder_json' => json_encode($this->sampleBuilderJson),
                'body_html' => 'temp placeholder', // will be auto-generated in controller
                'is_active' => 1,
            ]);

        $response->assertRedirect();
        
        $template = EmailTemplate::where('name', 'Visual Test Template')->first();
        $this->assertNotNull($template);
        $this->assertNotNull($template->builder_json);
        $this->assertStringContainsString('Welcome to Australia!', $template->body_html);
        $this->assertStringContainsString('Download Now', $template->body_html);
        
        // Assert preheader is inside HTML
        $this->assertStringContainsString('Check this out!', $template->body_html);

        // Assert MSO Outlook conditional tables wrapper exists
        $this->assertStringContainsString('xml', $template->body_html);
    }

    public function test_template_revisions_are_created_automatically(): void
    {
        $template = EmailTemplate::create([
            'name' => 'Original Template',
            'type' => 'download',
            'subject' => 'Download info',
            'body_html' => '<p>Original HTML</p>',
            'builder_json' => null,
            'is_active' => true,
            'created_by' => $this->admin->id,
        ]);

        $this->assertDatabaseHas('email_template_revisions', [
            'email_template_id' => $template->id,
            'name' => 'Original Template',
        ]);

        // Save modification updates template and spawns a new revision backup
        $response = $this->actingAs($this->admin)
            ->put(route('admin.email-templates.update', $template), [
                'name' => 'Modified Name',
                'type' => 'download',
                'subject' => 'Updated Subject',
                'body_html' => '<p>New HTML</p>',
                'builder_json' => json_encode($this->sampleBuilderJson),
                'is_active' => 1,
            ]);

        $response->assertRedirect();

        // Template attributes are updated
        $template->refresh();
        $this->assertEquals('Modified Name', $template->name);
        
        // Revision history now has 2 entries (original and modified backup)
        $this->assertEquals(2, $template->revisions()->count());
    }

    public function test_can_restore_revision(): void
    {
        $template = EmailTemplate::create([
            'name' => 'Template State A',
            'type' => 'download',
            'subject' => 'Subject A',
            'body_html' => '<p>HTML A</p>',
            'is_active' => true,
            'created_by' => $this->admin->id,
        ]);

        // Get the revision created for State A
        $revision = EmailTemplateRevision::where('email_template_id', $template->id)->first();
        $this->assertNotNull($revision);

        // Modify the template to State B
        $template->update([
            'name' => 'Template State B',
            'subject' => 'Subject B',
            'body_html' => '<p>HTML B</p>',
        ]);

        // Restore State A from revision
        $response = $this->actingAs($this->admin)
            ->post(route('admin.email-templates.restore-revision', [$template, $revision]));

        $response->assertRedirect();
        
        $template->refresh();
        $this->assertEquals('Template State A', $template->name);
        $this->assertEquals('Subject A', $template->subject);
        $this->assertEquals('<p>HTML A</p>', $template->body_html);
    }

    public function test_can_duplicate_template(): void
    {
        $template = EmailTemplate::create([
            'name' => 'Template to Duplicate',
            'type' => 'download',
            'subject' => 'Original Subject',
            'body_html' => '<p>Original Body</p>',
            'builder_json' => $this->sampleBuilderJson,
            'is_active' => true,
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.email-templates.duplicate', $template));

        $response->assertRedirect();

        $duplicate = EmailTemplate::where('name', 'Template to Duplicate Copy')->first();
        $this->assertNotNull($duplicate);
        $this->assertEquals('Original Subject', $duplicate->subject);
        $this->assertEquals('<p>Original Body</p>', $duplicate->body_html);
        $this->assertEquals($this->sampleBuilderJson, $duplicate->builder_json);
        $this->assertFalse($duplicate->is_active); // Must default to draft
    }

    public function test_saving_theme_settings_regenerates_builder_templates(): void
    {
        // 1. Create a builder template
        $template = EmailTemplate::create([
            'name' => 'Theme Reactivity Template',
            'type' => 'download',
            'subject' => 'Reactivity Check',
            'body_html' => 'placeholder html',
            'builder_json' => array_replace_recursive($this->sampleBuilderJson, [
                'settings' => ['theme' => ['primaryColor' => null]]
            ]),
            'is_active' => true,
            'created_by' => $this->admin->id,
        ]);

        // Run compiler manually to sync placeholder
        $template->body_html = EmailTemplateRenderer::render($template->builder_json);
        $template->save();

        // 2. Change the global primary color inside site settings
        $response = $this->actingAs($this->admin)
            ->put(route('admin.email-settings.update'), [
                'mail_mailer' => 'log',
                'mail_from_address' => 'hello@settleanz.com',
                'email_theme_primary_color' => '#ff0000', // red override
            ]);

        $response->assertRedirect();

        $this->assertEquals('#ff0000', SiteSetting::getValue('email_theme_primary_color'));

        // Assert template was automatically recompiled with the new primary color
        $template->refresh();
        $this->assertStringContainsString('color: #ff0000', $template->body_html);
    }

    public function test_send_test_email(): void
    {
        Mail::fake();

        $template = EmailTemplate::create([
            'name' => 'Test Dispatch Template',
            'type' => 'download',
            'subject' => 'Test Subject {{name}}',
            'body_html' => '<p>Hello {{name}}! Here is your logo: logo.webp</p>',
            'is_active' => true,
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.email-templates.send-test', $template), [
                'email' => 'recipient@settleanz.test',
                'subject' => 'Test Subject {{name}}',
                'body_html' => '<p>Hello {{name}}! Here is your logo: logo.webp</p>',
            ]);

        $response->assertJson(['success' => true]);

        Mail::assertSent(\App\Mail\CustomHtmlMail::class, function ($mail) {
            return $mail->hasTo('recipient@settleanz.test');
        });
    }

    public function test_parse_variables_forces_png_logo(): void
    {
        $template = EmailTemplate::create([
            'name' => 'Logo Swap Test',
            'type' => 'download',
            'subject' => 'Download eBook',
            'body_html' => '<p>Your logo: logo.webp</p>',
            'is_active' => true,
            'created_by' => $this->admin->id,
        ]);

        $parsed = $template->parseVariables([
            'name' => 'Asif',
            'company_logo' => 'https://settleanz.com/media/logo/logo.webp'
        ]);

        // Verifies the HTML content references email_logo.png instead of logo.webp
        $this->assertStringContainsString('email_logo.png', $parsed['body_html']);
        $this->assertStringNotContainsString('logo.webp', $parsed['body_html']);
    }

    public function test_builder_pages_render_successfully(): void
    {
        $template = EmailTemplate::create([
            'name' => 'Render Test',
            'type' => 'download',
            'subject' => 'Render Subject',
            'body_html' => '<p>Render Body</p>',
            'is_active' => true,
            'created_by' => $this->admin->id,
        ]);

        $responseCreate = $this->actingAs($this->admin)
            ->get(route('admin.email-templates.create'));
        $responseCreate->assertOk();

        $responseEdit = $this->actingAs($this->admin)
            ->get(route('admin.email-templates.edit', $template));
        $responseEdit->assertOk();
    }
}
