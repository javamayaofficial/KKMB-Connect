<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $profile = auth()->user()->profile;
        return view('app.profile', compact('profile'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'angkatan' => ['nullable', 'string', 'max:10'],
            'profesi' => ['nullable', 'string', 'max:255'],
            'bidang_usaha' => ['nullable', 'string', 'max:255'],
            'kota' => ['nullable', 'string', 'max:255'],
            'negara' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'is_visible' => ['nullable', 'boolean'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], // maks 2MB
        ]);

        $user->update(['name' => $data['name'], 'phone' => $data['phone']]);

        $profile = $user->profile;
        $fotoPath = $profile->foto_path;

        if ($request->hasFile('foto')) {
            // Simpan file & tampilkan kembali via storage disk "public".
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('avatars', 'public');
        }

        $profile->update([
            'angkatan' => $data['angkatan'] ?? $profile->angkatan,
            'profesi' => $data['profesi'] ?? null,
            'bidang_usaha' => $data['bidang_usaha'] ?? null,
            'kota' => $data['kota'] ?? null,
            'negara' => $data['negara'] ?? 'Indonesia',
            'bio' => $data['bio'] ?? null,
            'is_visible' => $request->boolean('is_visible'),
            'foto_path' => $fotoPath,
        ]);

        return back()->with('status', 'Profil berhasil diperbarui.');
    }

    // Kartu keanggotaan digital + QR (QR berisi qr_token, bukan data sensitif)
    public function card()
    {
        $profile = auth()->user()->profile;
        abort_unless($profile && $profile->isVerified(), 403, 'Kartu aktif hanya untuk anggota terverifikasi.');

        $qrSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($profile->qr_token);

        return view('app.card', compact('profile', 'qrSvg'));
    }
}
