@extends('layouts.app')
@section('title', 'Beranda — KKMB Connect')
@section('content')
    @php
        $quickActions = [
            ['profile.card', 'Kartu Anggota', 'Akses identitas digital', 'M3 10h18M7 15h4m-4-9h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z'],
            ['business.index', 'Bisnis Saya', 'Kelola promosi usaha', 'M3 7h18v13H3zM3 7l2-3h14l2 3M9 12h6'],
            ['events.mine', 'Tiket Event', 'Pantau kehadiran & agenda', 'M3 7h18v4a2 2 0 000 4v4H3v-4a2 2 0 000-4z'],
            ['subscription.index', 'Status Member', 'Lihat manfaat langganan', 'M12 8c-2 0-3 1-3 2s1 2 3 2 3 1 3 2-1 2-3 2m0-10V6m0 12v-2'],
        ];
    @endphp

    <section class="mb-6 rounded-[28px] border border-brand/10 bg-[linear-gradient(145deg,rgba(14,124,134,1),rgba(15,94,90,0.94),rgba(2,6,23,0.96))] p-5 text-white shadow-[0_22px_60px_rgba(15,94,90,0.22)]">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.24em] text-white/65">Member Lounge</p>
                <h1 class="mt-2 text-2xl font-bold leading-tight">{{ auth()->user()->name }} 👋</h1>
                <p class="mt-2 text-sm leading-relaxed text-white/75">
                    Selamat datang kembali. Kelola kartu anggota, temukan koneksi strategis,
                    dan buka peluang baru dari ekosistem alumni yang terus berkembang.
                </p>
            </div>
            <div class="rounded-2xl border border-white/15 bg-white/10 px-3 py-2 text-right">
                <p class="text-[11px] text-white/65">Status</p>
                <p class="text-sm font-semibold">Aktif</p>
            </div>
        </div>

        <div class="mt-5 grid grid-cols-2 gap-3">
            <a href="{{ route('subscription.index') }}" class="rounded-2xl bg-white text-slate-900 px-4 py-3 font-semibold text-sm text-center">Lihat Keanggotaan</a>
            <a href="{{ route('profile.card') }}" class="rounded-2xl border border-white/15 bg-white/10 px-4 py-3 font-semibold text-sm text-center text-white">Buka Kartu Digital</a>
        </div>

        <div class="mt-5 grid grid-cols-3 gap-3">
            <div class="rounded-2xl bg-white/10 border border-white/10 p-4">
                <p class="text-2xl font-extrabold">{{ $stats['total_alumni'] }}</p>
                <p class="mt-1 text-xs text-white/65">Alumni terverifikasi</p>
            </div>
            <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                <p class="text-2xl font-extrabold">{{ $stats['total_kota'] }}</p>
                <p class="mt-1 text-xs text-white/65">Kota terhubung</p>
            </div>
            <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                <p class="text-2xl font-extrabold">{{ $stats['total_negara'] }}</p>
                <p class="mt-1 text-xs text-white/65">Negara aktif</p>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <div class="flex items-center justify-between mb-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Akses Cepat</p>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">Langkah berikutnya</h2>
            </div>
            <span class="text-xs font-medium text-brand">Designed for action</span>
        </div>
        <div class="grid grid-cols-2 gap-3">
            @foreach ($quickActions as $qa)
                <a href="{{ route($qa[0]) }}" class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-sm hover:shadow-md transition">
                    <span class="w-12 h-12 rounded-2xl bg-brand/10 text-brand grid place-items-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $qa[3] }}"/></svg>
                    </span>
                    <p class="mt-3 text-sm font-semibold text-slate-900 dark:text-white">{{ $qa[1] }}</p>
                    <p class="mt-1 text-xs leading-relaxed text-slate-500 dark:text-slate-400">{{ $qa[2] }}</p>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Peta sebaran (ringkas: daftar kota + jumlah) --}}
    <section class="mb-6">
        <div class="flex items-end justify-between gap-3 mb-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Network Map</p>
                <h2 class="font-bold text-slate-900 dark:text-white">Sebaran Alumni</h2>
            </div>
            <span class="text-xs text-slate-400">Pusat koneksi teratas</span>
        </div>
        @if ($sebaran->isEmpty())
            <div class="rounded-3xl border border-dashed border-slate-300 dark:border-slate-700 p-6 text-center bg-white/70 dark:bg-slate-900/60">
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">Sebaran belum muncul</p>
                <p class="mt-1 text-sm text-slate-500">Lengkapi data kota di profil agar jaringan Anda tampil lebih kredibel.</p>
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center mt-4 rounded-2xl bg-brand px-4 py-2.5 text-sm font-semibold text-white">Lengkapi Profil</a>
            </div>
        @else
            <div class="space-y-2">
                @foreach ($sebaran->sortByDesc('jumlah')->take(6) as $s)
                    <div class="flex items-center justify-between rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 px-4 py-3.5 shadow-sm">
                        <div>
                            <span class="text-sm font-semibold">{{ $s->kota ?: 'Tidak diketahui' }}</span>
                            <p class="text-xs text-slate-500 mt-0.5">Node alumni aktif</p>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-brand/10 px-3 py-1 text-sm font-bold text-brand">{{ $s->jumlah }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Temukan Relasi --}}
    <section class="mb-6">
        <div class="flex items-end justify-between gap-3 mb-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Curated Match</p>
                <h2 class="font-bold text-slate-900 dark:text-white">Temukan Relasi</h2>
            </div>
            <a href="{{ route('alumni.index') }}" class="text-xs text-brand font-semibold">Lihat direktori</a>
        </div>
        @if ($rekomendasi->isEmpty())
            <div class="rounded-3xl border border-dashed border-slate-300 dark:border-slate-700 p-6 text-center bg-white/70 dark:bg-slate-900/60">
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">Relasi strategis belum tersedia</p>
                <p class="mt-1 text-sm text-slate-500">Lengkapi profesi dan bidang usaha agar sistem bisa mencarikan koneksi yang lebih relevan.</p>
                <div class="mt-4 flex flex-col gap-2">
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center rounded-2xl bg-brand px-4 py-2.5 text-sm font-semibold text-white">Lengkapi Profil</a>
                    <a href="{{ route('alumni.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-200">Jelajahi Direktori</a>
                </div>
            </div>
        @else
            <div class="flex gap-3 overflow-x-auto pb-2 -mx-4 px-4">
                @foreach ($rekomendasi as $r)
                    <div class="shrink-0 w-44 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 shadow-sm">
                        <div class="w-12 h-12 rounded-full bg-teal-50 text-brand grid place-items-center font-bold mb-3">{{ strtoupper(substr($r->user->name, 0, 1)) }}</div>
                        <p class="font-semibold text-sm truncate">{{ $r->user->name }}</p>
                        <p class="text-xs text-slate-500 truncate mt-1">{{ $r->profesi ?: 'Alumni' }}</p>
                        <p class="text-[11px] text-slate-400 mt-1 line-clamp-2">{{ $r->bidang_usaha ?: 'Potensial untuk dijajaki sebagai koneksi profesional.' }}</p>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $r->user->phone) }}" target="_blank"
                           class="mt-3 block text-center text-xs font-semibold text-brand bg-teal-50 dark:bg-teal-950/40 rounded-2xl py-2">Hubungi Sekarang</a>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Event terdekat --}}
    <section>
        <div class="flex items-center justify-between mb-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Upcoming Agenda</p>
                <h2 class="font-bold text-slate-900 dark:text-white">Event Terdekat</h2>
            </div>
            <a href="{{ route('events.index') }}" class="text-xs text-brand font-semibold">Lihat semua</a>
        </div>
        @forelse ($eventTerdekat as $e)
            <a href="{{ route('events.show', $e) }}" class="flex items-center gap-3 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3.5 mb-2 shadow-sm">
                <div class="w-14 h-14 rounded-2xl bg-teal-50 text-brand grid place-items-center shrink-0">
                    <div class="text-center leading-none">
                        <p class="text-base font-extrabold">{{ $e->mulai_at->format('d') }}</p>
                        <p class="text-[9px] uppercase">{{ $e->mulai_at->format('M') }}</p>
                    </div>
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-sm truncate">{{ $e->judul }}</p>
                    <p class="text-xs text-slate-500 truncate mt-1">{{ $e->lokasi ?: 'Online' }} · {{ $e->mulai_at->format('H:i') }}</p>
                    <p class="text-[11px] text-brand font-medium mt-1">Siapkan kehadiran Anda lebih awal</p>
                </div>
            </a>
        @empty
            <div class="rounded-3xl border border-dashed border-slate-300 dark:border-slate-700 p-6 text-center bg-white/70 dark:bg-slate-900/60">
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">Belum ada event terdekat</p>
                <p class="mt-1 text-sm text-slate-500">Pantau agenda terbaru dan ikut serta saat program komunitas dibuka kembali.</p>
                <a href="{{ route('events.index') }}" class="inline-flex items-center justify-center mt-4 rounded-2xl bg-brand px-4 py-2.5 text-sm font-semibold text-white">Lihat Semua Event</a>
            </div>
        @endforelse
    </section>
@endsection
