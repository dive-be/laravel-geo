<?php declare(strict_types=1);

namespace Dive\Geo\Facades;

use Dive\Geo\Detectors\StaticDetector;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void extend($driver, \Closure $callback)
 */
class Detector extends Facade
{
    public static function fake(): StaticDetector
    {
        static::swap($fake = new StaticDetector(static::$app['config']['geo.fallback']));

        return $fake;
    }

    protected static function getFacadeAccessor(): string
    {
        return 'geo.detector';
    }
}
