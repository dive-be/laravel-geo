<?php declare(strict_types=1);

namespace Dive\Geo\Facades;

use Dive\Geo\Detectors\StaticDetector;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void extend($driver, \Closure $callback)
 */
final class Detector extends Facade
{
    public static function fake(): StaticDetector
    {
        self::swap($fake = new StaticDetector(self::$app['config']['geo.fallback']));

        return $fake;
    }

    protected static function getFacadeAccessor(): string
    {
        return 'geo.detector';
    }
}
