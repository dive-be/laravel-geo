<?php

namespace Dive\Geo\Detectors;

use Dive\Geo\Contracts\Detector;
use Illuminate\Support\Manager;

class DetectorManager extends Manager implements Detector
{
    public function createStaticDriver(): StaticDetector
    {
        return new StaticDetector($this->config);
    }

    public function detect(string $ipAddress): string
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    public function getDefaultDriver()
    {
        return $this->config->get('geo.detectors.driver');
    }
}
