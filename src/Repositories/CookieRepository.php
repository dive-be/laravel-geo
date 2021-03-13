<?php

namespace Dive\Geo\Repositories;

use Closure;
use Dive\Geo\Contracts\Repository;
use Dive\Geo\Contracts\Transformer;
use Illuminate\Config\Repository as Config;
use Illuminate\Support\Str;

class CookieRepository implements Repository
{
    private Closure $cookie;

    private Closure $jar;

    private ?string $queued = null;

    private ?Transformer $transformer = null;

    public function __construct(private Config $config) {}

    public function get()
    {
        $countryCode = $this->queued ?? ($this->cookie)($this->config->get('geo.repos.cookie.name'));

        if (! is_string($countryCode)) {
            return null;
        }

        if ($this->transformer instanceof Transformer) {
            return $this->transformer->transform($countryCode);
        }

        return $countryCode;
    }

    public function isEmpty(): bool
    {
        return ! $this->isNotEmpty();
    }

    public function isNotEmpty(): bool
    {
        return is_string(($this->cookie)($this->config->get('geo.repos.cookie.name')));
    }

    public function put(string $countryCode): void
    {
        $jar = ($this->jar)();

        $this->queued = Str::upper($countryCode);

        $jar->queue($jar->forever($this->config->get('geo.repos.cookie.name'), $this->queued));
    }

    public function setCookieResolver(Closure $callback): self
    {
        $this->cookie = $callback;

        return $this;
    }

    public function setCookieJarResolver(Closure $callback): self
    {
        $this->jar = $callback;

        return $this;
    }

    public function setTransformer(Transformer $transformer): self
    {
        $this->transformer = $transformer;

        return $this;
    }
}
