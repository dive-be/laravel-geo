<?php

namespace Tests;

use Dive\Geo\GeoServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [GeoServiceProvider::class];
    }
}
