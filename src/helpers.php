<?php

use Dive\Geo\Contracts\Repository;

if (! function_exists('geo')) {
    function geo(?string $countryCode = null)
    {
        if (is_null($countryCode)) {
            return app(Repository::class);
        }

        app(Repository::class)->put($countryCode);
    }
}
