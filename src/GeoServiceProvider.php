<?php

namespace Dive\Geo;

use Dive\Geo\Cache\DetectionCache;
use Dive\Geo\Commands\ClearCacheCommand;
use Dive\Geo\Commands\InstallPackageCommand;
use Dive\Geo\Commands\UpdateDatabaseCommand;
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

        $this->app->alias(Repository::class, 'geo');
        $this->app->singleton(Repository::class, static function (Application $app) {
            $config = $app->make('config')->get('geo');

            return (new CookieRepository($config['repos']['cookie']['name'], $config['fallback']))
                ->setCookieJarResolver(fn () => $app->make('cookie'))
                ->setCookieResolver(fn (string $name) => $app->make('request')->cookie($name))
                ->setTransformer(class_exists($transformer = $config['transformer']) ? $app->make($transformer) : null);
        });

        $this->app->alias(DetectorManager::class, 'geo.detector');
        $this->app->alias(DetectorManager::class, Detector::class);
        $this->app->singleton(DetectorManager::class);
    }

    private function registerCommands()
    {
        $this->commands([
            ClearCacheCommand::class,
            InstallPackageCommand::class,
            UpdateDatabaseCommand::class,
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
