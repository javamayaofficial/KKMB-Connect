@extends('layouts.app')
@section('title', 'Profil Saya')
@section('content')
    <h1 class="text-xl font-bold mb-4">Profil Saya</h1>

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
        <button class="w-full py-3.5 rounded-2xl bg-brand text-white font-semibold active:scale-95 transition">Simpan Perubahan</button>
    </form>
@endsection
