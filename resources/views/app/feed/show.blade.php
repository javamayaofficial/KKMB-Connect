@extends('layouts.app')
@section('title', $post->judul)
@section('content')
    <a href="{{ route('feed.index') }}" class="text-sm text-slate-400 mb-3 inline-block">← Kembali</a>
    <article class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 overflow-hidden">
        @if ($post->gambar_path)<img src="{{ Storage::url($post->gambar_path) }}" class="w-full h-48 object-cover" alt="">@endif
        <div class="p-5">
            <span class="text-[10px] font-bold uppercase text-brand">{{ $post->tipe }}</span>
            <h1 class="text-lg font-bold mt-1">{{ $post->judul }}</h1>
            <p class="text-[11px] text-slate-400 mt-1">{{ $post->author->name }} · {{ $post->published_at?->translatedFormat('d M Y') }}</p>
            <div class="prose prose-sm dark:prose-invert max-w-none mt-4 text-slate-600 dark:text-slate-300">{!! $post->konten !!}</div>
        </div>
    </article>
@endsection
