<?php

namespace Dive\Geo;

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

        $this->app->afterResolving(DetectGeoLocation::class, fn (DetectGeoLocation $middleware, Application $app) =>
            $middleware->setDetectorResolver(fn () => $app->make(Detector::class)));

        $this->app->singleton(Repository::class, static function (Application $app) {
            $transformer = $app->make('config')->get('geo.transformer');

            return $app->make(CookieRepository::class)
                ->setCookieJarResolver(fn () => $app->make('cookie'))
                ->setCookieResolver(fn (string $name) => $app->make('request')->cookie($name))
                ->setTransformer(is_string($transformer) ? $app->make($transformer) : null);
        });

        $this->app->singleton(Detector::class, DetectorManager::class);
    }

    private function registerCommands()
    {
        $this->commands([
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
