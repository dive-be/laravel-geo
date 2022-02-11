<?php declare(strict_types=1);

namespace Dive\Geo\Detectors;

use Dive\Geo\Contracts\Detector;

class IP2CountryDetector implements Detector
{
    public function __construct(
        private string $endpoint,
        private string $fallback,
    ) {}

    public function detect(string $ipAddress): string
    {
        ['countryCode' => $isoCode] = $this->sendRequest($this->endpoint . '?' . $ipAddress);

        if (empty($isoCode)) {
            return $this->fallback;
        }

        return $isoCode;
    }

    private function sendRequest(string $url): array
    {
        $handle = curl_init();

        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($handle, CURLOPT_TIMEOUT, 5);
        curl_setopt($handle, CURLOPT_URL, $url);

        $contents = curl_exec($handle);

        curl_close($handle);

        return json_decode($contents, true) ?? ['countryCode' => ''];
    }
}
