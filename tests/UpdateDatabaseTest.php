<?php declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

final class UpdateDatabaseTest extends TestCase
{
    #[DataProvider('drivers')]
    #[Test]
    public function it_updates_the_maxmind_db_driver_only(string $driver): void
    {
        $this->app['config']->set('geo.detectors.driver', $driver);

        $this->artisan('geo:update')
            ->assertExitCode(1)
            ->expectsOutput("🤚  The '{$driver}' driver does not need updating.");
    }

    public static function drivers(): array
    {
        return [['static'], ['maxmind_web']];
    }
}
