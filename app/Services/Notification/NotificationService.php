<?php

namespace App\Services\Notification;

use App\Models\AppNotification;
use App\Models\NotificationDelivery;
use App\Models\NotificationIntegration;
use App\Models\NotificationTemplate;
use App\Models\User;
use App\Jobs\SendNotificationDeliveryJob;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class NotificationService
{
    public function __construct(
        protected NotificationChannelManager $channels,
        protected NotificationTemplateService $templates,
    ) {}

    public function notify(
        User $user,
        string $judul,
        string $pesan,
        array $channels = ['in_app'],
        ?string $url = null,
        ?string $emailHtml = null,
    ): AppNotification {
        $channels = $this->normalizeChannels($channels);

        $notif = AppNotification::create([
            'user_id' => $user->id,
            'tipe' => 'info',
            'judul' => $judul,
            'pesan' => $pesan,
            'url' => $url,
        ]);

        if (in_array('whatsapp', $channels, true) && filled($user->phone)) {
            $this->queueDirectDelivery(
                channel: 'whatsapp',
                recipient: $user->phone,
                user: $user,
                subject: $judul,
                content: "*{$judul}*\n\n{$pesan}".($url ? "\n\n{$url}" : ''),
                deliveryType: 'direct',
                payload: ['url' => $url],
            );
        }

        if (in_array('email', $channels, true) && filled($user->email)) {
            $this->queueDirectDelivery(
                channel: 'email',
                recipient: $user->email,
                user: $user,
                subject: $judul,
                content: $emailHtml ?? $this->defaultEmailTemplate($judul, $pesan, $url),
                deliveryType: 'direct',
                payload: ['url' => $url],
            );
        }

        return $notif;
    }

    public function triggerEvent(
        User $user,
        string $eventKey,
        array $variables = [],
        array $channels = ['in_app', 'whatsapp', 'email'],
        array $options = [],
    ): ?AppNotification {
        $channels = $this->normalizeChannels($channels);
        $variables = $this->buildVariables($user, $variables);
        $notif = null;

        if (in_array('in_app', $channels, true)) {
            $rendered = $this->templates->render($eventKey, 'in_app', $variables);
            $notif = AppNotification::create([
                'user_id' => $user->id,
                'tipe' => 'info',
                'judul' => $rendered['subject'] ?: Str::headline($eventKey),
                'pesan' => strip_tags($rendered['content'] ?: ''),
                'url' => $variables['url'] ?? null,
            ]);
        }

        if (in_array('whatsapp', $channels, true) && filled($user->phone)) {
            $rendered = $this->templates->render($eventKey, 'whatsapp', $variables);

            $this->queueEventDelivery(
                channel: 'whatsapp',
                recipient: $user->phone,
                user: $user,
                eventKey: $eventKey,
                rendered: $rendered,
                options: $options,
            );
        }

        if (in_array('email', $channels, true) && filled($user->email)) {
            $rendered = $this->templates->render($eventKey, 'email', $variables);

            $this->queueEventDelivery(
                channel: 'email',
                recipient: $user->email,
                user: $user,
                eventKey: $eventKey,
                rendered: $rendered,
                options: $options,
            );
        }

        return $notif;
    }

    public function sendDirectWhatsApp(
        string $phone,
        string $message,
        array $options = [],
        ?User $actor = null,
    ): NotificationDelivery {
        return $this->queueDirectDelivery(
            channel: 'whatsapp',
            recipient: $phone,
            user: null,
            subject: $options['subject'] ?? 'Tes WhatsApp',
            content: $message,
            deliveryType: $options['delivery_type'] ?? 'manual',
            payload: $options,
            createdBy: $actor?->id,
        );
    }

    public function sendDirectEmail(
        string $email,
        string $subject,
        string $html,
        array $options = [],
        ?User $actor = null,
    ): NotificationDelivery {
        return $this->queueDirectDelivery(
            channel: 'email',
            recipient: $email,
            user: null,
            subject: $subject,
            content: $html,
            deliveryType: $options['delivery_type'] ?? 'manual',
            payload: $options,
            attachments: $options['attachments'] ?? [],
            createdBy: $actor?->id,
        );
    }

    public function broadcastUsers(
        iterable $users,
        string $eventKey,
        array $variables = [],
        array $channels = ['in_app', 'whatsapp', 'email'],
        ?User $actor = null,
        array $options = [],
    ): int {
        $count = 0;

        foreach ($users as $user) {
            if (! $user instanceof User) {
                continue;
            }

            $this->triggerEvent(
                user: $user,
                eventKey: $eventKey,
                variables: $variables,
                channels: $channels,
                options: array_merge($options, ['delivery_type' => 'broadcast', 'created_by' => $actor?->id]),
            );

            $count++;
        }

        return $count;
    }

    public function recipientsForScope(string $scope): Collection
    {
        return match ($scope) {
            'pengurus' => User::query()->role(['super_admin', 'pengurus'])->get(),
            'active_members' => User::query()->where('status', 'active')->get(),
            default => User::query()->get(),
        };
    }

    protected function queueEventDelivery(
        string $channel,
        string $recipient,
        User $user,
        string $eventKey,
        array $rendered,
        array $options = [],
    ): NotificationDelivery {
        return $this->queueDelivery(
            channel: $channel,
            recipient: $recipient,
            user: $user,
            eventKey: $eventKey,
            template: $rendered['template'] ?? null,
            subject: $rendered['subject'],
            content: $rendered['content'],
            attachments: $options['attachments'] ?? [],
            deliveryType: $options['delivery_type'] ?? 'event',
            payload: $options,
            createdBy: $options['created_by'] ?? auth()->id(),
        );
    }

    protected function queueDirectDelivery(
        string $channel,
        string $recipient,
        ?User $user,
        ?string $subject,
        string $content,
        string $deliveryType,
        array $payload = [],
        array $attachments = [],
        ?int $createdBy = null,
    ): NotificationDelivery {
        return $this->queueDelivery(
            channel: $channel,
            recipient: $recipient,
            user: $user,
            eventKey: $payload['event_key'] ?? null,
            template: null,
            subject: $subject,
            content: $content,
            attachments: $attachments,
            deliveryType: $deliveryType,
            payload: $payload,
            createdBy: $createdBy ?? auth()->id(),
        );
    }

    protected function queueDelivery(
        string $channel,
        string $recipient,
        ?User $user,
        ?string $eventKey,
        ?NotificationTemplate $template,
        ?string $subject,
        string $content,
        array $attachments,
        string $deliveryType,
        array $payload,
        ?int $createdBy,
    ): NotificationDelivery {
        /** @var NotificationIntegration|null $integration */
        $integration = $this->channels->integration($channel);

        $delivery = NotificationDelivery::create([
            'user_id' => $user?->id,
            'integration_id' => $integration?->id,
            'template_id' => $template?->id,
            'created_by' => $createdBy,
            'event_key' => $eventKey,
            'delivery_type' => $deliveryType,
            'channel' => $channel,
            'provider' => $integration?->provider,
            'recipient' => $recipient,
            'subject' => $subject,
            'content' => $content,
            'attachments' => $attachments,
            'payload' => $payload,
            'status' => 'pending',
            'max_attempts' => 3,
            'queued_at' => now(),
        ]);

        if (! $integration || ! $integration->is_active) {
            $delivery->update([
                'status' => 'failed',
                'last_error' => 'Integrasi channel ini belum aktif di panel admin.',
            ]);

            return $delivery;
        }

        SendNotificationDeliveryJob::dispatch($delivery->id);

        return $delivery;
    }

    protected function buildVariables(User $user, array $variables = []): array
    {
        return array_merge([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'app_url' => url('/'),
            'admin_url' => url('/admin'),
            'url' => $variables['url'] ?? url('/'),
            'subject' => $variables['subject'] ?? null,
            'message' => $variables['message'] ?? null,
        ], $variables);
    }

    protected function normalizeChannels(array $channels): array
    {
        return collect($channels)
            ->map(function (string $channel) {
                return match ($channel) {
                    'wa' => 'whatsapp',
                    default => $channel,
                };
            })
            ->unique()
            ->values()
            ->all();
    }

    protected function defaultEmailTemplate(string $judul, string $pesan, ?string $url): string
    {
        $btn = $url ? "<p><a href=\"{$url}\" style=\"background:#0E7C86;color:#fff;padding:10px 18px;border-radius:12px;text-decoration:none\">Buka</a></p>" : '';

        return "<div style=\"font-family:sans-serif;max-width:520px;margin:auto\">"
            ."<h2 style=\"color:#0E7C86\">{$judul}</h2>"
            ."<p style=\"color:#333;line-height:1.6\">{$pesan}</p>{$btn}"
            ."<hr><p style=\"font-size:12px;color:#999\">KKMB Connect &middot; Satu Jaringan, Ribuan Peluang.</p>"
            ."</div>";
    }
}
