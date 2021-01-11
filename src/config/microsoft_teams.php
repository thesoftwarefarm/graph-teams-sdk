<?php

return [
    'tenant_id' => env('MICROSOFT_TEAMS_TENANT_ID', ''),
    'client_id' => env('MICROSOFT_TEAMS_CLIENT_ID', ''),
    'client_secret' => env('MICROSOFT_TEAMS_SECRET', ''),
    'base_url' => 'https://graph.microsoft.com',
    'api_version' => 'v1.0'
];