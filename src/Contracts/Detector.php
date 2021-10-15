<?php declare(strict_types=1);

namespace Dive\Geo\Contracts;

interface Detector
{
    public function detect(string $ipAddress): string;
}
