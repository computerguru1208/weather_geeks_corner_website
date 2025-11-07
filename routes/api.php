<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WeatherApiController;

Route::get('/weather/alerts/raw', [WeatherApiController::class, 'alertsRaw']);
Route::get('/weather/alerts',     [WeatherApiController::class, 'alerts']);
Route::get('/weather/forecast',   [WeatherApiController::class, 'forecast']);

// (optional) quick health check during setup
Route::get('/ping', fn() => response()->json(['ok' => true, 'ts' => now()->toIso8601String()]));
