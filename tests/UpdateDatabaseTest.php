<?php declare(strict_types=1);

namespace Tests;

use function Pest\Laravel\artisan;

it('updates the maxmind_db driver only', function (string $driver) {
    config()->set('geo.detectors.driver', $driver);

    artisan('geo:update')
        ->assertExitCode(1)
        ->expectsOutput("🤚  The '{$driver}' driver does not need updating.");
})->with(['static', 'maxmind_web']);
