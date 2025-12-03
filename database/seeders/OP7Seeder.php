<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;

class OP7Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $faker = Faker::create('id_ID');

        // Daftar Nama Karyawan berdasarkan Dokumen OP.7
        $groupsData = [
            'A' => [
                'Aziz Bukhari S',
                'Juprianto',
                'Ahmad Faitsal',
                'Ashar',
                'Edi Ansyah',
                'Artanto Adhiguna',
                'Kiki Arfin Saputra',
                'Firman',
                'Aji Faisal',
                'Edi Sutomo',
            ],
            'B' => [
                'Abdul Salim',
                'Julyo Gabriel',
                'Yonas',
                'Wahyu',
                'Muchlas Abduh',
                'Junaidi',
                'M.Azhar Fadly Sinaga',
                'Wahyudi',
                'Imam Buchori',
                'Sutrisno Sikombong',
            ],
            'C' => [
                'Muhammad Bakri',
                'Muhammad Fikri',
                'Muhammad Agita',
                'Muhammad Dwian Jaya.G',
                'Muhammad Fikrianur',
                'Ali Murdani',
                'Sholaiman',
                'Yasser Daniel',
                'Muhammad Dandi',
                'M.Amar Ma\'ruf.M',
            ],
            'D' => [
                'Muhammad Ridwan',
                'Samsir',
                'Yodi Fatir.AN',
                'Randi Satrio.W',
                'Rusbandi',
                'Muhammad Rizki',
                'Salama',
                'Boyska',
                'Herwin Saputra',
                'Rustam',
                'Nurdin', // Pengganti di Group D
            ],
        ];

        $employees = [];

        foreach ($groupsData as $groupCode => $names) {
            foreach ($names as $name) {
                // Generate NPK Random Unik (Format mirip contoh: 2025.K.XXX)
                $employees[] = [
                    'npk'        => '2025.7.' . $faker->unique()->numerify('###'),
                    'name'       => $name,
                    'group_name' => 'OP.7 Group ' . $groupCode,
                    'status'     => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        // Upsert: Jika NPK sudah ada, update datanya.
        // Pastikan tabel Anda memiliki unique constraint pada kolom 'npk'.
        DB::table('master_employees')->upsert(
            $employees,
            ['npk'], // Kolom unique untuk pengecekan
            ['name', 'group_name', 'status', 'updated_at'] // Kolom yang diupdate jika duplicate
        );
    }
}
