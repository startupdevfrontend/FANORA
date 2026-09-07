<?php

// Payment gateway abstraction configuration.
//
// sandbox  -> no real charges, provider is a stub used only in local/dev/test
// production -> real provider integration

return [

    'env' => env('PAYMENT_ENV', 'sandbox'),

    'provider' => env('PAYMENT_PROVIDER'),

    'public_key' => env('PAYMENT_PUBLIC_KEY', ''),

    'secret_key' => env('PAYMENT_SECRET_KEY', ''),

    'webhook_secret' => env('PAYMENT_WEBHOOK_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Gateway fee assumptions (configured by the provider contract).
    |--------------------------------------------------------------------------
    | In sandbox mode we use a flat reference rate so the financial flows can be
    | exercised end to end. In production the real gateway fee must be reported
    | by the provider transaction payload.
    */
    'reference_gateway_fee_rate' => (float) env('PAYMENT_GATEWAY_FEE_RATE', 4.99),
];