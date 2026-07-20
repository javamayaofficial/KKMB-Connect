<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;

class EventApiController extends Controller
{
    public function index()
    {
        return response()->json(Event::published()->orderBy('mulai_at')->paginate(15));
    }

    public function show(Event $event)
    {
        abort_unless($event->status === 'published', 404);
        return response()->json(['data' => $event->loadCount('registrations')]);
    }

    public function register(Request $request, Event $event)
    {
        abort_unless($event->status === 'published', 404);

        if (EventRegistration::where('event_id', $event->id)->where('user_id', $request->user()->id)->exists()) {
            return response()->json(['message' => 'Sudah terdaftar.'], 422);
        }
        if ($event->isFull()) {
            return response()->json(['message' => 'Kuota penuh.'], 422);
        }

        $reg = EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => $request->user()->id,
            'status' => 'registered',
        ]);

        return response()->json([
            'message' => 'Registrasi berhasil',
            'data' => ['qr_token' => $reg->qr_token],
        ], 201);
    }

    public function myEvents(Request $request)
    {
        $data = EventRegistration::with('event')
            ->where('user_id', $request->user()->id)->latest()->paginate(15);
        return response()->json($data);
    }
}
