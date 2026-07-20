@php
    $tabs = [
        ['route' => 'dashboard', 'label' => 'Beranda', 'icon' => 'M3 12l9-9 9 9M5 10v10h14V10'],
        ['route' => 'alumni.index', 'label' => 'Alumni', 'icon' => 'M17 20h5V4H2v16h5M9 20h6M12 12a3 3 0 100-6 3 3 0 000 6z'],
        ['route' => 'events.index', 'label' => 'Event', 'icon' => 'M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ['route' => 'feed.index', 'label' => 'Feed', 'icon' => 'M4 6h16M4 12h16M4 18h10'],
        ['route' => 'profile.edit', 'label' => 'Profil', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
    ];
@endphp
<nav class="fixed bottom-0 inset-x-0 z-40 bg-white/90 dark:bg-slate-900/90 backdrop-blur border-t border-slate-200 dark:border-slate-800">
    <div class="max-w-lg mx-auto grid grid-cols-5">
        @foreach ($tabs as $tab)
            @php $active = request()->routeIs($tab['route']); @endphp
            <a href="{{ route($tab['route']) }}"
               class="flex flex-col items-center justify-center py-2.5 gap-0.5 {{ $active ? 'text-teal-700 dark:text-teal-400' : 'text-slate-400 dark:text-slate-500' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $tab['icon'] }}" />
                </svg>
                <span class="text-[10px] font-medium">{{ $tab['label'] }}</span>
            </a>
        @endforeach
    </div>
</nav>
