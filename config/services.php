<?php
use App\Models\settingsApi;


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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // 'facebook' => [
    //     'client_id' => env('FACEBOOK_CLIENT_ID'),
    //     'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    //     'redirect' => 'http://localhost/e-commerce/Social/schedual-posts/auth/callback',
    //     // 'redirect' => 'https://social.evolvetechsys.com/auth/callback',
    // ],

    'facebook' => [
        'client_id' => config('services.facebook.client_id'),
        'client_secret' => config('services.facebook.client_secret'),
        'redirect' => config('services.facebook.redirect'),
    ],

    'instagram' => [
        'client_id' => env('INSTAGRAM_CLIENT_ID'),
        'client_secret' => env('INSTAGRAM_CLIENT_SECRET'),
        'redirect' => 'https://www.google.com.eg/instagram/public/login/instagram/callback',
    ],

    'twitter' => [
        'client_id' => env('TWITTER_CONSUMER_KEY'),
        'client_secret' => env('TWITTER_CONSUMER_SECRET'),
        'redirect' => 'http://192.168.1.15:8000/auth/twitter/callback',
        // 'redirect' => 'http://localhost/e-commerce/Social/schedual-posts/auth/twitter/callback',
    ],

    'youtube' => [
        'api_key' => env('YOUTUBE_API_KEY'),
        'redirect' => 'http://localhost/e-commerce/Social/schedual-posts/auth/youtube/callback',
    ],
];
