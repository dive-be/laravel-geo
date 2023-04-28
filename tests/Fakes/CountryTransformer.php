<?php declare(strict_types=1);

namespace Tests\Fakes;

use Dive\Geo\Contracts\Transformer;

final readonly class CountryTransformer implements Transformer
{
    public function transform(string $countryCode): Country
    {
        return new Country($countryCode);
    }
}
