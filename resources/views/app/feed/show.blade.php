@extends('layouts.app')
@section('title', $post->judul)
@section('content')
    <a href="{{ route('feed.index') }}" class="mb-3 inline-flex items-center rounded-2xl bg-white/80 px-3 py-2 text-sm text-slate-500 shadow-sm backdrop-blur dark:bg-slate-900/80 dark:text-slate-300">← Kembali</a>
    <article class="overflow-hidden rounded-[30px] border border-white/70 bg-white/85 shadow-[0_12px_32px_rgba(15,23,42,0.06)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/88 dark:shadow-[0_12px_32px_rgba(2,6,23,0.32)]">
        @if ($post->gambar_path)<img src="{{ Storage::url($post->gambar_path) }}" class="h-52 w-full object-cover" alt="">@endif
        <div class="p-5">
            <span class="text-[10px] font-bold uppercase text-brand">{{ $post->tipe }}</span>
            <h1 class="mt-1 text-xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $post->judul }}</h1>
            <p class="text-[11px] text-slate-400 mt-1">{{ $post->author->name }} · {{ $post->published_at?->translatedFormat('d M Y') }}</p>
            <div class="prose prose-sm dark:prose-invert max-w-none mt-4 text-slate-600 dark:text-slate-300">{!! $post->konten !!}</div>
        </div>
    </article>
@endsection
