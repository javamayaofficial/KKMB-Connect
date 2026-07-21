@extends('layouts.guest')
@section('title', 'Menunggu Verifikasi')
@section('content')
    @php
        $user = auth()->user();
        $percentage = $user->onboardingCompletionPercentage();
    @endphp
    <div class="text-center">
        <div class="w-16 h-16 rounded-2xl bg-amber-50 grid place-items-center mx-auto mb-4">
            <svg class="w-8 h-8 text-brand-accent" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h2 class="text-lg font-bold mb-2">Menunggu Verifikasi</h2>
        <p class="text-sm text-slate-500 leading-relaxed mb-6">Terima kasih telah mendaftar. Akun Anda sedang ditinjau pengurus KKMB. Sambil menunggu, pastikan profil dan portofolio Anda sudah lengkap agar proses penilaian lebih cepat dan akurat.</p>
        <div class="mb-6 rounded-[30px] border border-slate-200 bg-white/90 p-4 text-left shadow-[0_12px_32px_rgba(15,23,42,0.08)] backdrop-blur">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-xs uppercase tracking-[0.18em] text-slate-400">Profiling</p>
                    <p class="font-semibold text-slate-900">Kelengkapan saat ini {{ $percentage }}%</p>
                </div>
                <span class="rounded-full bg-brand/10 px-3 py-1 text-xs font-semibold text-brand">{{ $user->onboardingCompletedCount() }}/{{ $user->onboardingTotalCount() }} selesai</span>
            </div>
            <div class="mt-3 h-2 rounded-full bg-slate-100 overflow-hidden">
                <div class="h-full rounded-full bg-brand" style="width: {{ $percentage }}%"></div>
            </div>
            <div class="mt-4 grid grid-cols-1 gap-2">
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center rounded-2xl bg-brand px-4 py-3 text-sm font-semibold text-white">Lengkapi Profil</a>
                <a href="{{ route('business.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700">Kelola Portofolio</a>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button class="text-sm text-brand font-semibold">Keluar</button>
        </form>
    </div>
@endsection
