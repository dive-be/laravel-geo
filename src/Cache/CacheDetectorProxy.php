<?php declare(strict_types=1);

namespace Dive\Geo\Cache;

use Dive\Geo\Contracts\Detector;

class CacheDetectorProxy implements Detector
{
    public function __construct(
        private DetectionCache $cache,
        private Detector $detector,
    ) {}

    public function detect(string $ipAddress): string
    {
        return $this->cache->remember($ipAddress, fn () => $this->detector->detect($ipAddress));
    }
}
