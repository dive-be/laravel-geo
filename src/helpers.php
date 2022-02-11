<?php declare(strict_types=1);

if (! function_exists('geo')) {
    function geo(?string $countryCode = null)
    {
        if (is_null($countryCode)) {
            return app('geo');
        }

        app('geo')->put($countryCode);
    }
}
