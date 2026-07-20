@extends('layouts.app')
@section('title', 'Feed Komunitas')
@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-bold">Feed Komunitas</h1>
        <a href="{{ route('feed.create') }}" class="text-sm font-semibold text-white bg-brand px-3 py-1.5 rounded-xl">+ Tulis</a>
    </div>
    @forelse ($posts as $p)
        <a href="{{ route('feed.show', $p) }}" class="block rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 overflow-hidden mb-3">
            @if ($p->gambar_path)<img src="{{ Storage::url($p->gambar_path) }}" class="w-full h-40 object-cover" alt="">@endif
            <div class="p-4">
                <span class="text-[10px] font-bold uppercase text-brand">{{ $p->tipe }}</span>
                <p class="font-semibold mt-1">{{ $p->judul }}</p>
                <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ strip_tags($p->konten) }}</p>
                <p class="text-[11px] text-slate-400 mt-2">{{ $p->author->name }} · {{ $p->published_at?->diffForHumans() }}</p>
            </div>
        </a>
    @empty
        <div class="rounded-2xl border border-dashed border-slate-200 dark:border-slate-800 p-10 text-center">
            <p class="text-sm text-slate-400">Belum ada postingan.</p>
        </div>
    @endforelse
    <div class="mt-4">{{ $posts->links() }}</div>
@endsection
