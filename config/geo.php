<?php declare(strict_types=1);

return [
    /**
     * IP address translations can be cached to improve successive lookup times.
     */
    'cache' => [
        'enabled' => false,

        /**
         * Address translations are tagged to only clear them when a clear command is run.
         * Not supported by the "file", "database" and "dynamodb" cache drivers.
         */
        'tag' => 'dive-geo-location',

        /**
         * Time-to-live in seconds.
         */
        'ttl' => 3600,
    ],

    /**
     * Detectors are classes responsible for detecting the geo location of a given IP address.
     *
     * Supported drivers:
     *  - "static" (always translates to the fallback country)
     *  - "maxmind_db" (GeoIP2/GeoLite2 Databases)
     *  - "maxmind_web" (GeoIP2 Precision Web Services)
     *  - "ip2c" (IP 2 Country free web service)
     */
    'detectors' => [
        'driver' => env('GEO_DETECTORS_DRIVER', 'static'),

        'ip2c' => [
            'endpoint' => 'https://api.ip2country.info/ip',
        ],

        'maxmind_db' => [
            'database_path' => storage_path('app/geoip2.mmdb'),
            'license_key' => env('GEO_DETECTORS_MAXMIND_DB_LICENSE_KEY'),
            'url' => 'https://download.maxmind.com/app/geoip_download?edition_id=GeoLite2-Country&license_key=%s&suffix=tar.gz',
        ],

        'maxmind_web' => [
            'account_id' => env('GEO_DETECTORS_MAXMIND_WEB_ACCOUNT_ID'),
            'license_key' => env('GEO_DETECTORS_MAXMIND_WEB_LICENSE_KEY'),
        ],
    ],

    /**
     * A valid ISO alpha-2 country code.
     * Used when an IP address cannot be translated.
     */
    'fallback' => 'BE',

    'repos' => [
        'cookie' => [
            'name' => 'geo', // The cookie's name when set on the users' browsers
        ],
    ],

    /**
     * The valid FQCN of your custom transformer.
     * When set, values retrieved from the repository will be transformed first using this class.
     */
    'transformer' => null,
];
