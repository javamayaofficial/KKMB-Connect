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

    public function isConfigured(): bool
    {
        return ! empty($this->token);
    }

    public function send(string $phone, string $message): bool
    {
        if (! $this->isConfigured()) {
            Log::warning('Fonnte belum dikonfigurasi, WA dilewati.', ['phone' => $phone]);
            return false;
        }

        try {
            $response = Http::timeout(15)
                ->withHeaders(['Authorization' => $this->token])
                ->asForm()
                ->post('https://api.fonnte.com/send', [
                    'target' => $this->normalize($phone),
                    'message' => $message,
                    'countryCode' => '62',
                ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('Fonnte gagal mengirim WA', ['status' => $response->status(), 'body' => $response->body()]);
            return false;
        } catch (\Throwable $e) {
            // Fallback aman: catat, jangan crash-kan aplikasi.
            Log::error('Fonnte exception: '.$e->getMessage());
            return false;
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
