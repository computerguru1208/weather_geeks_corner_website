<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\WeatherHistory;

class WeatherController extends Controller
{
    public function index(Request $request)
    {
        // 1. Load weather alerts from the NWS API
        $alertsResponse = Http::withHeaders([
            'User-Agent' => 'WeatherGeeksCorner (weathergeekscorner@gmail.com)'
        ])->get('https://api.weather.gov/alerts/active?area=KS');

        $all = $alertsResponse->json()['features'] ?? [];

        // 2. Filter by ?type= in the URL
        $filteredType = $request->query('type');
        $alerts = collect($all)->filter(function ($alert) use ($filteredType) {
            if (!$filteredType) return true;

            $event = strtolower($alert['properties']['event'] ?? '');
            return str_contains($event, strtolower($filteredType));
        });

        // 3. Group by event type (for accordion headings)
        $groupedAlerts = $alerts->groupBy(fn($a) => $a['properties']['event'] ?? 'Other');

        // Kansas cities and their coordinates
        $kansasCities = [
            'Wichita' => ['lat' => 37.6872, 'lon' => -97.3301],
            'Topeka' => ['lat' => 39.0558, 'lon' => -95.6890],
            'Dodge City' => ['lat' => 37.7528, 'lon' => -100.0171],
            'Goodland' => ['lat' => 39.3506, 'lon' => -101.7107],
        ];

        $forecasts = [];

        foreach ($kansasCities as $name => $coords) {
            $pointsResponse = Http::withHeaders([
                'User-Agent' => 'WeatherGeeksCorner (weathergeekscorner.com)'
            ])->get("https://api.weather.gov/points/{$coords['lat']},{$coords['lon']}");

            if ($pointsResponse->successful()) {
                $properties = $pointsResponse->json()['properties'] ?? [];
                $forecastUrl = $properties['forecast'] ?? null;
                $observationStationsUrl = $properties['observationStations'] ?? null;

                // Get forecast
                $forecastData = [];
                if ($forecastUrl) {
                    $forecastResponse = Http::withHeaders([
                        'User-Agent' => 'WeatherGeeksCorner (weathergeekscorner.com)'
                    ])->get($forecastUrl);

                    $forecastData = $forecastResponse->json()['properties']['periods'][0] ?? [];
                }

                // Get current observations (humidity, dewpoint, wind)
                $obsData = [];
                if ($observationStationsUrl) {
                    $stationsResponse = Http::withHeaders([
                        'User-Agent' => 'WeatherGeeksCorner (weathergeekscorner.com)'
                    ])->get($observationStationsUrl);

                    $stations = $stationsResponse->json()['features'] ?? [];
                    $stationId = $stations[0]['properties']['stationIdentifier'] ?? null;

                    if ($stationId) {
                        $obsResponse = Http::withHeaders([
                            'User-Agent' => 'WeatherGeeksCorner (weathergeekscorner.com)'
                        ])->get("https://api.weather.gov/stations/{$stationId}/observations/latest");

                        $obsProps = $obsResponse->json()['properties'] ?? [];
                        $obsData = [
                            'humidity' => $obsProps['relativeHumidity']['value'] ?? null,
                            'dewpoint' => $obsProps['dewpoint']['value'] ?? null,
                            'wind' => $obsProps['windSpeed']['value'] ?? null,
                        ];
                    }
                }

                // Combine into final array
                $forecasts[] = [
                    'name' => $name,
                    'description' => $forecastData['shortForecast'] ?? 'No forecast available',
                    'temperature' => $forecastData['temperature'] ?? 'N/A',
                    'wind' => isset($obsData['wind']) ? round($obsData['wind']) . ' mph' : 'N/A',
                    'humidity' => isset($obsData['humidity']) ? round($obsData['humidity']) . '%' : 'N/A',
                    'dewpoint' => isset($obsData['dewpoint']) ? round($obsData['dewpoint']) . '°' : 'N/A',
                ];
            }
        }


        $posts = [];

        $today = Carbon::now('America/Chicago')->toDateString();
        $weatherHistory = WeatherHistory::whereDate('date', $today)->get();


        // 4. Pass to the Blade view
        return view('home', [
            'alerts' => $alerts,
            'groupedAlerts' => $groupedAlerts,
            'city' => 'Kansas',
            'forecasts' => $forecasts,
            'kansasCities' => $forecasts,
            'posts' => $posts,
            'weatherHistory' => $weatherHistory,
        ]);
    }
}
