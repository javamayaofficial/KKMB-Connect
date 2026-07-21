@extends('layouts.app')
@section('title', 'Kartu Keanggotaan')
@section('content')
    <section class="mb-5 rounded-[30px] border border-brand/10 bg-[linear-gradient(145deg,rgba(14,124,134,1),rgba(15,94,90,0.94),rgba(2,6,23,0.96))] p-5 text-white shadow-[0_18px_55px_rgba(15,94,90,0.18)]">
        <p class="text-xs uppercase tracking-[0.22em] text-white/60">Member Pass</p>
        <h1 class="mt-2 text-2xl font-bold tracking-tight">Kartu Keanggotaan Digital</h1>
        <p class="mt-2 text-sm leading-relaxed text-white/75">Tampilkan identitas anggota resmi KKMB dengan kartu digital yang lebih premium dan siap dipakai untuk verifikasi.</p>
    </section>

    <div class="rounded-[34px] bg-gradient-to-br from-brand-dark via-brand to-slate-950 p-6 text-white shadow-xl shadow-teal-700/20">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2">
                <span class="grid h-10 w-10 place-items-center rounded-xl bg-white p-1.5 shadow-sm">
                    <img src="/images/kkmb-logo-solid.png" alt="Logo KKMB" class="h-full w-full object-contain">
                </span>
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
