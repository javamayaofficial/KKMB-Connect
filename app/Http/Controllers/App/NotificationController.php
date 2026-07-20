<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\AppNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(20);
        // Tandai terbaca saat halaman dibuka
        auth()->user()->notifications()->whereNull('read_at')->update(['read_at' => now()]);

        return view('app.notifications', compact('notifications'));
    }

    public function markRead(AppNotification $notification)
    {
        abort_unless($notification->user_id === auth()->id(), 403);
        $notification->update(['read_at' => now()]);
        return back();
    }
}
