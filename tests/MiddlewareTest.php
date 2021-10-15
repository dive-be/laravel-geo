<?php declare(strict_types=1);

namespace Tests;

use Dive\Geo\Contracts\Detector;
use Dive\Geo\Contracts\Repository;
use Dive\Geo\Middleware\DetectGeoLocation;
use Illuminate\Http\Request;
use Mockery;

it('detects the geo location and updates the repository when repo is empty', function () {
    $detector = Mockery::mock(Detector::class);
    $detector->shouldReceive('detect')->with('127.0.0.1')->andReturn('BE');

    $repo = Mockery::mock(Repository::class);
    $repo->shouldReceive('isEmpty')->andReturn(true);
    $repo->shouldReceive('put');

    (new DetectGeoLocation($repo))
        ->setDetectorResolver(fn () => $detector)
        ->handle(new Request(server: ['REMOTE_ADDR' => '127.0.0.1']), fn () => null);
});

it('skips detection if repository already contains a value', function () {
    $detector = Mockery::mock(Detector::class);
    $detector->shouldNotReceive('detect');

    $repo = Mockery::mock(Repository::class);
    $repo->shouldReceive('isEmpty')->andReturn(false);
    $repo->shouldNotReceive('put');

    $res = (new DetectGeoLocation($repo))
        ->setDetectorResolver(fn () => $detector)
        ->handle(new Request(server: ['REMOTE_ADDR' => '127.0.0.1']), fn () => 'next');

    expect($res)->toBe('next');
});
