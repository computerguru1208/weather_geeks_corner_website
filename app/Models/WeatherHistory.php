<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeatherHistory extends Model
{
    protected $table = 'weather_history';

    protected $fillable = [
        'date',
        'summary',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public static function forToday(): ?string
    {
        $today = now()->format('m-d');

        return self::whereRaw("DATE_FORMAT(date, '%m-%d') = ?", [$today])
            ->first()
            ?->summary;
    }
}
