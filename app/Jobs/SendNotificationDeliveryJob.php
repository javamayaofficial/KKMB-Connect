<?php

namespace App\Jobs;

use App\Models\NotificationDelivery;
use App\Services\Notification\EmailProvider;
use App\Services\Notification\NotificationChannelManager;
use App\Services\Notification\WhatsAppProvider;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RuntimeException;

class SendNotificationDeliveryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [60, 300, 900];

    public function __construct(public readonly int $deliveryId)
    {
    }

    public function handle(NotificationChannelManager $channels): void
    {
        $delivery = NotificationDelivery::query()->with('integration')->find($this->deliveryId);

        if (! $delivery || ! $delivery->integration) {
            return;
        }

        $delivery->update([
            'attempts' => $delivery->attempts + 1,
            'last_attempt_at' => now(),
            'status' => 'pending',
        ]);

        $provider = $channels->provider($delivery->integration);

        $response = match ($delivery->channel) {
            'whatsapp' => $provider instanceof WhatsAppProvider
                ? $provider->send($delivery->recipient, $delivery->content, $delivery->payload ?? [])
                : throw new RuntimeException('Provider WhatsApp tidak valid.'),
            'email' => $provider instanceof EmailProvider
                ? $provider->send(
                    $delivery->recipient,
                    $delivery->subject ?? 'Notifikasi KKMB Connect',
                    $delivery->content,
                    array_merge($delivery->payload ?? [], ['attachments' => $delivery->attachments ?? []]),
                )
                : throw new RuntimeException('Provider email tidak valid.'),
            default => throw new RuntimeException('Channel delivery tidak dikenali.'),
        };

        if (! $response->success) {
            $delivery->update([
                'response' => $response->payload,
                'last_error' => $response->message,
            ]);

            throw new RuntimeException($response->message ?: 'Provider menolak pengiriman notifikasi.');
        }

        $delivery->update([
            'status' => 'sent',
            'provider_message_id' => $response->providerMessageId,
            'response' => $response->payload,
            'last_error' => null,
            'sent_at' => now(),
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        NotificationDelivery::query()
            ->whereKey($this->deliveryId)
            ->update([
                'status' => 'failed',
                'last_error' => $exception->getMessage(),
            ]);
    }
}
