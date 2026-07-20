<?php

namespace App\Services\Notification;

interface WhatsAppProvider
{
    /**
     * Kirim pesan WhatsApp. Kembalikan true jika terkirim, false jika gagal.
     * Implementasi TIDAK boleh melempar exception ke pemanggil (harus ditangani internal).
     */
    public function send(string $phone, string $message): bool;

    public function isConfigured(): bool;
}
