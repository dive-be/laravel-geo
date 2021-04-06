<?php

namespace Tests;

use Dive\Geo\Repositories\CookieRepository;
use Mockery;
use Tests\Fakes\Country;
use Tests\Fakes\CountryTransformer;

beforeEach(function () {
    $this->cookieName = 'geo-test';
    $this->repo = new CookieRepository($this->cookieName);
});

it('can retrieve the current country', function () {
    $this->repo->setCookieResolver(fn ($name) => 'TR');

    $country = $this->repo->get();

    expect($country)->toBe('TR');
});

it('can transform the country after retrieval', function () {
    $this->repo
        ->setCookieResolver(fn ($name) => 'TR')
        ->setTransformer(new CountryTransformer());

    $country = $this->repo->get();

    expect($country)->toBeInstanceOf(Country::class);
    expect($country->name)->toBe('TR');
});

it('can determine emptiness', function () {
    $this->repo->setCookieResolver(fn ($name) => 'TR');

    expect($this->repo->isEmpty())->toBeFalse();
    expect($this->repo->isNotEmpty())->toBeTrue();
});

it('can put a new value', function () {
    $jar = Mockery::mock();
    $jar->shouldReceive('forever')->withArgs([$this->cookieName, 'TR']);
    $jar->shouldReceive('queue');

    $this->repo
        ->setCookieJarResolver(fn () => $jar)
        ->setCookieResolver(fn () => null);

    expect($this->repo->get())->toBeNull();

    $this->repo->put('TR');

    expect($this->repo->get())->toBe('TR');
});
