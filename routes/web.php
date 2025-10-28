<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Http;

Route::get('/', [WeatherController::class, 'index']);
Route::get('/alerts/{type}', [WeatherController::class, 'index']);
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/radar-live', function () {
    $response = Http::withHeaders([
        'User-Agent' => 'WeatherGeeksCorner (weathergeekscorner@gmail.com)'
    ])->get('https://api.weather.gov/alerts/active?area=KS');

    $alerts = $response->json()['features'] ?? [];

    return view('radar-live', compact('alerts'));
});
