<?php

namespace App\Services\Notification;

interface EmailProvider
{
    public function send(string $to, string $subject, string $htmlBody): bool;

    public function isConfigured(): bool;
}
