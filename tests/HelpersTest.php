<?php declare(strict_types=1);

namespace Tests;

use Dive\Geo\Facades\Geo;

test('geo can put a new country', function () {
    expect(Geo::get())->not->toBe($iso = 'CZ');

    geo($iso);

    expect(Geo::get())->toBe($iso);
});

test('geo can retrieve an instance', function () {
    expect(geo())->toBe(app('geo'));
});
