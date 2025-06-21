<?php

return [
    'paths' => ['api/*', 'broadcasting/*', 'sanctum/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['https://autogest-carwash-berrios-production.up.railway.app'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];