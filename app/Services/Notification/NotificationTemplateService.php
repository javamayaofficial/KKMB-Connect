<?php

namespace App\Services\Notification;

use App\Models\NotificationTemplate;
use Illuminate\Support\Str;

class NotificationTemplateService
{
    public function resolve(string $eventKey, string $channel): ?NotificationTemplate
    {
        return NotificationTemplate::query()
            ->where('event_key', $eventKey)
            ->where('channel', $channel)
            ->where('is_active', true)
            ->first();
    }

    public function render(string $eventKey, string $channel, array $variables = []): array
    {
        $template = $this->resolve($eventKey, $channel);

        if (! $template) {
            return [
                'template' => null,
                'subject' => $variables['subject'] ?? Str::headline($eventKey),
                'content' => $variables['message'] ?? '',
            ];
        }

        return [
            'template' => $template,
            'subject' => $this->replaceVariables($template->subject, $variables),
            'content' => $this->replaceVariables($template->content, $variables),
        ];
    }

    public function replaceVariables(?string $content, array $variables = []): ?string
    {
        if ($content === null) {
            return null;
        }

        $pairs = [];

        foreach ($variables as $key => $value) {
            $pairs['{{'.$key.'}}'] = is_scalar($value) || $value === null
                ? (string) ($value ?? '')
                : json_encode($value);
        }

        return strtr($content, $pairs);
    }
}
