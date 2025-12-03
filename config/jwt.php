<?php

return [
    /*
    |--------------------------------------------------------------------------
    | JWT Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for JWT token expiration times and settings.
    |
    */

    'ttl' => [
        'access_token' => (int) env('JWT_ACCESS_TTL', 60), // minutes
        'refresh_token' => (int) env('JWT_REFRESH_TTL', 21600), // minutes (15 days)
    ],

    'token_type' => 'bearer',
];
