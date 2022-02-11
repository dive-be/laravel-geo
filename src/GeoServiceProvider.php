<?php declare(strict_types=1);

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
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class GeoServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerCommands();
            $this->registerConfig();
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/geo.php', 'geo');

        $this->app->alias('geo', Repository::class);
        $this->app->singleton('geo', static function (Application $app) {
            $config = $app['config']['geo'];

            return (new CookieRepository($config['repos']['cookie']['name']))
                ->setCookieJarResolver(static fn () => $app['cookie'])
                ->setCookieResolver(static fn (string $name) => $app['request']->cookie($name))
                ->setTransformer(
                    class_exists($transformer = (string) $config['transformer']) ? $app->make($transformer) : null
                );
        });

        $this->app->alias('geo.cache', DetectionCache::class);
        $this->app->singleton('geo.cache', static function (Application $app) {
            $config = $app['config']['geo.cache'];

            return new DetectionCache($app->make('cache'), $config['ttl'], $config['tag']);
        });

        $this->app->alias('geo.detector', Detector::class);
        $this->app->singleton('geo.detector', DetectorManager::class);

        $this->callAfterResolving(DetectGeoLocation::class, $this->registerMiddlewareResolvers(...));
        $this->callAfterResolving('router', $this->registerMiddleware(...));
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
            __DIR__ . '/../config/' . $config => $this->app->configPath($config),
        ], 'config');
    }

    private function registerMiddleware(Router $router)
    {
        $router->aliasMiddleware('geo', DetectGeoLocation::class);
    }

    private function registerMiddlewareResolvers(DetectGeoLocation $middleware, Application $app)
    {
        $middleware->setDetectorResolver(static fn () => $app['geo.detector']);
    }
}
