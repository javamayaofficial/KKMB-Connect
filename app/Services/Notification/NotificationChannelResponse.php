<?php

namespace App\Services\Notification;

class NotificationChannelResponse
{
    public function __construct(
        public readonly bool $success,
        public readonly ?string $status = null,
        public readonly ?string $message = null,
        public readonly ?string $providerMessageId = null,
        public readonly array $payload = [],
    ) {}

    public static function success(
        ?string $message = null,
        ?string $providerMessageId = null,
        array $payload = [],
        ?string $status = 'sent',
    ): self {
        return new self(true, $status, $message, $providerMessageId, $payload);
    }

    public static function failed(
        ?string $message = null,
        array $payload = [],
        ?string $status = 'failed',
    ): self {
        return new self(false, $status, $message, null, $payload);
    }
}
