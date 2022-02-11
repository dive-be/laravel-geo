<?php declare(strict_types=1);

namespace Tests;

use Dive\Geo\Contracts\Detector;
use Dive\Geo\Detectors\IP2CountryDetector;
use Dive\Geo\Detectors\MaxMindDatabaseDetector;
use Dive\Geo\Detectors\StaticDetector;
use GeoIp2\Database\Reader;

beforeEach(function () {
    $this->fallback = 'AQ';
});

it('can detect the country', function (Detector $detector) {
    expect($detector->detect('8.8.4.4'))->toHaveLength(2)->toBeString();
})->with('detectors');

it('returns the fallback country if none can be detected', function (Detector $detector) {
    expect($detector->detect('172.16.3.4'))->toBe($this->fallback);
})->with('detectors');

dataset('detectors', [
    fn () => new MaxMindDatabaseDetector(new Reader(__DIR__ . '/db/geoip2.mmdb'), test()->fallback),
    fn () => new IP2CountryDetector(config('geo.detectors.ip2c.endpoint'), test()->fallback),
    fn () => new StaticDetector(test()->fallback),
]);
