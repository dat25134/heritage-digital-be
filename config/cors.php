<?php

// Calculate allowed origins
$corsOrigins = array_filter([
    env('FRONTEND_URL'),
    ...explode(',', env('CORS_ALLOWED_ORIGINS', '')),
]);

$supportsCredentials = env('CORS_SUPPORTS_CREDENTIALS', true);

// If supports_credentials is true, we cannot use wildcard '*'
// Return specific origins or empty array (which will be handled by middleware)
if ($supportsCredentials && !empty($corsOrigins)) {
    $allowedOrigins = $corsOrigins;
} else {
    // If no specific origins and credentials disabled, allow all
    $allowedOrigins = empty($corsOrigins) ? ['*'] : $corsOrigins;
}

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => $allowedOrigins,

    'allowed_origins_patterns' => [],

    'allowed_headers' => [
        'Accept',
        'Authorization',
        'Content-Type',
        'X-Requested-With',
        'X-CSRF-TOKEN',
        'X-XSRF-TOKEN',
        'Origin',
        'Access-Control-Request-Method',
        'Access-Control-Request-Headers',
    ],

    'exposed_headers' => [
        'Authorization',
        'X-Total-Count',
        'X-Page',
        'X-Per-Page',
    ],

    'max_age' => env('CORS_MAX_AGE', 86400),

    'supports_credentials' => $supportsCredentials,

];
