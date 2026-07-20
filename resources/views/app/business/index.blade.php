@extends('layouts.app')
@section('title', 'Bisnis Saya')
@section('content')
    @php
        $user = auth()->user();
        $percentage = $user->onboardingCompletionPercentage();
    @endphp

    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-bold">Portofolio Saya</h1>
        <a href="{{ route('business.create') }}" class="text-sm font-semibold text-white bg-brand px-3 py-1.5 rounded-xl">+ Tambah</a>
    </div>
    <section class="mb-4 rounded-3xl border border-brand/10 bg-white dark:bg-slate-900 p-4 shadow-sm">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Onboarding</p>
                <h2 class="mt-1 font-bold text-slate-900 dark:text-white">Lengkapi profil profesional Anda</h2>
                <p class="mt-1 text-sm text-slate-500">Tambahkan minimal satu portofolio, usaha, atau brand agar profil anggota Anda terlihat utuh dan kredibel.</p>
            </div>
            <div class="rounded-2xl bg-brand/10 px-3 py-2 text-right text-brand">
                <p class="text-[11px] uppercase tracking-[0.18em]">Progress</p>
                <p class="text-lg font-bold">{{ $percentage }}%</p>
            </div>
        </div>
    </section>
    @forelse ($businesses as $b)
        <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-4 mb-2.5">
            <div class="flex items-center justify-between">
                <p class="font-semibold text-sm">{{ $b->nama }}</p>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full
                    @if($b->status==='approved') bg-teal-100 text-teal-700
                    @elseif($b->status==='pending') bg-amber-100 text-amber-700
                    @else bg-rose-100 text-rose-700 @endif">
                    {{ ['pending'=>'Menunggu','approved'=>'Tampil','rejected'=>'Ditolak'][$b->status] }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">{{ $b->kategori ?: 'Umum' }}@if($b->kota) · {{ $b->kota }}@endif</p>
            <a href="{{ route('business.edit', $b) }}" class="inline-block mt-2 text-xs font-semibold text-brand">Edit →</a>
        </div>
    @empty
        <div class="rounded-2xl border border-dashed border-slate-200 dark:border-slate-800 p-10 text-center">
            <p class="text-sm text-slate-400 mb-3">Belum ada portofolio terdaftar.</p>
            <a href="{{ route('business.create') }}" class="text-sm font-semibold text-brand">Tambah portofolio pertama →</a>
        </div>
    @endforelse
@endsection
