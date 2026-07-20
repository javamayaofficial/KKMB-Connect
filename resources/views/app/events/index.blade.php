@extends('layouts.app')
@section('title', 'Event')
@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-bold">Event</h1>
        <a href="{{ route('events.mine') }}" class="text-xs font-semibold text-brand">Tiket saya</a>
    </div>
    @forelse ($events as $e)
        <a href="{{ route('events.show', $e) }}" class="block rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 overflow-hidden mb-3">
            @if ($e->poster_path)<img src="{{ Storage::url($e->poster_path) }}" class="w-full h-32 object-cover" alt="">@endif
            <div class="p-4">
                <p class="font-semibold">{{ $e->judul }}</p>
                <p class="text-xs text-slate-500 mt-1">{{ $e->mulai_at->translatedFormat('l, d M Y · H:i') }}</p>
                <p class="text-xs text-slate-500">{{ $e->lokasi ?: 'Online' }}</p>
                <div class="flex items-center gap-2 mt-2">
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
        <div class="rounded-2xl border border-dashed border-slate-200 dark:border-slate-800 p-10 text-center">
            <p class="text-sm text-slate-400">Belum ada event.</p>
        </div>
    @endforelse
    <div class="mt-4">{{ $events->links() }}</div>
@endsection
