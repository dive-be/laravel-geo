<?php declare(strict_types=1);

namespace Tests;

use Dive\Geo\Cache\DetectionCache;

it('can clear the geo cache', function () {
    $this->mock(DetectionCache::class)->shouldReceive('flush');

    $this->artisan('geo:clear')->assertExitCode(0)->expectsOutput('🔥  Geo cache has been cleared.');
});
