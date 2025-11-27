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
     * Partner platforms that are allowed to talk to adwallet. Each entry maps
     * a partner key (e.g. "escort") to its shared API token.
     *
     * Example env:
     * ADWALLET_PARTNER_ESCORT_TOKEN=...
     * ADWALLET_PARTNER_ESCORT_WEBHOOK_SECRET=...
     * ADWALLET_PARTNER_ESCORT_SSO_SECRET=...
     * ADWALLET_PARTNER_ESCORT_STRIPE_EMAIL_DOMAIN=escortxxx.ch (optional)
     *
     * stripe_email_domain: Optional. Domain used for Stripe customer emails.
     *                      Format: wallet{account_id}@{stripe_email_domain}
     *                      If not set, will try to extract from Partner model's website_url.
     *                      Falls back to 'adwallet.ch' if neither is available.
     */
    'partners' => [
        'escort' => [
            'api_token' => env('ADWALLET_PARTNER_ESCORT_TOKEN'),
            'webhook_secret' => env('ADWALLET_PARTNER_ESCORT_WEBHOOK_SECRET'),
            'sso_secret' => env('ADWALLET_PARTNER_ESCORT_SSO_SECRET'),
            'stripe_email_domain' => env('ADWALLET_PARTNER_ESCORT_STRIPE_EMAIL_DOMAIN', 'escortxxx.ch'),
        ],
    ],

    'stripe' => [
        'secret' => env('STRIPE_SECRET'),
        'public' => env('STRIPE_PUBLIC_KEY'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

];
