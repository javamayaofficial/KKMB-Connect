@extends('layouts.app')
@section('title', 'Langganan')
@section('content')
    <h1 class="text-xl font-bold mb-4">Keanggotaan & Langganan</h1>

    @if ($langganan && $langganan->status === 'active')
        <div class="rounded-2xl bg-gradient-to-br from-brand-dark to-brand text-white p-5 mb-5">
            <p class="text-xs text-white/70">Paket Aktif</p>
            <p class="font-bold text-lg">{{ $langganan->plan->nama }}</p>
            <p class="text-xs text-white/70 mt-1">Berlaku hingga {{ $langganan->berakhir_at?->translatedFormat('d M Y') }}</p>
        </div>
    @endif

    @if ($tagihan)
        <div class="rounded-2xl bg-amber-50 border border-amber-200 p-4 mb-5">
            <p class="text-sm font-semibold text-amber-800">Ada tagihan menunggu pembayaran</p>
            <a href="{{ route('subscription.pay', $tagihan) }}" class="inline-block mt-2 text-sm font-semibold text-white bg-brand-accent px-4 py-2 rounded-xl">Selesaikan Pembayaran →</a>
        </div>
    @endif

    <div class="space-y-3">
        @foreach ($plans as $plan)
            <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-5">
                <div class="flex items-center justify-between">
                    <p class="font-bold">{{ $plan->nama }}</p>
                    <p class="font-extrabold text-brand">{{ $plan->harga > 0 ? 'Rp'.number_format($plan->harga,0,',','.') : 'Gratis' }}</p>
                </div>
                <p class="text-[11px] text-slate-400 mb-3">Berlaku {{ $plan->durasi_hari }} hari</p>
                <ul class="space-y-1 mb-4">
                    @foreach (explode("\n", $plan->deskripsi_fitur) as $fitur)
                        <li class="text-xs text-slate-500 flex items-start gap-1.5">
                            <span class="text-brand mt-0.5">✓</span> {{ $fitur }}
                        </li>
                    @endforeach
                </ul>
                <form method="POST" action="{{ route('subscription.subscribe', $plan) }}">@csrf
                    <button class="w-full py-3 rounded-2xl {{ $plan->harga > 0 ? 'bg-brand text-white' : 'bg-teal-50 text-brand' }} font-semibold text-sm active:scale-95 transition">
                        {{ $plan->harga > 0 ? 'Pilih Paket' : 'Aktifkan Gratis' }}
                    </button>
                </form>
            </div>
        @endforeach
    </div>
@endsection
