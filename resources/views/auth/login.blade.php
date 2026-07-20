@extends('layouts.guest')
@section('title', 'Masuk — KKMB Connect')
@section('content')
    <h2 class="text-lg font-bold mb-1">Selamat datang kembali</h2>
    <p class="text-sm text-slate-500 mb-5">Masuk untuk melanjutkan.</p>
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full rounded-2xl border-slate-200 focus:border-brand focus:ring-brand text-sm" placeholder="nama@email.com">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5">Kata Sandi</label>
            <input type="password" name="password" required
                   class="w-full rounded-2xl border-slate-200 focus:border-brand focus:ring-brand text-sm" placeholder="••••••••">
        </div>
        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 text-slate-500"><input type="checkbox" name="remember" class="rounded border-slate-300 text-brand focus:ring-brand"> Ingat saya</label>
            <a href="{{ route('password.request') }}" class="text-brand font-medium">Lupa?</a>
        </div>
        <button class="w-full py-3.5 rounded-2xl bg-brand text-white font-semibold active:scale-95 transition">Masuk</button>
    </form>
    <p class="text-center text-sm text-slate-500 mt-5">Belum punya akun?
        <a href="{{ route('register') }}" class="text-brand font-semibold">Daftar</a>
    </p>
@endsection
