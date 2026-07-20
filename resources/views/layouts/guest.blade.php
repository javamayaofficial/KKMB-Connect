<!DOCTYPE html>
<html lang="id" x-data="{ dark: (localStorage.theme === 'dark') }" :class="{ 'dark': dark }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0E7C86">
    <title>@yield('title', 'KKMB Connect')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = { darkMode: 'class', theme: { extend: { colors: { brand: { DEFAULT: '#0E7C86', dark: '#0F5E5A', accent: '#F5A623' } } } } }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body{font-family:'Inter',system-ui,sans-serif}</style>
</head>
<body class="min-h-screen bg-gradient-to-b from-brand-dark to-brand text-slate-800 antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center px-5 py-10">
        <div class="w-full max-w-sm">
            <div class="text-center mb-6">
                <span class="inline-grid place-items-center w-16 h-16 rounded-full bg-white p-2 shadow-[0_16px_40px_rgba(2,6,23,0.18)] mb-3">
                    <img src="/images/kkmb-logo-solid.png" alt="Logo KKMB" class="w-full h-full object-contain">
                </span>
                <h1 class="text-white text-xl font-bold">KKMB Connect</h1>
                <p class="text-white/70 text-sm mt-1">Satu Jaringan, Ribuan Peluang.</p>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl p-6">
                <x-flash />
                @yield('content')
            </div>
            <p class="text-center text-[11px] text-white/50 mt-6">
                Dibangun oleh
                <a href="{{ config('integrations.builder.wa') }}" target="_blank" class="underline">{{ config('integrations.builder.name') }}</a>
            </p>
        </div>
    </div>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
