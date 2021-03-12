<?php

namespace Dive\Skeleton\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Dive\Skeleton\Skeleton
 */
class Skeleton extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'skeleton';
    }
}
