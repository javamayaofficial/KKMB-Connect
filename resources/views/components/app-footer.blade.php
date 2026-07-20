{{-- Kredit pembuat: kecil, muted, hanya di footer. Bukan iklan. --}}
<footer class="mt-8 pb-24 pt-6 text-center">
    <p class="text-[11px] text-slate-400 dark:text-slate-600">
        Dibangun oleh
        <a href="{{ config('integrations.builder.wa') }}" target="_blank" rel="noopener"
           class="text-slate-500 dark:text-slate-500 underline underline-offset-2 hover:text-slate-600">
            {{ config('integrations.builder.name') }}
        </a>
    </p>
</footer>
