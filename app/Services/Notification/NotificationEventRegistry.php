<?php

namespace App\Services\Notification;

class NotificationEventRegistry
{
    public static function labels(): array
    {
        return [
            'account_registration' => 'Registrasi akun',
            'first_login' => 'Login pertama',
            'forgot_password' => 'Lupa password',
            'account_verified' => 'Verifikasi akun',
            'member_submission' => 'Pengajuan anggota',
            'member_approved' => 'Persetujuan anggota',
            'payment_submitted' => 'Pembayaran diajukan',
            'payment_approved' => 'Pembayaran disetujui',
            'new_event' => 'Event baru',
            'event_reminder' => 'Pengingat event',
            'loan_submitted' => 'Pengajuan pinjaman',
            'loan_approved' => 'Persetujuan pinjaman',
            'cooperative_announcement' => 'Pengumuman koperasi',
            'admin_broadcast' => 'Broadcast admin',
        ];
    }

    public static function channels(): array
    {
        return [
            'in_app' => 'In App',
            'whatsapp' => 'WhatsApp',
            'email' => 'Email',
        ];
    }
}
