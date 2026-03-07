<?php

namespace App\Services\Weather;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class NwsClient
{
    protected array $headers = [
        'User-Agent' => 'Weather Geeks Corner (your-email@example.com)',
        'Accept' => 'application/geo+json',
    ];

    public function get(string $url): array
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->timeout(15)
                ->get($url);

            if (!$response->successful()) {
                return [];
            }

            return $response->json() ?? [];
        } catch (RequestException $e) {
            report($e);
            return [];
        } catch (\Throwable $e) {
            report($e);
            return [];
        }
    }
}
