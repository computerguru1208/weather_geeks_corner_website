<?php

namespace App\Services\Weather;

use Illuminate\Support\Facades\Http;

class NwsClient
{
    private function http()
    {
        return Http::withHeaders([
            'User-Agent' => '(weathergeekscorner.com; contact: your@email)',
            'Accept'     => 'application/geo+json, application/json'
        ])->timeout(10);
    }

    public function alertsByArea(string $area = 'KS'): array
    {
        return $this->http()
            ->get('https://api.weather.gov/alerts/active', ['area' => strtoupper($area)])
            ->throw()->json();
    }

    public function points(float $lat, float $lon): array
    {
        return $this->http()
            ->get("https://api.weather.gov/points/{$lat},{$lon}")
            ->throw()->json();
    }

    public function forecastFromPoints(array $points): array
    {
        $dailyUrl  = $points['properties']['forecast']       ?? null;
        $hourlyUrl = $points['properties']['forecastHourly'] ?? null;
        abort_if(!$dailyUrl || !$hourlyUrl, 500, 'NWS points resolution failed');

        $daily  = $this->http()->get($dailyUrl)->throw()->json();
        $hourly = $this->http()->get($hourlyUrl)->throw()->json();

        return compact('daily', 'hourly');
    }
}
