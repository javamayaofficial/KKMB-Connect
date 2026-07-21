<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class EventRegistration extends Model
{
    protected $fillable = [
        'event_id', 'user_id', 'qr_token', 'status', 'checked_in_at', 'checked_in_by', 'reminder_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'checked_in_at' => 'datetime',
            'reminder_sent_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (EventRegistration $reg) {
            $reg->qr_token = $reg->qr_token ?: Str::random(40);
        });
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
