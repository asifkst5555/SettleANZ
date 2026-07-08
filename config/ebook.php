<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Storage Configuration
    |--------------------------------------------------------------------------
    */
    'storage' => [
        'disk' => env('EBOOK_STORAGE_DISK', 'local'),
        'prefix' => 'ebooks',
        'thumbnail_prefix' => 'thumbnails',
        'allowed_extensions' => ['pdf', 'zip', 'docx', 'png', 'jpg', 'jpeg', 'gif', 'epub', 'mobi'],
        'max_file_size' => env('EBOOK_MAX_FILE_SIZE', 50 * 1024 * 1024), // 50MB
    ],

    /*
    |--------------------------------------------------------------------------
    | Download Settings
    |--------------------------------------------------------------------------
    */
    'download' => [
        'token_expiry_hours' => env('EBOOK_TOKEN_EXPIRY_HOURS', 72),
        'max_downloads_per_token' => env('EBOOK_MAX_DOWNLOADS_PER_TOKEN', 5),
        'require_email_verification' => env('EBOOK_REQUIRE_EMAIL_VERIFICATION', false),
        'rate_limit_per_ip' => env('EBOOK_RATE_LIMIT_PER_IP', 10),
        'rate_limit_decay_minutes' => env('EBOOK_RATE_LIMIT_DECAY_MINUTES', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | Thumbnail Settings
    |--------------------------------------------------------------------------
    */
    'thumbnail' => [
        'width' => 300,
        'height' => 400,
        'format' => 'webp',
        'quality' => 80,
    ],

    /*
    |--------------------------------------------------------------------------
    | AI Email Assistant
    |--------------------------------------------------------------------------
    */
    'ai' => [
        'provider' => env('AI_EMAIL_PROVIDER', 'openai'),
        'model' => env('AI_EMAIL_MODEL', 'gpt-4o'),
        'api_key' => env('AI_EMAIL_API_KEY'),
        'max_tokens' => (int) env('AI_EMAIL_MAX_TOKENS', 2000),
        'temperature' => (float) env('AI_EMAIL_TEMPERATURE', 0.7),
        'timeout' => (int) env('AI_EMAIL_TIMEOUT', 60),

        'providers' => [
            'openai' => [
                'url' => 'https://api.openai.com/v1/chat/completions',
                'model' => 'gpt-4o',
            ],
            'gemini' => [
                'url' => 'https://generativelanguage.googleapis.com/v1/models/gemini-pro:generateContent',
                'model' => 'gemini-pro',
            ],
            'claude' => [
                'url' => 'https://api.anthropic.com/v1/messages',
                'model' => 'claude-3-opus-20240229',
            ],
            'deepseek' => [
                'url' => 'https://api.deepseek.com/v1/chat/completions',
                'model' => 'deepseek-chat',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Settings
    |--------------------------------------------------------------------------
    */
    'admin' => [
        'pagination' => [
            'per_page' => 20,
        ],
        'dashboard' => [
            'recent_downloads_limit' => 10,
            'top_ebooks_limit' => 5,
            'days_range' => 30,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Lead Form Settings
    |--------------------------------------------------------------------------
    */
    /*
    |--------------------------------------------------------------------------
    | Default Lead Magnet Ebook
    |--------------------------------------------------------------------------
    |
    | The slug of the ebook used as the default lead magnet on the homepage
    | and other lead capture forms. Change this to match your primary ebook.
    |
    */
    'default_ebook_slug' => env('EBOOK_DEFAULT_SLUG', 'settleanZ-new-arrival-checklist'),

    'lead_form' => [
        'require_phone' => env('EBOOK_REQUIRE_PHONE', false),
        'require_company' => env('EBOOK_REQUIRE_COMPANY', false),
        'require_country' => env('EBOOK_REQUIRE_COUNTRY', false),
        'consent_required' => true,
        'honeypot_field' => 'website_url',
        'minimum_time_seconds' => 3,
    ],
];
