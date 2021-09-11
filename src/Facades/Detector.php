<?php

namespace Dive\Geo\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void extend($driver, \Closure $callback)
 */
class Detector extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'geo.detector';
    }
}
