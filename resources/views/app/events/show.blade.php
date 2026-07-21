@extends('layouts.app')
@section('title', $event->judul)
@section('content')
    <a href="{{ route('events.index') }}" class="mb-3 inline-flex items-center rounded-2xl bg-white/80 px-3 py-2 text-sm text-slate-500 shadow-sm backdrop-blur dark:bg-slate-900/80 dark:text-slate-300">← Kembali</a>
    <div class="overflow-hidden rounded-[30px] border border-white/70 bg-white/82 shadow-[0_12px_32px_rgba(15,23,42,0.06)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/88 dark:shadow-[0_12px_32px_rgba(2,6,23,0.32)]">
        @if ($event->poster_path)<img src="{{ Storage::url($event->poster_path) }}" class="h-52 w-full object-cover" alt="">@endif
        <div class="p-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Event Detail</p>
                    <h1 class="mt-2 text-xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $event->judul }}</h1>
                </div>
                <span class="shrink-0 rounded-full bg-brand/10 px-2.5 py-1 text-[11px] font-bold text-brand">{{ $event->is_paid ? 'Berbayar' : 'Gratis' }}</span>
            </div>
            <div class="mt-4 space-y-2 rounded-[26px] bg-slate-50 px-4 py-4 text-sm text-slate-600 dark:bg-slate-950/70 dark:text-slate-300">
                <p>🗓️ {{ $event->mulai_at->translatedFormat('l, d M Y · H:i') }}</p>
                <p>📍 {{ $event->lokasi ?: 'Online' }}</p>
                <p>🎟️ {{ $event->is_paid ? 'Rp'.number_format($event->harga,0,',','.') : 'Gratis' }}</p>
                @if (!is_null($event->sisaKuota()))<p>👥 Sisa {{ $event->sisaKuota() }} kuota</p>@endif
            </div>
            @if ($event->deskripsi)<p class="mt-4 text-sm text-slate-500 leading-relaxed whitespace-pre-line">{{ $event->deskripsi }}</p>@endif

            <div class="mt-5">
                @if ($registration)
                    <a href="{{ route('events.ticket', $registration) }}" class="block rounded-[24px] bg-teal-50 py-4 text-center font-semibold text-brand ring-1 ring-brand/10 dark:bg-teal-950/40">Lihat Tiket QR Saya</a>
                @elseif ($event->isFull())
                    <button disabled class="w-full rounded-[24px] bg-slate-100 py-4 font-semibold text-slate-400">Kuota Penuh</button>
                @else
                    <form method="POST" action="{{ route('events.register', $event) }}">@csrf
                        <button class="w-full rounded-[24px] bg-brand py-4 text-white font-semibold shadow-[0_18px_40px_rgba(14,124,134,0.28)] transition active:scale-95">Daftar Event</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
