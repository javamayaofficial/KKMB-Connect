<?php

namespace App\Services\Notification;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteProvider implements WhatsAppProvider
{
    public function __construct(
        protected ?string $token = null,
        protected ?string $device = null,
    ) {}

    public function send(array|string $phone, string $message, array $options = []): NotificationChannelResponse
    {
        if (empty($this->token)) {
            Log::warning('Fonnte belum dikonfigurasi, WA dilewati.', ['phone' => $phone]);

            return NotificationChannelResponse::failed('API key Fonnte belum diatur.');
        }

        try {
            $targets = collect(is_array($phone) ? $phone : [$phone])
                ->filter()
                ->map(fn (string $item) => $this->normalize($item))
                ->implode(',');

            $payload = array_filter([
                'target' => $targets,
                'message' => $message,
                'countryCode' => $options['country_code'] ?? '62',
                'url' => $options['attachment_url'] ?? null,
                'filename' => $options['filename'] ?? null,
                'schedule' => $options['schedule'] ?? null,
                'delay' => $options['delay'] ?? null,
                'typing' => $options['typing'] ?? null,
                'device' => $options['device_id'] ?? $this->device,
            ], fn ($value) => $value !== null && $value !== '');

            $response = Http::timeout(15)
                ->withHeaders(['Authorization' => $this->token])
                ->asForm()
                ->post('https://api.fonnte.com/send', $payload);

            $json = $response->json() ?? [];

            if ($response->successful() && ($json['status'] ?? true) !== false) {
                return NotificationChannelResponse::success(
                    message: $json['detail'] ?? 'Pesan WhatsApp diterima provider.',
                    providerMessageId: (string) ($json['id'][0] ?? $json['id'] ?? ''),
                    payload: $json,
                );
            }

            Log::error('Fonnte gagal mengirim WA', ['status' => $response->status(), 'body' => $response->body()]);

            return NotificationChannelResponse::failed(
                message: $json['detail'] ?? 'Fonnte menolak permintaan pengiriman.',
                payload: ['status' => $response->status(), 'body' => $response->json() ?? $response->body()],
            );
        } catch (\Throwable $e) {
            Log::error('Fonnte exception: '.$e->getMessage());

            return NotificationChannelResponse::failed($e->getMessage());
        }
    }

    protected function normalize(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }
        return $phone;
    }
}
