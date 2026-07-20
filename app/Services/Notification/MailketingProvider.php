<?php

namespace App\Services\Notification;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailketingProvider implements EmailProvider
{
    public function __construct(
        protected ?string $token = null,
        protected ?string $fromEmail = null,
        protected ?string $fromName = null,
    ) {}

    public function isConfigured(): bool
    {
        return ! empty($this->token);
    }

    public function send(string $to, string $subject, string $htmlBody): bool
    {
        // Jika Mailketing belum dikonfigurasi, fallback ke mailer bawaan Laravel
        // (default .env: MAIL_MAILER=log) sehingga tetap aman & tidak crash.
        if (! $this->isConfigured()) {
            try {
                Mail::html($htmlBody, function ($m) use ($to, $subject) {
                    $m->to($to)->subject($subject);
                });
                return true;
            } catch (\Throwable $e) {
                Log::error('Fallback mailer gagal: '.$e->getMessage());
                return false;
            }
        }

        try {
            $response = Http::timeout(20)->asForm()->post('https://api.mailketing.co.id/api/v1/send', [
                'api_token' => $this->token,
                'from_name' => $this->fromName,
                'from_email' => $this->fromEmail,
                'recipient' => $to,
                'subject' => $subject,
                'content' => $htmlBody,
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('Mailketing gagal', ['status' => $response->status(), 'body' => $response->body()]);
            return false;
        } catch (\Throwable $e) {
            Log::error('Mailketing exception: '.$e->getMessage());
            return false;
        }
    }
}
