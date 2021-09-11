<?php

namespace Dive\Geo\Repositories;

use Dive\Geo\Contracts\Repository;
use Dive\Geo\Contracts\Transformer;

class InMemoryRepository implements Repository
{
    private ?Transformer $transformer = null;

    public function __construct(private string $countryCode) {}

    public function get()
    {
        if (is_null($this->transformer)) {
            return $this->countryCode;
        }

        return $this->transformer->transform($this->countryCode);
    }

    public function put(string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    public function setTransformer(?Transformer $transformer): self
    {
        $this->transformer = $transformer;

        return $this;
    }
}
