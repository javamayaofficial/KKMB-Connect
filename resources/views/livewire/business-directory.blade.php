<div>
    <div class="space-y-2.5 mb-4">
        <input type="text" wire:model.live.debounce.400ms="q" placeholder="Cari nama bisnis..."
               class="w-full rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:border-brand focus:ring-brand text-sm">
        <div class="grid grid-cols-2 gap-2.5">
            <input wire:model.live.debounce.400ms="kategori" placeholder="Kategori"
                   class="rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:border-brand focus:ring-brand text-sm">
            <input wire:model.live.debounce.400ms="kota" placeholder="Kota"
                   class="rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:border-brand focus:ring-brand text-sm">
        </div>
    </div>

    <div wire:loading class="text-center py-4 text-sm text-slate-400">Memuat...</div>

    <div wire:loading.remove>
        @forelse ($businesses as $b)
            <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-4 mb-2.5">
                <div class="flex items-start gap-3">
                    @if ($b->logo_path)
                        <img src="{{ Storage::url($b->logo_path) }}" class="w-12 h-12 rounded-xl object-cover shrink-0" alt="">
                    @else
                        <div class="w-12 h-12 rounded-xl bg-amber-50 text-brand-accent grid place-items-center font-bold shrink-0">{{ strtoupper(substr($b->nama, 0, 1)) }}</div>
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
                @if ($b->deskripsi)<p class="text-xs text-slate-500 mt-2 line-clamp-2">{{ $b->deskripsi }}</p>@endif
                @if ($b->kontak_wa)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $b->kontak_wa) }}?text={{ urlencode('Halo, saya tertarik dengan '.$b->nama.' (via KKMB Connect).') }}"
                       target="_blank" class="mt-3 block text-center text-xs font-semibold text-white bg-brand rounded-xl py-2">Hubungi via WhatsApp</a>
                @endif
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-200 dark:border-slate-800 p-8 text-center">
                <p class="text-sm text-slate-400">Belum ada bisnis pada filter ini.</p>
            </div>
        @endforelse
        <div class="mt-4">{{ $businesses->links() }}</div>
    </div>
</div>
