<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Role
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $rolePetugas = Role::firstOrCreate(['name' => 'petugas']);

        // 2. Buat User ADMIN (Manajer Operasional)
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Manajer Operasional',
                'email' => 'manager@example.com',
                'password' => Hash::make('password'),
                'role_id' => $roleAdmin->id,
                'status' => 'aktif',
                'group' => null,
                // Arahkan ke file admin.png di folder signatures
                'signature_path' => 'signatures/admin.png',
            ]
        );

        // 3. Buat User PETUGAS (Looping Group A, B, C, D)
        $groups = ['a', 'b', 'c', 'd'];

        foreach ($groups as $group) {
            $upperGroup = strtoupper($group);
            $password = Hash::make("group.{$group}");

            // A. Buat KEPALA REGU (Karu)
            User::updateOrCreate(
                ['username' => "karu.{$group}"],
                [
                    'name' => "Kepala Regu {$upperGroup}",
                    'email' => "karu.{$group}@example.com",
                    'password' => $password,
                    'role_id' => $rolePetugas->id,
                    'status' => 'aktif',
                    'group' => $group,
                    // Otomatis set path tanda tangan
                    'signature_path' => "signatures/karu.{$group}.png",
                ]
            );

            // B. Buat WAKIL REGU (Wakaru)
            User::updateOrCreate(
                ['username' => "wakaru.{$group}"],
                [
                    'name' => "Wakil Regu {$upperGroup}",
                    'email' => "wakil.{$group}@example.com",
                    'password' => $password,
                    'role_id' => $rolePetugas->id,
                    'status' => 'aktif',
                    'group' => $group,
                    // Otomatis set path tanda tangan
                    'signature_path' => "signatures/wakaru.{$group}.png",
                ]
            );
        }
    }
}
