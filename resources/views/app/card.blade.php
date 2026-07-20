@extends('layouts.app')
@section('title', 'Kartu Keanggotaan')
@section('content')
    <h1 class="text-xl font-bold mb-4">Kartu Keanggotaan Digital</h1>

    <div class="rounded-3xl bg-gradient-to-br from-brand-dark to-brand text-white p-6 shadow-xl shadow-teal-700/20">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-white/20 grid place-items-center font-extrabold">K</span>
                <span class="font-bold text-sm">KKMB Connect</span>
            </div>
            <span class="text-[10px] uppercase tracking-widest text-white/60">Member</span>
        </div>
        <div class="flex items-center gap-4">
            <div class="bg-white p-2 rounded-xl shrink-0">{!! $qrSvg !!}</div>
            <div class="min-w-0">
                <p class="font-bold text-lg leading-tight">{{ auth()->user()->name }}</p>
                <p class="text-white/70 text-sm">{{ $profile->member_number }}</p>
                <p class="text-white/70 text-xs mt-1">{{ $profile->profesi ?: 'Alumni' }}</p>
                @if ($profile->angkatan)<p class="text-white/70 text-xs">Angkatan {{ $profile->angkatan }}</p>@endif
            </div>
        </div>
        <div class="mt-6 pt-4 border-t border-white/15 flex justify-between items-center">
            <span class="text-[10px] text-white/50">Terverifikasi {{ $profile->verified_at?->format('M Y') }}</span>
            <span class="text-[10px] text-white/50">Satu Jaringan, Ribuan Peluang</span>
        </div>
    </div>

    <p class="text-center text-xs text-slate-400 mt-4">Tunjukkan QR ini saat verifikasi keanggotaan atau check-in event.</p>
@endsection
