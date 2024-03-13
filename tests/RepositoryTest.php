<?php declare(strict_types=1);

namespace Tests;

use Dive\Geo\Repositories\CookieRepository;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\Fakes\Country;
use Tests\Fakes\CountryTransformer;

final class RepositoryTest extends TestCase
{
    private const COOKIE = 'geo-test';
    private const TURKEY = 'TR';

    private CookieRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repo = new CookieRepository(self::COOKIE);
    }

    #[Test]
    public function can_retrieve_the_current_country(): void
    {
        $this->repo->setCookieResolver(static fn () => self::TURKEY);

        $country = $this->repo->get();

        $this->assertSame(self::TURKEY, $country);
    }

    #[Test]
    public function can_transform_the_country_after_retrieval(): void
    {
        $this->repo
            ->setCookieResolver(static fn () => self::TURKEY)
            ->setTransformer(new CountryTransformer());

        $country = $this->repo->get();

        $this->assertInstanceOf(Country::class, $country);
        $this->assertSame(self::TURKEY, $country->name);
    }

    #[Test]
    public function can_determine_emptiness(): void
    {
        $this->repo->setCookieResolver(static fn () => self::TURKEY);

        $this->assertFalse($this->repo->isEmpty());
        $this->assertTrue($this->repo->isNotEmpty());
    }

    #[Test]
    public function can_put_a_new_value(): void
    {
        $jar = Mockery::mock();
        $jar->shouldReceive('forever')->withArgs([self::COOKIE, $switzerland = 'CH']);
        $jar->shouldReceive('queue');

        $this->repo
            ->setCookieJarResolver(static fn () => $jar)
            ->setCookieResolver(static fn () => null);

        $this->assertNull($this->repo->get());

        $this->repo->put($switzerland);

        $this->assertSame($switzerland, $this->repo->get());
    }
}
