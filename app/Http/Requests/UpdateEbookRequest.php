<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEbookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin ?? false;
    }

    public function rules(): array
    {
        $ebookId = $this->route('ebook')?->id ?? $this->route('ebook');

        return [
            'title' => ['sometimes', 'required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:220', Rule::unique('ebooks', 'slug')->ignore($ebookId)],
            'description' => ['nullable', 'string', 'max:5000'],
            'file' => ['nullable', 'file', 'max:' . config('ebook.storage.max_file_size', 52428800)],
            'file.*' => ['mimes:' . implode(',', config('ebook.storage.allowed_extensions', []))],
            'thumbnail' => ['nullable', 'image', 'max:5120'],
            'category_id' => ['nullable', 'exists:ebook_categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:ebook_tags,id'],
            'author' => ['nullable', 'string', 'max:150'],
            'isbn' => ['nullable', 'string', 'max:20'],
            'page_count' => ['nullable', 'integer', 'min:1'],
            'language' => ['nullable', 'string', 'max:10'],
            'status' => ['nullable', Rule::in(['draft', 'published', 'archived'])],
            'change_log' => ['nullable', 'string', 'max:2000'],
            'metadata' => ['nullable', 'json'],
        ];
    }
}
