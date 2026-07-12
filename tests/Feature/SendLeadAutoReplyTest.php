<?php

namespace Tests\Feature;

use App\Jobs\SendLeadAutoReply;
use App\Mail\CustomHtmlMail;
use App\Models\EmailTemplate;
use App\Models\Lead;
use App\Services\AiEmailService;
use App\Services\EmailService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendLeadAutoReplyTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_auto_reply_uses_clean_settleanz_template(): void
    {
        Mail::fake();

        $lead = Lead::create([
            'full_name' => 'Md Ashifur Rahman',
            'email' => 'ashif@example.com',
            'form_type' => 'contact-page',
            'source_page' => 'contact-page',
        ]);

        (new SendLeadAutoReply($lead))->handle(app(EmailService::class));

        Mail::assertSent(CustomHtmlMail::class, function (CustomHtmlMail $mail) {
            return $mail->hasTo('ashif@example.com')
                && $mail->subject === 'Thanks for contacting SettleANZ'
                && str_contains($mail->bodyHtml, 'Md Ashifur Rahman')
                && str_contains($mail->bodyHtml, 'The SettleANZ Team')
                && str_contains($mail->bodyHtml, 'within <strong>24 hours</strong>')
                && !str_contains($mail->bodyHtml, 'Laravel')
                && !str_contains($mail->bodyHtml, 'Okay, let me start')
                && !str_contains($mail->bodyHtml, '"body_html"')
                && !str_contains($mail->bodyHtml, '\\n');
        });
    }

    public function test_contact_auto_reply_uses_active_admin_template_when_available(): void
    {
        Mail::fake();

        $template = EmailTemplate::create([
            'name' => 'Admin Contact Auto Reply',
            'type' => EmailTemplate::TYPE_CONTACT_AUTO_REPLY,
            'subject' => 'Custom contact reply for {{ lead_name }}',
            'body_html' => '<p>Hello {{ lead_name }}. Custom contact template. Reply within {{ response_time }}.</p>',
            'is_active' => true,
        ]);

        $lead = Lead::create([
            'full_name' => 'Md Ashifur Rahman',
            'email' => 'ashif@example.com',
            'form_type' => 'contact-page',
            'source_page' => 'contact-page',
        ]);

        (new SendLeadAutoReply($lead))->handle(app(EmailService::class));

        Mail::assertSent(CustomHtmlMail::class);

        $this->assertDatabaseHas('email_logs', [
            'lead_id' => $lead->id,
            'email_template_id' => $template->id,
            'subject' => 'Custom contact reply for Md Ashifur Rahman',
        ]);
    }

    public function test_booking_auto_reply_uses_booking_template(): void
    {
        Mail::fake();

        EmailTemplate::create([
            'name' => 'Admin Booking Auto Reply',
            'type' => EmailTemplate::TYPE_BOOKING_AUTO_REPLY,
            'subject' => 'Custom booking reply',
            'body_html' => '<p>Booking template for {{ lead_name }} and {{ enquiry_type }}.</p>',
            'is_active' => true,
        ]);

        $lead = Lead::create([
            'full_name' => 'Md Ashifur Rahman',
            'email' => 'ashif@example.com',
            'form_type' => 'package_booking',
            'source_page' => 'settlement-services',
        ]);

        (new SendLeadAutoReply($lead))->handle(app(EmailService::class));

        Mail::assertSent(CustomHtmlMail::class);

        $this->assertDatabaseHas('email_logs', [
            'lead_id' => $lead->id,
            'subject' => 'Custom booking reply',
        ]);
    }

    public function test_ai_email_parser_extracts_json_without_leaking_reasoning_text(): void
    {
        $service = app(AiEmailService::class);
        $method = new \ReflectionMethod($service, 'parseResponse');
        $method->setAccessible(true);

        $response = [
            'choices' => [[
                'message' => [
                    'content' => 'Okay, let me start by understanding the request. {"subject":"We received your enquiry","body_html":"<p>Dear Md Ashifur,\\nThank you for reaching out.</p>","body_text":"Dear Md Ashifur,\\n\\nThank you for reaching out."}',
                ],
            ]],
        ];

        $parsed = $method->invoke($service, $response, 'openai');

        $this->assertSame('We received your enquiry', $parsed['subject']);
        $this->assertStringContainsString('Dear Md Ashifur', $parsed['body_html']);
        $this->assertStringNotContainsString('Okay, let me start', $parsed['body_html']);
        $this->assertStringNotContainsString('\\n', $parsed['body_html']);
        $this->assertStringContainsString("\n\n", $parsed['body_text']);
    }

    public function test_ai_email_parser_uses_safe_fallback_for_invalid_response(): void
    {
        $service = app(AiEmailService::class);
        $method = new \ReflectionMethod($service, 'parseResponse');
        $method->setAccessible(true);

        $response = [
            'choices' => [[
                'message' => ['content' => 'Okay, let me start by understanding the request, but here is no valid JSON.'],
            ]],
        ];

        $parsed = $method->invoke($service, $response, 'openai');

        $this->assertSame('Thank you from SettleANZ', $parsed['subject']);
        $this->assertStringContainsString('The SettleANZ Team', $parsed['body_html']);
        $this->assertStringNotContainsString('Okay, let me start', $parsed['body_html']);
        $this->assertStringNotContainsString('Laravel', $parsed['body_html']);
    }
}
