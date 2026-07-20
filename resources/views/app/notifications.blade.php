@extends('layouts.app')
@section('title', 'Notifikasi')
@section('content')
    <h1 class="text-xl font-bold mb-4">Notifikasi</h1>
    @forelse ($notifications as $n)
        <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-4 mb-2.5">
            <div class="flex items-start justify-between gap-2">
                <p class="font-semibold text-sm">{{ $n->judul }}</p>
                <span class="text-[10px] text-slate-400 shrink-0">{{ $n->created_at->diffForHumans() }}</span>
            </div>
            <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $n->pesan }}</p>
            @if ($n->url)<a href="{{ $n->url }}" class="inline-block mt-2 text-xs font-semibold text-brand">Buka →</a>@endif
        </div>
    @empty
        <div class="rounded-2xl border border-dashed border-slate-200 dark:border-slate-800 p-10 text-center">
            <p class="text-sm text-slate-400">Belum ada notifikasi.</p>
        </div>
    @endforelse
    <div class="mt-4">{{ $notifications->links() }}</div>
@endsection
