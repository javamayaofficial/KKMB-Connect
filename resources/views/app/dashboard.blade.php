@extends('layouts.app')
@section('title', 'Beranda — KKMB Connect')
@section('content')
    <div class="mb-5">
        <p class="text-sm text-slate-500">Halo,</p>
        <h1 class="text-xl font-bold">{{ auth()->user()->name }} 👋</h1>
    </div>

    {{-- Statistik jaringan --}}
    <div class="grid grid-cols-3 gap-3 mb-5">
        <div class="rounded-2xl bg-brand text-white p-4">
            <p class="text-2xl font-extrabold">{{ $stats['total_alumni'] }}</p>
            <p class="text-[11px] text-white/80 mt-0.5">Alumni</p>
        </div>
        <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-4">
            <p class="text-2xl font-extrabold text-brand">{{ $stats['total_kota'] }}</p>
            <p class="text-[11px] text-slate-500 mt-0.5">Kota</p>
        </div>
        <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-4">
            <p class="text-2xl font-extrabold text-brand">{{ $stats['total_negara'] }}</p>
            <p class="text-[11px] text-slate-500 mt-0.5">Negara</p>
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="grid grid-cols-4 gap-3 mb-6">
        @foreach ([
            ['profile.card', 'Kartu', 'M3 10h18M7 15h4m-4-9h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z'],
            ['business.index', 'Bisnis', 'M3 7h18v13H3zM3 7l2-3h14l2 3M9 12h6'],
            ['events.mine', 'Tiket', 'M3 7h18v4a2 2 0 000 4v4H3v-4a2 2 0 000-4z'],
            ['subscription.index', 'Langganan', 'M12 8c-2 0-3 1-3 2s1 2 3 2 3 1 3 2-1 2-3 2m0-10V6m0 12v-2'],
        ] as $qa)
            <a href="{{ route($qa[0]) }}" class="flex flex-col items-center gap-1.5">
                <span class="w-14 h-14 rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 grid place-items-center text-brand">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $qa[2] }}"/></svg>
                </span>
                <span class="text-[11px] text-slate-500">{{ $qa[1] }}</span>
            </a>
        @endforeach
    </div>

    {{-- Peta sebaran (ringkas: daftar kota + jumlah) --}}
    <section class="mb-6">
        <h2 class="font-bold mb-3">Sebaran Alumni</h2>
        @if ($sebaran->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-200 dark:border-slate-800 p-6 text-center text-sm text-slate-400">
                Data sebaran belum tersedia. Lengkapi kota di profil untuk muncul di peta.
            </div>
        @else
            <div class="space-y-2">
                @foreach ($sebaran->sortByDesc('jumlah')->take(6) as $s)
                    <div class="flex items-center justify-between rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 px-4 py-3">
                        <span class="text-sm font-medium">{{ $s->kota ?: 'Tidak diketahui' }}</span>
                        <span class="text-sm font-bold text-brand">{{ $s->jumlah }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Temukan Relasi --}}
    <section class="mb-6">
        <h2 class="font-bold mb-3">Temukan Relasi</h2>
        @if ($rekomendasi->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-200 dark:border-slate-800 p-6 text-center text-sm text-slate-400">
                Belum ada rekomendasi. Lengkapi profesi & bidang usaha Anda agar kami temukan relasi yang cocok.
            </div>
        @else
            <div class="flex gap-3 overflow-x-auto pb-2 -mx-4 px-4">
                @foreach ($rekomendasi as $r)
                    <div class="shrink-0 w-40 rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-4">
                        <div class="w-10 h-10 rounded-full bg-teal-50 text-brand grid place-items-center font-bold mb-2">{{ strtoupper(substr($r->user->name, 0, 1)) }}</div>
                        <p class="font-semibold text-sm truncate">{{ $r->user->name }}</p>
                        <p class="text-[11px] text-slate-500 truncate">{{ $r->profesi ?: 'Alumni' }}</p>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $r->user->phone) }}" target="_blank"
                           class="mt-2 block text-center text-[11px] font-semibold text-brand bg-teal-50 dark:bg-teal-950/40 rounded-xl py-1.5">Hubungi</a>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Event terdekat --}}
    <section>
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-bold">Event Terdekat</h2>
            <a href="{{ route('events.index') }}" class="text-xs text-brand font-semibold">Lihat semua</a>
        </div>
        @forelse ($eventTerdekat as $e)
            <a href="{{ route('events.show', $e) }}" class="flex items-center gap-3 rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-3 mb-2">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-brand grid place-items-center shrink-0">
                    <div class="text-center leading-none">
                        <p class="text-base font-extrabold">{{ $e->mulai_at->format('d') }}</p>
                        <p class="text-[9px] uppercase">{{ $e->mulai_at->format('M') }}</p>
                    </div>
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-sm truncate">{{ $e->judul }}</p>
                    <p class="text-[11px] text-slate-500 truncate">{{ $e->lokasi ?: 'Online' }} · {{ $e->mulai_at->format('H:i') }}</p>
                </div>
            </a>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-200 dark:border-slate-800 p-6 text-center text-sm text-slate-400">Belum ada event.</div>
        @endforelse
    </section>
@endsection
