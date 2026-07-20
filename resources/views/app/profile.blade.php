@extends('layouts.app')
@section('title', 'Profil Saya')
@section('content')
    @php
        $user = auth()->user();
        $checklist = $user->onboardingChecklist();
        $percentage = $user->onboardingCompletionPercentage();
    @endphp

    <h1 class="text-xl font-bold mb-4">Profil Saya</h1>

    <section class="mb-5 rounded-[28px] border border-brand/10 bg-[linear-gradient(145deg,rgba(14,124,134,1),rgba(15,94,90,0.95),rgba(2,6,23,0.94))] p-5 text-white shadow-[0_18px_55px_rgba(15,94,90,0.18)]">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.22em] text-white/60">Profiling Progress</p>
                <h2 class="mt-2 text-xl font-bold">Lengkapi profil anggota Anda</h2>
                <p class="mt-2 text-sm leading-relaxed text-white/75">
                    Pengurus dan sesama alumni akan lebih mudah mengenali kompetensi, domisili, dan potensi kolaborasi Anda jika profil diisi lengkap.
                </p>
            </div>
            <div class="rounded-2xl border border-white/15 bg-white/10 px-3 py-2 text-right">
                <p class="text-[11px] text-white/60">Kelengkapan</p>
                <p class="text-lg font-bold">{{ $percentage }}%</p>
            </div>
        </div>
        <div class="mt-4 h-2 rounded-full bg-white/10 overflow-hidden">
            <div class="h-full rounded-full bg-white" style="width: {{ $percentage }}%"></div>
        </div>
        <div class="mt-4 grid grid-cols-2 gap-2 text-xs">
            <div class="rounded-2xl bg-white/8 px-3 py-2">Identitas: {{ $checklist['identitas'] ? 'Lengkap' : 'Belum lengkap' }}</div>
            <div class="rounded-2xl bg-white/8 px-3 py-2">Angkatan: {{ $checklist['angkatan'] ? 'Lengkap' : 'Belum lengkap' }}</div>
            <div class="rounded-2xl bg-white/8 px-3 py-2">Domisili: {{ $checklist['domisili'] ? 'Lengkap' : 'Belum lengkap' }}</div>
            <div class="rounded-2xl bg-white/8 px-3 py-2">Profesional: {{ $checklist['profesional'] ? 'Lengkap' : 'Belum lengkap' }}</div>
            <div class="rounded-2xl bg-white/8 px-3 py-2">Narasi: {{ $checklist['narasi'] ? 'Lengkap' : 'Belum lengkap' }}</div>
            <div class="rounded-2xl bg-white/8 px-3 py-2">Portofolio: {{ $checklist['portofolio'] ? 'Tersedia' : 'Perlu ditambahkan' }}</div>
        </div>
    </section>

    <div class="flex items-center gap-3 mb-5">
        @if ($profile->foto_path)
            <img src="{{ Storage::url($profile->foto_path) }}" class="w-16 h-16 rounded-2xl object-cover" alt="">
        @else
            <div class="w-16 h-16 rounded-2xl bg-teal-50 text-brand grid place-items-center text-2xl font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        @endif
        <div>
            <p class="font-bold">{{ auth()->user()->name }}</p>
            <p class="text-xs text-slate-500">{{ $profile->member_number ?: 'Belum bernomor' }}</p>
            @if ($profile->isVerified())
                <a href="{{ route('profile.card') }}" class="inline-block mt-1 text-xs font-semibold text-brand">Lihat Kartu Digital →</a>
            @endif
        </div>
    </div>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-3.5">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium mb-1.5">Foto (JPG/PNG, maks 2MB)</label>
            <input type="file" name="foto" accept="image/*" class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-teal-50 file:text-brand file:text-sm">
        </div>
        <input name="name" value="{{ old('name', auth()->user()->name) }}" required placeholder="Nama"
               class="w-full rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:border-brand focus:ring-brand text-sm">
        <input name="phone" value="{{ old('phone', auth()->user()->phone) }}" required placeholder="No. WhatsApp"
               class="w-full rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:border-brand focus:ring-brand text-sm">
        <div class="grid grid-cols-2 gap-3">
            <input name="angkatan" value="{{ old('angkatan', $profile->angkatan) }}" placeholder="Angkatan"
                   class="rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:border-brand focus:ring-brand text-sm">
            <input name="kota" value="{{ old('kota', $profile->kota) }}" placeholder="Kota"
                   class="rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:border-brand focus:ring-brand text-sm">
        </div>
        <input name="profesi" value="{{ old('profesi', $profile->profesi) }}" placeholder="Profesi"
               class="w-full rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:border-brand focus:ring-brand text-sm">
        <input name="bidang_usaha" value="{{ old('bidang_usaha', $profile->bidang_usaha) }}" placeholder="Bidang usaha"
               class="w-full rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:border-brand focus:ring-brand text-sm">
        <input name="negara" value="{{ old('negara', $profile->negara) }}" placeholder="Negara"
               class="w-full rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:border-brand focus:ring-brand text-sm">
        <textarea name="bio" rows="3" placeholder="Bio singkat"
                  class="w-full rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:border-brand focus:ring-brand text-sm">{{ old('bio', $profile->bio) }}</textarea>
        <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
            <input type="checkbox" name="is_visible" value="1" @checked($profile->is_visible) class="rounded border-slate-300 text-brand focus:ring-brand">
            Tampilkan profil saya di directory
        </label>
        <button class="w-full py-3.5 rounded-2xl bg-brand text-white font-semibold active:scale-95 transition">
            {{ $user->needsPortfolioCompletion() ? 'Simpan & Lanjutkan ke Portofolio' : 'Simpan Perubahan' }}
        </button>
    </form>
@endsection
