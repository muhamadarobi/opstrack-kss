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
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $rolePetugas = Role::firstOrCreate(['name' => 'petugas']);

        // 2. Buat User ADMIN (Password tetap default 'password' atau sesuaikan keinginan)
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Super Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role_id' => $roleAdmin->id,
                'status' => 'aktif',
                'group' => null, // Admin tidak punya grup
            ]
        );

        // 3. Buat User PETUGAS (Looping Group A, B, C, D)
        $groups = ['a', 'b', 'c', 'd'];

        foreach ($groups as $group) {
            $upperGroup = strtoupper($group); // Menjadi 'A', 'B', 'C', 'D'

            // Password dinamis sesuai grup (group.a, group.b, dst)
            $password = Hash::make("group.{$group}");

            // A. Buat KEPALA REGU (Karu) untuk Group ini
            // Username: karu.a, karu.b, dst.
            User::updateOrCreate(
                ['username' => "karu.{$group}"],
                [
                    'name' => "Kepala Regu {$upperGroup}",
                    'email' => "karu.{$group}@example.com",
                    'password' => $password, // Password: group.a
                    'role_id' => $rolePetugas->id,
                    'status' => 'aktif',
                    'group' => $group, // Mengisi kolom enum group
                ]
            );

            // B. Buat WAKIL REGU (Wakaru) untuk Group ini
            // Username: wakaru.a, wakaru.b, dst.
            User::updateOrCreate(
                ['username' => "wakaru.{$group}"],
                [
                    'name' => "Wakil Regu {$upperGroup}",
                    'email' => "wakaru.{$group}@example.com",
                    'password' => $password, // Password: group.a
                    'role_id' => $rolePetugas->id,
                    'status' => 'aktif',
                    'group' => $group, // Mengisi kolom enum group
                ]
            );
        }

        // 4. (Opsional) Buat User NONAKTIF
        User::updateOrCreate(
            ['username' => 'mantan_petugas'],
            [
                'name' => 'User Nonaktif',
                'email' => 'nonaktif@example.com',
                'password' => Hash::make('password'),
                'role_id' => $rolePetugas->id,
                'status' => 'nonaktif',
                'group' => 'a',
            ]
        );
    }
}
