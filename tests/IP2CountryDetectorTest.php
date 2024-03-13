<?php declare(strict_types=1);

namespace Tests;

use Dive\Geo\Contracts\Detector;
use Dive\Geo\Detectors\IP2CountryDetector;
use PHPUnit\Framework\Attributes\Group;

#[Group('network')]
final class IP2CountryDetectorTest extends TestCase
{
    use DetectorContractTests;

    protected function getInstance(): Detector
    {
        return new IP2CountryDetector(
            $this->app['config']['geo.detectors.ip2c.endpoint'],
            self::FALLBACK,
        );
    }
}
