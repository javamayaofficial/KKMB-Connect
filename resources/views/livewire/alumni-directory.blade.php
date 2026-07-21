<div>
    <div class="mb-4 rounded-[30px] border border-white/70 bg-white/82 p-4 shadow-[0_12px_32px_rgba(15,23,42,0.06)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/88 dark:shadow-[0_12px_32px_rgba(2,6,23,0.32)]">
        <div class="space-y-2.5">
            <input type="text" wire:model.live.debounce.400ms="q" placeholder="Cari nama alumni..."
                   class="w-full rounded-2xl border-slate-200 bg-white/80 text-sm focus:border-brand focus:ring-brand dark:border-slate-700 dark:bg-slate-950">
            <div class="grid grid-cols-2 gap-2.5">
                <input wire:model.live.debounce.400ms="profesi" placeholder="Profesi"
                       class="rounded-2xl border-slate-200 bg-white/80 text-sm focus:border-brand focus:ring-brand dark:border-slate-700 dark:bg-slate-950">
                <input wire:model.live.debounce.400ms="bidang" placeholder="Bidang usaha"
                       class="rounded-2xl border-slate-200 bg-white/80 text-sm focus:border-brand focus:ring-brand dark:border-slate-700 dark:bg-slate-950">
                <input wire:model.live.debounce.400ms="kota" placeholder="Kota"
                       class="rounded-2xl border-slate-200 bg-white/80 text-sm focus:border-brand focus:ring-brand dark:border-slate-700 dark:bg-slate-950">
                <input wire:model.live.debounce.400ms="angkatan" placeholder="Angkatan"
                       class="rounded-2xl border-slate-200 bg-white/80 text-sm focus:border-brand focus:ring-brand dark:border-slate-700 dark:bg-slate-950">
            </div>
            @if ($q || $profesi || $bidang || $kota || $angkatan)
                <button wire:click="clearFilters" class="rounded-2xl bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-500 dark:bg-slate-950/70 dark:text-slate-300">Reset filter</button>
            @endif
        </div>
    </div>

    <div wire:loading class="text-center py-4 text-sm text-slate-400">Memuat...</div>

    <div wire:loading.remove>
        @forelse ($alumni as $a)
            <div class="mb-2.5 flex items-center gap-3 rounded-[28px] border border-white/70 bg-white/82 p-3.5 shadow-[0_12px_32px_rgba(15,23,42,0.06)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/88 dark:shadow-[0_12px_32px_rgba(2,6,23,0.32)]">
                @if ($a->foto_path)
                    <img src="{{ Storage::url($a->foto_path) }}" class="h-12 w-12 shrink-0 rounded-full object-cover ring-1 ring-brand/10" alt="">
                @else
                    <div class="grid h-12 w-12 shrink-0 place-items-center rounded-full bg-teal-50 font-bold text-brand ring-1 ring-brand/10 dark:bg-teal-950/40">{{ strtoupper(substr($a->user->name, 0, 1)) }}</div>
                @endif
                <div class="min-w-0 flex-1">
                    <p class="font-semibold text-sm truncate">{{ $a->user->name }}</p>
                    <p class="text-[11px] text-slate-500 truncate">
                        {{ $a->profesi ?: '—' }}@if ($a->kota) · {{ $a->kota }}@endif @if ($a->angkatan) · A{{ $a->angkatan }}@endif
                    </p>
                    <p class="mt-1 text-[11px] text-slate-400 truncate">{{ $a->bidang_usaha ?: 'Potensial untuk dijajaki sebagai relasi profesional.' }}</p>
                </div>
                @if ($a->user->phone)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $a->user->phone) }}?text={{ urlencode('Halo '.$a->user->name.', saya menemukan Anda di KKMB Connect.') }}"
                       target="_blank" class="shrink-0 rounded-2xl bg-teal-50 p-2.5 text-brand ring-1 ring-brand/10 dark:bg-teal-950/40">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a10 10 0 00-8.6 15l-1.3 4.7 4.8-1.3A10 10 0 1012 2zm0 18a8 8 0 01-4.1-1.1l-.3-.2-2.8.7.7-2.8-.2-.3A8 8 0 1112 20zm4.4-6c-.2-.1-1.4-.7-1.6-.8s-.4-.1-.5.1l-.7.9c-.1.2-.3.2-.5.1a6.5 6.5 0 01-3.2-2.8c-.2-.4.2-.4.6-1.2.1-.1 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.5-.4h-.5c-.2 0-.4.1-.6.3a3 3 0 00-.9 2.2c0 1.3 1 2.6 1.1 2.8.2.2 2 3 4.7 4.1 1.7.7 2.3.8 3.1.7.5-.1 1.4-.6 1.6-1.1.2-.6.2-1 .1-1.1z"/></svg>
                    </a>
                @endif
            </div>
        @empty
            <div class="rounded-[28px] border border-dashed border-slate-300 bg-white/80 p-8 text-center shadow-sm dark:border-slate-800 dark:bg-slate-900/60">
                <p class="text-sm text-slate-400">Belum ada hasil untuk filter ini.</p>
            </div>
        @endforelse
        <div class="mt-4">{{ $alumni->links() }}</div>
    </div>
</div>
