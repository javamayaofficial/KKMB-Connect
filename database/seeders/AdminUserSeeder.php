<?php

namespace Database\Seeders;

use App\Models\MemberProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@kkmbconnect.id'],
            [
                'name' => 'Admin KKMB',
                'phone' => '6285722224391',
                'password' => Hash::make('password'), // WAJIB diganti setelah deploy
                'status' => 'active',
                'email_verified_at' => now(),
            ],
        );
        $admin->syncRoles(['super_admin']);

        MemberProfile::firstOrCreate(
            ['user_id' => $admin->id],
            [
                'member_number' => 'KKMB-0001',
                'angkatan' => '2010',
                'profesi' => 'Administrator',
                'kota' => 'Bandung',
                'negara' => 'Indonesia',
                'qr_token' => Str::random(40),
                'verified_at' => now(),
            ],
        );

        // Contoh pengurus
        $pengurus = User::firstOrCreate(
            ['email' => 'pengurus@kkmbconnect.id'],
            [
                'name' => 'Pengurus KKMB',
                'phone' => '6281234567890',
                'password' => Hash::make('password'),
                'status' => 'active',
                'email_verified_at' => now(),
            ],
        );
        $pengurus->syncRoles(['pengurus']);
        MemberProfile::firstOrCreate(['user_id' => $pengurus->id], [
            'angkatan' => '2012', 'profesi' => 'Pengurus', 'kota' => 'Bandung',
            'qr_token' => Str::random(40), 'verified_at' => now(),
        ]);
    }
}
