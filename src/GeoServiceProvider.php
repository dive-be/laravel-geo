<?php

namespace Dive\Geo;

use Dive\Geo\Cache\DetectionCache;
use Dive\Geo\Commands\ClearCacheCommand;
use Dive\Geo\Commands\InstallPackageCommand;
use Dive\Geo\Contracts\Detector;
use Dive\Geo\Contracts\Repository;
use Dive\Geo\Detectors\DetectorManager;
use Dive\Geo\Middleware\DetectGeoLocation;
use Dive\Geo\Repositories\CookieRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class GeoServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerCommands();
            $this->registerConfig();
        }

        $this->app->make('router')->aliasMiddleware('geo', DetectGeoLocation::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/geo.php', 'geo');

        $this->app->afterResolving(DetectGeoLocation::class, static function ($middleware, Application $app) {
            $middleware->setDetectorResolver(fn () => $app->make(Detector::class));
        });

        $this->app->singleton(DetectionCache::class, static function (Application $app) {
            $config = $app->make('config')->get('geo.cache');

            return new DetectionCache($app->make('cache'), $config['ttl'], $config['tag']);
        });

        $this->app->singleton(Repository::class, static function (Application $app) {
            $config = $app->make('config')->get('geo');

            return (new CookieRepository($config['repos']['cookie']['name']))
                ->setCookieJarResolver(fn () => $app->make('cookie'))
                ->setCookieResolver(fn (string $name) => $app->make('request')->cookie($name))
                ->setTransformer(is_string($config['transformer']) ? $app->make($config['transformer']) : null);
        });

        $this->app->singleton(Detector::class, DetectorManager::class);
    }

    private function registerCommands()
    {
        $this->commands([
            ClearCacheCommand::class,
            InstallPackageCommand::class,
        ]);
    }

    private function registerConfig()
    {
        $config = 'geo.php';

        $this->publishes([
            __DIR__.'/../config/'.$config => $this->app->configPath($config),
        ], 'config');
    }
}
