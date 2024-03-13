<?php declare(strict_types=1);

namespace Tests;

use Dive\Geo\Cache\DetectionCache;
use PHPUnit\Framework\Attributes\Test;

final class ClearCacheTest extends TestCase
{
    #[Test]
    public function it_can_clear_the_geo_cache(): void
    {
        $this->mock(DetectionCache::class)->shouldReceive('flush');

        $this->artisan('geo:clear')->assertExitCode(0)->expectsOutput('🔥  Geo cache has been cleared.');
    }
}
