<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | PayPal
    |--------------------------------------------------------------------------
    |
    | Used by PaymentService to create and capture PayPal orders.
    |
    */

    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID', 'AYDoGkmudfhlWcn_hGY_Cr-_wMC0aMh75psI3V6QFkGf53CaaFIuiZVPijGuAWsoTxJ1SU_awuTNSsGk'),
        'secret'    => env('PAYPAL_SECRET', 'EOxkkjfLk2mjNeBiQ5iltPVKg_IA7ELv6q8f4_GZGI4tQD34kL-YJaVBSxbn7eDnaJy1zfickF8bx70X'),
        'base_url'  => env('PAYPAL_BASE_URL', 'https://api-m.sandbox.paypal.com'),
    ],

];
