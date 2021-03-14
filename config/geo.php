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

    'fallback' => 'BE',

    'repos' => [
        'cookie' => [
            'name' => 'geo',
        ],
    ],

    'transformer' => null,
];
