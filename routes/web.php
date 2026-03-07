<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\BlogController; // <-- add this import

// Home + alerts pages (server-rendered)
Route::get('/', [WeatherController::class, 'index']);
Route::get('/alerts/{type}', [WeatherController::class, 'index'])->name('alerts.byType');

// Blog pages
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Radar page: let the JS in the Blade file call your proxy /api/weather/alerts/raw
Route::get('/radar-live', function () {
    return view('radar-live'); // no server-side NWS call needed
})->name('radar.live');

Route::get('/api/openapi.yaml', function () {
    $path = storage_path('api-docs/weather-proxy.yaml');
    abort_unless(File::exists($path), 404, 'Spec not found');
    return response(File::get($path), 200, ['Content-Type' => 'application/yaml']);
})->name('openapi.yaml');

// Optional: a simple, CDN-backed docs page (great for quick verification)
Route::view('/api/docs-standalone', 'swagger-standalone')->name('docs.standalone');
