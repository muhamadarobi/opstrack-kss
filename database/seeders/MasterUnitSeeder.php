<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MasterUnitSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Nonaktifkan pengecekan Foreign Key agar bisa melakukan truncate tanpa error constraint
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 2. Kosongkan tabel master_units (reset ID dan hapus data lama)
        DB::table('master_units')->truncate();

        // 3. Aktifkan kembali pengecekan Foreign Key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = Carbon::now();

        // Data Unit lengkap sesuai urutan gambar
        $units = [
            // 1-9: Trailer & Tronton (Tetap)
            ['id' => 1,  'name' => 'Trailler KSS-01', 'type' => 'Trailer'],
            ['id' => 2,  'name' => 'Trailler KSS-02', 'type' => 'Trailer'],
            ['id' => 3,  'name' => 'Trailler KSS-03', 'type' => 'Trailer'],
            ['id' => 4,  'name' => 'Trailler KSS-04', 'type' => 'Trailer'],
            ['id' => 5,  'name' => 'Trailler KSS-05', 'type' => 'Trailer'],
            ['id' => 6,  'name' => 'Trailler KSS-06', 'type' => 'Trailer'],
            ['id' => 7,  'name' => 'Trailler KSS-08', 'type' => 'Trailer'],
            ['id' => 8,  'name' => 'Trailer KSS-09',  'type' => 'Trailer'],
            ['id' => 9,  'name' => 'Tronton-KSS-01',  'type' => 'Tronton'],

            // 10: Trailer KAD (Pindahan dari bawah)
            ['id' => 10, 'name' => 'Trailer KAD-63',  'type' => 'Trailer'],

            // 11: DT Baru
            ['id' => 11, 'name' => 'DT KSS-01',       'type' => 'Dump Truck'],

            // 12-28: Forklift KSS Awal (Geser urutan mulai dari ID 12)
            ['id' => 12, 'name' => 'Forklift KSS-01', 'type' => 'Forklift'],
            ['id' => 13, 'name' => 'Forklift KSS-03', 'type' => 'Forklift'],
            ['id' => 14, 'name' => 'Forklift KSS-04', 'type' => 'Forklift'],
            ['id' => 15, 'name' => 'Forklift KSS-05', 'type' => 'Forklift'],
            ['id' => 16, 'name' => 'Forklift KSS-08', 'type' => 'Forklift'],
            ['id' => 17, 'name' => 'Forklift KSS-09', 'type' => 'Forklift'],
            ['id' => 18, 'name' => 'Forklift KSS-11', 'type' => 'Forklift'],
            ['id' => 19, 'name' => 'Forklift KSS-12', 'type' => 'Forklift'],
            ['id' => 20, 'name' => 'Forklift KSS-13', 'type' => 'Forklift'],
            ['id' => 21, 'name' => 'Forklift KSS-14', 'type' => 'Forklift'],
            ['id' => 22, 'name' => 'Forklift KSS-15', 'type' => 'Forklift'],
            ['id' => 23, 'name' => 'Forklift KSS-16', 'type' => 'Forklift'],
            ['id' => 24, 'name' => 'Forklift KSS-17', 'type' => 'Forklift'],
            ['id' => 25, 'name' => 'Forklift KSS-70', 'type' => 'Forklift'],
            ['id' => 26, 'name' => 'Forklift KSS-71', 'type' => 'Forklift'],
            ['id' => 27, 'name' => 'Forklift KSS-72', 'type' => 'Forklift'],
            ['id' => 28, 'name' => 'Forklift KSS-75', 'type' => 'Forklift'],

            // 29-34: Forklift Lanjutan
            ['id' => 29, 'name' => 'Forklift KSS-73', 'type' => 'Forklift'],
            ['id' => 30, 'name' => 'Forklift KSS-74', 'type' => 'Forklift'],
            ['id' => 31, 'name' => 'Forklift KSS-100', 'type' => 'Forklift'],
            ['id' => 32, 'name' => 'Forklift KSS-101', 'type' => 'Forklift'],
            ['id' => 33, 'name' => 'Forklift KSS-102', 'type' => 'Forklift'],
            ['id' => 34, 'name' => 'Forklift KSS-103', 'type' => 'Forklift'],

            // 35-36: Wheel Loader (WL)
            ['id' => 35, 'name' => 'WL.KSS-02',       'type' => 'Wheel Loader'],
            ['id' => 36, 'name' => 'WL.KSS-03',       'type' => 'Wheel Loader'],

            // 37-38: Excavator (Exc)
            ['id' => 37, 'name' => 'Exc.KSS-01',      'type' => 'Excavator'],
            ['id' => 38, 'name' => 'Exc.KSS-02',      'type' => 'Excavator'],

            // 39-40: Pick Up
            ['id' => 39, 'name' => 'Pick Up KSS-05',  'type' => 'Pick Up'],
            ['id' => 40, 'name' => 'Pick Up KSS-08',  'type' => 'Pick Up'],

            // 41-43: Bus
            ['id' => 41, 'name' => 'Bus KSS-06',      'type' => 'Bus'],
            ['id' => 42, 'name' => 'Bus KSS-07',      'type' => 'Bus'],
            ['id' => 43, 'name' => 'Bus KSS-10',      'type' => 'Bus'],
        ];

        // Menyiapkan data untuk insert (menambah timestamp)
        $data = array_map(function($unit) use ($now) {
            return array_merge($unit, [
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }, $units);

        // Insert ke database
        DB::table('master_units')->insert($data);
    }
}
