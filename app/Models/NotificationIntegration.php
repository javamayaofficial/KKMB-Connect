<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationIntegration extends Model
{
    protected $fillable = [
        'channel',
        'name',
        'provider',
        'config',
        'is_active',
        'last_test_status',
        'last_test_message',
        'last_tested_at',
    ];

    protected function casts(): array
    {
        return [
            'config' => 'encrypted:array',
            'is_active' => 'boolean',
            'last_tested_at' => 'datetime',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
