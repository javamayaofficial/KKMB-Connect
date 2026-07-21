@extends('layouts.app')
@section('title', 'Langganan')
@section('content')
    <section class="mb-5 rounded-[30px] border border-brand/10 bg-[linear-gradient(145deg,rgba(14,124,134,1),rgba(15,94,90,0.94),rgba(2,6,23,0.96))] p-5 text-white shadow-[0_18px_55px_rgba(15,94,90,0.18)]">
        <p class="text-xs uppercase tracking-[0.22em] text-white/60">Membership Access</p>
        <h1 class="mt-2 text-2xl font-bold tracking-tight">Keanggotaan & Langganan</h1>
        <p class="mt-2 text-sm leading-relaxed text-white/75">Pilih paket yang sesuai dan kelola status member Anda dalam tampilan yang lebih meyakinkan di HP.</p>
    </section>

    @if ($langganan && $langganan->status === 'active')
        <div class="mb-5 rounded-[30px] bg-gradient-to-br from-brand-dark to-brand p-5 text-white shadow-[0_18px_55px_rgba(15,94,90,0.18)]">
            <p class="text-xs text-white/70">Paket Aktif</p>
            <p class="font-bold text-lg">{{ $langganan->plan->nama }}</p>
            <p class="text-xs text-white/70 mt-1">Berlaku hingga {{ $langganan->berakhir_at?->translatedFormat('d M Y') }}</p>
        </div>
    @endif

    @if ($tagihan)
        <div class="mb-5 rounded-[28px] border border-amber-200 bg-amber-50 p-4">
            <p class="text-sm font-semibold text-amber-800">Ada tagihan menunggu pembayaran</p>
            <a href="{{ route('subscription.pay', $tagihan) }}" class="inline-block mt-2 text-sm font-semibold text-white bg-brand-accent px-4 py-2 rounded-xl">Selesaikan Pembayaran →</a>
        </div>
    @endif

    <div class="space-y-3">
        @foreach ($plans as $plan)
            <div class="rounded-[30px] border border-white/70 bg-white/85 p-5 shadow-[0_12px_32px_rgba(15,23,42,0.06)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/88 dark:shadow-[0_12px_32px_rgba(2,6,23,0.32)]">
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
