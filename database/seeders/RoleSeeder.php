<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // 5 role inti MVP (guard: web). Sesuai PRD §8.2.
        foreach (['super_admin', 'pengurus', 'alumni', 'pemilik_bisnis', 'calon_anggota'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
    }
}
