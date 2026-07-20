<?php

use Illuminate\Support\Facades\Schedule;

// Tandai langganan yang sudah lewat masa berlaku sebagai expired (harian).
Schedule::call(function () {
    \App\Models\Subscription::where('status', 'active')
        ->whereDate('berakhir_at', '<', now())
        ->update(['status' => 'expired']);
})->daily();
