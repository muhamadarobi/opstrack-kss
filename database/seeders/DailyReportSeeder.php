<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DailyReport;
use App\Models\LoadingActivity;
use App\Models\BulkLoadingActivity;
use App\Models\UnloadingActivity;
use App\Models\TurbaActivity;
use App\Models\MasterUnit;
use App\Models\MasterInventoryItem;
use Carbon\Carbon;

class DailyReportSeeder extends Seeder
{
    public function run()
    {
        // 1. PASTIKAN MASTER DATA ADA (Agar UnitCheckLogs berjalan)
        $this->seedMasterData();

        // 2. BUAT DAILY REPORT (PARENT)
        $report = DailyReport::create([
            'report_date' => Carbon::now()->format('Y-m-d'),
            'shift'       => 'Malam',
            'group_name'  => 'D',
            'time_range'  => '23:00 - 07:00',
            'status'      => 'submitted',
        ]);

        // ==========================================
        // SECTION I: PEMUATAN PUPUK KANTONG
        // ==========================================

        // Sequence 1: Bahtera Sukses
        $loading1 = $report->loadingActivities()->create([
            'sequence'             => 1,
            'ship_name'            => 'Bahtera Sukses',
            'agent'                => 'PT.NDSB',
            'jetty'                => 'Kumai',
            'destination'          => 'Tursina',
            'capacity'             => 3700,
            'wo_number'            => '-',
            'cargo_type'           => 'UK.Granul',
            'marking'              => 'Nitrea',
            'arrival_time'         => Carbon::now()->subHours(5),
            'operating_gang'       => '2',
            'tkbm_count'           => 26,
            'foreman'              => 'Nasir',
            'qty_delivery_current' => 309.00,
            'qty_delivery_prev'    => 3020.00,
            'qty_loading_current'  => 377.50,
            'qty_loading_prev'     => 2643.85,
            'qty_damage_current'   => 0,
            'qty_damage_prev'      => 0,
            'tally_warehouse'      => 'Syamsuddin',
            'driver_name'          => 'Arlis, udin, nurdian',
            'truck_number'         => '02, 05, 06',
            'tally_ship'           => 'Jefry, Zein',
            'operator_ship'        => 'Wirawan',
            'forklift_ship'        => '71, 16',
            'operator_warehouse'   => 'Gudang Op',
            'forklift_warehouse'   => '17'
        ]);

        // Timesheet Loading 1
        $loading1->timesheets()->createMany([
            ['category' => 'delivery', 'time' => '23:00', 'activity' => 'Lanjut kirim'],
            ['category' => 'delivery', 'time' => '04:00', 'activity' => 'Stop kirim'],
            ['category' => 'loading', 'time' => '00:00', 'activity' => 'Stop muat sampai jam 00:00'],
            ['category' => 'loading', 'time' => '01:00', 'activity' => 'Kapal tidak muat'],
        ]);

        // Sequence 2: DHANA BAHARI 2
        $loading2 = $report->loadingActivities()->create([
            'sequence'             => 2,
            'ship_name'            => 'DHANA BAHARI 2',
            'agent'                => 'NDSB',
            'jetty'                => 'TURSINA',
            'destination'          => 'PONTIANAK',
            'capacity'             => 2500,
            'wo_number'            => '-',
            'cargo_type'           => 'UK.GRANUL',
            'marking'              => 'NITREA',
            'arrival_time'         => Carbon::now()->subHours(8),
            'operating_gang'       => '2',
            'tkbm_count'           => 23,
            'foreman'              => 'Linta',
            'qty_delivery_current' => 224.00,
            'qty_delivery_prev'    => 1832.00,
            'qty_loading_current'  => 403.65,
            'qty_loading_prev'     => 1425.55,
            'tally_warehouse'      => 'Asmuni',
            'driver_name'          => 'Doni, Rahim, Azis',
            'truck_number'         => '07, 08, 09',
            'tally_ship'           => 'Ardy',
            'operator_ship'        => 'Musliadi, rifky',
            'forklift_ship'        => '71, 16',
            'operator_warehouse'   => 'Zein, syamrisal',
            'forklift_warehouse'   => '03, 13'
        ]);

        // Timesheet Loading 2
        $loading2->timesheets()->createMany([
            ['category' => 'delivery', 'time' => '23:00', 'activity' => 'Lanjut kirim'],
            ['category' => 'delivery', 'time' => '04:00', 'activity' => 'Stop kirim'],
            ['category' => 'loading', 'time' => '00:00', 'activity' => 'Stop muat sampai jam 00:00'],
            ['category' => 'loading', 'time' => '01:00', 'activity' => 'Kapal tidak muat'],
        ]);

        // ==========================================
        // SECTION II: PEMUATAN UREA CURAH
        // ==========================================

        $bulk = $report->bulkLoadingActivities()->create([
            'sequence'           => 1,
            'ship_name'          => 'MAXIMUS-I (sadam)',
            'agent'              => 'BERKAH SAMUDERA BERJAYA',
            'jetty'              => 'Jetty 1',
            'destination'        => 'Luar Negeri',
            'stevedoring'        => 'PBM KSS',
            'commodity'          => 'UC.GRANUL',
            'capacity'           => 15000,
            'berthing_time'      => Carbon::now()->subDays(1),
            'start_loading_time' => Carbon::now()->subHours(10),
        ]);

        $bulk->logs()->createMany([
            ['datetime' => Carbon::now()->setTime(0, 5), 'activity' => 'Stop muat #1', 'cob' => 123],
            ['datetime' => Carbon::now()->setTime(0, 45), 'activity' => 'Lanjut muat #3', 'cob' => 125],
        ]);


        // ==========================================
        // SECTION III: GUDANG TURBA (Disimpan di table deliveries)
        // ==========================================

        $turba = $report->turbaActivity()->create([
            'tally_gudang_names'      => 'Asmuni',
            'forklift_operator_names' => 'Syamsudin',
            'driver_names'            => 'Dony, rahim',
            'working_hours'           => '23:00 - 03:00'
        ]);

        $turba->deliveries()->createMany([
            [
                'truck_name'      => 'BUFFER Stok',
                'do_so_number'    => '5940',
                'capacity'        => 943.80,
                'marking_type'    => 'Granul Khusus',
                'qty_current'     => 63.80,
                'qty_prev'        => 880.00,
                'qty_accumulated' => 943.80
            ],
            [
                'truck_name'      => 'BUFFER Stok',
                'do_so_number'    => '-',
                'capacity'        => 0,
                'marking_type'    => '-',
                'qty_current'     => 0,
                'qty_prev'        => 0,
                'qty_accumulated' => 0
            ],
        ]);


        // ==========================================
        // SECTION IV: BONGKAR (UNLOADING)
        // ==========================================

        $unloading = $report->unloadingActivity()->create([
            'ship_name'               => 'MV. BONGKAR JAYA',
            'agent'                   => 'AGEN KSS',
            'capacity'                => 5000,
            'ship_tally_names'        => 'Budi',
            'forklift_operator_names' => 'Santoso',
            'delivery_tally_names'    => 'Rudi',
            'driver_names'            => 'Eko, Dwi',
            'gudang_tally_names'      => 'Tally Gudang Container',
            'working_hours'           => '23:00 - 07:00'
        ]);

        // Bahan Baku
        $unloading->materials()->createMany([
            ['raw_material_type' => 'Clay JB', 'qty_current' => 0, 'qty_prev' => 0, 'qty_total' => 0],
            ['raw_material_type' => 'Dolomite JB', 'qty_current' => 0, 'qty_prev' => 0, 'qty_total' => 0],
            ['raw_material_type' => 'MGO 18% 50kg', 'qty_current' => 0, 'qty_prev' => 0, 'qty_total' => 0],
            ['raw_material_type' => 'Limestone', 'qty_current' => 0, 'qty_prev' => 0, 'qty_total' => 0],
        ]);

        // Container (Kosong di gambar, kita isi sample 1)
        $unloading->containers()->create([
            'time'        => '01:00',
            'status'      => 'Full',
            'qty_current' => 1,
            'qty_prev'    => 0,
            'qty_total'   => 1
        ]);


        // ==========================================
        // SECTION V: KEADAAN PERALATAN & INVENTARIS
        // ==========================================

        // 1. Vehicle Logs (Generate berdasarkan MasterUnit)
        $units = MasterUnit::all();
        foreach ($units as $unit) {
            // Simulasi data acak (Baik/Rusak)
            $condRec = rand(0, 1) ? 'Baik' : 'Rusak';
            $condHand = rand(0, 1) ? 'Baik' : 'Rusak';

            $report->unitCheckLogs()->create([
                'category'              => 'vehicle',
                'item_name'             => $unit->name,
                'master_id'             => $unit->id,
                'fuel_level'            => rand(1, 5) . '/4',
                'condition_received'    => $condRec,
                'condition_handed_over' => $condHand,
            ]);
        }

        // 2. Inventory Logs
        $inventories = MasterInventoryItem::all();
        foreach ($inventories as $inv) {
            $report->unitCheckLogs()->create([
                'category'              => 'inventory',
                'item_name'             => $inv->name,
                'master_id'             => $inv->id,
                'quantity'              => $inv->stock ?? rand(1, 10),
                'condition_received'    => 'Baik',
                'condition_handed_over' => 'Baik',
            ]);
        }

        // 3. Shelter Logs (Hardcoded Items)
        $shelterItems = [
            'Ruangan Shelter', 'Halaman Shelter', 'Selokan/Parit', // Kebersihan
            'Jala-Jala Angkat', 'Jala-Jala Lambung', 'Terpal', 'Chain Sling' // Kerapian
        ];
        foreach ($shelterItems as $item) {
            $report->unitCheckLogs()->create([
                'category'              => 'shelter',
                'item_name'             => $item,
                'condition_received'    => 'Baik',
                'condition_handed_over' => 'Baik',
            ]);
        }


        // ==========================================
        // SECTION VI: KARYAWAN
        // ==========================================

        // 1. Shift Employees
        $shiftNames = [
            'Sugianto', 'Jhon Mailoor', 'Yacob', 'Sadam hasanuddin',
            'Wirawan', 'Jefry', 'Syamrisal', 'Irfan', 'Supriadi',
            'Fadli', 'Reza', 'Abd.Azis', 'Rahim', 'Doni amping'
        ];

        foreach ($shiftNames as $index => $name) {
            $report->employeeLogs()->create([
                'category'    => 'shift',
                'name'        => $name,
                'time_in'     => '23:00',
                'time_out'    => '07:00',
                'description' => '-'
            ]);
        }

        // 2. Operasi Employees (Lembur & Relief)
        $report->employeeLogs()->createMany([
            ['category' => 'operasi', 'name' => 'syamsudin udin', 'description' => 'Lembur'],
            ['category' => 'operasi', 'name' => 'zein', 'description' => 'Lembur'],
            ['category' => 'operasi', 'name' => 'rifky', 'description' => 'Lembur'],
            ['category' => 'operasi', 'name' => 'musliady', 'description' => 'Lembur'],
            ['category' => 'operasi', 'name' => 'asmuni', 'description' => 'Lembur'],

            ['category' => 'operasi', 'name' => 'ronal', 'description' => 'Relief Malam'],
            ['category' => 'operasi', 'name' => 'ardy', 'description' => 'Relief Malam'],
            ['category' => 'operasi', 'name' => 'ardian', 'description' => 'Relief Malam'],
            ['category' => 'operasi', 'name' => 'edo', 'description' => 'Relief Malam'],
        ]);

        // 3. Lain Activities
        $report->employeeLogs()->create([
            'category'    => 'lain',
            'description' => 'Pemberian Safety Briefing',
            'name'        => 'All Team',
            'time_in'     => '22:45'
        ]);
    }

    /**
     * Helper untuk membuat Master Data jika belum ada
     */
    private function seedMasterData()
    {
        // Master Unit (Trailler & Forklift)
        $units = [
            'Trailler KSS-02', 'Trailler KSS-01', 'Trailler KSS-04', 'Trailler KSS-05',
            'Trailler KSS-06', 'Trailler KSS-07', 'Trailler KSS-08', 'Trailler KSS-09',
            'Tronton KSS-01', 'Forklift KSS-03', 'Forklift KSS-04', 'Forklift KSS-08',
            'Forklift KSS-09', 'Forklift KSS-11', 'Forklift KSS-12', 'Forklift KSS-13',
            'Forklift KSS-14', 'Forklift KSS-15', 'Forklift KSS-16', 'Forklift KSS-17',
            'Forklift KSS-75', 'Pick UP KSS-05', 'Pick UP KSS-08', 'ELF KSS-06',
            'ELF KSS-07', 'ELF KSS-10', 'Trailler KSS-63'
        ];

        foreach ($units as $unitName) {
            // PERBAIKAN: Tentukan 'type' berdasarkan string nama unit
            $type = 'Unit'; // Default fallback
            if (stripos($unitName, 'Trailler') !== false) {
                $type = 'Trailler';
            } elseif (stripos($unitName, 'Forklift') !== false) {
                $type = 'Forklift';
            } elseif (stripos($unitName, 'Tronton') !== false) {
                $type = 'Tronton';
            } elseif (stripos($unitName, 'Pick UP') !== false) {
                $type = 'Pick Up';
            } elseif (stripos($unitName, 'ELF') !== false) {
                $type = 'Minibus';
            }

            // Sertakan 'type' dalam array kedua (values) pada firstOrCreate
            MasterUnit::firstOrCreate(
                ['name' => $unitName],
                ['type' => $type]
            );
        }

        // Master Inventory (Barang di Shelter)
        $items = [
            ['name' => 'Mesin jahit Portable', 'stock' => 1],
            ['name' => 'HT Mot.CP1660', 'stock' => 2],
            ['name' => 'HT Mot. P6620i', 'stock' => 2],
            ['name' => 'HT Mot. Xir c2620', 'stock' => 10],
            ['name' => 'Spare Battery', 'stock' => 7],
            ['name' => 'Charger', 'stock' => 7],
            ['name' => 'Computer+Printer', 'stock' => 1],
            ['name' => 'Lemari Blanko', 'stock' => 1],
            ['name' => 'Lemari Etalase', 'stock' => 1],
            ['name' => 'Gas Masker', 'stock' => 1],
            ['name' => 'Kalkulator', 'stock' => 1],
            ['name' => 'Lemari Loker', 'stock' => 4],
            ['name' => 'Pemadam Api', 'stock' => 1],
            ['name' => 'AC', 'stock' => 2],
        ];

        foreach ($items as $item) {
            MasterInventoryItem::firstOrCreate(
                ['name' => $item['name']],
                ['stock' => $item['stock']]
            );
        }
    }
}
