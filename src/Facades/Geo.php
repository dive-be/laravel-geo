<?php

namespace Dive\Geo\Facades;

use Dive\Geo\Contracts\Repository;
use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed get()
 * @method static bool isEmpty()
 * @method static bool isNotEmpty()
 * @method static void put(string $countryCode)
 */
class Geo extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Repository::class;
    }
}
