<?php declare(strict_types=1);

namespace Tests;

use Dive\Geo\Contracts\Detector;
use Dive\Geo\Detectors\MaxMindDatabaseDetector;
use GeoIp2\Database\Reader;

final class MaxMindDatabaseDetectorTest extends TestCase
{
    use DetectorContractTests;

    protected function getInstance(): Detector
    {
        return new MaxMindDatabaseDetector(
            new Reader(__DIR__ . '/db/geoip2.mmdb'),
            self::FALLBACK,
        );
    }
}
