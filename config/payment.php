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

    'asaas' => [
        'sandbox_url' => env('ASAAS_SANDBOX_URL', 'https://sandbox.asaas.com/api/v3'),
        'production_url' => env('ASAAS_PRODUCTION_URL', 'https://www.asaas.com/api/v3'),
        'base_url' => env('PAYMENT_ENV', 'sandbox') === 'production'
            ? env('ASAAS_PRODUCTION_URL', 'https://www.asaas.com/api/v3')
            : env('ASAAS_SANDBOX_URL', 'https://sandbox.asaas.com/api/v3'),
        'api_key' => env('PAYMENT_SECRET_KEY', ''),
        'webhook_token' => env('ASAAS_WEBHOOK_TOKEN', env('PAYMENT_WEBHOOK_SECRET', '')),
        'timeout' => (int) env('ASAAS_TIMEOUT', 15),
        'connect_timeout' => (int) env('ASAAS_CONNECT_TIMEOUT', 5),
    ],
];