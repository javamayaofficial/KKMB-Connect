<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AppNotification;
use Illuminate\Http\Request;

class NotificationApiController extends Controller
{
    public function index(Request $request)
    {
        return response()->json($request->user()->notifications()->latest()->paginate(20));
    }

    public function markRead(Request $request, AppNotification $notification)
    {
        abort_unless($notification->user_id === $request->user()->id, 403);
        $notification->update(['read_at' => now()]);
        return response()->json(['message' => 'Ditandai terbaca']);
    }
}
