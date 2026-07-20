@extends('layouts.guest')
@section('title', 'Lupa Password')
@section('content')
    <h2 class="text-lg font-bold mb-1">Lupa kata sandi</h2>
    <p class="text-sm text-slate-500 mb-5">Masukkan email Anda, kami kirim tautan reset.</p>
    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf
        <input type="email" name="email" value="{{ old('email') }}" required placeholder="Email"
               class="w-full rounded-2xl border-slate-200 focus:border-brand focus:ring-brand text-sm">
        <button class="w-full py-3.5 rounded-2xl bg-brand text-white font-semibold active:scale-95 transition">Kirim Tautan Reset</button>
    </form>
    <p class="text-center text-sm text-slate-500 mt-5"><a href="{{ route('login') }}" class="text-brand font-semibold">Kembali ke Masuk</a></p>
@endsection
