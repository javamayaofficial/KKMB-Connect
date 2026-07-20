@extends('layouts.app')
@section('title', $event->judul)
@section('content')
    <a href="{{ route('events.index') }}" class="text-sm text-slate-400 mb-3 inline-block">← Kembali</a>
    <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 overflow-hidden">
        @if ($event->poster_path)<img src="{{ Storage::url($event->poster_path) }}" class="w-full h-44 object-cover" alt="">@endif
        <div class="p-5">
            <h1 class="text-lg font-bold">{{ $event->judul }}</h1>
            <div class="mt-3 space-y-1.5 text-sm text-slate-600 dark:text-slate-300">
                <p>🗓️ {{ $event->mulai_at->translatedFormat('l, d M Y · H:i') }}</p>
                <p>📍 {{ $event->lokasi ?: 'Online' }}</p>
                <p>🎟️ {{ $event->is_paid ? 'Rp'.number_format($event->harga,0,',','.') : 'Gratis' }}</p>
                @if (!is_null($event->sisaKuota()))<p>👥 Sisa {{ $event->sisaKuota() }} kuota</p>@endif
            </div>
            @if ($event->deskripsi)<p class="mt-4 text-sm text-slate-500 leading-relaxed whitespace-pre-line">{{ $event->deskripsi }}</p>@endif

            <div class="mt-5">
                @if ($registration)
                    <a href="{{ route('events.ticket', $registration) }}" class="block text-center py-3.5 rounded-2xl bg-teal-50 text-brand font-semibold">Lihat Tiket QR Saya</a>
                @elseif ($event->isFull())
                    <button disabled class="w-full py-3.5 rounded-2xl bg-slate-100 text-slate-400 font-semibold">Kuota Penuh</button>
                @else
                    <form method="POST" action="{{ route('events.register', $event) }}">@csrf
                        <button class="w-full py-3.5 rounded-2xl bg-brand text-white font-semibold active:scale-95 transition">Daftar Event</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
