<?php

namespace App\Providers;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Vite;

class FilamentServiceProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('KKMB Connect Admin')
            ->colors([
                'primary' => Color::hex('#0E7C86'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->pages([\Filament\Pages\Dashboard::class])
            ->middleware([
                \Illuminate\Cookie\Middleware\EncryptCookies::class,
                \Illuminate\Session\Middleware\StartSession::class,
                \Illuminate\View\Middleware\ShareErrorsFromSession::class,
                \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
                \Filament\Http\Middleware\Authenticate::class,
            ])
            // Kredit pembuat kecil & muted di footer admin (render hook resmi Filament).
            ->renderHook(
                PanelsRenderHook::FOOTER,
                fn (): string => Blade::render(
                    '<div style="text-align:center;padding:10px;font-size:11px;color:#94a3b8">Dibangun oleh <a href="{{ $wa }}" target="_blank" style="color:#64748b;text-decoration:underline">Java Maya Studio</a></div>',
                    ['wa' => config('integrations.builder.wa')],
                ),
            );
    }
}
