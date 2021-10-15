<?php declare(strict_types=1);

namespace Dive\Geo\Contracts;

interface Transformer
{
    public function transform(string $countryCode);
}
