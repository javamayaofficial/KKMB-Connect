@extends('layouts.app')
@section('title', 'Tiket Saya')
@section('content')
    <section class="mb-5 rounded-[30px] border border-brand/10 bg-[linear-gradient(145deg,rgba(14,124,134,1),rgba(15,94,90,0.94),rgba(2,6,23,0.96))] p-5 text-white shadow-[0_18px_55px_rgba(15,94,90,0.18)]">
        <p class="text-xs uppercase tracking-[0.22em] text-white/60">Ticket Wallet</p>
        <h1 class="mt-2 text-2xl font-bold tracking-tight">Tiket Saya</h1>
        <p class="mt-2 text-sm leading-relaxed text-white/75">Simpan seluruh tiket event Anda dalam satu tampilan yang nyaman untuk check-in dari HP.</p>
    </section>
    @forelse ($registrations as $r)
        <a href="{{ route('events.ticket', $r) }}" class="mb-2.5 flex items-center justify-between rounded-[28px] border border-white/70 bg-white/82 p-4 shadow-[0_12px_32px_rgba(15,23,42,0.06)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/88 dark:shadow-[0_12px_32px_rgba(2,6,23,0.32)]">
            <div>
                <p class="font-semibold text-sm">{{ $r->event->judul }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ $r->event->mulai_at->translatedFormat('d M Y · H:i') }}</p>
            </div>
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $r->status === 'checked_in' ? 'bg-teal-100 text-teal-700' : 'bg-amber-100 text-amber-700' }}">
                {{ $r->status === 'checked_in' ? 'HADIR' : 'TIKET' }}
            </span>
        </a>
    @empty
        <div class="rounded-[28px] border border-dashed border-slate-300 bg-white/80 p-10 text-center shadow-sm dark:border-slate-800 dark:bg-slate-900/60">
            <p class="text-sm text-slate-400">Belum ada tiket. Daftar event dulu!</p>
        </div>
    @endforelse
@endsection
