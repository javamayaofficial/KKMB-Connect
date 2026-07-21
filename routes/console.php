<?php

use Illuminate\Support\Facades\Schedule;

// Tandai langganan yang sudah lewat masa berlaku sebagai expired (harian).
Schedule::call(function () {
    \App\Models\Subscription::where('status', 'active')
        ->whereDate('berakhir_at', '<', now())
        ->update(['status' => 'expired']);
})->daily();

// Kirim pengingat event untuk peserta yang event-nya dimulai dalam 24 jam ke depan.
Schedule::call(function () {
    $registrations = \App\Models\EventRegistration::query()
        ->with(['event', 'user'])
        ->whereNull('reminder_sent_at')
        ->where('status', 'registered')
        ->whereHas('event', function ($query) {
            $query->where('status', 'published')
                ->whereBetween('mulai_at', [now(), now()->copy()->addDay()]);
        })
        ->get();

    $notifications = app(\App\Services\Notification\NotificationService::class);

    foreach ($registrations as $registration) {
        if (! $registration->event || ! $registration->user) {
            continue;
        }

        $notifications->triggerEvent(
            $registration->user,
            'event_reminder',
            [
                'event_title' => $registration->event->judul,
                'event_date' => $registration->event->mulai_at?->translatedFormat('d M Y H:i'),
                'event_location' => $registration->event->lokasi ?: 'Lokasi akan diinformasikan',
                'event_url' => route('events.show', $registration->event),
                'url' => route('events.show', $registration->event),
            ],
            ['in_app', 'wa', 'email'],
        );

        $registration->forceFill(['reminder_sent_at' => now()])->save();
    }
})->everyThirtyMinutes();
