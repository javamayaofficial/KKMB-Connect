@extends('layouts.app')
@section('title', 'Tulis Artikel')
@section('content')
    <h1 class="text-xl font-bold mb-4">Tulis Artikel</h1>
    <form method="POST" action="{{ route('feed.store') }}" enctype="multipart/form-data" class="space-y-3.5">
        @csrf
        <input name="judul" value="{{ old('judul') }}" required placeholder="Judul artikel"
               class="w-full rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:border-brand focus:ring-brand text-sm">
        <div>
            <label class="block text-sm font-medium mb-1.5">Gambar (opsional, maks 2MB)</label>
            <input type="file" name="gambar" accept="image/*" class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-teal-50 file:text-brand file:text-sm">
        </div>
        <textarea name="konten" rows="8" required placeholder="Tulis isi artikel..."
                  class="w-full rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:border-brand focus:ring-brand text-sm">{{ old('konten') }}</textarea>
        <p class="text-xs text-slate-400">Artikel dari anggota akan ditinjau pengurus sebelum tampil di feed.</p>
        <button class="w-full py-3.5 rounded-2xl bg-brand text-white font-semibold active:scale-95 transition">Kirim</button>
    </form>
@endsection
