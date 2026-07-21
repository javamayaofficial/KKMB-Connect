<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationDelivery extends Model
{
    protected $fillable = [
        'user_id',
        'integration_id',
        'template_id',
        'created_by',
        'event_key',
        'delivery_type',
        'channel',
        'provider',
        'recipient',
        'subject',
        'content',
        'attachments',
        'payload',
        'response',
        'status',
        'attempts',
        'max_attempts',
        'last_error',
        'provider_message_id',
        'queued_at',
        'last_attempt_at',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'attachments' => 'array',
            'payload' => 'array',
            'response' => 'array',
            'queued_at' => 'datetime',
            'last_attempt_at' => 'datetime',
            'sent_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function integration(): BelongsTo
    {
        return $this->belongsTo(NotificationIntegration::class, 'integration_id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(NotificationTemplate::class, 'template_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
