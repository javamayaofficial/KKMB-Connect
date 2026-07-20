<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Memastikan pengguna sudah terverifikasi (status = active) sebelum mengakses fitur inti.
 * Calon anggota (pending) diarahkan ke halaman "menunggu verifikasi".
 */
class EnsureActiveMember
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->status !== 'active') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akun Anda belum terverifikasi.'], 403);
            }
            return redirect()->route('pending')->with('info', 'Akun Anda masih menunggu verifikasi pengurus.');
        }

        return $next($request);
    }
}
