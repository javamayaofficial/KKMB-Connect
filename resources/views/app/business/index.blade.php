@extends('layouts.app')
@section('title', 'Bisnis Saya')
@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-bold">Bisnis Saya</h1>
        <a href="{{ route('business.create') }}" class="text-sm font-semibold text-white bg-brand px-3 py-1.5 rounded-xl">+ Tambah</a>
    </div>
    @forelse ($businesses as $b)
        <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-4 mb-2.5">
            <div class="flex items-center justify-between">
                <p class="font-semibold text-sm">{{ $b->nama }}</p>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full
                    @if($b->status==='approved') bg-teal-100 text-teal-700
                    @elseif($b->status==='pending') bg-amber-100 text-amber-700
                    @else bg-rose-100 text-rose-700 @endif">
                    {{ ['pending'=>'Menunggu','approved'=>'Tampil','rejected'=>'Ditolak'][$b->status] }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">{{ $b->kategori ?: 'Umum' }}@if($b->kota) · {{ $b->kota }}@endif</p>
            <a href="{{ route('business.edit', $b) }}" class="inline-block mt-2 text-xs font-semibold text-brand">Edit →</a>
        </div>
    @empty
        <div class="rounded-2xl border border-dashed border-slate-200 dark:border-slate-800 p-10 text-center">
            <p class="text-sm text-slate-400 mb-3">Belum punya bisnis terdaftar.</p>
            <a href="{{ route('business.create') }}" class="text-sm font-semibold text-brand">Tambah bisnis pertama →</a>
        </div>
    @endforelse
@endsection
