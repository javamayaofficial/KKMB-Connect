<!DOCTYPE html>
<html lang="id" class="scroll-smooth" x-data="{ dark: (localStorage.theme === 'dark') }" :class="{ 'dark': dark }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0E7C86">
    <meta name="description" content="KKMB Connect menghadirkan direktori alumni, event, kartu anggota digital, dan peluang kolaborasi dalam ekosistem premium KKMB.">
    <title>@yield('title', 'KKMB Connect')</title>
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32.png">
    <link rel="apple-touch-icon" href="/images/apple-touch-icon.png">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { colors: {
                brand: { DEFAULT: '#0E7C86', dark: '#0F5E5A', accent: '#F5A623' },
            }, fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] } } }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @livewireStyles
    <style>
        :root{
            --safe-top: env(safe-area-inset-top, 0px);
            --safe-bottom: env(safe-area-inset-bottom, 0px);
        }
        body{font-family:'Inter',system-ui,sans-serif}
    </style>
</head>
<body class="min-h-screen bg-[radial-gradient(circle_at_top,rgba(14,124,134,0.16),transparent_28%),linear-gradient(180deg,#f8fafc_0%,#eef5f6_38%,#f8fafc_100%)] text-slate-800 antialiased dark:bg-[radial-gradient(circle_at_top,rgba(14,124,134,0.24),transparent_30%),linear-gradient(180deg,#020617_0%,#06121a_38%,#020617_100%)] dark:text-slate-100">
    @php
        $authUser = auth()->user();
        $homeRoute = $authUser ? route($authUser->nextAppRoute()) : route('landing');
        $showMemberNavigation = $authUser?->isActiveMember() && $authUser?->hasCompletedOnboarding();
    @endphp
    <div class="pointer-events-none fixed inset-x-0 top-0 z-0 h-40 bg-[radial-gradient(circle_at_top,rgba(245,166,35,0.16),transparent_45%)] dark:bg-[radial-gradient(circle_at_top,rgba(245,166,35,0.10),transparent_45%)]"></div>
    <div class="relative z-10 mx-auto w-full max-w-lg px-3 pb-[calc(6.75rem+var(--safe-bottom))] pt-[calc(0.75rem+var(--safe-top))] sm:px-4">
        <header class="sticky top-[calc(0.5rem+var(--safe-top))] z-30 mb-5">
            <div class="flex items-center justify-between rounded-[28px] border border-white/60 bg-white/78 px-3.5 py-3 shadow-[0_18px_45px_rgba(15,23,42,0.08)] backdrop-blur-2xl dark:border-white/10 dark:bg-slate-900/72 dark:shadow-[0_18px_45px_rgba(2,6,23,0.45)]">
                <a href="{{ $homeRoute }}" class="flex min-w-0 items-center gap-3">
                    <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full border border-slate-200/80 bg-white p-1 shadow-[0_10px_24px_rgba(15,94,90,0.16)] ring-1 ring-brand/10 dark:border-slate-700 dark:bg-slate-950">
                        <img src="/images/kkmb-logo.png" alt="Logo KKMB" class="h-full w-full object-contain">
                    </span>
                    <span class="min-w-0 leading-tight">
                        <span class="block truncate text-sm font-bold tracking-tight text-slate-900 dark:text-white">KKMB <span class="text-brand">Connect</span></span>
                        <span class="block truncate text-[10px] uppercase tracking-[0.22em] text-slate-400 dark:text-slate-500">Luxury Member App</span>
                    </span>
                </a>
                <div class="flex items-center gap-1.5">
                    @if ($showMemberNavigation)
                        <a href="{{ route('notifications.index') }}" aria-label="Buka notifikasi" class="relative rounded-2xl border border-slate-200/80 bg-white/70 p-2.5 text-slate-600 shadow-sm transition hover:-translate-y-0.5 hover:bg-white dark:border-slate-700 dark:bg-slate-900/80 dark:text-slate-300 dark:hover:bg-slate-900">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0"/></svg>
                            @php $unread = auth()->check() ? auth()->user()->notifications()->whereNull('read_at')->count() : 0; @endphp
                            @if ($unread > 0)
                                <span class="absolute right-1 top-1 grid h-4 w-4 place-items-center rounded-full bg-rose-500 text-[9px] text-white">{{ $unread > 9 ? '9+' : $unread }}</span>
                            @endif
                        </a>
                    @endif
                    <button @click="dark = !dark; localStorage.theme = dark ? 'dark' : 'light'" aria-label="Ganti mode terang gelap" class="rounded-2xl border border-slate-200/80 bg-white/70 p-2.5 text-slate-600 shadow-sm transition hover:-translate-y-0.5 hover:bg-white dark:border-slate-700 dark:bg-slate-900/80 dark:text-slate-300 dark:hover:bg-slate-900">
                        <svg x-show="!dark" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.8A9 9 0 1111.2 3a7 7 0 009.8 9.8z"/></svg>
                        <svg x-show="dark" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.4 6.4l-1.4-1.4M7 7L5.6 5.6m12.8 0L17 7M7 17l-1.4 1.4M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </button>
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button aria-label="Keluar dari akun" class="rounded-2xl border border-slate-200/80 bg-white/70 p-2.5 text-slate-500 shadow-sm transition hover:-translate-y-0.5 hover:bg-white dark:border-slate-700 dark:bg-slate-900/80 dark:text-slate-400 dark:hover:bg-slate-900">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7M13 16v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="px-1">
        <x-flash />
        @yield('content')
        <x-app-footer />
        </main>
    </div>

    @auth
        @if ($showMemberNavigation)
            <x-bottom-nav />
        @endif
    @endauth
    <script src="/pwa.js"></script>
    @livewireScripts
</body>
</html>
