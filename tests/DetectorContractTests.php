<?php declare(strict_types=1);

namespace Tests;

use Dive\Geo\Contracts\Detector;
use PHPUnit\Framework\Attributes\Test;

/** @mixin TestCase */
trait DetectorContractTests
{
    protected const string FALLBACK = 'AQ';

    abstract protected function getInstance(): Detector;

    #[Test]
    public function it_can_detect_the_country(): void
    {
        $detector = $this->getInstance();

        $country = $detector->detect('8.8.4.4');

        $this->assertSame(2, strlen($country));
    }

    #[Test]
    public function it_returns_the_fallback_if_none_can_be_detected(): void
    {
        $detector = $this->getInstance();

        $fallback = $detector->detect('172.16.3.4');

        $this->assertSame(self::FALLBACK, $fallback);
    }
}
