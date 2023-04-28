<?php declare(strict_types=1);

namespace Dive\Geo\Cache;

use Illuminate\Cache\CacheManager;
use Illuminate\Cache\Repository;

class DetectionCache
{
    private readonly Repository $cache;

    private readonly int $ttl;

    public function __construct(CacheManager $cache, int $ttl, ?string $tag)
    {
        $this->cache = is_string($tag) ? $cache->tags($tag) : $cache->store();
        $this->ttl = $ttl;
    }

    public function flush(): void
    {
        $this->cache->flush();
    }

    public function remember(string $ipAddress, \Closure $callback): string
    {
        return $this->cache->remember($ipAddress, $this->ttl, $callback);
    }
}
