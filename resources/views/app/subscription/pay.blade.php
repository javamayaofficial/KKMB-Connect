@extends('layouts.app')
@section('title', 'Pembayaran')
@section('content')
    <section class="mb-5 rounded-[30px] border border-brand/10 bg-[linear-gradient(145deg,rgba(14,124,134,1),rgba(15,94,90,0.94),rgba(2,6,23,0.96))] p-5 text-white shadow-[0_18px_55px_rgba(15,94,90,0.18)]">
        <p class="text-xs uppercase tracking-[0.22em] text-white/60">Payment Desk</p>
        <h1 class="mt-2 text-2xl font-bold tracking-tight">Pembayaran Manual</h1>
        <p class="mt-2 text-sm leading-relaxed text-white/75">Selesaikan tagihan dan unggah bukti transfer dengan alur yang lebih rapi dari perangkat mobile.</p>
    </section>

    <div class="mb-4 rounded-[30px] border border-white/70 bg-white/85 p-5 shadow-[0_12px_32px_rgba(15,23,42,0.06)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/88 dark:shadow-[0_12px_32px_rgba(2,6,23,0.32)]">
        <p class="text-xs text-slate-500">Total yang harus dibayar</p>
        <p class="text-2xl font-extrabold text-brand">Rp{{ number_format($payment->jumlah, 0, ',', '.') }}</p>
        <span class="inline-block mt-1 text-[11px] font-bold px-2 py-0.5 rounded-full
            {{ $payment->status === 'verified' ? 'bg-teal-100 text-teal-700' : ($payment->status === 'rejected' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">
            {{ ['pending'=>'MENUNGGU BUKTI/VERIFIKASI','verified'=>'LUNAS','rejected'=>'DITOLAK'][$payment->status] }}
        </span>
    </div>

    <div class="mb-4 rounded-[30px] border border-white/70 bg-white/85 p-5 shadow-[0_12px_32px_rgba(15,23,42,0.06)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/88 dark:shadow-[0_12px_32px_rgba(2,6,23,0.32)]">
        <p class="font-semibold text-sm mb-2">Transfer ke:</p>
        <div class="text-sm space-y-1">
            <p><span class="text-slate-400">Bank</span> · <span class="font-semibold">{{ $bank['bank'] }}</span></p>
            <p><span class="text-slate-400">No. Rek</span> · <span class="font-semibold">{{ $bank['no_rekening'] }}</span></p>
            <p><span class="text-slate-400">a.n.</span> <span class="font-semibold">{{ $bank['atas_nama'] }}</span></p>
        </div>
        @if ($bank['qris'])
            <p class="text-xs text-slate-400 mt-3 mb-2">Atau scan QRIS:</p>
            <img src="{{ asset('storage/'.$bank['qris']) }}" onerror="this.style.display='none'" class="w-40 rounded-xl border border-slate-100" alt="QRIS">
        @endif
    </div>

    @if ($payment->status !== 'verified')
        <form method="POST" action="{{ route('subscription.proof', $payment) }}" enctype="multipart/form-data" class="space-y-3 rounded-[30px] border border-white/70 bg-white/85 p-5 shadow-[0_12px_32px_rgba(15,23,42,0.06)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/88 dark:shadow-[0_12px_32px_rgba(2,6,23,0.32)]">
            @csrf
            <p class="font-semibold text-sm">Upload Bukti Transfer</p>
            <input type="file" name="bukti" accept="image/*,application/pdf" required
                   class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-teal-50 file:text-brand file:text-sm">
            <p class="text-[11px] text-slate-400">JPG/PNG/PDF, maks 2MB. Admin akan memverifikasi dalam 1×24 jam.</p>
            <button class="w-full py-3 rounded-2xl bg-brand text-white font-semibold text-sm active:scale-95 transition">Kirim Bukti</button>
        </form>
    @endif
@endsection
