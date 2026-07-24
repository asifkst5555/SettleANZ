<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Verification Driver
    |--------------------------------------------------------------------------
    |
    | Supported: "math", "recaptcha", "turnstile", "hcaptcha"
    |
    */
    'driver' => env('VERIFICATION_DRIVER', 'math'),

    /*
    |--------------------------------------------------------------------------
    | Verification Enabled State
    |--------------------------------------------------------------------------
    |
    | Enable/disable human verification globally for public forms.
    |
    */
    'enabled' => env('VERIFICATION_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Driver Specific Configurations
    |--------------------------------------------------------------------------
    |
    | API Keys and settings for the various captcha providers.
    |
    */
    'drivers' => [
        'recaptcha' => [
            'site_key' => env('RECAPTCHA_SITE_KEY'),
            'secret_key' => env('RECAPTCHA_SECRET_KEY'),
        ],
        'turnstile' => [
            'site_key' => env('TURNSTILE_SITE_KEY'),
            'secret_key' => env('TURNSTILE_SECRET_KEY'),
        ],
        'hcaptcha' => [
            'site_key' => env('HCAPTCHA_SITE_KEY'),
            'secret_key' => env('HCAPTCHA_SECRET_KEY'),
        ],
    ],
];
