<?php

namespace App\Services\Notification;

interface WhatsAppProvider
{
    public function send(array|string $phone, string $message, array $options = []): NotificationChannelResponse;
}
