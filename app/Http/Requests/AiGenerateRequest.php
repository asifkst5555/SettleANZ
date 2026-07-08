<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AiGenerateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin ?? false;
    }

    public function rules(): array
    {
        return [
            'action' => ['required', Rule::in(['generate_download_email', 'rewrite', 'follow_up', 'campaign', 'chat'])],
            'lead_id' => ['required_if:action,generate_download_email,follow_up', 'integer', 'exists:leads,id'],
            'ebook_id' => ['required_if:action,generate_download_email', 'integer', 'exists:ebooks,id'],
            'template_id' => ['nullable', 'integer', 'exists:email_templates,id'],
            'tone' => ['nullable', Rule::in(['professional', 'friendly', 'marketing'])],
            'language' => ['nullable', 'string', 'max:10'],
            'content' => ['required_if:action,rewrite,chat', 'string'],
            'conversation_id' => ['nullable', 'integer', 'exists:ai_conversations,id'],
        ];
    }
}
