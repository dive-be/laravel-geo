<?php declare(strict_types=1);

namespace Tests;

use Dive\Geo\Cache\CacheDetectorProxy;
use Dive\Geo\Cache\DetectionCache;
use Dive\Geo\Contracts\Detector;
use Mockery;

it('can proxy a real detector', function () {
    $cache = Mockery::mock(DetectionCache::class);
    $cache->shouldReceive('remember')->withArgs(fn ($ip, $callback) => $ip === '127.0.0.1' && is_callable($callback));
    $detector = Mockery::mock(Detector::class);

    (new CacheDetectorProxy($cache, $detector))->detect('127.0.0.1');
});
