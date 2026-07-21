@extends('layouts.app')
@section('title', 'Directory Alumni')
@section('content')
    <section class="mb-5 rounded-[30px] border border-brand/10 bg-[linear-gradient(145deg,rgba(14,124,134,1),rgba(15,94,90,0.94),rgba(2,6,23,0.96))] p-5 text-white shadow-[0_18px_55px_rgba(15,94,90,0.18)]">
        <p class="text-xs uppercase tracking-[0.22em] text-white/60">Curated Network</p>
        <h1 class="mt-2 text-2xl font-bold tracking-tight">Directory Alumni</h1>
        <p class="mt-2 text-sm leading-relaxed text-white/75">
            Temukan alumni yang relevan berdasarkan profesi, kota, dan angkatan dalam tampilan yang lebih nyaman untuk eksplorasi dari HP.
        </p>
    </section>
    @livewire('alumni-directory')
@endsection
