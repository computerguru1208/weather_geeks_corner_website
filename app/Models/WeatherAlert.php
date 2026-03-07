<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeatherAlert extends Model
{
    protected $fillable = [
        'nws_id',
        'event',
        'severity',
        'urgency',
        'certainty',
        'area_desc',
        'headline',
        'description',
        'sent_at',
        'effective_at',
        'expires_at',
        'status',
        'geometry',
        'is_announced',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'effective_at' => 'datetime',
        'expires_at' => 'datetime',
        'geometry' => 'array',
        'is_announced' => 'boolean',
    ];
}
