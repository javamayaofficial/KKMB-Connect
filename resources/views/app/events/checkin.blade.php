@extends('layouts.app')
@section('title', 'Check-in Event')
@section('content')
    <section class="mb-5 rounded-[30px] border border-brand/10 bg-[linear-gradient(145deg,rgba(14,124,134,1),rgba(15,94,90,0.94),rgba(2,6,23,0.96))] p-5 text-white shadow-[0_18px_55px_rgba(15,94,90,0.18)]">
        <p class="text-xs uppercase tracking-[0.22em] text-white/60">Gate Access</p>
        <h1 class="mt-2 text-2xl font-bold tracking-tight">QR Check-in</h1>
        <p class="mt-2 text-sm leading-relaxed text-white/75">Tempel kode tiket peserta untuk memproses kehadiran dengan cepat saat event berlangsung.</p>
    </section>
    <form method="POST" action="{{ route('checkin.process') }}" class="rounded-[30px] border border-white/70 bg-white/85 p-5 shadow-[0_12px_32px_rgba(15,23,42,0.06)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/88 dark:shadow-[0_12px_32px_rgba(2,6,23,0.32)]">
        @csrf
        <input name="qr_token" required autofocus placeholder="Kode QR peserta"
               class="w-full rounded-2xl border-slate-200 bg-white/80 text-sm focus:border-brand focus:ring-brand dark:border-slate-700 dark:bg-slate-950">
        <button class="mt-4 w-full rounded-[24px] bg-brand py-4 text-white font-semibold shadow-[0_18px_40px_rgba(14,124,134,0.28)] transition active:scale-95">Proses Check-in</button>
        <p class="mt-3 text-[11px] text-slate-400">Tips: gunakan aplikasi scanner QR HP untuk membaca kode, lalu tempel di kolom di atas.</p>
    </form>
@endsection
