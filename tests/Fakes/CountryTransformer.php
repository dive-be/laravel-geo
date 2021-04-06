<?php

namespace Tests\Fakes;

use Dive\Geo\Contracts\Transformer;

class CountryTransformer implements Transformer
{
    public function transform(string $countryCode)
    {
        return new Country($countryCode);
    }
}
