@extends('layouts.app')
@section('title', $business->exists ? 'Edit Portofolio' : 'Tambah Portofolio')
@section('content')
    @php
        $user = auth()->user();
        $percentage = $user->onboardingCompletionPercentage();
    @endphp

    <h1 class="text-xl font-bold mb-4">{{ $business->exists ? 'Edit Portofolio' : 'Tambah Portofolio' }}</h1>

    <section class="mb-5 rounded-[28px] border border-brand/10 bg-white dark:bg-slate-900 p-5 shadow-sm">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Portfolio Step</p>
                <h2 class="mt-2 text-lg font-bold text-slate-900 dark:text-white">Tunjukkan karya, usaha, atau fokus profesional Anda</h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-500">
                    Portofolio ini membantu pengurus memvalidasi profil dan membantu alumni lain memahami potensi kolaborasi Anda dengan cepat.
                </p>
            </div>
            <div class="rounded-2xl bg-brand/10 text-brand px-3 py-2 text-right">
                <p class="text-[11px] uppercase tracking-[0.18em]">Progress</p>
                <p class="text-lg font-bold">{{ $percentage }}%</p>
            </div>
        </div>
        <div class="mt-4 h-2 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
            <div class="h-full rounded-full bg-brand" style="width: {{ $percentage }}%"></div>
        </div>
    </section>

    <form method="POST" action="{{ $business->exists ? route('business.update', $business) : route('business.store') }}" enctype="multipart/form-data" class="space-y-3.5">
        @csrf
        @if ($business->exists) @method('PUT') @endif
        <div>
            <label class="block text-sm font-medium mb-1.5">Logo portofolio (JPG/PNG, maks 2MB)</label>
            <input type="file" name="logo" accept="image/*" class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-teal-50 file:text-brand file:text-sm">
            @if ($business->logo_path)<img src="{{ Storage::url($business->logo_path) }}" class="w-16 h-16 rounded-xl object-cover mt-2" alt="">@endif
        </div>
        <input name="nama" value="{{ old('nama', $business->nama) }}" required placeholder="Nama portofolio, usaha, atau brand"
               class="w-full rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:border-brand focus:ring-brand text-sm">
        <div class="grid grid-cols-2 gap-3">
            <input name="kategori" value="{{ old('kategori', $business->kategori) }}" placeholder="Kategori atau fokus"
                   class="rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:border-brand focus:ring-brand text-sm">
            <input name="kota" value="{{ old('kota', $business->kota) }}" placeholder="Kota"
                   class="rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:border-brand focus:ring-brand text-sm">
        </div>
        <input name="kontak_wa" value="{{ old('kontak_wa', $business->kontak_wa) }}" placeholder="No. WhatsApp utama"
               class="w-full rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:border-brand focus:ring-brand text-sm">
        <textarea name="deskripsi" rows="4" placeholder="Ceritakan layanan, karya, keahlian, atau nilai utama Anda"
                  class="w-full rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:border-brand focus:ring-brand text-sm">{{ old('deskripsi', $business->deskripsi) }}</textarea>
        <p class="text-xs text-slate-400">Portofolio akan ditinjau admin sebelum tampil di directory jaringan.</p>
        <button class="w-full py-3.5 rounded-2xl bg-brand text-white font-semibold active:scale-95 transition">
            @if ($business->exists)
                Simpan Portofolio
            @elseif ($user->needsProfileCompletion())
                Simpan & Kembali ke Profil
            @else
                Simpan & Selesaikan Profiling
            @endif
        </button>
    </form>
@endsection
