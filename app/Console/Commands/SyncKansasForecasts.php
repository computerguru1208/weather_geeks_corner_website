<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ForecastSnapshot;
use App\Services\Weather\NwsForecastService;

class SyncKansasForecasts extends Command
{
    protected $signature = 'weather:sync-forecasts';
    protected $description = 'Sync current Kansas city forecasts from NWS';

    public function handle(NwsForecastService $forecastService): int
    {
        $cities = [
            'Wichita' => ['lat' => 37.6872, 'lon' => -97.3301],
            'Topeka' => ['lat' => 39.0558, 'lon' => -95.6890],
            'Dodge City' => ['lat' => 37.7528, 'lon' => -100.0171],
            'Goodland' => ['lat' => 39.3506, 'lon' => -101.7107],
        ];

        $count = 0;

        foreach ($cities as $city => $coords) {
            $data = $forecastService->getCityWeather($city, $coords['lat'], $coords['lon']);

            if (!$data) {
                $this->warn("Failed to sync {$city}");
                continue;
            }

            ForecastSnapshot::create($data);
            $count++;

            $this->info("Synced {$city}");
        }

        $this->info("Synced {$count} forecast snapshots.");

        return self::SUCCESS;
    }
}
