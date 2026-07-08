<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEbookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:220', Rule::unique('ebooks', 'slug')],
            'description' => ['nullable', 'string', 'max:5000'],
            'file' => ['required', 'file', 'max:' . config('ebook.storage.max_file_size', 52428800)],
            'file.*' => ['mimes:' . implode(',', config('ebook.storage.allowed_extensions', ['pdf', 'zip', 'docx', 'png', 'jpg', 'jpeg', 'gif', 'epub', 'mobi']))],
            'thumbnail' => ['nullable', 'image', 'max:5120'],
            'category_id' => ['nullable', 'exists:ebook_categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:ebook_tags,id'],
            'author' => ['nullable', 'string', 'max:150'],
            'isbn' => ['nullable', 'string', 'max:20'],
            'page_count' => ['nullable', 'integer', 'min:1'],
            'language' => ['nullable', 'string', 'max:10'],
            'status' => ['nullable', Rule::in(['draft', 'published', 'archived'])],
            'version_number' => ['nullable', 'integer', 'min:1'],
            'change_log' => ['nullable', 'string', 'max:2000'],
            'metadata' => ['nullable', 'json'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Please upload an ebook file.',
            'file.mimes' => 'Allowed file types: ' . implode(', ', config('ebook.storage.allowed_extensions', [])),
            'file.max' => 'File size must not exceed ' . (config('ebook.storage.max_file_size', 52428800) / 1024 / 1024) . 'MB.',
            'title.required' => 'The ebook title is required.',
        ];
    }
}
