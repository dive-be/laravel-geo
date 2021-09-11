<?php

namespace Dive\Geo\Contracts;

interface Repository
{
    public function get();

    public function put(string $countryCode): void;

    public function setTransformer(?Transformer $transformer): self;
}
