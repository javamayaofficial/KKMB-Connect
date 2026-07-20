@extends('layouts.app')
@section('title', $business->exists ? 'Edit Bisnis' : 'Tambah Bisnis')
@section('content')
    <h1 class="text-xl font-bold mb-4">{{ $business->exists ? 'Edit Bisnis' : 'Tambah Bisnis' }}</h1>
    <form method="POST" action="{{ $business->exists ? route('business.update', $business) : route('business.store') }}" enctype="multipart/form-data" class="space-y-3.5">
        @csrf
        @if ($business->exists) @method('PUT') @endif
        <div>
            <label class="block text-sm font-medium mb-1.5">Logo (JPG/PNG, maks 2MB)</label>
            <input type="file" name="logo" accept="image/*" class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-teal-50 file:text-brand file:text-sm">
            @if ($business->logo_path)<img src="{{ Storage::url($business->logo_path) }}" class="w-16 h-16 rounded-xl object-cover mt-2" alt="">@endif
        </div>
        <input name="nama" value="{{ old('nama', $business->nama) }}" required placeholder="Nama bisnis"
               class="w-full rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:border-brand focus:ring-brand text-sm">
        <div class="grid grid-cols-2 gap-3">
            <input name="kategori" value="{{ old('kategori', $business->kategori) }}" placeholder="Kategori"
                   class="rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:border-brand focus:ring-brand text-sm">
            <input name="kota" value="{{ old('kota', $business->kota) }}" placeholder="Kota"
                   class="rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:border-brand focus:ring-brand text-sm">
        </div>
        <input name="kontak_wa" value="{{ old('kontak_wa', $business->kontak_wa) }}" placeholder="No. WhatsApp bisnis"
               class="w-full rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:border-brand focus:ring-brand text-sm">
        <textarea name="deskripsi" rows="4" placeholder="Deskripsi bisnis"
                  class="w-full rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:border-brand focus:ring-brand text-sm">{{ old('deskripsi', $business->deskripsi) }}</textarea>
        <p class="text-xs text-slate-400">Bisnis akan ditinjau admin sebelum tampil di directory.</p>
        <button class="w-full py-3.5 rounded-2xl bg-brand text-white font-semibold active:scale-95 transition">{{ $business->exists ? 'Simpan' : 'Ajukan' }}</button>
    </form>
@endsection
