<?php

use Dive\Geo\Contracts\Repository;

if (! function_exists('geo')) {
    function geo(?string $countryCode = null): ?Repository
    {
        if (is_null($countryCode)) {
            return app(Repository::class);
        }

        return app(Repository::class)->put($countryCode);
    }
}
