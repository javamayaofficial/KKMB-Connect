<?php

namespace App\Filament\Widgets;

use App\Models\Business;
use App\Models\Event;
use App\Models\Payment;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Anggota Aktif', User::where('status', 'active')->count())
                ->description('Alumni terverifikasi')
                ->color('success'),
            Stat::make('Menunggu Verifikasi', User::where('status', 'pending')->count())
                ->description('Perlu ditinjau pengurus')
                ->color('warning'),
            Stat::make('Bisnis Disetujui', Business::where('status', 'approved')->count())
                ->description('Tampil di directory')
                ->color('info'),
            Stat::make('Event Aktif', Event::where('status', 'published')->where('mulai_at', '>=', now())->count())
                ->description('Akan datang')
                ->color('primary'),
            Stat::make('Pembayaran Pending', Payment::where('status', 'pending')->count())
                ->description('Perlu verifikasi')
                ->color('danger'),
        ];
    }
}
