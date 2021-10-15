<?php declare(strict_types=1);

namespace Tests;

use Dive\Geo\Cache\DetectionCache;
use function Pest\Laravel\artisan;
use function Pest\Laravel\mock;

it('can clear the geo cache', function () {
    mock(DetectionCache::class)->shouldReceive('flush');

    artisan('geo:clear')->assertExitCode(0)->expectsOutput('🔥  Geo cache has been cleared.');
});
