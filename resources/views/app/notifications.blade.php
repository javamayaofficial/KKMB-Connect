@extends('layouts.app')
@section('title', 'Notifikasi')
@section('content')
    <section class="mb-5 rounded-[30px] border border-brand/10 bg-[linear-gradient(145deg,rgba(14,124,134,1),rgba(15,94,90,0.94),rgba(2,6,23,0.96))] p-5 text-white shadow-[0_18px_55px_rgba(15,94,90,0.18)]">
        <p class="text-xs uppercase tracking-[0.22em] text-white/60">Alert Center</p>
        <h1 class="mt-2 text-2xl font-bold tracking-tight">Notifikasi</h1>
        <p class="mt-2 text-sm leading-relaxed text-white/75">Pantau update akun, event, pembayaran, dan broadcast penting dalam satu layar yang rapi untuk mobile.</p>
    </section>
    @forelse ($notifications as $n)
        <div class="mb-2.5 rounded-[28px] border border-white/70 bg-white/85 p-4 shadow-[0_12px_32px_rgba(15,23,42,0.06)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/88 dark:shadow-[0_12px_32px_rgba(2,6,23,0.32)]">
            <div class="flex items-start justify-between gap-2">
                <p class="font-semibold text-sm">{{ $n->judul }}</p>
                <span class="text-[10px] text-slate-400 shrink-0">{{ $n->created_at->diffForHumans() }}</span>
            </div>
            <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $n->pesan }}</p>
            @if ($n->url)<a href="{{ $n->url }}" class="inline-block mt-2 text-xs font-semibold text-brand">Buka →</a>@endif
        </div>
    @empty
        <div class="rounded-[28px] border border-dashed border-slate-300 bg-white/80 p-10 text-center shadow-sm dark:border-slate-800 dark:bg-slate-900/60">
            <p class="text-sm text-slate-400">Belum ada notifikasi.</p>
        </div>
    @endforelse
    <div class="mt-4">{{ $notifications->links() }}</div>
@endsection
