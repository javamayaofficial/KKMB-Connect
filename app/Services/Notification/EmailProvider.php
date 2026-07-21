<?php

namespace App\Services\Notification;

interface EmailProvider
{
    public function send(array|string $to, string $subject, string $htmlBody, array $options = []): NotificationChannelResponse;
}
