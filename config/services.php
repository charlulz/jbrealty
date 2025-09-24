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
        'access_token' => env('FLEXMLS_ACCESS_TOKEN', 'bbqc409db06nezg8fdsz0jaw7'),
        'feed_id' => env('FLEXMLS_FEED_ID', 'ddnzarj1vajdzzvcdp2es4tro'),
        'base_url' => env('FLEXMLS_BASE_URL', 'https://replication.sparkapi.com'), // Revert - our key only works with replication
        'replication_url' => env('FLEXMLS_REPLICATION_URL', 'https://replication.sparkapi.com'), // Keep as fallback
    ],

];
