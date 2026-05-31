<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Superadmin
        User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@aplikasi.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Password123*'), // Ganti dengan password yang aman
            'role' => 'superadmin',
        ]);

        // 2. Akun Admin
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@aplikasi.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Password123*'),
            'role' => 'admin',
        ]);

        // 3. Akun Verifikator
        User::create([
            'name' => 'Budi Verifikator',
            'email' => 'verifikator@aplikasi.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Password123*'),
            'role' => 'verifikator',
        ]);

        // 4. Akun Observator
        User::create([
            'name' => 'Siti Observator',
            'email' => 'observator@aplikasi.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Password123*'),
            'role' => 'observator',
        ]);

        // 5. Akun User Biasa
        User::create([
            'name' => 'User Biasa',
            'email' => 'user@aplikasi.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Password123*'),
            'role' => 'user',
        ]);
    }
}
