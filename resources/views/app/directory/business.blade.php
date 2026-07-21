@extends('layouts.app')
@section('title', 'Directory Bisnis')
@section('content')
    <section class="mb-5 rounded-[30px] border border-brand/10 bg-[linear-gradient(145deg,rgba(14,124,134,1),rgba(15,94,90,0.94),rgba(2,6,23,0.96))] p-5 text-white shadow-[0_18px_55px_rgba(15,94,90,0.18)]">
        <div class="flex items-center justify-between gap-3">
            <div>
                <p class="text-xs uppercase tracking-[0.22em] text-white/60">Business Network</p>
                <h1 class="mt-2 text-2xl font-bold tracking-tight">Bisnis Alumni</h1>
                <p class="mt-2 text-sm leading-relaxed text-white/75">Jelajahi usaha alumni dalam tampilan daftar kartu yang lebih nyaman dan cepat untuk mobile.</p>
            </div>
            <a href="{{ route('business.index') }}" class="shrink-0 rounded-2xl border border-white/15 bg-white/10 px-3 py-2 text-xs font-semibold text-white">Kelola</a>
        </div>
    </section>
    @livewire('business-directory')
@endsection
