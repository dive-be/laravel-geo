<?php declare(strict_types=1);

namespace Dive\Geo\Repositories;

use Dive\Geo\Contracts\Repository;
use Dive\Geo\Contracts\Transformer;

class InMemoryRepository implements Repository
{
    private ?Transformer $transformer = null;

    public function __construct(
        private ?string $countryCode = null,
    ) {}

    public function get()
    {
        if (is_null($this->transformer) || is_null($this->countryCode)) {
            return $this->countryCode;
        }

        return $this->transformer->transform($this->countryCode);
    }

    public function isEmpty(): bool
    {
        return is_null($this->countryCode);
    }

    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
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
