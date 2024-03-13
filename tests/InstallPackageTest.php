<?php declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\Test;

final class InstallPackageTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();

        file_exists(config_path('geo.php')) && unlink(config_path('geo.php'));
    }

    #[Test]
    public function it_copies_the_config(): void
    {
        $this->artisan('geo:install')->execute();

        $this->assertTrue(file_exists(config_path('geo.php')));
    }
}
