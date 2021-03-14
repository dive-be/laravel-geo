<?php

return [
    'cache' => [
        'enabled' => false,
        'tag' => 'dive-geo-location',
        'ttl' => 3600,
    ],

    'detectors' => [
        'driver' => env('GEO_DETECTORS_DRIVER', 'static'),
    ],

    'fallback' => env('GEO_FALLBACK', 'BE'),

    'repos' => [
        'cookie' => [
            'name' =>  env('GEO_REPOS_COOKIE_NAME', 'geo'),
        ],
    ],

    'transformer' => null,
];
