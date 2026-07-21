<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Notification\NotificationService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class PasswordResetController extends Controller
{
    public function showLinkRequest()
    {
        return view('auth.forgot-password');
    }

    public function sendLink(Request $request, NotificationService $notifications)
    {
        $request->validate(['email' => ['required', 'email']]);

        $user = User::query()->where('email', $request->string('email'))->first();

        if ($user) {
            $token = Password::broker()->createToken($user);
            $resetUrl = route('password.reset', [
                'token' => $token,
                'email' => $user->email,
            ]);

            $notifications->triggerEvent(
                $user,
                'forgot_password',
                ['reset_url' => $resetUrl, 'url' => $resetUrl],
                ['in_app', 'wa', 'email'],
            );
        }

        return back()->with('status', 'Jika email terdaftar, kami telah mengirimkan instruksi reset kata sandi.');
    }

    public function showReset(Request $request, string $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            },
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Kata sandi berhasil diubah. Silakan masuk.')
            : back()->withErrors(['email' => __($status)]);
    }
}
