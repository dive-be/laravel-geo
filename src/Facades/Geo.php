<?php declare(strict_types=1);

namespace Dive\Geo\Facades;

use Dive\Geo\Repositories\InMemoryRepository;
use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed get()
 * @method static bool  isEmpty()
 * @method static bool  isNotEmpty()
 * @method static void  put(string $countryCode)
 */
final class Geo extends Facade
{
    public static function fake(): InMemoryRepository
    {
        $transformer = self::$app['config']['geo.transformer'];

        self::swap($fake = (new InMemoryRepository())->setTransformer(
            class_exists($transformer) ? self::$app->make($transformer) : null
        ));

        return $fake;
    }

    protected static function getFacadeAccessor(): string
    {
        return 'geo';
    }
}
