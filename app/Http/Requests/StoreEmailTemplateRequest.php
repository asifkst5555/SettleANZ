<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmailTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:200'],
            'subject' => ['required', 'string', 'max:500'],
            'body_html' => ['required', 'string'],
            'body_text' => ['nullable', 'string'],
            'variables' => ['nullable', 'array'],
            'builder_json' => ['nullable', 'string'],
            'type' => ['required', Rule::in(['download', 'campaign', 'follow_up', 'verification'])],
            'is_active' => ['boolean'],
        ];
    }
}
