@extends('layouts.app')
@section('title', 'Tulis Artikel')
@section('content')
    <section class="mb-5 rounded-[30px] border border-brand/10 bg-[linear-gradient(145deg,rgba(14,124,134,1),rgba(15,94,90,0.94),rgba(2,6,23,0.96))] p-5 text-white shadow-[0_18px_55px_rgba(15,94,90,0.18)]">
        <p class="text-xs uppercase tracking-[0.22em] text-white/60">Community Voice</p>
        <h1 class="mt-2 text-2xl font-bold tracking-tight">Tulis Artikel</h1>
        <p class="mt-2 text-sm leading-relaxed text-white/75">Bagikan insight, peluang, atau kabar komunitas dengan tampilan editor yang lebih nyaman di mobile.</p>
    </section>
    <form method="POST" action="{{ route('feed.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <section class="rounded-[30px] border border-white/70 bg-white/85 p-4 shadow-[0_12px_32px_rgba(15,23,42,0.06)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/88 dark:shadow-[0_12px_32px_rgba(2,6,23,0.32)]">
            <input name="judul" value="{{ old('judul') }}" required placeholder="Judul artikel"
                   class="w-full rounded-2xl border-slate-200 bg-white/80 text-sm focus:border-brand focus:ring-brand dark:border-slate-700 dark:bg-slate-950">
            <div class="mt-3">
                <label class="mb-1.5 block text-sm font-medium">Gambar (opsional, maks 2MB)</label>
                <input type="file" name="gambar" accept="image/*" class="w-full text-sm text-slate-500 file:mr-3 file:rounded-xl file:border-0 file:bg-teal-50 file:px-4 file:py-2 file:text-sm file:text-brand dark:file:bg-teal-950/40">
            </div>
            <textarea name="konten" rows="9" required placeholder="Tulis isi artikel..."
                      class="mt-3 w-full rounded-2xl border-slate-200 bg-white/80 text-sm focus:border-brand focus:ring-brand dark:border-slate-700 dark:bg-slate-950">{{ old('konten') }}</textarea>
        </section>
        <div class="rounded-[28px] border border-brand/10 bg-brand/5 px-4 py-3 text-xs leading-relaxed text-slate-500 dark:bg-brand/10 dark:text-slate-300">Artikel dari anggota akan ditinjau pengurus sebelum tampil di feed.</div>
        <button class="w-full rounded-[24px] bg-brand py-4 text-white font-semibold shadow-[0_18px_40px_rgba(14,124,134,0.28)] transition active:scale-95">Kirim</button>
    </form>
@endsection
