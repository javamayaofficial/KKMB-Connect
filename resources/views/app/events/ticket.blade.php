@extends('layouts.app')
@section('title', 'Tiket Event')
@section('content')
    <section class="mb-5 rounded-[30px] border border-brand/10 bg-[linear-gradient(145deg,rgba(14,124,134,1),rgba(15,94,90,0.94),rgba(2,6,23,0.96))] p-5 text-white shadow-[0_18px_55px_rgba(15,94,90,0.18)]">
        <p class="text-xs uppercase tracking-[0.22em] text-white/60">Event Pass</p>
        <h1 class="mt-2 text-2xl font-bold tracking-tight">Tiket Event</h1>
        <p class="mt-2 text-sm leading-relaxed text-white/75">Tunjukkan QR ini saat check-in untuk pengalaman masuk yang cepat dan rapi dari HP.</p>
    </section>
    <div class="rounded-[32px] border border-white/70 bg-white/85 p-6 text-center shadow-[0_12px_32px_rgba(15,23,42,0.06)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/88 dark:shadow-[0_12px_32px_rgba(2,6,23,0.32)]">
        <p class="font-bold text-slate-900 dark:text-white">{{ $registration->event->judul }}</p>
        <p class="mt-1 text-xs text-slate-500">{{ $registration->event->mulai_at->translatedFormat('l, d M Y · H:i') }}</p>
        <div class="my-5 flex justify-center">
            <div class="rounded-[28px] border border-slate-100 bg-white p-4 shadow-sm">{!! $qrSvg !!}</div>
        </div>
        <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $registration->user->name }}</p>
        <span class="inline-block mt-2 rounded-full px-2.5 py-1 text-[11px] font-bold
            {{ $registration->status === 'checked_in' ? 'bg-teal-100 text-teal-700' : 'bg-amber-100 text-amber-700' }}">
            {{ $registration->status === 'checked_in' ? 'SUDAH CHECK-IN' : 'TERDAFTAR' }}
        </span>
        <p class="text-[11px] text-slate-400 mt-4">Tunjukkan QR ini ke panitia saat check-in.</p>
    </div>
@endsection
