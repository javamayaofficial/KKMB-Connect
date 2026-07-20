@extends('layouts.guest')
@section('title', 'Reset Password')
@section('content')
    <h2 class="text-lg font-bold mb-5">Atur kata sandi baru</h2>
    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="email" name="email" value="{{ $email ?? old('email') }}" required placeholder="Email"
               class="w-full rounded-2xl border-slate-200 focus:border-brand focus:ring-brand text-sm">
        <input type="password" name="password" required placeholder="Kata sandi baru"
               class="w-full rounded-2xl border-slate-200 focus:border-brand focus:ring-brand text-sm">
        <input type="password" name="password_confirmation" required placeholder="Ulangi kata sandi"
               class="w-full rounded-2xl border-slate-200 focus:border-brand focus:ring-brand text-sm">
        <button class="w-full py-3.5 rounded-2xl bg-brand text-white font-semibold active:scale-95 transition">Simpan</button>
    </form>
@endsection
