<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#0E7C86">
    <meta name="description" content="KKMB Connect adalah platform resmi Koperasi Kesejahteraan Mahasiswa Bandung untuk membangun relasi alumni, peluang bisnis, event, dan layanan komunitas dalam satu pengalaman premium.">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32.png">
    <link rel="apple-touch-icon" href="/images/apple-touch-icon.png">
    <link rel="manifest" href="/manifest.json">
    <title>KKMB Connect — Satu Jaringan, Ribuan Peluang</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>tailwind.config={theme:{extend:{colors:{brand:{DEFAULT:'#0E7C86',dark:'#0F5E5A',accent:'#F5A623'}}}}}</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{--safe-bottom: env(safe-area-inset-bottom, 0px)}
        body{font-family:'Inter',system-ui,sans-serif}
    </style>
</head>
<body class="bg-slate-950 text-slate-100 antialiased">
    <div class="absolute inset-x-0 top-0 h-[420px] bg-[radial-gradient(circle_at_top,_rgba(14,124,134,0.45),_transparent_55%)] pointer-events-none"></div>
    <header class="sticky top-0 z-20 px-3 pt-3">
        <div class="relative max-w-lg mx-auto flex h-16 items-center justify-between rounded-[28px] border border-white/10 bg-slate-950/70 px-4 shadow-[0_18px_55px_rgba(2,6,23,0.35)] backdrop-blur-2xl">
        <div class="flex items-center gap-3">
            <span class="w-12 h-12 rounded-full bg-white p-1.5 grid place-items-center shadow-[0_10px_30px_rgba(2,6,23,0.18)] ring-1 ring-brand/20">
                <img src="/images/kkmb-logo-solid.png" alt="Logo KKMB" class="w-full h-full object-contain">
            </span>
            <span class="leading-tight">
                <span class="block font-bold text-white">KKMB <span class="text-brand">Connect</span></span>
                <span class="block text-[10px] uppercase tracking-[0.22em] text-slate-400">Premium Mobile Experience</span>
            </span>
        </div>
        <a href="{{ route('login') }}" class="text-sm font-semibold text-brand bg-white/8 border border-white/10 px-4 py-2 rounded-full backdrop-blur">Masuk</a>
        </div>
    </header>

    <section class="relative max-w-lg mx-auto px-5 pt-8 pb-28">
        <div class="overflow-hidden rounded-[34px] border border-white/10 bg-white/6 shadow-[0_30px_90px_rgba(15,94,90,0.28)] backdrop-blur-xl">
            <div class="p-6">
                <span class="mb-4 inline-flex items-center gap-2 rounded-full border border-brand/20 bg-brand/10 px-3 py-1.5 text-[11px] font-semibold text-brand">
                    Aplikasi Alumni Resmi
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                </span>
                <h1 class="text-3xl font-extrabold leading-tight tracking-tight text-white">
                    KKMB Connect
                    <span class="block text-brand">Simple, Cepat, Terhubung.</span>
                </h1>
                <p class="mt-3 text-sm leading-relaxed text-slate-300">
                    Direktori alumni, kartu anggota, event, dan peluang kolaborasi dalam satu aplikasi yang ringkas dan mudah dipakai.
                </p>

                <div class="mt-5 rounded-[28px] border border-white/10 bg-[linear-gradient(160deg,rgba(14,124,134,0.18),rgba(2,6,23,0.85))] p-4">
                    <div class="rounded-[24px] border border-white/10 bg-slate-950/65 p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="grid h-10 w-10 place-items-center rounded-full bg-white p-1 ring-1 ring-brand/20">
                                    <img src="/images/kkmb-logo-solid.png" alt="Logo KKMB" class="h-full w-full object-contain">
                                </span>
                                <div>
                                    <p class="text-sm font-bold text-white">KKMB Connect</p>
                                    <p class="text-[10px] uppercase tracking-[0.22em] text-white/45">Member App</p>
                                </div>
                            </div>
                            <span class="rounded-full bg-emerald-400/15 px-2 py-1 text-[9px] font-semibold text-emerald-300">Active</span>
                        </div>
                        <div class="mt-4 grid grid-cols-3 gap-2">
                            @foreach ([
                                ['Direktori', 'Alumni'],
                                ['Kartu', 'Digital'],
                                ['Event', 'Cepat'],
                            ] as $item)
                                <div class="rounded-2xl border border-white/10 bg-white/8 px-3 py-3 text-center">
                                    <p class="text-sm font-bold text-white">{{ $item[0] }}</p>
                                    <p class="mt-1 text-[10px] text-slate-400">{{ $item[1] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-3 gap-2">
                    @foreach ([
                        ['Relasi', 'Cari alumni cepat'],
                        ['Event', 'Daftar tanpa ribet'],
                        ['Bisnis', 'Buka kolaborasi'],
                    ] as $feature)
                        <div class="rounded-2xl border border-white/10 bg-white/5 px-3 py-3">
                            <p class="text-sm font-bold text-white">{{ $feature[0] }}</p>
                            <p class="mt-1 text-[11px] leading-relaxed text-slate-400">{{ $feature[1] }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 flex flex-col gap-3">
                    <a href="{{ route('register') }}" class="w-full rounded-2xl bg-brand py-3.5 text-center font-semibold text-white shadow-lg shadow-teal-950/40 transition active:scale-95">Gabung Sekarang</a>
                    <a href="{{ route('login') }}" class="w-full rounded-2xl border border-white/10 bg-white/5 py-3.5 text-center font-semibold text-white transition active:scale-95">Saya Sudah Punya Akun</a>
                </div>
                <p class="mt-3 text-[11px] text-slate-400">Daftar singkat, lanjutkan profil setelah masuk.</p>
            </div>
        </div>
    </section>

    <div class="fixed inset-x-0 bottom-0 z-30 px-3 pb-[calc(0.85rem+var(--safe-bottom))]">
        <div class="max-w-lg mx-auto flex items-center gap-3 rounded-[28px] border border-white/10 bg-slate-950/88 px-5 py-3 shadow-[0_18px_55px_rgba(2,6,23,0.42)] backdrop-blur-2xl">
            <div class="min-w-0">
                <p class="text-xs font-semibold text-white truncate">Gabung ke jaringan alumni yang lebih premium</p>
                <p class="text-[11px] text-slate-400 truncate">Direktori, kartu anggota, event, dan peluang bisnis dalam satu aplikasi</p>
            </div>
            <a href="{{ route('register') }}" class="shrink-0 rounded-2xl bg-brand px-4 py-2.5 text-sm font-semibold text-white">Daftar</a>
        </div>
    </div>

    <footer class="text-center py-8 border-t border-white/10">
        <p class="text-[11px] text-slate-500">Dibangun oleh
            <a href="{{ config('integrations.builder.wa') }}" target="_blank" class="underline text-slate-500">{{ config('integrations.builder.name') }}</a>
        </p>
    </footer>
</body>
</html>
