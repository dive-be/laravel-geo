<?php

namespace Dive\Geo\Detectors;

use Dive\Geo\Cache\CacheDetectorProxy;
use Dive\Geo\Cache\DetectionCache;
use Dive\Geo\Contracts\Detector;
use GeoIp2\Database\Reader;
use GeoIp2\WebService\Client;
use Illuminate\Support\Manager;

class DetectorManager extends Manager implements Detector
{
    public function getDefaultDriver()
    {
        return $this->config->get('geo.detectors.driver');
    }

    protected function createMaxmindDbDriver(): Detector
    {
        $reader = new Reader($this->config->get('geo.detectors.maxmind_db.database_path'));
        $detector = (new MaxMindDatabaseDetector($reader, $this->config->get('geo.fallback')))
            ->setLogResolver(fn () => $this->container->make('log'));

        return $this->proxy($detector);
    }

    protected function createMaxmindWebDriver(): Detector
    {
        $config = $this->config->get('geo.detectors.maxmind_web');
        $client = new Client($config['account_id'], $config['license_key']);
        $detector = (new MaxMindWebDetector($client, $this->config->get('geo.fallback')))
            ->setLogResolver(fn () => $this->container->make('log'));

        return $this->proxy($detector);
    }

    protected function createStaticDriver(): Detector
    {
        return new StaticDetector($this->config->get('geo.fallback'));
    }

    private function proxy(Detector $detector): Detector
    {
        if (! $this->config->get('geo.cache.enabled')) {
            return $detector;
        }

        return new CacheDetectorProxy($this->container->make(DetectionCache::class), $detector);
    }

    // region CONTRACT

    public function detect(string $ipAddress): string
    {
        return $this->driver()->detect($ipAddress);
    }

    // endregion
}
