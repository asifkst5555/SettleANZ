<?php

namespace App\Http\Controllers;

use App\Jobs\SendLeadAutoReply;
use App\Models\Lead;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LeadCaptureController extends Controller
{
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'name' => ['nullable', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:2000'],
            'message' => ['nullable', 'string', 'max:3000'],
            'goal' => ['nullable', 'string', 'max:255'],
            'current_visa_status' => ['nullable', 'string', 'max:100'],
            'country_of_citizenship' => ['nullable', 'string', 'max:100'],
            'help_details' => ['nullable', 'string', 'max:2000'],
            'form_type' => ['nullable', 'string', 'max:40'],
            'source_page' => ['nullable', 'string', 'max:100'],
            'listing_id' => ['nullable', 'integer'],
            'listing_name' => ['nullable', 'string', 'max:255'],
            'preferred_date' => ['nullable', 'date'],
            'preferred_time' => ['nullable', 'string', 'max:100'],
            'consultation_format' => ['nullable', 'string', 'max:100'],
            'visa_type' => ['nullable', 'string', 'max:100'],
            'referral_url' => ['nullable', 'string', 'max:500'],
            'form_name' => ['nullable', 'string', 'max:100'],
            'landing_page_name' => ['nullable', 'string', 'max:255'],
            'package_name' => ['nullable', 'string', 'max:255'],
            'preferred_contact_method' => ['nullable', 'string', 'max:100'],
        ]);

        $firstName = $validated['first_name'] ?? null;
        $lastName = $validated['last_name'] ?? null;
        $fullName = $validated['name'] ?? null;

        if (!$fullName && ($firstName || $lastName)) {
            $fullName = trim(implode(' ', array_filter([$firstName, $lastName])));
        }

        if (!$firstName && $fullName) {
            $firstName = str($fullName)->trim()->explode(' ')->filter()->first();
        }

        $goalCandidate = $validated['goal'] ?? $validated['subject'] ?? null;
        $goal = ($goalCandidate !== null && $goalCandidate !== '')
            ? Str::limit($goalCandidate, 255, '…')
            : null;

        $lead = Lead::create([
            'first_name' => $firstName,
            'full_name' => $fullName,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'goal' => $goal,
            'form_type' => $validated['form_type'] ?? 'general',
            'form_name' => $validated['form_name'] ?? null,
            'source_page' => $validated['source_page'] ?? 'homepage',
            'landing_page_name' => $validated['landing_page_name'] ?? null,
            'package_name' => $validated['package_name'] ?? null,
            'visa_type' => $validated['visa_type'] ?? null,
            'preferred_date' => $validated['preferred_date'] ?? null,
            'preferred_time' => $validated['preferred_time'] ?? null,
            'preferred_contact_method' => $validated['preferred_contact_method'] ?? null,
            'referral_url' => $validated['referral_url'] ?? $request->headers->get('referer'),
            'status' => 'new',
            'metadata' => [
                'last_name' => $lastName,
                'subject' => $validated['subject'] ?? null,
                'message' => $validated['message'] ?? null,
                'current_visa_status' => $validated['current_visa_status'] ?? null,
                'country_of_citizenship' => $validated['country_of_citizenship'] ?? null,
                'help_details' => $validated['help_details'] ?? null,
                'listing_id' => $validated['listing_id'] ?? null,
                'listing_name' => $validated['listing_name'] ?? null,
                'consultation_format' => $validated['consultation_format'] ?? null,
                'session_id' => $request->session()->getId(),
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Create notification for new lead
        NotificationService::createLeadNotification($lead);

        // Dispatch auto-reply email for contact and booking leads
        $autoReplyTypes = ['contact-page', 'package_booking', 'consultation-booking', 'migration-consultation'];
        $formType = $validated['form_type'] ?? 'general';
        $shouldAutoReply = in_array($formType, $autoReplyTypes, true);

        Log::debug('[TRACE] LeadCaptureController::store auto-reply check', [
            'lead_id' => $lead->id,
            'email' => $lead->email,
            'form_type' => $formType,
            'should_auto_reply' => $shouldAutoReply,
            'valid_form_types' => $autoReplyTypes,
            'queue_connection' => config('queue.default'),
        ]);

        if ($shouldAutoReply) {
            Log::debug('[TRACE] Dispatching SendLeadAutoReply (sync)', [
                'lead_id' => $lead->id,
            ]);

            try {
                SendLeadAutoReply::dispatch($lead)->onQueue('emails');
                Log::debug('[TRACE] SendLeadAutoReply completed', [
                    'lead_id' => $lead->id,
                ]);
            } catch (\Throwable $e) {
                $context = [
                    'lead_id' => $lead->id,
                    'exception_class' => get_class($e),
                    'exception_message' => $e->getMessage(),
                    'exception_code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ];
                if ($e instanceof \Illuminate\Http\Client\HttpException && method_exists($e, 'response')) {
                    $response = $e->response;
                    $context['http_status'] = $response->status();
                    $context['http_body'] = $response->body();
                }
                Log::error('[TRACE] SendLeadAutoReply EXCEPTION CAUGHT', $context);
                Log::error('[TRACE] SendLeadAutoReply stack trace: ' . $e->getTraceAsString());
            }
        }

        $formType = $validated['form_type'] ?? 'general';
        $successMessage = match ($formType) {
            'package_booking' => 'Thank you for your booking request. We will contact you within 24 hours to confirm your session.',
            'consultation-booking' => 'Thanks - your consultation request has been received.',
            'contact-page', 'migration-consultation' => 'Thanks - we have received your message and will get back to you within 24 hours.',
            default => 'Thanks - we have received your message and will be in touch soon.',
        };

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $successMessage,
            ]);
        }

        return back()
            ->with('status', $successMessage)
            ->with('lead_submitted', true);
    }
}

