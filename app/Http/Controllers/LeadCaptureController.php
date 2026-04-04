<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
            'subject' => ['nullable', 'string', 'max:100'],
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
            'booking_notes' => ['nullable', 'string', 'max:2000'],
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

        Lead::create([
            'first_name' => $firstName,
            'full_name' => $fullName,
            'email' => $validated['email'],
            'goal' => $validated['goal'] ?? $validated['subject'] ?? null,
            'form_type' => $validated['form_type'] ?? 'general',
            'source_page' => $validated['source_page'] ?? 'homepage',
            'status' => 'new',
            'metadata' => [
                'phone' => $validated['phone'] ?? null,
                'last_name' => $lastName,
                'subject' => $validated['subject'] ?? null,
                'message' => $validated['message'] ?? null,
                'current_visa_status' => $validated['current_visa_status'] ?? null,
                'country_of_citizenship' => $validated['country_of_citizenship'] ?? null,
                'help_details' => $validated['help_details'] ?? null,
                'listing_id' => $validated['listing_id'] ?? null,
                'listing_name' => $validated['listing_name'] ?? null,
                'preferred_date' => $validated['preferred_date'] ?? null,
                'preferred_time' => $validated['preferred_time'] ?? null,
                'consultation_format' => $validated['consultation_format'] ?? null,
                'booking_notes' => $validated['booking_notes'] ?? null,
                'referrer' => $request->headers->get('referer'),
                'session_id' => $request->session()->getId(),
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $successMessage = 'Thanks - your consultation request has been received.';

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

