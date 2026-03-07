<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForecastSnapshot extends Model
{
    protected $fillable = [
        'city',
        'latitude',
        'longitude',
        'forecast_period_name',
        'short_forecast',
        'temperature',
        'temperature_unit',
        'wind_speed',
        'wind_direction',
        'humidity',
        'dewpoint_f',
        'forecast_updated_at',
        'hash',
    ];

    protected $casts = [
        'forecast_updated_at' => 'datetime',
        'latitude' => 'decimal:4',
        'longitude' => 'decimal:4',
        'dewpoint_f' => 'decimal:1',
    ];
}
