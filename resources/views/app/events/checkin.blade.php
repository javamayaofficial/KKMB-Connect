@extends('layouts.app')
@section('title', 'Check-in Event')
@section('content')
    <h1 class="text-xl font-bold mb-2">QR Check-in</h1>
    <p class="text-sm text-slate-500 mb-4">Masukkan / tempel kode QR dari tiket peserta.</p>
    <form method="POST" action="{{ route('checkin.process') }}" class="space-y-3">
        @csrf
        <input name="qr_token" required autofocus placeholder="Kode QR peserta"
               class="w-full rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:border-brand focus:ring-brand text-sm">
        <button class="w-full py-3.5 rounded-2xl bg-brand text-white font-semibold active:scale-95 transition">Proses Check-in</button>
    </form>
    <p class="text-[11px] text-slate-400 mt-3">Tips: gunakan aplikasi scanner QR HP untuk membaca kode, lalu tempel di kolom di atas.</p>
@endsection
