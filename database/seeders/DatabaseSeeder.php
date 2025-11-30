<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Role
        // Menggunakan firstOrCreate untuk memastikan role hanya dibuat jika belum ada
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $rolePetugas = Role::firstOrCreate(['name' => 'petugas']);

        // 2. Buat User ADMIN
        User::updateOrCreate(
            ['username' => 'admin'], // Cek berdasarkan username
            [
                'name' => 'Super Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'), // Password login: 'password'
                'role_id' => $roleAdmin->id,
                'status' => 'aktif',
            ]
        );

        // 3. Buat User PETUGAS
        User::updateOrCreate(
            ['username' => 'petugas'], // Cek berdasarkan username
            [
                'name' => 'Petugas Lapangan',
                'email' => 'petugas@example.com',
                'password' => Hash::make('password'), // Password login: 'password'
                'role_id' => $rolePetugas->id,
                'status' => 'aktif',
            ]
        );

        // 4. (Opsional) Buat User NONAKTIF untuk tes validasi login status
        User::updateOrCreate(
            ['username' => 'mantan_petugas'],
            [
                'name' => 'User Nonaktif',
                'email' => 'nonaktif@example.com',
                'password' => Hash::make('password'),
                'role_id' => $rolePetugas->id,
                'status' => 'nonaktif', // Status nonaktif
            ]
        );
    }
}
