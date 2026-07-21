<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    protected $fillable = [
        'event_key',
        'channel',
        'name',
        'subject',
        'content',
        'is_active',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'meta' => 'array',
        ];
    }
}
