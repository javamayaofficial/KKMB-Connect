<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        SubscriptionPlan::firstOrCreate(['slug' => 'alumni-basic'], [
            'nama' => 'Alumni Basic',
            'harga' => 0,
            'durasi_hari' => 365,
            'deskripsi_fitur' => "Profil terverifikasi\nAkses directory alumni\nDaftar event\nKartu keanggotaan digital",
            'is_active' => true,
        ]);

        SubscriptionPlan::firstOrCreate(['slug' => 'alumni-premium'], [
            'nama' => 'Alumni Premium',
            'harga' => 150000,
            'durasi_hari' => 365,
            'deskripsi_fitur' => "Semua fitur Basic\nPrioritas rekomendasi relasi\nBadge premium di profil\nAkses lebih awal ke event",
            'is_active' => true,
        ]);

        SubscriptionPlan::firstOrCreate(['slug' => 'bisnis-featured'], [
            'nama' => 'Bisnis Featured',
            'harga' => 250000,
            'durasi_hari' => 90,
            'deskripsi_fitur' => "Bisnis tampil prioritas di directory\nBadge featured\nPeriode 90 hari",
            'is_active' => true,
        ]);
    }
}
