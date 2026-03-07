<?php

namespace App\Services\Weather;

use Carbon\Carbon;

class NwsForecastService
{
    public function __construct(
        protected NwsClient $client
    ) {}

    public function getCityWeather(string $city, float $lat, float $lon): ?array
    {
        $pointData = $this->client->get("https://api.weather.gov/points/{$lat},{$lon}");

        if (empty($pointData['properties'])) {
            return null;
        }

        $forecastUrl = $pointData['properties']['forecast'] ?? null;
        $stationsUrl = $pointData['properties']['observationStations'] ?? null;

        if (!$forecastUrl || !$stationsUrl) {
            return null;
        }

        $forecastData = $this->client->get($forecastUrl);
        $forecastPeriod = $forecastData['properties']['periods'][0] ?? [];

        $stationList = $this->client->get($stationsUrl);
        $stationUrl = $stationList['observationStations'][0] ?? null;

        $observation = [];
        if ($stationUrl) {
            $observation = $this->client->get($stationUrl . '/observations/latest');
        }

        return $this->normalizeCityWeather($city, $lat, $lon, $forecastPeriod, $observation);
    }

    protected function normalizeCityWeather(
        string $city,
        float $lat,
        float $lon,
        array $forecastPeriod,
        array $observation
    ): array {
        $obsProps = $observation['properties'] ?? [];

        $humidity = $obsProps['relativeHumidity']['value'] ?? null;
        $dewpointC = $obsProps['dewpoint']['value'] ?? null;
        $dewpointF = is_numeric($dewpointC) ? round(($dewpointC * 9 / 5) + 32, 1) : null;

        $updatedAt = $forecastPeriod['startTime'] ?? null;

        $hashInput = [
            'city' => $city,
            'forecast_period_name' => $forecastPeriod['name'] ?? null,
            'short_forecast' => $forecastPeriod['shortForecast'] ?? null,
            'temperature' => $forecastPeriod['temperature'] ?? null,
            'wind_speed' => $forecastPeriod['windSpeed'] ?? null,
            'wind_direction' => $forecastPeriod['windDirection'] ?? null,
            'humidity' => $humidity,
            'dewpoint_f' => $dewpointF,
        ];

        return [
            'city' => $city,
            'latitude' => $lat,
            'longitude' => $lon,
            'forecast_period_name' => $forecastPeriod['name'] ?? null,
            'short_forecast' => $forecastPeriod['shortForecast'] ?? null,
            'temperature' => $forecastPeriod['temperature'] ?? null,
            'temperature_unit' => $forecastPeriod['temperatureUnit'] ?? 'F',
            'wind_speed' => $forecastPeriod['windSpeed'] ?? null,
            'wind_direction' => $forecastPeriod['windDirection'] ?? null,
            'humidity' => is_numeric($humidity) ? (int) round($humidity) : null,
            'dewpoint_f' => $dewpointF,
            'forecast_updated_at' => $updatedAt ? Carbon::parse($updatedAt) : null,
            'hash' => md5(json_encode($hashInput)),
        ];
    }
}
