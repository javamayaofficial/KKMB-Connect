@extends('layouts.app')
@section('title', $business->exists ? 'Edit Portofolio' : 'Tambah Portofolio')
@section('content')
    @php
        $user = auth()->user();
        $percentage = $user->onboardingCompletionPercentage();
    @endphp

    <div class="mb-4">
        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Portfolio Studio</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $business->exists ? 'Edit Portofolio' : 'Tambah Portofolio' }}</h1>
    </div>

    <section class="mb-5 rounded-[30px] border border-white/70 bg-white/82 p-5 shadow-[0_12px_32px_rgba(15,23,42,0.06)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/88 dark:shadow-[0_12px_32px_rgba(2,6,23,0.32)]">
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
        <div class="mt-4 rounded-2xl bg-slate-50 px-4 py-3 text-xs leading-relaxed text-slate-500 dark:bg-slate-950/70 dark:text-slate-400">
            Buat tampilan profesional Anda lebih kuat dengan nama brand yang jelas, narasi singkat, dan kontak yang mudah dihubungi.
        </div>
    </section>

    <form method="POST" action="{{ $business->exists ? route('business.update', $business) : route('business.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @if ($business->exists) @method('PUT') @endif
        <section class="rounded-[30px] border border-white/70 bg-white/82 p-4 shadow-[0_12px_32px_rgba(15,23,42,0.06)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/88 dark:shadow-[0_12px_32px_rgba(2,6,23,0.32)]">
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Brand Presence</p>
            <div class="mt-4 space-y-3.5">
                <div>
                    <label class="mb-1.5 block text-sm font-medium">Logo portofolio (JPG/PNG, maks 2MB)</label>
                    <input type="file" name="logo" accept="image/*" class="w-full text-sm text-slate-500 file:mr-3 file:rounded-xl file:border-0 file:bg-teal-50 file:px-4 file:py-2 file:text-sm file:text-brand dark:file:bg-teal-950/40">
                    @if ($business->logo_path)<img src="{{ Storage::url($business->logo_path) }}" class="mt-3 h-16 w-16 rounded-2xl object-cover ring-1 ring-brand/10" alt="">@endif
                </div>
                <input name="nama" value="{{ old('nama', $business->nama) }}" required placeholder="Nama portofolio, usaha, atau brand"
                       class="w-full rounded-2xl border-slate-200 bg-white/80 text-sm focus:border-brand focus:ring-brand dark:border-slate-700 dark:bg-slate-950">
                <div class="grid grid-cols-2 gap-3">
                    <input name="kategori" value="{{ old('kategori', $business->kategori) }}" placeholder="Kategori atau fokus"
                           class="rounded-2xl border-slate-200 bg-white/80 text-sm focus:border-brand focus:ring-brand dark:border-slate-700 dark:bg-slate-950">
                    <input name="kota" value="{{ old('kota', $business->kota) }}" placeholder="Kota"
                           class="rounded-2xl border-slate-200 bg-white/80 text-sm focus:border-brand focus:ring-brand dark:border-slate-700 dark:bg-slate-950">
                </div>
                <input name="kontak_wa" value="{{ old('kontak_wa', $business->kontak_wa) }}" placeholder="No. WhatsApp utama"
                       class="w-full rounded-2xl border-slate-200 bg-white/80 text-sm focus:border-brand focus:ring-brand dark:border-slate-700 dark:bg-slate-950">
                <textarea name="deskripsi" rows="5" placeholder="Ceritakan layanan, karya, keahlian, atau nilai utama Anda"
                          class="w-full rounded-2xl border-slate-200 bg-white/80 text-sm focus:border-brand focus:ring-brand dark:border-slate-700 dark:bg-slate-950">{{ old('deskripsi', $business->deskripsi) }}</textarea>
            </div>
        </section>
        <div class="rounded-[28px] border border-brand/10 bg-brand/5 px-4 py-3 text-xs leading-relaxed text-slate-500 dark:bg-brand/10 dark:text-slate-300">
            Portofolio akan ditinjau admin sebelum tampil di directory jaringan. Semakin jelas narasi dan kontaknya, semakin tinggi peluang relasi menghubungi Anda.
        </div>
        <button class="w-full rounded-[24px] bg-brand py-4 text-sm font-semibold text-white shadow-[0_18px_40px_rgba(14,124,134,0.28)] transition active:scale-95">
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
