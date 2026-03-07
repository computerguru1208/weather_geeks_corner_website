<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WeatherAlert;
use App\Services\Weather\NwsAlertService;

class SyncKansasAlerts extends Command
{
    protected $signature = 'weather:sync-alerts';
    protected $description = 'Sync active Kansas weather alerts from NWS';

    public function handle(NwsAlertService $alertService): int
    {
        $features = $alertService->getKansasAlerts();

        $count = 0;

        foreach ($features as $feature) {
            $normalized = $alertService->normalizeAlert($feature);

            if (empty($normalized['nws_id'])) {
                continue;
            }

            WeatherAlert::updateOrCreate(
                ['nws_id' => $normalized['nws_id']],
                $normalized
            );

            $count++;
        }

        $this->info("Synced {$count} active Kansas alerts.");

        return self::SUCCESS;
    }
}
