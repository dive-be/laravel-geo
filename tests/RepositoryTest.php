<?php

namespace Tests;

use Dive\Geo\Repositories\CookieRepository;
use Mockery;
use Tests\Fakes\Country;
use Tests\Fakes\CountryTransformer;

beforeEach(function () {
    $this->belgium = 'BE';
    $this->cookieName = 'geo-test';
    $this->repo = new CookieRepository($this->cookieName, $this->belgium);
    $this->turkey = 'TR';
});

it('can retrieve the current country', function () {
    $this->repo->setCookieResolver(fn ($name) => $this->turkey);

    $country = $this->repo->get();

    expect($country)->toBe($this->turkey);
});

it('can transform the country after retrieval', function () {
    $this->repo
        ->setCookieResolver(fn ($name) => $this->turkey)
        ->setTransformer(new CountryTransformer());

    $country = $this->repo->get();

    expect($country)->toBeInstanceOf(Country::class);
    expect($country->name)->toBe($this->turkey);
});

it('can put a new value', function () {
    $jar = Mockery::mock();
    $jar->shouldReceive('forever')->withArgs([$this->cookieName, $swiss = 'CH']);
    $jar->shouldReceive('queue');

    $this->repo
        ->setCookieJarResolver(fn () => $jar)
        ->setCookieResolver(fn () => null);

    expect($this->repo->get())->toBe($this->belgium); // fallback

    $this->repo->put($swiss);

    expect($this->repo->get())->toBe($swiss);
});
