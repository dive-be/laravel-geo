<?php declare(strict_types=1);

namespace Dive\Geo\Contracts;

interface Repository
{
    public function get();

    public function isEmpty(): bool;

    public function isNotEmpty(): bool;

    public function put(string $countryCode): void;

    public function setTransformer(?Transformer $transformer): self;
}
