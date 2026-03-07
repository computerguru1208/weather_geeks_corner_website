<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncementQueue extends Model
{
    protected $table = 'announcement_queue';

    protected $fillable = [
        'type',
        'source_id',
        'priority',
        'message',
        'available_at',
        'processed_at',
        'status',
    ];

    protected $casts = [
        'available_at' => 'datetime',
        'processed_at' => 'datetime',
    ];
}
