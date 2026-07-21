<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Notification\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function store(Request $request, NotificationService $notifications)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau kata sandi salah.',
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();
        if ($user->status === 'suspended' || $user->status === 'rejected') {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Akun Anda tidak dapat mengakses aplikasi. Hubungi pengurus.',
            ]);
        }

        if (! $user->first_login_at) {
            $user->forceFill(['first_login_at' => now()])->save();

            $notifications->triggerEvent($user, 'first_login');
        }

        if ($user->requiresOnboardingCompletion()) {
            return redirect()
                ->route($user->onboardingRedirectRoute())
                ->with('info', 'Lengkapi profil dan portofolio Anda terlebih dahulu agar profil anggota tampil utuh.');
        }

        if ($user->status !== 'active') {
            return redirect()->route('pending');
        }

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
