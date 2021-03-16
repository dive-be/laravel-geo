<?php

return [
    'cache' => [
        'enabled' => false,
        'tag' => 'dive-geo-location',
        'ttl' => 3600,
    ],

    'detectors' => [
        'driver' => env('GEO_DETECTORS_DRIVER', 'static'),

        'maxmind_db' => [
            'database_path' => storage_path('app/geo/geoip2.mmdb'),
        ],

        'maxmind_web' => [
            'account_id' => env('GEO_DETECTORS_MAXMIND_WEB_ACCOUNT_ID'),
            'license_key' => env('GEO_DETECTORS_MAXMIND_WEB_LICENSE_KEY'),
        ],
    ],

    'fallback' => 'BE',

    'repos' => [
        'cookie' => [
            'name' => 'geo',
        ],
    ],

    'transformer' => null,
];
