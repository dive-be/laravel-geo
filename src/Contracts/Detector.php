<?php

namespace Dive\Geo\Contracts;

interface Detector
{
    public function detect(string $ipAddress): string;
}
