<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MasterTruckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Data Nama Truk Sesuai Gambar
        $trucks = [
            ['name' => 'Buffer Stock'],
            ['name' => 'Buffer Stufing'],
            // Anda bisa menambahkan lebih banyak jika diperlukan
            ['name' => 'Buffer Stock'],
            ['name' => 'Buffer Stufing'],
        ];

        // Format data agar siap diinsert
        $data = array_map(function($item) use ($now) {
            return array_merge($item, [
                'plate_number' => null, // Kosongkan dulu
                'description'  => null, // Kosongkan dulu
                // 'status' dihapus
                'created_at'   => $now,
                'updated_at'   => $now
            ]);
        }, $trucks);

        DB::table('master_trucks')->insert($data);
    }
}
