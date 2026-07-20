@extends('layouts.app')
@section('title', 'Tiket Event')
@section('content')
    <h1 class="text-xl font-bold mb-4">Tiket Event</h1>
    <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-6 text-center">
        <p class="font-bold">{{ $registration->event->judul }}</p>
        <p class="text-xs text-slate-500 mt-1">{{ $registration->event->mulai_at->translatedFormat('l, d M Y · H:i') }}</p>
        <div class="my-5 flex justify-center">
            <div class="p-3 bg-white rounded-2xl border border-slate-100">{!! $qrSvg !!}</div>
        </div>
        <p class="text-sm font-semibold">{{ $registration->user->name }}</p>
        <span class="inline-block mt-2 text-[11px] font-bold px-2.5 py-1 rounded-full
            {{ $registration->status === 'checked_in' ? 'bg-teal-100 text-teal-700' : 'bg-amber-100 text-amber-700' }}">
            {{ $registration->status === 'checked_in' ? 'SUDAH CHECK-IN' : 'TERDAFTAR' }}
        </span>
        <p class="text-[11px] text-slate-400 mt-4">Tunjukkan QR ini ke panitia saat check-in.</p>
    </div>
@endsection
