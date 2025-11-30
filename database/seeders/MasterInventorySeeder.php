<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MasterInventorySeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Data Inventaris sesuai gambar, dengan kolom stock dikembalikan
        $items = [
            ['name' => 'Mesin Jahit Portable', 'stock' => 1],
            ['name' => 'HT Mot. CP1660', 'stock' => 2],
            ['name' => 'HT Mot. P6620i', 'stock' => 2],
            ['name' => 'HT Mot. Xir C2620', 'stock' => 10],
            ['name' => 'Spare Battery', 'stock' => 7],
            ['name' => 'Charger', 'stock' => 7],
            ['name' => 'Computer + Printer', 'stock' => 1],
            ['name' => 'Kalkulator', 'stock' => 1],
            ['name' => 'Lemari Etalase', 'stock' => 1],
            ['name' => 'Gas Masker', 'stock' => 1],
            ['name' => 'Lemari Loker', 'stock' => 4],
            ['name' => 'Pemadam Api', 'stock' => 1],
            ['name' => 'AC', 'stock' => 2],
        ];

        // Menyiapkan data untuk insert
        $data = array_map(function($item) use ($now) {
            return array_merge($item, [
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }, $items);

        DB::table('master_inventory_items')->insert($data);
    }
}
