<?php declare(strict_types=1);

namespace Dive\Geo\Detectors;

use Dive\Geo\Cache\CacheDetectorProxy;
use Dive\Geo\Contracts\Detector;
use GeoIp2\Database\Reader;
use GeoIp2\WebService\Client;
use Illuminate\Support\Manager;

class DetectorManager extends Manager implements Detector
{
    public function config(string $key): array|bool|int|string
    {
        return $this->config->get("geo.{$key}");
    }

    public function fallback(): string
    {
        return $this->config(__FUNCTION__);
    }

    public function getDefaultDriver(): string
    {
        return $this->config('detectors.driver');
    }

    protected function createIp2CDriver(): Detector
    {
        return $this->proxy(
            new IP2CountryDetector($this->config('detectors.ip2c.endpoint'), $this->fallback())
        );
    }

    protected function createMaxmindDbDriver(): Detector
    {
        $reader = new Reader($this->config('detectors.maxmind_db.database_path'));
        $detector = (new MaxMindDatabaseDetector($reader, $this->fallback()))
            ->setLogResolver(fn () => $this->container->make('log'));

        return $this->proxy($detector);
    }

    protected function createMaxmindWebDriver(): Detector
    {
        $config = $this->config('detectors.maxmind_web');
        $client = new Client($config['account_id'], $config['license_key']);
        $detector = (new MaxMindWebDetector($client, $this->fallback()))
            ->setLogResolver(fn () => $this->container->make('log'));

        return $this->proxy($detector);
    }

    protected function createStaticDriver(): Detector
    {
        return new StaticDetector($this->fallback());
    }

    private function proxy(Detector $detector): Detector
    {
        if (! $this->config('cache.enabled')) {
            return $detector;
        }

        return new CacheDetectorProxy($this->container['geo.cache'], $detector);
    }

    // region CONTRACT

    public function detect(string $ipAddress): string
    {
        return $this->driver()->detect($ipAddress);
    }

    // endregion
}
