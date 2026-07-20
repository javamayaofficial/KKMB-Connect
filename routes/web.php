<?php

use App\Http\Controllers\App\BusinessController;
use App\Http\Controllers\App\CheckinController;
use App\Http\Controllers\App\DashboardController;
use App\Http\Controllers\App\EventController;
use App\Http\Controllers\App\FeedController;
use App\Http\Controllers\App\NotificationController;
use App\Http\Controllers\App\ProfileController;
use App\Http\Controllers\App\SubscriptionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

// ---------- PUBLIK (ringkas) ----------
Route::view('/', 'landing')->name('landing');

// ---------- TAMU (belum login) ----------
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [LoginController::class, 'show'])->name('login');
    Route::post('/masuk', [LoginController::class, 'store']);
    Route::get('/daftar', [RegisterController::class, 'show'])->name('register');
    Route::post('/daftar', [RegisterController::class, 'store']);

    Route::get('/lupa-password', [PasswordResetController::class, 'showLinkRequest'])->name('password.request');
    Route::post('/lupa-password', [PasswordResetController::class, 'sendLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

// ---------- SUDAH LOGIN ----------
Route::middleware('auth')->group(function () {
    Route::post('/keluar', [LoginController::class, 'destroy'])->name('logout');
    Route::view('/menunggu-verifikasi', 'app.pending')->name('pending');

    // Area inti hanya untuk anggota AKTIF (terverifikasi)
    Route::middleware('active.member')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Directory (Livewire di dalam view)
        Route::view('/alumni', 'app.directory.alumni')->name('alumni.index');
        Route::view('/bisnis', 'app.directory.business')->name('business.directory');

        // Profil & Kartu
        Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/kartu', [ProfileController::class, 'card'])->name('profile.card');

        // Kelola bisnis saya
        Route::get('/bisnis-saya', [BusinessController::class, 'index'])->name('business.index');
        Route::get('/bisnis-saya/baru', [BusinessController::class, 'create'])->name('business.create');
        Route::post('/bisnis-saya', [BusinessController::class, 'store'])->name('business.store');
        Route::get('/bisnis-saya/{business}/edit', [BusinessController::class, 'edit'])->name('business.edit');
        Route::put('/bisnis-saya/{business}', [BusinessController::class, 'update'])->name('business.update');

        // Event
        Route::get('/event', [EventController::class, 'index'])->name('events.index');
        Route::get('/event/saya', [EventController::class, 'myEvents'])->name('events.mine');
        Route::get('/event/{event}', [EventController::class, 'show'])->name('events.show');
        Route::post('/event/{event}/daftar', [EventController::class, 'register'])->name('events.register');
        Route::get('/tiket/{registration}', [EventController::class, 'ticket'])->name('events.ticket');

        // Check-in (pengurus)
        Route::get('/checkin', [CheckinController::class, 'form'])->name('checkin.form');
        Route::post('/checkin', [CheckinController::class, 'process'])->name('checkin.process');

        // Feed
        Route::get('/feed', [FeedController::class, 'index'])->name('feed.index');
        Route::get('/feed/tulis', [FeedController::class, 'create'])->name('feed.create');
        Route::post('/feed', [FeedController::class, 'store'])->name('feed.store');
        Route::get('/feed/{post:slug}', [FeedController::class, 'show'])->name('feed.show');

        // Notifikasi
        Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifikasi/{notification}/baca', [NotificationController::class, 'markRead'])->name('notifications.read');

        // Langganan & Pembayaran (manual)
        Route::get('/langganan', [SubscriptionController::class, 'index'])->name('subscription.index');
        Route::post('/langganan/{plan}', [SubscriptionController::class, 'subscribe'])->name('subscription.subscribe');
        Route::get('/pembayaran/{payment}', [SubscriptionController::class, 'pay'])->name('subscription.pay');
        Route::post('/pembayaran/{payment}/bukti', [SubscriptionController::class, 'uploadProof'])->name('subscription.proof');
    });
});
