<?php

namespace App\Providers;

use App\Services\Notification\EmailProvider;
use App\Services\Notification\FonnteProvider;
use App\Services\Notification\MailketingProvider;
use App\Services\Notification\WhatsAppProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Binding provider WhatsApp berdasarkan config (default: Fonnte).
        $this->app->bind(WhatsAppProvider::class, function () {
            $provider = config('integrations.whatsapp.provider', 'fonnte');

            return match ($provider) {
                // Alternatif OneSender/StarSender bisa ditambahkan di fase berikut
                // dengan class yang mengimplementasikan WhatsAppProvider.
                default => new FonnteProvider(
                    token: config('integrations.whatsapp.fonnte.token'),
                    device: config('integrations.whatsapp.fonnte.device'),
                ),
            };
        });

        // Binding provider Email (default: Mailketing).
        $this->app->bind(EmailProvider::class, function () {
            return new MailketingProvider(
                token: config('integrations.email.mailketing.token'),
                fromEmail: config('integrations.email.from_email'),
                fromName: config('integrations.email.from_name'),
            );
        });
    }

    public function boot(): void
    {
        // Paksa HTTPS di produksi (aman untuk FastPanel/cPanel di belakang SSL).
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
