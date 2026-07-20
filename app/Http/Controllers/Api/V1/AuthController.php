<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\MemberProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:30'],
            'angkatan' => ['required', 'string', 'max:10'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'status' => 'pending',
        ]);
        $user->assignRole('calon_anggota');
        MemberProfile::create(['user_id' => $user->id, 'angkatan' => $data['angkatan']]);

        return response()->json([
            'message' => 'Registrasi berhasil. Menunggu verifikasi.',
            'data' => ['user' => $user->only('id', 'name', 'email', 'status')],
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'device_name' => ['nullable', 'string'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages(['email' => 'Kredensial salah.']);
        }

        $token = $user->createToken($request->device_name ?? 'mobile')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'data' => [
                'token' => $token,
                'user' => $user->only('id', 'name', 'email', 'status'),
                'roles' => $user->getRoleNames(),
            ],
        ]);
    }

    public function me(Request $request)
    {
        return response()->json(['data' => $request->user()->load('profile')]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    }
}
