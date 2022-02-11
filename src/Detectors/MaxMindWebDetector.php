<?php declare(strict_types=1);

namespace Dive\Geo\Detectors;

use Closure;
use Dive\Geo\Contracts\Detector;
use GeoIp2\Exception\AddressNotFoundException;
use GeoIp2\Exception\GeoIp2Exception;
use GeoIp2\WebService\Client;

class MaxMindWebDetector implements Detector
{
    private Closure $log;

    public function __construct(
        private Client $client,
        private string $fallback,
    ) {}

    public function detect(string $ipAddress): string
    {
        try {
            $record = $this->client->country($ipAddress);

            return $record->country->isoCode;
        } catch (AddressNotFoundException) {
            return $this->fallback;
        } catch (GeoIp2Exception $exception) {
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
