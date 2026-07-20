@extends('layouts.app')
@section('title', 'Tiket Saya')
@section('content')
    <h1 class="text-xl font-bold mb-4">Tiket Saya</h1>
    @forelse ($registrations as $r)
        <a href="{{ route('events.ticket', $r) }}" class="flex items-center justify-between rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-4 mb-2.5">
            <div>
                <p class="font-semibold text-sm">{{ $r->event->judul }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ $r->event->mulai_at->translatedFormat('d M Y · H:i') }}</p>
            </div>
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $r->status === 'checked_in' ? 'bg-teal-100 text-teal-700' : 'bg-amber-100 text-amber-700' }}">
                {{ $r->status === 'checked_in' ? 'HADIR' : 'TIKET' }}
            </span>
        </a>
    @empty
        <div class="rounded-2xl border border-dashed border-slate-200 dark:border-slate-800 p-10 text-center">
            <p class="text-sm text-slate-400">Belum ada tiket. Daftar event dulu!</p>
        </div>
    @endforelse
@endsection
