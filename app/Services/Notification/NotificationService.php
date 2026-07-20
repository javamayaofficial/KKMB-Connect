<?php

namespace App\Services\Notification;

use App\Models\AppNotification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Pusat notifikasi KKMB Connect.
 * - In-app: SELALU dibuat (sumber kebenaran, tidak pernah gagal diam-diam).
 * - WhatsApp (Fonnte) & Email (Mailketing): best-effort; kegagalan hanya dicatat,
 *   tidak pernah menggagalkan aksi utama pengguna.
 */
class NotificationService
{
    public function __construct(
        protected WhatsAppProvider $whatsapp,
        protected EmailProvider $email,
    ) {}

    /**
     * @param array $channels kombinasi dari: in_app, wa, email
     */
    public function notify(
        User $user,
        string $judul,
        string $pesan,
        array $channels = ['in_app'],
        ?string $url = null,
        ?string $emailHtml = null,
    ): AppNotification {
        // 1) In-app selalu tersimpan
        $notif = AppNotification::create([
            'user_id' => $user->id,
            'tipe' => 'info',
            'judul' => $judul,
            'pesan' => $pesan,
            'url' => $url,
        ]);

        // 2) WhatsApp (best-effort)
        if (in_array('wa', $channels) && ! empty($user->phone)) {
            try {
                $this->whatsapp->send($user->phone, "*{$judul}*\n\n{$pesan}");
            } catch (\Throwable $e) {
                Log::error('Notif WA gagal: '.$e->getMessage());
            }
        }

        // 3) Email (best-effort)
        if (in_array('email', $channels) && ! empty($user->email)) {
            try {
                $html = $emailHtml ?? $this->defaultEmailTemplate($judul, $pesan, $url);
                $this->email->send($user->email, $judul, $html);
            } catch (\Throwable $e) {
                Log::error('Notif email gagal: '.$e->getMessage());
            }
        }

        return $notif;
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
