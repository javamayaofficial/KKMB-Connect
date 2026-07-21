<?php

namespace App\Services\Notification;

use App\Models\NotificationIntegration;
use InvalidArgumentException;

class NotificationChannelManager
{
    public function integration(string $channel): ?NotificationIntegration
    {
        return NotificationIntegration::query()
            ->where('channel', $channel)
            ->first();
    }

    public function activeIntegration(string $channel): ?NotificationIntegration
    {
        return NotificationIntegration::query()
            ->where('channel', $channel)
            ->where('is_active', true)
            ->first();
    }

    public function provider(NotificationIntegration $integration): WhatsAppProvider|EmailProvider
    {
        $config = $integration->config ?? [];

        return match ($integration->channel) {
            'whatsapp' => match ($integration->provider) {
                'fonnte' => new FonnteProvider(
                    token: $config['api_key'] ?? null,
                    device: $config['device_id'] ?? null,
                ),
                default => throw new InvalidArgumentException('Provider WhatsApp tidak didukung.'),
            },
            'email' => match ($integration->provider) {
                'mailketing' => new MailketingProvider(
                    token: $config['api_key'] ?? null,
                    fromEmail: $config['sender_email'] ?? null,
                    fromName: $config['sender_name'] ?? null,
                ),
                default => throw new InvalidArgumentException('Provider email tidak didukung.'),
            },
            default => throw new InvalidArgumentException('Channel notifikasi tidak didukung.'),
        };
    }
}
