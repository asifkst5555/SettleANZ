<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadCaptureRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'source_page' => $this->input('source_page', url()->previous()),
        ]);
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email:rfc,dns', 'max:150'],
            'consent' => ['required', 'accepted'],
            'ebook_id' => ['required', 'integer', 'exists:ebooks,id'],
            'source_page' => ['nullable', 'string', 'max:500'],
        ];

        if (config('ebook.lead_form.require_phone', false)) {
            $rules['phone'] = ['required', 'string', 'max:30'];
        } else {
            $rules['phone'] = ['nullable', 'string', 'max:30'];
        }

        if (config('ebook.lead_form.require_company', false)) {
            $rules['company'] = ['required', 'string', 'max:200'];
        } else {
            $rules['company'] = ['nullable', 'string', 'max:200'];
        }

        if (config('ebook.lead_form.require_country', false)) {
            $rules['country'] = ['required', 'string', 'max:100'];
        } else {
            $rules['country'] = ['nullable', 'string', 'max:100'];
        }

        if (config('ebook.lead_form.honeypot_field', 'website_url')) {
            $rules[config('ebook.lead_form.honeypot_field', 'website_url')] = ['nullable', 'string', 'max:0'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'consent.required' => 'You must agree to receive the ebook.',
            'consent.accepted' => 'You must agree to receive the ebook.',
            'ebook_id.required' => 'Invalid ebook selection.',
            'ebook_id.exists' => 'The requested ebook is not available.',
        ];
    }
}
