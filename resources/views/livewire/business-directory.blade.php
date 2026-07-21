<div>
    <div class="mb-4 rounded-[30px] border border-white/70 bg-white/82 p-4 shadow-[0_12px_32px_rgba(15,23,42,0.06)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/88 dark:shadow-[0_12px_32px_rgba(2,6,23,0.32)]">
        <div class="space-y-2.5">
            <input type="text" wire:model.live.debounce.400ms="q" placeholder="Cari nama bisnis..."
                   class="w-full rounded-2xl border-slate-200 bg-white/80 text-sm focus:border-brand focus:ring-brand dark:border-slate-700 dark:bg-slate-950">
            <div class="grid grid-cols-2 gap-2.5">
                <input wire:model.live.debounce.400ms="kategori" placeholder="Kategori"
                       class="rounded-2xl border-slate-200 bg-white/80 text-sm focus:border-brand focus:ring-brand dark:border-slate-700 dark:bg-slate-950">
                <input wire:model.live.debounce.400ms="kota" placeholder="Kota"
                       class="rounded-2xl border-slate-200 bg-white/80 text-sm focus:border-brand focus:ring-brand dark:border-slate-700 dark:bg-slate-950">
            </div>
            @if ($q || $kategori || $kota)
                <button wire:click="clearFilters" class="rounded-2xl bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-500 dark:bg-slate-950/70 dark:text-slate-300">Reset filter</button>
            @endif
        </div>
    </div>

    <div wire:loading class="text-center py-4 text-sm text-slate-400">Memuat...</div>

    <div wire:loading.remove>
        @forelse ($businesses as $b)
            <div class="mb-2.5 rounded-[28px] border border-white/70 bg-white/82 p-4 shadow-[0_12px_32px_rgba(15,23,42,0.06)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/88 dark:shadow-[0_12px_32px_rgba(2,6,23,0.32)]">
                <div class="flex items-start gap-3">
                    @if ($b->logo_path)
                        <img src="{{ Storage::url($b->logo_path) }}" class="h-12 w-12 shrink-0 rounded-xl object-cover ring-1 ring-brand/10" alt="">
                    @else
                        <div class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-amber-50 font-bold text-brand-accent ring-1 ring-brand/10 dark:bg-amber-950/40">{{ strtoupper(substr($b->nama, 0, 1)) }}</div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-1.5">
                            <p class="font-semibold text-sm truncate">{{ $b->nama }}</p>
                            @if ($b->is_featured && (!$b->featured_until || $b->featured_until >= now()))
                                <span class="text-[9px] font-bold text-amber-700 bg-amber-100 px-1.5 py-0.5 rounded-full">FEATURED</span>
                            @endif
                        </div>
                        <p class="text-[11px] text-slate-500 truncate">{{ $b->kategori ?: 'Umum' }}@if ($b->kota) · {{ $b->kota }}@endif</p>
                    </div>
                </div>
                @if ($b->deskripsi)<p class="mt-2 line-clamp-2 text-xs text-slate-500">{{ $b->deskripsi }}</p>@endif
                @if ($b->kontak_wa)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $b->kontak_wa) }}?text={{ urlencode('Halo, saya tertarik dengan '.$b->nama.' (via KKMB Connect).') }}"
                       target="_blank" class="mt-3 block rounded-2xl bg-brand py-2.5 text-center text-xs font-semibold text-white shadow-[0_12px_30px_rgba(14,124,134,0.22)]">Hubungi via WhatsApp</a>
                @endif
            </div>
        @empty
            <div class="rounded-[28px] border border-dashed border-slate-300 bg-white/80 p-8 text-center shadow-sm dark:border-slate-800 dark:bg-slate-900/60">
                <p class="text-sm text-slate-400">Belum ada bisnis pada filter ini.</p>
            </div>
        @endforelse
        <div class="mt-4">{{ $businesses->links() }}</div>
    </div>
</div>
