<?php declare(strict_types=1);

namespace Tests;

use Dive\Geo\Contracts\Detector;
use Dive\Geo\Detectors\StaticDetector;

final class StaticDetectorTest extends TestCase
{
    use DetectorContractTests;

    protected function getInstance(): Detector
    {
        return new StaticDetector(self::FALLBACK);
    }
}
