<?php

namespace Dive\Geo\Facades;

use Dive\Geo\Repositories\InMemoryRepository;
use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed get()
 * @method static bool isEmpty()
 * @method static bool isNotEmpty()
 * @method static void put(string $countryCode)
 */
class Geo extends Facade
{
    public static function fake(): InMemoryRepository
    {
        $config = static::$app['config']['geo'];

        static::swap($fake = (new InMemoryRepository($config['fallback']))->setTransformer(
            class_exists($config['transformer']) ? static::$app->make($config['transformer']) : null
        ));

        return $fake;
    }

    protected static function getFacadeAccessor()
    {
        return 'geo';
    }
}
