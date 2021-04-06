<?php

namespace Tests;

use Dive\Geo\Detectors\StaticDetector;

it('always returns the set fallback value', function (string $ipAddress) {
    $detector = new StaticDetector('DE');

    expect($detector->detect($ipAddress))->toBe('DE');
})->with([
    '127.0.0.1',
    '1.1.1.1',
    '213.219.142.235',
]);
