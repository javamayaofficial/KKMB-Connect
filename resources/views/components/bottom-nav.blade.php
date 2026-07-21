@php
    $tabs = [
        ['route' => 'dashboard', 'label' => 'Beranda', 'icon' => 'M3 12l9-9 9 9M5 10v10h14V10'],
        ['route' => 'alumni.index', 'label' => 'Alumni', 'icon' => 'M17 20h5V4H2v16h5M9 20h6M12 12a3 3 0 100-6 3 3 0 000 6z'],
        ['route' => 'events.index', 'label' => 'Event', 'icon' => 'M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ['route' => 'feed.index', 'label' => 'Feed', 'icon' => 'M4 6h16M4 12h16M4 18h10'],
        ['route' => 'profile.edit', 'label' => 'Profil', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
    ];
@endphp
<nav class="pointer-events-none fixed inset-x-0 bottom-0 z-40 px-3 pb-[calc(0.85rem+env(safe-area-inset-bottom,0px))]">
    <div class="pointer-events-auto mx-auto max-w-lg">
        <div class="grid grid-cols-5 rounded-[28px] border border-white/60 bg-white/88 px-1.5 py-2 shadow-[0_18px_55px_rgba(15,23,42,0.16)] backdrop-blur-2xl dark:border-white/10 dark:bg-slate-900/88 dark:shadow-[0_18px_55px_rgba(2,6,23,0.55)]">
        @foreach ($tabs as $tab)
            @php $active = request()->routeIs($tab['route']); @endphp
            <a href="{{ route($tab['route']) }}"
               aria-label="Buka {{ $tab['label'] }}"
               class="flex flex-col items-center justify-center gap-1 rounded-2xl py-2 {{ $active ? 'bg-brand/10 text-brand dark:bg-brand/15 dark:text-teal-300' : 'text-slate-400 dark:text-slate-500' }}">
                <span class="grid h-9 w-9 place-items-center rounded-2xl {{ $active ? 'bg-white text-brand shadow-sm dark:bg-slate-950/90 dark:text-teal-300' : 'bg-transparent' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $tab['icon'] }}" />
                    </svg>
                </span>
                <span class="text-[11px] font-semibold">{{ $tab['label'] }}</span>
            </a>
        @endforeach
        </div>
    </div>
</nav>
