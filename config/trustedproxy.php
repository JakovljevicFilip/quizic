<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Trusted Proxy IP Addresses
    |--------------------------------------------------------------------------
    |
    | If your application is behind a reverse proxy, you may need to specify
    | the proxy's IP addresses or CIDR blocks here. Use TRUSTED_PROXIES in
    | your .env to set this at runtime (comma-separated).
    |
    */
    'proxies' => env('TRUSTED_PROXIES')
        ? array_map('trim', explode(',', env('TRUSTED_PROXIES')))
        : null,

    /*
    |--------------------------------------------------------------------------
    | Trusted Proxy Headers
    |--------------------------------------------------------------------------
    */
    'headers' => Illuminate\Http\Request::HEADER_X_FORWARDED_ALL,
];
