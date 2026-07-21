@extends('layouts.app')
@section('title', 'Event')
@section('content')
    <section class="mb-5 rounded-[30px] border border-brand/10 bg-[linear-gradient(145deg,rgba(14,124,134,1),rgba(15,94,90,0.94),rgba(2,6,23,0.96))] p-5 text-white shadow-[0_18px_55px_rgba(15,94,90,0.18)]">
        <div class="flex items-center justify-between gap-3">
            <div>
                <p class="text-xs uppercase tracking-[0.22em] text-white/60">Upcoming Agenda</p>
                <h1 class="mt-2 text-2xl font-bold tracking-tight">Event</h1>
                <p class="mt-2 text-sm leading-relaxed text-white/75">Lihat agenda terbaru, cek kuota, dan daftar event langsung dari tampilan mobile yang lebih premium.</p>
            </div>
            <a href="{{ route('events.mine') }}" class="shrink-0 rounded-2xl border border-white/15 bg-white/10 px-3 py-2 text-xs font-semibold text-white">Tiket saya</a>
        </div>
    </section>
    @forelse ($events as $e)
        <a href="{{ route('events.show', $e) }}" class="mb-3 block overflow-hidden rounded-[30px] border border-white/70 bg-white/82 shadow-[0_12px_32px_rgba(15,23,42,0.06)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/88 dark:shadow-[0_12px_32px_rgba(2,6,23,0.32)]">
            @if ($e->poster_path)<img src="{{ Storage::url($e->poster_path) }}" class="h-36 w-full object-cover" alt="">@endif
            <div class="p-4">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="font-semibold text-slate-900 dark:text-white">{{ $e->judul }}</p>
                        <p class="mt-1 text-xs text-slate-500">{{ $e->mulai_at->translatedFormat('l, d M Y · H:i') }}</p>
                        <p class="text-xs text-slate-500">{{ $e->lokasi ?: 'Online' }}</p>
                    </div>
                    <span class="rounded-full bg-brand/10 px-2.5 py-1 text-[11px] font-bold text-brand">{{ $e->is_paid ? 'Berbayar' : 'Gratis' }}</span>
                </div>
                <div class="mt-3 flex items-center gap-2">
                    @if ($e->is_paid)
                        <span class="text-xs font-bold text-brand">Rp{{ number_format($e->harga, 0, ',', '.') }}</span>
                    @else
                        <span class="text-xs font-bold text-teal-600">Gratis</span>
                    @endif
                    @if (!is_null($e->sisaKuota()))<span class="text-[11px] text-slate-400">· sisa {{ $e->sisaKuota() }} kuota</span>@endif
                </div>
            </div>
        </a>
    @empty
        <div class="rounded-[28px] border border-dashed border-slate-300 bg-white/80 p-10 text-center shadow-sm dark:border-slate-800 dark:bg-slate-900/60">
            <p class="text-sm text-slate-400">Belum ada event.</p>
        </div>
    @endforelse
    <div class="mt-4">{{ $events->links() }}</div>
@endsection
