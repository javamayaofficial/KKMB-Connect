@extends('layouts.app')
@section('title', 'Feed Komunitas')
@section('content')
    <section class="mb-5 rounded-[30px] border border-brand/10 bg-[linear-gradient(145deg,rgba(14,124,134,1),rgba(15,94,90,0.94),rgba(2,6,23,0.96))] p-5 text-white shadow-[0_18px_55px_rgba(15,94,90,0.18)]">
        <div class="flex items-center justify-between gap-3">
            <div>
                <p class="text-xs uppercase tracking-[0.22em] text-white/60">Community Pulse</p>
                <h1 class="mt-2 text-2xl font-bold tracking-tight">Feed Komunitas</h1>
                <p class="mt-2 text-sm leading-relaxed text-white/75">Ikuti update komunitas, insight alumni, dan pengumuman penting dalam tampilan mobile yang lebih hidup.</p>
            </div>
            <a href="{{ route('feed.create') }}" class="shrink-0 rounded-2xl border border-white/15 bg-white/10 px-3 py-2 text-sm font-semibold text-white">+ Tulis</a>
        </div>
    </section>
    @forelse ($posts as $p)
        <a href="{{ route('feed.show', $p) }}" class="mb-3 block overflow-hidden rounded-[30px] border border-white/70 bg-white/85 shadow-[0_12px_32px_rgba(15,23,42,0.06)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/88 dark:shadow-[0_12px_32px_rgba(2,6,23,0.32)]">
            @if ($p->gambar_path)<img src="{{ Storage::url($p->gambar_path) }}" class="h-40 w-full object-cover" alt="">@endif
            <div class="p-4">
                <span class="text-[10px] font-bold uppercase text-brand">{{ $p->tipe }}</span>
                <p class="mt-1 font-semibold text-slate-900 dark:text-white">{{ $p->judul }}</p>
                <p class="mt-1 line-clamp-2 text-xs text-slate-500">{{ strip_tags($p->konten) }}</p>
                <p class="mt-2 text-[11px] text-slate-400">{{ $p->author->name }} · {{ $p->published_at?->diffForHumans() }}</p>
            </div>
        </a>
    @empty
        <div class="rounded-[28px] border border-dashed border-slate-300 bg-white/80 p-10 text-center shadow-sm dark:border-slate-800 dark:bg-slate-900/60">
            <p class="text-sm text-slate-400">Belum ada postingan.</p>
        </div>
    @endforelse
    <div class="mt-4">{{ $posts->links() }}</div>
@endsection
