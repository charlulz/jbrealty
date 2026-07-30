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

    'gohighlevel' => [
        'api_token' => env('GHL_API_TOKEN'),
        'location_id' => env('GHL_LOCATION_ID', '7YwBmZCIpKXv2NPxltud'),
        'base_url' => env('GHL_BASE_URL', 'https://rest.gohighlevel.com/v1'),
    ],

    'flexmls' => [
        // ImagineMLS "Broker or Agent's Own Data" feed (user Imagine.20271)
        'access_token' => env('FLEXMLS_ACCESS_TOKEN'),
        'feed_id' => env('FLEXMLS_FEED_ID'),
        'base_url' => env('FLEXMLS_BASE_URL', 'https://replication.sparkapi.com'),
        'replication_url' => env('FLEXMLS_REPLICATION_URL', 'https://replication.sparkapi.com'),
        // RESO Web API v3 (recommended for new integrations)
        'reso_url' => env('FLEXMLS_RESO_URL', 'https://replication.sparkapi.com/Version/3/Reso/OData'),
        // Own-data plans should use /v1/my/listings; IDX plans use /v1/listings
        'listings_endpoint' => env('FLEXMLS_LISTINGS_ENDPOINT', '/v1/my/listings'),
        // User imported listings are attributed to; falls back to the first user
        'listing_agent_id' => env('FLEXMLS_LISTING_AGENT_ID'),
    ],

];
