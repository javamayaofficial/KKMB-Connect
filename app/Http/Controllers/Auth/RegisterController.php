<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\MemberProfile;
use App\Models\User;
use App\Services\Notification\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function store(Request $request, NotificationService $notifications)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:30'],
            'angkatan' => ['required', 'string', 'max:10'],
            'profesi' => ['nullable', 'string', 'max:255'],
            'bidang_usaha' => ['nullable', 'string', 'max:255'],
            'kota' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [], [
            'name' => 'nama', 'email' => 'email', 'phone' => 'nomor WhatsApp',
        ]);

        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
                'status' => 'pending',
            ]);
            $user->assignRole('calon_anggota');

            MemberProfile::create([
                'user_id' => $user->id,
                'angkatan' => $data['angkatan'],
                'profesi' => $data['profesi'] ?? null,
                'bidang_usaha' => $data['bidang_usaha'] ?? null,
                'kota' => $data['kota'] ?? null,
            ]);

            return $user;
        });

        // Notifikasi (best-effort; tidak menggagalkan registrasi bila WA/email gagal)
        $notifications->notify(
            $user,
            'Pendaftaran Diterima',
            'Terima kasih telah mendaftar di KKMB Connect. Akun Anda sedang menunggu verifikasi pengurus.',
            ['in_app', 'wa', 'email'],
        );

        Auth::login($user);

        return redirect()->route('pending');
    }
}
