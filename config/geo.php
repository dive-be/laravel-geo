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
    ],

    'fallback' => 'BE',

    'repos' => [
        'cookie' => [
            'name' => 'geo',
        ],
    ],

    'transformer' => null,
];
