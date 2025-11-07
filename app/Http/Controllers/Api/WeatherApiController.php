<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Weather\NwsClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WeatherApiController extends Controller
{
    public function __construct(private NwsClient $nws) {}

    // A) GeoJSON passthrough (perfect for your radar JS)
    public function alertsRaw(Request $req)
    {
        $area = strtoupper($req->query('state', 'KS'));
        return Cache::remember(
            "nws_alerts_raw_{$area}",
            90,
            fn() =>
            response()->json($this->nws->alertsByArea($area))
        );
    }

    // B) Normalized alerts (nice for cards/banners)
    public function alerts(Request $req)
    {
        $area = strtoupper($req->query('state', 'KS'));
        $raw = Cache::remember("nws_alerts_raw_{$area}", 90, fn() => $this->nws->alertsByArea($area));

        $alerts = collect($raw['features'] ?? [])->map(function ($f) {
            $p = $f['properties'] ?? [];
            return [
                'id'          => $p['id'] ?? null,
                'event'       => $p['event'] ?? null,
                'severity'    => $p['severity'] ?? null,
                'status'      => $p['status'] ?? null,
                'effective'   => $p['effective'] ?? null,
                'onset'       => $p['onset'] ?? null,
                'expires'     => $p['expires'] ?? null,
                'areaDesc'    => $p['areaDesc'] ?? null,
                'headline'    => $p['headline'] ?? null,
                'description' => $p['description'] ?? null,
                'instruction' => $p['instruction'] ?? null,
            ];
        })->values();

        return response()->json(['source' => 'api.weather.gov', 'count' => $alerts->count(), 'alerts' => $alerts]);
    }

    // C) Forecast by city (daily + hourly)
    private array $cityCoords = [
        'Wichita'       => [37.6872, -97.3301],
        'Kansas City'   => [39.1142, -94.6275],
        'Topeka'        => [39.0473, -95.6752],
        'Overland Park' => [38.9822, -94.6708],
        'Olathe'        => [38.8814, -94.8191],
        'Lawrence'      => [38.9717, -95.2353],
        'Manhattan'     => [39.1836, -96.5717],
        'Salina'        => [38.8403, -97.6114],
        'Dodge City'    => [37.7528, -100.0171],
        'Hutchinson'    => [38.0608, -97.9298],
        'Independence'  => [37.2242, -95.7086],
        'Goodland'      => [39.3506, -101.7107],
    ];

    public function forecast(Request $req)
    {
        $city = $req->query('city', 'Wichita');
        abort_unless(isset($this->cityCoords[$city]), 400, 'Unsupported city');

        [$lat, $lon] = $this->cityCoords[$city];
        $points = Cache::remember("nws_points_{$lat}_{$lon}", 43200, fn() => $this->nws->points($lat, $lon));
        $out    = Cache::remember("nws_forecast_{$city}", 90, fn() => $this->nws->forecastFromPoints($points));

        $days = collect($out['daily']['properties']['periods'] ?? [])->map(fn($p) => [
            'name'            => $p['name'] ?? null,
            'startTime'       => $p['startTime'] ?? null,
            'endTime'         => $p['endTime'] ?? null,
            'isDaytime'       => $p['isDaytime'] ?? null,
            'temperature'     => $p['temperature'] ?? null,
            'temperatureUnit' => $p['temperatureUnit'] ?? 'F',
            'wind'            => trim(($p['windDirection'] ?? '') . ' ' . ($p['windSpeed'] ?? '')),
            'shortForecast'   => $p['shortForecast'] ?? null,
            'detailedForecast' => $p['detailedForecast'] ?? null,
        ]);

        $hours = collect($out['hourly']['properties']['periods'] ?? [])->take(48)->map(fn($p) => [
            'startTime'                 => $p['startTime'] ?? null,
            'temperature'               => $p['temperature'] ?? null,
            'temperatureUnit'           => $p['temperatureUnit'] ?? 'F',
            'wind'                      => trim(($p['windDirection'] ?? '') . ' ' . ($p['windSpeed'] ?? '')),
            'probabilityOfPrecipitation' => $p['probabilityOfPrecipitation']['value'] ?? null,
            'shortForecast'             => $p['shortForecast'] ?? null,
        ]);

        return response()->json(['source' => 'api.weather.gov', 'city' => $city, 'daily' => $days, 'hourly' => $hours]);
    }
}
