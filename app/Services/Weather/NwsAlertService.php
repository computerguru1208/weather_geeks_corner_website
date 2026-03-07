<?php

namespace App\Services\Weather;

class NwsAlertService
{
    public function __construct(
        protected NwsClient $client
    ) {}

    public function getKansasAlerts(): array
    {
        $data = $this->client->get('https://api.weather.gov/alerts/active?area=KS');

        return $data['features'] ?? [];
    }

    public function normalizeAlert(array $feature): array
    {
        $props = $feature['properties'] ?? [];

        return [
            'nws_id' => $props['id'] ?? $feature['id'] ?? null,
            'event' => $props['event'] ?? 'Unknown',
            'severity' => $props['severity'] ?? null,
            'urgency' => $props['urgency'] ?? null,
            'certainty' => $props['certainty'] ?? null,
            'area_desc' => $props['areaDesc'] ?? null,
            'headline' => $props['headline'] ?? null,
            'description' => $props['description'] ?? null,
            'sent_at' => $props['sent'] ?? null,
            'effective_at' => $props['effective'] ?? null,
            'expires_at' => $props['expires'] ?? null,
            'status' => strtolower($props['status'] ?? 'active'),
            'geometry' => $feature['geometry'] ?? null,
        ];
    }
}
