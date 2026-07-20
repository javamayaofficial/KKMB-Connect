@extends('layouts.guest')
@section('title', 'Daftar — KKMB Connect')
@section('content')
    <h2 class="text-lg font-bold mb-1">Buat akun</h2>
    <p class="text-sm text-slate-500 mb-5">Bergabung dengan jaringan alumni KKMB.</p>
    <form method="POST" action="{{ route('register') }}" class="space-y-3.5">
        @csrf
        <input name="name" value="{{ old('name') }}" required placeholder="Nama lengkap"
               class="w-full rounded-2xl border-slate-200 focus:border-brand focus:ring-brand text-sm">
        <input type="email" name="email" value="{{ old('email') }}" required placeholder="Email"
               class="w-full rounded-2xl border-slate-200 focus:border-brand focus:ring-brand text-sm">
        <input name="phone" value="{{ old('phone') }}" required placeholder="No. WhatsApp (08xx / +62)"
               class="w-full rounded-2xl border-slate-200 focus:border-brand focus:ring-brand text-sm">
        <div class="grid grid-cols-2 gap-3">
            <input name="angkatan" value="{{ old('angkatan') }}" required placeholder="Angkatan"
                   class="w-full rounded-2xl border-slate-200 focus:border-brand focus:ring-brand text-sm">
            <input name="kota" value="{{ old('kota') }}" placeholder="Kota"
                   class="w-full rounded-2xl border-slate-200 focus:border-brand focus:ring-brand text-sm">
        </div>
        <input name="profesi" value="{{ old('profesi') }}" placeholder="Profesi (opsional)"
               class="w-full rounded-2xl border-slate-200 focus:border-brand focus:ring-brand text-sm">
        <input name="bidang_usaha" value="{{ old('bidang_usaha') }}" placeholder="Bidang usaha (opsional)"
               class="w-full rounded-2xl border-slate-200 focus:border-brand focus:ring-brand text-sm">
        <input type="password" name="password" required placeholder="Kata sandi (min. 8 karakter)"
               class="w-full rounded-2xl border-slate-200 focus:border-brand focus:ring-brand text-sm">
        <input type="password" name="password_confirmation" required placeholder="Ulangi kata sandi"
               class="w-full rounded-2xl border-slate-200 focus:border-brand focus:ring-brand text-sm">
        <button class="w-full py-3.5 rounded-2xl bg-brand text-white font-semibold active:scale-95 transition">Daftar</button>
    </form>
    <p class="text-center text-sm text-slate-500 mt-5">Sudah punya akun?
        <a href="{{ route('login') }}" class="text-brand font-semibold">Masuk</a>
    </p>
@endsection
