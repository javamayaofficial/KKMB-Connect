<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingCompleted
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->requiresOnboardingCompletion()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Lengkapi profil dan portofolio Anda terlebih dahulu.',
                    'redirect_to' => route($user->onboardingRedirectRoute()),
                ], 423);
            }

            return redirect()
                ->route($user->onboardingRedirectRoute())
                ->with('info', 'Lengkapi profil dan portofolio Anda terlebih dahulu agar profil anggota tampil utuh.');
        }

        return $next($request);
    }
}
