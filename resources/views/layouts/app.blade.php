<!DOCTYPE html>
<html lang="id" class="scroll-smooth" x-data="{ dark: (localStorage.theme === 'dark') }" :class="{ 'dark': dark }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0E7C86">
    <title>@yield('title', 'KKMB Connect')</title>
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/images/icon-192.png">
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
    <style>body{font-family:'Inter',system-ui,sans-serif}</style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 min-h-screen antialiased">
    {{-- Top bar --}}
    <header class="sticky top-0 z-30 bg-white/80 dark:bg-slate-900/80 backdrop-blur border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-lg mx-auto px-4 h-14 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-brand text-white grid place-items-center font-extrabold text-sm">K</span>
                <span class="font-bold tracking-tight">KKMB <span class="text-brand">Connect</span></span>
            </a>
            <div class="flex items-center gap-1">
                <a href="{{ route('notifications.index') }}" aria-label="Buka notifikasi" class="relative p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0"/></svg>
                    @php $unread = auth()->check() ? auth()->user()->notifications()->whereNull('read_at')->count() : 0; @endphp
                    @if ($unread > 0)
                        <span class="absolute top-1 right-1 w-4 h-4 rounded-full bg-rose-500 text-white text-[9px] grid place-items-center">{{ $unread > 9 ? '9+' : $unread }}</span>
                    @endif
                </a>
                <button @click="dark = !dark; localStorage.theme = dark ? 'dark' : 'light'" aria-label="Ganti mode terang gelap" class="p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">
                    <svg x-show="!dark" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.8A9 9 0 1111.2 3a7 7 0 009.8 9.8z"/></svg>
                    <svg x-show="dark" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.4 6.4l-1.4-1.4M7 7L5.6 5.6m12.8 0L17 7M7 17l-1.4 1.4M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>
                <form method="POST" action="{{ route('logout') }}">@csrf
                    <button aria-label="Keluar dari akun" class="p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7M13 16v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-lg mx-auto px-4 pt-5">
        <x-flash />
        @yield('content')
        <x-app-footer />
    </main>

    @auth <x-bottom-nav /> @endauth
    <script src="/pwa.js"></script>
    @livewireScripts
</body>
</html>
