<?php

//generate config for midtrans env
return [
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sandbox' => env('MIDTRANS_IS_SANDBOX', true),
    'is_debug' => env('MIDTRANS_IS_DEBUG', false),
    'base_url' => env('MIDTRANS_BASE_URL', 'https://api.sandbox.midtrans.com/v2/'),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
];
