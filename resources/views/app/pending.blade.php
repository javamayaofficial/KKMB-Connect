@extends('layouts.guest')
@section('title', 'Menunggu Verifikasi')
@section('content')
    <div class="text-center">
        <div class="w-16 h-16 rounded-2xl bg-amber-50 grid place-items-center mx-auto mb-4">
            <svg class="w-8 h-8 text-brand-accent" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h2 class="text-lg font-bold mb-2">Menunggu Verifikasi</h2>
        <p class="text-sm text-slate-500 leading-relaxed mb-6">Terima kasih telah mendaftar. Akun Anda sedang ditinjau pengurus KKMB. Anda akan mendapat notifikasi WhatsApp & email begitu disetujui.</p>
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button class="text-sm text-brand font-semibold">Keluar</button>
        </form>
    </div>
@endsection
