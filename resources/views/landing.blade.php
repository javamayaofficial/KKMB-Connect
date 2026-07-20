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
<body class="bg-slate-950 text-slate-100 antialiased">
    <div class="absolute inset-x-0 top-0 h-[420px] bg-[radial-gradient(circle_at_top,_rgba(14,124,134,0.45),_transparent_55%)] pointer-events-none"></div>
    <header class="relative max-w-lg mx-auto px-5 h-16 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="w-8 h-8 rounded-xl bg-brand text-white grid place-items-center font-extrabold">K</span>
            <span class="font-bold">KKMB <span class="text-brand">Connect</span></span>
        </div>
        <a href="{{ route('login') }}" class="text-sm font-semibold text-brand bg-white/8 border border-white/10 px-4 py-2 rounded-full backdrop-blur">Masuk</a>
    </header>

    <section class="relative max-w-lg mx-auto px-5 pt-8 pb-10">
        <div class="rounded-[32px] border border-white/10 bg-white/6 backdrop-blur-xl overflow-hidden shadow-[0_30px_90px_rgba(15,94,90,0.28)]">
            <div class="p-6 text-center">
                <span class="inline-flex items-center gap-2 text-[11px] font-semibold text-brand bg-brand/10 border border-brand/20 px-3 py-1.5 rounded-full mb-4">
                    Jaringan Alumni Premium
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                </span>
                <h1 class="text-4xl font-extrabold leading-tight tracking-tight text-white">
                    Satu Jaringan,
                    <span class="text-brand">Ribuan Peluang Nyata.</span>
                </h1>
                <p class="text-slate-300 mt-4 leading-relaxed text-sm">
                    Platform resmi KKMB untuk mempererat relasi alumni, membuka peluang kolaborasi bisnis,
                    dan memudahkan akses event, kartu anggota, serta layanan komunitas dalam satu pengalaman yang elegan.
                </p>
                <div class="mt-5 grid grid-cols-3 gap-2 text-left">
                    @foreach ([
                        ['2.000+', 'Alumni aktif'],
                        ['Bandung+', 'Koneksi lokal'],
                        ['1 aplikasi', 'Kartu, event, bisnis'],
                    ] as $proof)
                        <div class="rounded-2xl border border-white/10 bg-white/5 px-3 py-3">
                            <p class="text-sm font-bold text-white">{{ $proof[0] }}</p>
                            <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">{{ $proof[1] }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6 flex flex-col gap-3">
                    <a href="{{ route('register') }}" class="w-full py-3.5 rounded-2xl bg-brand text-white font-semibold shadow-lg shadow-teal-950/40 active:scale-95 transition">Gabung Sekarang</a>
                    <a href="{{ route('login') }}" class="w-full py-3.5 rounded-2xl border border-white/10 bg-white/5 font-semibold text-white active:scale-95 transition">Saya Sudah Punya Akun</a>
                </div>
                <p class="mt-3 text-[11px] text-slate-400">Proses daftar singkat. Mulai dari profil dasar, lalu lengkapi saat sudah masuk.</p>
            </div>

            <div class="px-4 pb-4">
                <div class="rounded-[28px] bg-[linear-gradient(160deg,rgba(14,124,134,0.18),rgba(2,6,23,0.85))] border border-white/10 p-4">
                    <img
                        src="https://coresg-normal.trae.ai/api/ide/v1/text_to_image?prompt=luxury%20mobile%20app%20interface%20for%20an%20alumni%20network%20platform%2C%20teal%20and%20gold%20premium%20brand%2C%20clean%20ios%20dashboard%20mockup%2C%20community%20directory%2C%20event%20ticket%20qr%2C%20business%20networking%2C%20realistic%20device%20presentation%2C%20high-end%20corporate%20islamic%20aesthetic%2C%20soft%20studio%20lighting&image_size=portrait_16_9"
                        alt="Pratinjau premium KKMB Connect"
                        class="w-full h-56 object-cover rounded-2xl border border-white/10"
                    >
                    <div class="grid grid-cols-2 gap-2 mt-3">
                        <div class="rounded-2xl bg-white/8 border border-white/10 px-3 py-3">
                            <p class="text-[11px] uppercase tracking-[0.24em] text-slate-400">Trust Layer</p>
                            <p class="mt-1 text-sm font-semibold text-white">Direktori alumni terverifikasi</p>
                        </div>
                        <div class="rounded-2xl bg-white/8 border border-white/10 px-3 py-3">
                            <p class="text-[11px] uppercase tracking-[0.24em] text-slate-400">Fast Access</p>
                            <p class="mt-1 text-sm font-semibold text-white">Kartu digital, QR, dan event dalam satu layar</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-lg mx-auto px-5 pb-6">
        <div class="rounded-[28px] border border-white/10 bg-white/5 p-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-[11px] uppercase tracking-[0.24em] text-brand">Kenapa Lebih Istimewa</p>
                    <h2 class="mt-2 text-2xl font-bold text-white leading-tight">Bukan sekadar database alumni.</h2>
                </div>
                <span class="shrink-0 rounded-full border border-emerald-400/30 bg-emerald-400/10 px-3 py-1 text-[11px] font-semibold text-emerald-300">High Trust</span>
            </div>
            <p class="mt-3 text-sm leading-relaxed text-slate-300">
                KKMB Connect dirancang untuk mempertemukan relasi yang bernilai, mempermudah kehadiran di event,
                dan menjadikan ekosistem alumni terasa hidup, modern, dan terpercaya.
            </p>
        </div>
    </section>

    <section class="max-w-lg mx-auto px-5 pb-6 grid grid-cols-2 gap-3">
        @foreach ([
            ['Relasi Bernilai', 'Temukan alumni berdasarkan profesi, kota, dan peluang kolaborasi yang relevan.'],
            ['Kartu Anggota Elegan', 'Akses identitas anggota digital dan QR untuk pengalaman yang lebih eksklusif.'],
            ['Event Lebih Praktis', 'Daftar event, cek agenda, dan check-in dengan alur yang cepat dan profesional.'],
            ['Peluang Bisnis Nyata', 'Buka percakapan, kerja sama, dan promosi dalam ekosistem alumni yang terkurasi.'],
        ] as $f)
            <div class="rounded-3xl border border-white/10 bg-white/5 p-4 shadow-[0_10px_30px_rgba(2,6,23,0.12)]">
                <div class="w-11 h-11 rounded-2xl bg-brand/10 border border-brand/20 text-brand grid place-items-center mb-3 text-lg">✦</div>
                <h3 class="font-semibold text-sm text-white leading-snug">{{ $f[0] }}</h3>
                <p class="text-xs text-slate-400 mt-1.5 leading-relaxed">{{ $f[1] }}</p>
            </div>
        @endforeach
    </section>

    <section class="max-w-lg mx-auto px-5 pb-24">
        <div class="rounded-[28px] border border-brand/20 bg-[linear-gradient(135deg,rgba(14,124,134,0.18),rgba(245,166,35,0.10))] p-5">
            <p class="text-[11px] uppercase tracking-[0.24em] text-brand">Suara Komunitas</p>
            <p class="mt-3 text-base leading-relaxed text-white">
                “Platform ini membuat jaringan alumni terasa lebih dekat, lebih rapi, dan jauh lebih profesional saat dipakai untuk membangun koneksi baru.”
            </p>
            <div class="mt-4 flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-white">Komunitas KKMB</p>
                    <p class="text-xs text-slate-300">Direktori, event, dan kolaborasi dalam satu ekosistem</p>
                </div>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-2xl bg-white text-slate-900 px-4 py-2.5 text-sm font-semibold">Mulai</a>
            </div>
        </div>
    </section>

    <div class="fixed inset-x-0 bottom-0 z-30 border-t border-white/10 bg-slate-950/90 backdrop-blur">
        <div class="max-w-lg mx-auto px-5 py-3 flex items-center gap-3">
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
