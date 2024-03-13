<?php declare(strict_types=1);

namespace Tests;

use Dive\Geo\Contracts\Detector;
use Dive\Geo\Contracts\Repository;
use Dive\Geo\Middleware\DetectGeoLocation;
use Illuminate\Http\Request;
use Mockery;
use PHPUnit\Framework\Attributes\Test;

final class MiddlewareTest extends TestCase
{
    #[Test]
    public function skips_detection_if_repository_already_contains_a_value(): void
    {
        $detector = Mockery::mock(Detector::class);
        $detector->shouldNotReceive('detect');

        $repository = Mockery::mock(Repository::class);
        $repository->shouldReceive('isEmpty')->andReturn(false);
        $repository->shouldNotReceive('put');

        $response = (new DetectGeoLocation($repository))
            ->setDetectorResolver(static fn () => $detector)
            ->handle(new Request(server: ['REMOTE_ADDR' => '127.0.0.1']), static fn () => 'next');

        $this->assertSame('next', $response);
    }
}
