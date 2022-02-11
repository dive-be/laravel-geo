<?php declare(strict_types=1);

namespace Dive\Geo\Middleware;

use Closure;
use Dive\Geo\Contracts\Repository;
use Illuminate\Http\Request;

class DetectGeoLocation
{
    private Closure $detector;

    public function __construct(
        private Repository $repo,
    ) {}

    public function handle(Request $request, Closure $next)
    {
        if ($this->repo->isEmpty()) {
            $this->repo->put(call_user_func($this->detector)->detect($request->ip()));
        }

        return $next($request);
    }

    public function setDetectorResolver(Closure $callback): self
    {
        $this->detector = $callback;

        return $this;
    }
}
