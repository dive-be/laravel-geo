<?php declare(strict_types=1);

namespace Tests;

use Dive\Geo\Cache\DetectionCache;
use Illuminate\Cache\CacheManager;
use Illuminate\Cache\Repository;
use Mockery;

it('news up a tagged instance if a tag has been set', function () {
    $cache = Mockery::mock(CacheManager::class);
    $cache->shouldReceive('tags')->andReturn(Mockery::mock(Repository::class));

    new DetectionCache($cache, 123, 'test-tag');

    $cache->shouldReceive('store')->andReturn(Mockery::mock(Repository::class));

    new DetectionCache($cache, 123, null);
});

it('can flush the cache', function () {
    $cache = Mockery::mock(CacheManager::class);
    $store = Mockery::mock(Repository::class);
    $cache->shouldReceive('tags')->andReturn($store);
    $store->shouldReceive('flush');

    $detection = new DetectionCache($cache, 123, 'test-tag');

    $detection->flush();
});

it('can remember previous lookups', function () {
    $cache = Mockery::mock(CacheManager::class);
    $store = Mockery::mock(Repository::class);
    $cache->shouldReceive('tags')->andReturn($store);
    $store->shouldReceive('remember')->withArgs(['127.0.0.1', 123, $callback = fn () => 'TR'])->andReturn('TR');

    $detection = new DetectionCache($cache, 123, 'test-tag');

    $detection->remember('127.0.0.1', $callback);
});
