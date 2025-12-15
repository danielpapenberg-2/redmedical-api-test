<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'red_portal' => [
        'enabled' => env('RED_PORTAL_ENABLED', false),
        'base_url' => env('RED_PORTAL_BASE_URL', 'https://localhost:3000'),
        'client_id' => env('RED_PORTAL_CLIENT_ID', 'Fun'),
        'client_secret' => env('RED_PORTAL_CLIENT_SECRET', '=work@red'),
        'ssl_cert' => env('RED_PORTAL_SSL_CERT', base_path('ssl_cert.pem')),
    ],
];
