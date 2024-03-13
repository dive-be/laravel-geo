<?php declare(strict_types=1);

namespace Tests;

use Dive\Geo\Facades\Geo;
use PHPUnit\Framework\Attributes\Test;

final class HelpersTest extends TestCase
{
    #[Test]
    public function geo_can_put_a_new_country(): void
    {
        $this->assertNotEquals($iso = 'CZ', Geo::get());

        geo($iso);

        $this->assertEquals($iso, Geo::get());
    }

    #[Test]
    public function geo_can_retrieve_an_instance(): void
    {
        $this->assertSame(geo(), app('geo'));
    }
}
