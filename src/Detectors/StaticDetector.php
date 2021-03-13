<?php

namespace Dive\Geo\Detectors;

use Dive\Geo\Contracts\Detector;

class StaticDetector implements Detector
{
    public function __construct(private string $fallback) {}

    public function detect(string $ipAddress): string
    {
        return $this->fallback;
    }
}
