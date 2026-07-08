<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:5000'],
            'type' => ['required', Rule::in(['broadcast', 'follow_up', 'automated'])],
            'email_template_id' => ['required', 'integer', 'exists:email_templates,id'],
            'ebook_id' => ['nullable', 'integer', 'exists:ebooks,id'],
            'filters' => ['nullable', 'array'],
            'scheduled_at' => ['nullable', 'date', 'after:now'],
            'lead_ids' => ['nullable', 'array'],
            'lead_ids.*' => ['exists:leads,id'],
        ];
    }
}
