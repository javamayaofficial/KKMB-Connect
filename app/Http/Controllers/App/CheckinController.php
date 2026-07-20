<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\EventRegistration;
use Illuminate\Http\Request;

/**
 * QR Check-in oleh PENGURUS. Panitia memasukkan/scan qr_token peserta.
 * Aturan: hanya pengurus/admin, QR valid sekali (check-in ganda ditolak).
 */
class CheckinController extends Controller
{
    public function form()
    {
        return view('app.events.checkin');
    }

    public function process(Request $request)
    {
        abort_unless($request->user()->hasAnyRole(['super_admin', 'pengurus']), 403);

        $request->validate(['qr_token' => ['required', 'string']]);

        $reg = EventRegistration::with(['event', 'user'])
            ->where('qr_token', $request->qr_token)->first();

        if (! $reg) {
            return back()->with('error', 'QR tidak dikenali.');
        }

        if ($reg->status === 'checked_in') {
            return back()->with('info', "Peserta {$reg->user->name} SUDAH check-in pada {$reg->checked_in_at->format('d M H:i')}.");
        }

        if ($reg->status === 'cancelled') {
            return back()->with('error', 'Registrasi ini dibatalkan.');
        }

        $reg->update([
            'status' => 'checked_in',
            'checked_in_at' => now(),
            'checked_in_by' => $request->user()->id,
        ]);

        return back()->with('status', "Check-in berhasil: {$reg->user->name} untuk \"{$reg->event->judul}\".");
    }
}
