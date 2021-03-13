<?php

namespace Dive\Geo\Detectors;

use Dive\Geo\Contracts\Detector;
use Illuminate\Config\Repository;

class StaticDetector implements Detector
{
    public function __construct(private Repository $config) {}

    public function detect(string $ipAddress): string
    {
        return $this->config->get('geo.fallback');
    }
}
