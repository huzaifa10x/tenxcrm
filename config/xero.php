<?php
return [
    'client_id'     => env('XERO_CLIENT_ID', ''),
    'client_secret' => env('XERO_CLIENT_SECRET', ''),
    'redirect_uri'  => env('XERO_REDIRECT_URI', env('APP_URL') . '/xero/callback'),
    'tenant_id'     => env('XERO_TENANT_ID', 'a7e84e77-0b52-4b1a-82b5-faab6f7f6fc4'),
    'refresh_token' => env('XERO_REFRESH_TOKEN', ''),
    'sync' => [
        'queue'                => env('XERO_SYNC_QUEUE', 'xero'),
        'incremental_schedule' => env('XERO_SYNC_SCHEDULE', '0 */4 * * *'),
    ],
];