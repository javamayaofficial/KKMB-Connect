<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Services\Notification\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::published()->orderBy('mulai_at')->paginate(10);
        return view('app.events.index', compact('events'));
    }

    public function show(Event $event)
    {
        abort_unless($event->status === 'published', 404);
        $registration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', auth()->id())->first();

        return view('app.events.show', compact('event', 'registration'));
    }

    public function register(Request $request, Event $event, NotificationService $notifications)
    {
        abort_unless($event->status === 'published', 404);

        // Validasi: sudah terdaftar? kuota penuh?
        $existing = EventRegistration::where('event_id', $event->id)->where('user_id', auth()->id())->first();
        if ($existing) {
            return back()->with('info', 'Anda sudah terdaftar untuk event ini.');
        }
        if ($event->isFull()) {
            return back()->with('error', 'Maaf, kuota event sudah penuh.');
        }

        $reg = DB::transaction(function () use ($event) {
            return EventRegistration::create([
                'event_id' => $event->id,
                'user_id' => auth()->id(),
                'status' => 'registered',
            ]);
        });

        $notifications->notify(
            auth()->user(),
            'Pendaftaran Event Berhasil',
            "Anda terdaftar di \"{$event->judul}\". Tunjukkan QR tiket saat check-in.",
            ['in_app', 'wa'],
            route('events.ticket', $reg),
        );

        return redirect()->route('events.ticket', $reg)->with('status', 'Pendaftaran berhasil!');
    }

    // QR tiket peserta
    public function ticket(EventRegistration $registration)
    {
        abort_unless($registration->user_id === auth()->id(), 403);
        $qrSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(220)->generate($registration->qr_token);
        return view('app.events.ticket', compact('registration', 'qrSvg'));
    }

    public function myEvents()
    {
        $registrations = EventRegistration::with('event')
            ->where('user_id', auth()->id())->latest()->get();
        return view('app.events.mine', compact('registrations'));
    }
}
