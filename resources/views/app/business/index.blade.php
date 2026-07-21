@extends('layouts.app')
@section('title', 'Bisnis Saya')
@section('content')
    @php
        $user = auth()->user();
        $percentage = $user->onboardingCompletionPercentage();
    @endphp

    <div class="mb-4 flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Portfolio Hub</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Portofolio Saya</h1>
        </div>
        <a href="{{ route('business.create') }}" class="inline-flex items-center rounded-2xl bg-brand px-3.5 py-2 text-sm font-semibold text-white shadow-[0_12px_30px_rgba(14,124,134,0.28)]">+ Tambah</a>
    </div>
    <section class="mb-4 rounded-[30px] border border-white/70 bg-white/82 p-4 shadow-[0_12px_32px_rgba(15,23,42,0.06)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/88 dark:shadow-[0_12px_32px_rgba(2,6,23,0.32)]">
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
        <div class="mb-2.5 rounded-[28px] border border-white/70 bg-white/82 p-4 shadow-[0_12px_32px_rgba(15,23,42,0.06)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/88 dark:shadow-[0_12px_32px_rgba(2,6,23,0.32)]">
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
            <p class="mt-1 text-[11px] text-slate-400">{{ \Illuminate\Support\Str::limit($b->deskripsi ?: 'Tambahkan narasi singkat agar alumni lain cepat memahami fokus Anda.', 82) }}</p>
            <a href="{{ route('business.edit', $b) }}" class="inline-block mt-3 rounded-2xl bg-brand/10 px-3 py-2 text-xs font-semibold text-brand">Edit Portofolio</a>
        </div>
    @empty
        <div class="rounded-[28px] border border-dashed border-slate-300 p-10 text-center bg-white/80 shadow-sm dark:border-slate-800 dark:bg-slate-900/60">
            <p class="text-sm text-slate-400 mb-3">Belum ada portofolio terdaftar.</p>
            <a href="{{ route('business.create') }}" class="text-sm font-semibold text-brand">Tambah portofolio pertama →</a>
        </div>
    @endforelse
@endsection
