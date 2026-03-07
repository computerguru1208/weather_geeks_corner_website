<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'source_type',
        'source_id',
        'announced_at',
        'audio_path',
        'status',
    ];

    protected $casts = [
        'announced_at' => 'datetime',
    ];
}
