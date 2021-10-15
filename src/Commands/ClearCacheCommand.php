<?php declare(strict_types=1);

namespace Dive\Geo\Commands;

use Dive\Geo\Cache\DetectionCache;
use Illuminate\Console\Command;

class ClearCacheCommand extends Command
{
    protected $description = 'Clear the cache of detected geo locations.';

    protected $signature = 'geo:clear';

    public function handle(DetectionCache $cache): int
    {
        $cache->flush();

        $this->info('🔥  Geo cache has been cleared.');

        return self::SUCCESS;
    }
}
