<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>KKMB Connect — Satu Jaringan, Ribuan Peluang</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>tailwind.config={theme:{extend:{colors:{brand:{DEFAULT:'#0E7C86',dark:'#0F5E5A',accent:'#F5A623'}}}}}</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body{font-family:'Inter',system-ui,sans-serif}</style>
</head>
<body class="bg-white text-slate-800 antialiased">
    <header class="max-w-lg mx-auto px-5 h-16 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="w-8 h-8 rounded-xl bg-brand text-white grid place-items-center font-extrabold">K</span>
            <span class="font-bold">KKMB <span class="text-brand">Connect</span></span>
        </div>
        <a href="{{ route('login') }}" class="text-sm font-semibold text-brand">Masuk</a>
    </header>

    <section class="max-w-lg mx-auto px-5 pt-8 pb-12 text-center">
        <span class="inline-block text-xs font-semibold text-brand bg-teal-50 px-3 py-1 rounded-full mb-4">Super App Alumni KKMB</span>
        <h1 class="text-3xl font-extrabold leading-tight tracking-tight">Satu Jaringan,<br><span class="text-brand">Ribuan Peluang.</span></h1>
        <p class="text-slate-500 mt-4 leading-relaxed">Platform resmi Koperasi Kesejahteraan Mahasiswa Bandung. Temukan alumni, jalin kolaborasi bisnis, dan ikuti kegiatan komunitas dalam satu aplikasi.</p>
        <div class="mt-7 flex flex-col gap-3">
            <a href="{{ route('register') }}" class="w-full py-3.5 rounded-2xl bg-brand text-white font-semibold shadow-lg shadow-teal-600/20 active:scale-95 transition">Daftar Sekarang</a>
            <a href="{{ route('login') }}" class="w-full py-3.5 rounded-2xl border border-slate-200 font-semibold active:scale-95 transition">Sudah punya akun? Masuk</a>
        </div>
    </section>

    <section class="max-w-lg mx-auto px-5 pb-12 grid grid-cols-2 gap-3">
        @foreach ([
            ['Directory Alumni', 'Cari alumni berdasarkan profesi, bidang usaha & lokasi.'],
            ['Kartu Digital + QR', 'Identitas keanggotaan resmi berbasis QR.'],
            ['Event & Check-in', 'Daftar event dan check-in cukup dengan QR.'],
            ['Temukan Relasi', 'Rekomendasi relasi & peluang bisnis yang relevan.'],
        ] as $f)
            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                <h3 class="font-semibold text-sm">{{ $f[0] }}</h3>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $f[1] }}</p>
            </div>
        @endforeach
    </section>

    <footer class="text-center py-8 border-t border-slate-100">
        <p class="text-[11px] text-slate-400">Dibangun oleh
            <a href="{{ config('integrations.builder.wa') }}" target="_blank" class="underline text-slate-500">{{ config('integrations.builder.name') }}</a>
        </p>
    </footer>
</body>
</html>
