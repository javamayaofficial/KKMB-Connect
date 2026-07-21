<?php

namespace App\Services\Notification;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class MailketingProvider implements EmailProvider
{
    public function __construct(
        protected ?string $token = null,
        protected ?string $fromEmail = null,
        protected ?string $fromName = null,
    ) {}

    public function send(array|string $to, string $subject, string $htmlBody, array $options = []): NotificationChannelResponse
    {
        $recipients = collect(is_array($to) ? $to : [$to])->filter()->values()->all();
        $attachments = $options['attachments'] ?? [];

        if (empty($this->token)) {
            try {
                Mail::html($htmlBody, function ($m) use ($recipients, $subject, $attachments) {
                    $m->to($recipients)->subject($subject);

                    foreach ($attachments as $attachment) {
                        if (! empty($attachment['path']) && Storage::disk($attachment['disk'] ?? 'public')->exists($attachment['path'])) {
                            $m->attach(
                                Storage::disk($attachment['disk'] ?? 'public')->path($attachment['path']),
                                [
                                    'as' => $attachment['name'] ?? basename($attachment['path']),
                                    'mime' => $attachment['mime'] ?? null,
                                ],
                            );
                        }
                    }
                });

                return NotificationChannelResponse::success(
                    message: 'Email dikirim melalui mailer fallback.',
                    payload: ['recipients' => $recipients, 'fallback' => true],
                );
            } catch (\Throwable $e) {
                Log::error('Fallback mailer gagal: '.$e->getMessage());

                return NotificationChannelResponse::failed($e->getMessage());
            }
        }

        try {
            $payload = [
                'api_token' => $this->token,
                'from_name' => $this->fromName,
                'from_email' => $this->fromEmail,
                'recipient' => implode(',', $recipients),
                'subject' => $subject,
                'content' => $htmlBody,
            ];

            if (! empty($attachments)) {
                $payload['attachments'] = json_encode(
                    collect($attachments)->map(function (array $attachment) {
                        if (empty($attachment['path']) || ! Storage::disk($attachment['disk'] ?? 'public')->exists($attachment['path'])) {
                            return null;
                        }

                        $disk = $attachment['disk'] ?? 'public';
                        $path = Storage::disk($disk)->path($attachment['path']);

                        return [
                            'filename' => $attachment['name'] ?? basename($path),
                            'content' => base64_encode(file_get_contents($path)),
                            'mime' => $attachment['mime'] ?? mime_content_type($path),
                        ];
                    })->filter()->values()->all(),
                    JSON_THROW_ON_ERROR,
                );
            }

            $response = Http::timeout(20)->asForm()->post('https://api.mailketing.co.id/api/v1/send', $payload);
            $json = $response->json() ?? [];

            if ($response->successful()) {
                return NotificationChannelResponse::success(
                    message: $json['message'] ?? 'Email diterima provider.',
                    providerMessageId: (string) ($json['id'] ?? $json['data']['id'] ?? ''),
                    payload: $json,
                );
            }

            Log::error('Mailketing gagal', ['status' => $response->status(), 'body' => $response->body()]);

            return NotificationChannelResponse::failed(
                message: $json['message'] ?? 'Mailketing menolak permintaan email.',
                payload: ['status' => $response->status(), 'body' => $json ?: $response->body()],
            );
        } catch (\Throwable $e) {
            Log::error('Mailketing exception: '.$e->getMessage());

            return NotificationChannelResponse::failed($e->getMessage());
        }
    }
}
