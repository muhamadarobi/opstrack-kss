<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\MasterUnit;
use App\Models\MasterInventoryItem;
use App\Models\MasterEmployee; // Tambahkan Model MasterEmployee
use Exception;

class ReportController extends Controller
{
    /**
     * Menampilkan Halaman Form Pembuatan Laporan
     */
    public function create()
    {
        $vehicles = MasterUnit::select('id', 'name')->orderBy('id', 'asc')->get();
        $inventories = MasterInventoryItem::select('id', 'name', 'stock as qty')->orderBy('id', 'asc')->get();

        // --- TAMBAHAN: Ambil Data Karyawan Group A-D ---
        // Kita ambil yang status active, lalu group berdasarkan kolom 'group_name'
        // Hasilnya nanti format JSON: {"Group A": [...], "Group B": [...]}
        $employeesGrouped = MasterEmployee::where('status', 'active')
                            ->orderBy('name', 'asc')
                            ->get()
                            ->groupBy('group_name');

        return view('officer.create', compact('vehicles', 'inventories', 'employeesGrouped'));
    }

    /**
     * Menampilkan Halaman Riwayat Laporan (History)
     */
    public function history()
    {
        $reports = DailyReport::latest('report_date')
                              ->latest('created_at')
                              ->paginate(10);

        return view('officer.history', compact('reports'));
    }

    /**
     * Menyimpan Data Laporan ke Database
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // 1. SIMPAN PARENT (DAILY REPORT)
            $report = DailyReport::create([
                'report_date'       => $request->report_date,
                'shift'             => $request->shift,
                'group_name'        => $request->group_name,
                'received_by_group' => $request->received_by_group, // <--- TAMBAHKAN INI
                'time_range'        => $request->time_range,
                'status'            => 'submitted'
            ]);

            // 2. SECTION: MUAT KANTONG (Sequence 1-4)
            for ($i = 1; $i <= 4; $i++) {
                if ($request->filled("ship_name_{$i}")) {
                    $loadingActivity = $report->loadingActivities()->create([
                        'sequence'       => $i,
                        'ship_name'      => $request->input("ship_name_{$i}"),
                        'agent'          => $request->input("agent_{$i}"),
                        'jetty'          => $request->input("jetty_{$i}"),
                        'destination'    => $request->input("destination_{$i}"),
                        'capacity'       => $request->input("capacity_{$i}") ?? 0,
                        'wo_number'      => $request->input("wo_number_{$i}"),
                        'cargo_type'     => $request->input("cargo_type_{$i}"),
                        'marking'        => $request->input("marking_{$i}"),
                        'arrival_time'   => $request->input("arrival_time_{$i}"),
                        'operating_gang' => $request->input("operating_gang_{$i}"),
                        'tkbm_count'     => $request->input("tkbm_count_{$i}") ?? 0,
                        'foreman'        => $request->input("foreman_{$i}"),

                        // Quantities
                        'qty_delivery_current' => $request->input("qty_delivery_current_{$i}") ?? 0,
                        'qty_delivery_prev'    => $request->input("qty_delivery_prev_{$i}") ?? 0,
                        'qty_loading_current'  => $request->input("qty_loading_current_{$i}") ?? 0,
                        'qty_loading_prev'     => $request->input("qty_loading_prev_{$i}") ?? 0,
                        'qty_damage_current'   => $request->input("qty_damage_current_{$i}") ?? 0,
                        'qty_damage_prev'      => $request->input("qty_damage_prev_{$i}") ?? 0,

                        // Petugas
                        'tally_warehouse'    => $request->input("tally_warehouse_{$i}"),
                        'driver_name'        => $request->input("driver_name_{$i}"),
                        'truck_number'       => $request->input("truck_number_{$i}"),
                        'tally_ship'         => $request->input("tally_ship_{$i}"),
                        'operator_ship'      => $request->input("operator_ship_{$i}"),
                        'forklift_ship'      => $request->input("forklift_ship_{$i}"),
                        'operator_warehouse' => $request->input("operator_warehouse_{$i}"),
                        'forklift_warehouse' => $request->input("forklift_warehouse_{$i}"),
                    ]);

                    // Timesheets
                    if ($request->has("timesheets.{$i}")) {
                        foreach ($request->input("timesheets.{$i}") as $category => $entries) {
                            foreach ($entries as $entry) {
                                if (!empty($entry['time']) || !empty($entry['activity'])) {
                                    $loadingActivity->timesheets()->create([
                                        'category' => $category,
                                        'time'     => $entry['time'] ?? '00:00',
                                        'activity' => $entry['activity'] ?? '-'
                                    ]);
                                }
                            }
                        }
                    }
                }
            }

            // 3. SECTION: MUAT UREA (Sequence 1-2)
            for ($i = 1; $i <= 2; $i++) {
                if ($request->filled("ship_name_urea_{$i}")) {
                    $bulkActivity = $report->bulkLoadingActivities()->create([
                        'sequence'           => $i,
                        'ship_name'          => $request->input("ship_name_urea_{$i}"),
                        'jetty'              => $request->input("jetty_urea_{$i}"),
                        'destination'        => $request->input("destination_urea_{$i}"),
                        'agent'              => $request->input("agent_urea_{$i}"),
                        'stevedoring'        => $request->input("stevedoring_urea_{$i}"),
                        'commodity'          => $request->input("commodity_urea_{$i}"),
                        'capacity'           => $request->input("capacity_urea_{$i}") ?? 0,
                        'berthing_time'      => $request->input("berthing_time_urea_{$i}"),
                        'start_loading_time' => $request->input("start_loading_time_urea_{$i}"),
                    ]);

                    // Bulk Logs
                    if ($request->has("bulk_logs.{$i}")) {
                        foreach ($request->input("bulk_logs.{$i}") as $log) {
                            if (!empty($log['time']) || !empty($log['activity'])) {
                                $bulkActivity->logs()->create([
                                    'datetime' => $log['time'] ?? now(),
                                    'activity' => $log['activity'] ?? '-',
                                    'cob'      => $log['cob'] ?? null
                                ]);
                            }
                        }
                    }
                }
            }

            // ==========================================================
            // 4. SECTION: BONGKAR (UNLOADING) - DIPISAH JADI DUA BAGIAN
            // ==========================================================

            // 4.A. BONGKAR BAHAN BAKU (Material Activity)
            if ($request->filled('ship_name_material') || $request->has('unloading_materials')) {
                $materialActivity = $report->materialActivity()->create([
                    'ship_name'               => $request->ship_name_material ?? $request->ship_name,
                    'agent'                   => $request->agent_material ?? $request->agent,
                    'capacity'                => $request->capacity_material ?? $request->Capacity,

                    // Petugas
                    'ship_tally_names'        => $request->material_ship_tally_names,
                    'forklift_operator_names' => $request->material_forklift_operator_names,
                    'delivery_tally_names'    => $request->material_delivery_tally_names,
                    'driver_names'            => $request->material_driver_names,
                    'working_hours'           => $request->material_working_hours,
                ]);

                // Simpan Item Material
                if ($request->has('unloading_materials')) {
                    foreach ($request->unloading_materials as $mat) {
                        if (!empty($mat['raw_material_type'])) {
                            $materialActivity->items()->create([
                                'raw_material_type' => $mat['raw_material_type'],
                                'qty_current'       => $mat['qty_current'] ?? 0,
                                'qty_prev'          => $mat['qty_prev'] ?? 0,
                                'qty_total'         => $mat['qty_total'] ?? 0,
                            ]);
                        }
                    }
                }
            }

            // 4.B. BONGKAR CONTAINER (Container Activity)
            if ($request->filled('ship_name_container') || $request->has('unloading_containers')) {
                $containerActivity = $report->containerActivity()->create([
                    'ship_name'          => $request->ship_name_container,
                    'agent'              => $request->agent_container,
                    'capacity'           => $request->capacity_container, // UPDATED: Single capacity field

                    // Petugas Container
                    'ship_tally_names'   => $request->container_ship_tally_names,
                    'gudang_tally_names' => $request->container_gudang_tally_names,
                    'driver_names'       => $request->container_driver_names,
                ]);

                // Simpan Item Container
                if ($request->has('unloading_containers')) {
                    foreach ($request->unloading_containers as $cont) {
                        if (!empty($cont['time'])) {
                            $containerActivity->items()->create([
                                'time'        => $cont['time'],
                                'status'      => $cont['status'] ?? null,
                                'qty_current' => $cont['qty_current'] ?? 0,
                                'qty_prev'    => $cont['qty_prev'] ?? 0,
                                'qty_total'   => $cont['qty_total'] ?? 0,
                            ]);
                        }
                    }
                }
            }

            // 5. SECTION: GUDANG TURBA
            $turba = $report->turbaActivity()->create([
                'tally_gudang_names'      => $request->tally_gudang_names,
                'forklift_operator_names' => $request->turba_forklift_operator,
                'driver_names'            => $request->turba_driver_names,
                'working_hours'           => $request->turba_working_hours,
            ]);

            // 5.1 Turba Deliveries
            if ($request->has('turba_deliveries')) {
                foreach ($request->turba_deliveries as $truck) {
                    if (!empty($truck['truck_name'])) {
                        $turba->deliveries()->create([
                            'truck_name'      => $truck['truck_name'],
                            'do_so_number'    => $truck['do_so_number'] ?? null,
                            'capacity'        => $truck['capacity'] ?? 0,
                            'marking_type'    => $truck['marking_type'] ?? null,
                            'qty_current'     => $truck['qty_current'] ?? 0,
                            'qty_prev'        => $truck['qty_prev'] ?? 0,
                            'qty_accumulated' => $truck['qty_accumulated'] ?? 0,
                        ]);
                    }
                }
            }

            // 6. SECTION: CEK UNIT & INVENTARIS
            // A. Vehicle Logs
            if ($request->has('unit_logs')) {
                foreach ($request->unit_logs as $log) {
                    $report->unitCheckLogs()->create([
                        'category'              => 'vehicle',
                        'item_name'             => 'Unit ID: ' . ($log['master_unit_id'] ?? 'Unknown'),
                        'master_id'             => $log['master_unit_id'] ?? null,
                        'fuel_level'            => $log['fuel_level'] ?? null,
                        'condition_received'    => $log['condition_received'] ?? null,
                        'condition_handed_over' => $log['condition_handed_over'] ?? null,
                    ]);
                }
            }

            // B. Inventory Logs
            if ($request->has('inventory_logs')) {
                foreach ($request->inventory_logs as $log) {
                    $report->unitCheckLogs()->create([
                        'category'              => 'inventory',
                        'item_name'             => 'Item ID: ' . ($log['master_inventory_item_id'] ?? 'Unknown'),
                        'master_id'             => $log['master_inventory_item_id'] ?? null,
                        'quantity'              => $log['quantity'] ?? 1,
                        'condition_received'    => $log['condition_received'] ?? null,
                        'condition_handed_over' => $log['condition_handed_over'] ?? null,
                    ]);
                }
            }

            // C. Shelter Logs
            if ($request->has('shelter_logs')) {
                foreach ($request->shelter_logs as $log) {
                    $report->unitCheckLogs()->create([
                        'category'              => 'shelter',
                        'item_name'             => $log['item_name'],
                        'condition_received'    => $log['condition_received'] ?? null,
                        'condition_handed_over' => $log['condition_handed_over'] ?? null,
                    ]);
                }
            }

            // 7. SECTION: GUDANG KARYAWAN
            // A. Shift
            for ($s = 1; $s <= 14; $s++) {
                if ($request->filled("shift_nama_{$s}")) {
                    $report->employeeLogs()->create([
                        'category'    => 'shift',
                        'name'        => $request->input("shift_nama_{$s}"),
                        'time_in'     => $request->input("shift_masuk_{$s}"),
                        'time_out'    => $request->input("shift_pulang_{$s}"),
                        'description' => $request->input("shift_ket_{$s}"),
                    ]);
                }
            }

            // B. Operasi
            for ($o = 1; $o <= 7; $o++) {
                if ($request->filled("lembur_{$o}")) {
                    $report->employeeLogs()->create([
                        'category'    => 'operasi',
                        'name'        => $request->input("lembur_{$o}"),
                        'description' => 'Lembur',
                    ]);
                }
                $reliefIndex = $o + 7;
                if ($request->filled("relief_{$reliefIndex}")) {
                    $report->employeeLogs()->create([
                        'category'    => 'operasi',
                        'name'        => $request->input("relief_{$reliefIndex}"),
                        'description' => 'Relief Malam',
                    ]);
                }
            }

            // C. Lain
            for ($l = 1; $l <= 5; $l++) {
                if ($request->filled("kegiatan_desc_{$l}")) {
                    $report->employeeLogs()->create([
                        'category'       => 'lain',
                        'description'    => $request->input("kegiatan_desc_{$l}"),
                        'name'           => $request->input("kegiatan_personil_{$l}"),
                        'personil_count' => $request->input("kegiatan_personil_{$l}"),
                        'time_in'        => $request->input("kegiatan_jam_{$l}"),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('reports.history')->with('success', 'Laporan Harian berhasil disimpan.');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage() . ' | Line: ' . $e->getLine())->withInput();
        }
    }

    public function exportPdf($id)
    {
        $report = DailyReport::with([
            'loadingActivities.timesheets',
            'bulkLoadingActivities.logs',
            'materialActivity.items',
            'containerActivity.items',
            'turbaActivity.deliveries',
            'unitCheckLogs',
            'employeeLogs'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('officer.pdf', compact('report'));

        // --- DEFINISI UKURAN KERTAS SESUAI GAMBAR (LEGAL) ---
        $custom_paper = array(0, 0, 612.00, 1008.00);
        $pdf->setPaper($custom_paper, 'portrait');

        return $pdf->stream('Laporan-Harian-Shift-' . $report->id . '.pdf');
    }
}
