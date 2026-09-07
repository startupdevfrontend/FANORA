<?php

// Platform business rules for FANORA.
// All values below are manageable via environment variables.

return [

    /*
    |--------------------------------------------------------------------------
    | Commission / take rate
    |--------------------------------------------------------------------------
    | Percentage (integer) that FANORA keeps from each subscription charge.
    */
    'commission_rate' => (int) env('FANORA_COMMISSION_RATE', 20),

    /*
    |--------------------------------------------------------------------------
    | Allowed subscription prices (in cents)
    |--------------------------------------------------------------------------
    */
    'allowed_subscription_prices' => collect(explode(',', (string) env('FANORA_ALLOWED_SUBSCRIPTION_PRICES', '990,1990,2990')))
        ->map(fn ($v) => (int) trim($v))
        ->filter()
        ->values()
        ->all(),

    'min_subscription_price' => (int) env('FANORA_MIN_SUBSCRIPTION_PRICE', 990),

    'max_subscription_price' => (int) env('FANORA_MAX_SUBSCRIPTION_PRICE', 2990),

    /*
    |--------------------------------------------------------------------------
    | Media upload constraints
    |--------------------------------------------------------------------------
    */
    'media' => [
        'image_max_bytes' => (int) env('FANORA_IMAGE_MAX_KB', 10240) * 1024,
        'video_max_bytes' => (int) env('FANORA_VIDEO_MAX_MB', 256) * 1024 * 1024,
        'image_mimes' => ['jpeg', 'jpg', 'png', 'webp', 'gif'],
        'video_mimes' => ['mp4', 'webm', 'mov'],
        'sign_url_seconds' => (int) env('FANORA_SIGN_URL_SECONDS', 3600),
    ],

    /*
    |--------------------------------------------------------------------------
    | PWA
    |--------------------------------------------------------------------------
    */
    'pwa_enabled' => (bool) env('PWA_ENABLED', true),
];