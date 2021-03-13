<?php

namespace Dive\Geo\Contracts;

interface Transformer
{
    public function transform(string $countryCode);
}
