<?php declare(strict_types=1);

namespace Dive\Geo\Detectors;

use Closure;
use Dive\Geo\Contracts\Detector;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use MaxMind\Db\Reader\InvalidDatabaseException;

final class MaxMindDatabaseDetector implements Detector
{
    private Closure $log;

    public function __construct(private readonly Reader $reader, private readonly string $fallback) {}

    public function detect(string $ipAddress): string
    {
        try {
            $record = $this->reader->country($ipAddress);

            return $record->country->isoCode ?? $this->fallback;
        } catch (AddressNotFoundException) {
            return $this->fallback;
        } catch (InvalidDatabaseException $exception) {
            call_user_func($this->log)->error($exception->getMessage(), $exception->getTrace());

            return $this->fallback;
        }
    }

    public function setLogResolver(Closure $callback): self
    {
        $this->log = $callback;

        return $this;
    }
}
