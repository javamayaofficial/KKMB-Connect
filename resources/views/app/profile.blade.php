@extends('layouts.app')
@section('title', 'Profil Saya')
@section('content')
    @php
        $user = auth()->user();
        $checklist = $user->onboardingChecklist();
        $percentage = $user->onboardingCompletionPercentage();
    @endphp

    <div class="mb-4 flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Member Identity</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Profil Saya</h1>
        </div>
        @if ($profile->isVerified())
            <a href="{{ route('profile.card') }}" class="inline-flex items-center rounded-2xl border border-brand/15 bg-brand/10 px-3 py-2 text-xs font-semibold text-brand">Kartu Digital</a>
        @endif
    </div>

    <section class="relative mb-5 overflow-hidden rounded-[32px] border border-brand/10 bg-[linear-gradient(145deg,rgba(14,124,134,1),rgba(15,94,90,0.95),rgba(2,6,23,0.94))] p-5 text-white shadow-[0_18px_55px_rgba(15,94,90,0.18)]">
        <div class="pointer-events-none absolute inset-x-0 top-0 h-24 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.2),transparent_65%)]"></div>
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

    <section class="mb-5 rounded-[30px] border border-white/70 bg-white/82 p-4 shadow-[0_12px_32px_rgba(15,23,42,0.06)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/88 dark:shadow-[0_12px_32px_rgba(2,6,23,0.32)]">
        <div class="flex items-center gap-3">
            @if ($profile->foto_path)
                <img src="{{ Storage::url($profile->foto_path) }}" class="h-16 w-16 rounded-[22px] object-cover ring-1 ring-brand/10" alt="">
            @else
                <div class="grid h-16 w-16 place-items-center rounded-[22px] bg-teal-50 text-2xl font-bold text-brand ring-1 ring-brand/10 dark:bg-teal-950/40">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            @endif
            <div class="min-w-0">
                <p class="truncate font-bold text-slate-900 dark:text-white">{{ auth()->user()->name }}</p>
                <p class="mt-1 text-xs text-slate-500">{{ $profile->member_number ?: 'Nomor anggota akan muncul setelah verifikasi' }}</p>
                <p class="mt-1 text-[11px] text-slate-400">{{ $profile->profesi ?: 'Lengkapi peran profesional Anda agar profil lebih kredibel' }}</p>
            </div>
        </div>
    </section>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf @method('PUT')
        <section class="rounded-[30px] border border-white/70 bg-white/82 p-4 shadow-[0_12px_32px_rgba(15,23,42,0.06)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/88 dark:shadow-[0_12px_32px_rgba(2,6,23,0.32)]">
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Identitas Dasar</p>
            <div class="mt-4 space-y-3.5">
                <div>
                    <label class="mb-1.5 block text-sm font-medium">Foto (JPG/PNG, maks 2MB)</label>
                    <input type="file" name="foto" accept="image/*" class="w-full text-sm text-slate-500 file:mr-3 file:rounded-xl file:border-0 file:bg-teal-50 file:px-4 file:py-2 file:text-sm file:text-brand dark:file:bg-teal-950/40">
                </div>
                <input name="name" value="{{ old('name', auth()->user()->name) }}" required placeholder="Nama lengkap"
                       class="w-full rounded-2xl border-slate-200 bg-white/80 text-sm focus:border-brand focus:ring-brand dark:border-slate-700 dark:bg-slate-950">
                <input name="phone" value="{{ old('phone', auth()->user()->phone) }}" required placeholder="Nomor WhatsApp aktif"
                       class="w-full rounded-2xl border-slate-200 bg-white/80 text-sm focus:border-brand focus:ring-brand dark:border-slate-700 dark:bg-slate-950">
                <div class="grid grid-cols-2 gap-3">
                    <input name="angkatan" value="{{ old('angkatan', $profile->angkatan) }}" placeholder="Angkatan"
                           class="rounded-2xl border-slate-200 bg-white/80 text-sm focus:border-brand focus:ring-brand dark:border-slate-700 dark:bg-slate-950">
                    <input name="kota" value="{{ old('kota', $profile->kota) }}" placeholder="Kota domisili"
                           class="rounded-2xl border-slate-200 bg-white/80 text-sm focus:border-brand focus:ring-brand dark:border-slate-700 dark:bg-slate-950">
                </div>
            </div>
        </section>
        <section class="rounded-[30px] border border-white/70 bg-white/82 p-4 shadow-[0_12px_32px_rgba(15,23,42,0.06)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/88 dark:shadow-[0_12px_32px_rgba(2,6,23,0.32)]">
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Profesional</p>
            <div class="mt-4 space-y-3.5">
                <input name="profesi" value="{{ old('profesi', $profile->profesi) }}" placeholder="Profesi atau jabatan saat ini"
                       class="w-full rounded-2xl border-slate-200 bg-white/80 text-sm focus:border-brand focus:ring-brand dark:border-slate-700 dark:bg-slate-950">
                <input name="bidang_usaha" value="{{ old('bidang_usaha', $profile->bidang_usaha) }}" placeholder="Bidang usaha atau spesialisasi"
                       class="w-full rounded-2xl border-slate-200 bg-white/80 text-sm focus:border-brand focus:ring-brand dark:border-slate-700 dark:bg-slate-950">
                <input name="negara" value="{{ old('negara', $profile->negara) }}" placeholder="Negara"
                       class="w-full rounded-2xl border-slate-200 bg-white/80 text-sm focus:border-brand focus:ring-brand dark:border-slate-700 dark:bg-slate-950">
                <textarea name="bio" rows="4" placeholder="Ceritakan singkat profil, keahlian, dan nilai yang Anda bawa ke jaringan alumni"
                          class="w-full rounded-2xl border-slate-200 bg-white/80 text-sm focus:border-brand focus:ring-brand dark:border-slate-700 dark:bg-slate-950">{{ old('bio', $profile->bio) }}</textarea>
                <label class="flex items-center gap-2 rounded-2xl bg-slate-50 px-3 py-3 text-sm text-slate-600 dark:bg-slate-950/70 dark:text-slate-300">
                    <input type="checkbox" name="is_visible" value="1" @checked($profile->is_visible) class="rounded border-slate-300 text-brand focus:ring-brand">
                    Tampilkan profil saya di directory
                </label>
            </div>
        </section>
        <button class="w-full rounded-[24px] bg-brand py-4 text-sm font-semibold text-white shadow-[0_18px_40px_rgba(14,124,134,0.28)] transition active:scale-95">
            {{ $user->needsPortfolioCompletion() ? 'Simpan & Lanjutkan ke Portofolio' : 'Simpan Perubahan' }}
        </button>
    </form>
@endsection
