<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\MasterUnit;
use App\Models\MasterInventoryItem;
use App\Models\MasterEmployee;
use Exception;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Menampilkan Halaman Riwayat & Approval (Dashboard Officer)
     */
    public function history()
    {
        $user = Auth::user();
        $userGroup = $user->group; // Asumsi: Kolom 'group' ada di tabel users (enum: a, b, c, d)

        // 1. DATA TABEL ATAS: Laporan Masuk (Perlu Tanda Tangan)
        $pendingReports = DailyReport::with('creator')
            ->where('received_by_group', $userGroup) // Laporan yang ditujukan ke group user ini
            ->where('status', 'submitted')           // Status masih 'submitted' (belum diterima)
            ->orderBy('report_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingReports->map(function($item) {
            $item->user_name = $item->creator ? $item->creator->name : 'Unknown';
            return $item;
        });

        // 2. DATA TABEL BAWAH: Riwayat Laporan Group User
        $groupReports = DailyReport::with(['creator', 'receiver', 'approver'])
            ->where('group_name', $userGroup)
            ->orderBy('report_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $groupReports->getCollection()->transform(function($item) {
            $item->user_name = $item->creator ? $item->creator->name : 'Unknown';
            return $item;
        });

        return view('officer.history', compact('pendingReports', 'groupReports'));
    }

    /**
     * Menampilkan Halaman Form Pembuatan Laporan Baru
     */
    public function create()
    {
        $vehicles = MasterUnit::select('id', 'name')->orderBy('id', 'asc')->get();
        $inventories = MasterInventoryItem::select('id', 'name', 'stock as qty')->orderBy('id', 'asc')->get();

        // Ambil Data Karyawan Group A-D
        $employeesGrouped = MasterEmployee::where('status', 'active')
                                          ->orderBy('id', 'asc')
                                          ->get()
                                          ->groupBy('group_name');

        return view('officer.create', compact('vehicles', 'inventories', 'employeesGrouped'));
    }

    /**
     * Menyimpan Data Laporan Baru (Store)
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();

            // 1. SIMPAN PARENT (DAILY REPORT)
            $report = DailyReport::create([
                'report_date'       => $request->report_date,
                'shift'             => $request->shift,
                'group_name'        => $request->group_name,
                'received_by_group' => $request->received_by_group,
                'time_range'        => $request->time_range,
                'status'            => 'submitted',
                'created_by'        => $user->id,
            ]);

            // 2. SIMPAN DETAILS
            $this->storeDetails($report, $request);

            DB::commit();
            return redirect()->route('reports.index')->with('success', 'Laporan Harian berhasil disimpan.');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan Halaman Edit Laporan
     */
    public function edit($id)
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

        // Validasi: Hanya bisa edit jika status 'submitted' (belum ditandatangani penerima)
        if ($report->status !== 'submitted') {
            return redirect()->route('reports.index')->with('error', 'Laporan yang sudah ditanda tangani/diterima tidak dapat diedit.');
        }

        $vehicles = MasterUnit::select('id', 'name')->orderBy('id', 'asc')->get();
        $inventories = MasterInventoryItem::select('id', 'name', 'stock as qty')->orderBy('id', 'asc')->get();

        $employeesGrouped = MasterEmployee::where('status', 'active')
                                          ->orderBy('id', 'asc')
                                          ->get()
                                          ->groupBy('group_name');

        return view('officer.edit', compact('report', 'vehicles', 'inventories', 'employeesGrouped'));
    }

    /**
     * Memperbarui Data Laporan (Update)
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $report = DailyReport::findOrFail($id);

            // Validasi status lagi untuk keamanan
            if ($report->status !== 'submitted') {
                return redirect()->route('reports.index')->with('error', 'Laporan tidak dapat diedit karena status sudah berubah.');
            }

            // 1. UPDATE PARENT DATA
            $report->update([
                'report_date'       => $request->report_date,
                'shift'             => $request->shift,
                'group_name'        => $request->group_name,
                'received_by_group' => $request->received_by_group,
                'time_range'        => $request->time_range,
                // Status & Created By tidak berubah
            ]);

            // 2. HAPUS SEMUA DATA DETAIL LAMA (Reset Strategy)
            // Ini cara teraman untuk menangani dynamic rows yang bisa bertambah/berkurang/berubah urutan

            // Hapus Loading Activities & Timesheets
            $report->loadingActivities()->each(function($a) { $a->timesheets()->delete(); $a->delete(); });

            // Hapus Bulk Loading & Logs
            $report->bulkLoadingActivities()->each(function($a) { $a->logs()->delete(); $a->delete(); });

            // Hapus Material & Container Activities
            if($report->materialActivity) {
                $report->materialActivity->items()->delete();
                $report->materialActivity->delete();
            }
            if($report->containerActivity) {
                $report->containerActivity->items()->delete();
                $report->containerActivity->delete();
            }

            // Hapus Turba & Deliveries
            if($report->turbaActivity) {
                $report->turbaActivity->deliveries()->delete();
                $report->turbaActivity->delete();
            }

            // Hapus Logs Unit & Karyawan
            $report->unitCheckLogs()->delete();
            $report->employeeLogs()->delete();

            // 3. SIMPAN ULANG DATA DETAIL BARU
            $this->storeDetails($report, $request);

            DB::commit();
            return redirect()->route('reports.index')->with('success', 'Laporan Harian berhasil diperbarui.');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat update: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Helper Function: Menyimpan Detail Laporan (Digunakan oleh store & update)
     */
    private function storeDetails($report, $request)
    {
        // 2. SECTION: MUAT KANTONG (Loop 1-20 untuk mengakomodasi tab dinamis)
        for ($i = 1; $i <= 20; $i++) {
            if ($request->filled("ship_name_{$i}")) {
                $loadingActivity = $report->loadingActivities()->create([
                    'sequence'       => $i,
                    'ship_name'      => $request->input("ship_name_{$i}"),
                    'agent'          => $request->input("agent_{$i}"),
                    'jetty'          => $request->input("jetty_{$i}"),
                    'destination'    => $request->input("destination_{$i}"),
                    'capacity'       => (float) $request->input("capacity_{$i}"),
                    'wo_number'      => $request->input("wo_number_{$i}"),
                    'cargo_type'     => $request->input("cargo_type_{$i}"),
                    'marking'        => $request->input("marking_{$i}"),
                    'arrival_time'   => $request->input("arrival_time_{$i}") ?: null,
                    'operating_gang' => $request->input("operating_gang_{$i}"),
                    'tkbm_count'     => (int) $request->input("tkbm_count_{$i}"),
                    'foreman'        => $request->input("foreman_{$i}"),
                    'qty_delivery_current' => (float) $request->input("qty_delivery_current_{$i}"),
                    'qty_delivery_prev'    => (float) $request->input("qty_delivery_prev_{$i}"),
                    'qty_loading_current'  => (float) $request->input("qty_loading_current_{$i}"),
                    'qty_loading_prev'     => (float) $request->input("qty_loading_prev_{$i}"),
                    'qty_damage_current'   => (float) $request->input("qty_damage_current_{$i}"),
                    'qty_damage_prev'      => (float) $request->input("qty_damage_prev_{$i}"),
                    'tally_warehouse'    => $request->input("tally_warehouse_{$i}"),
                    'driver_name'        => $request->input("driver_name_{$i}"),
                    'truck_number'       => $request->input("truck_number_{$i}"),
                    'tally_ship'         => $request->input("tally_ship_{$i}"),
                    'operator_ship'      => $request->input("operator_ship_{$i}"),
                    'forklift_ship'      => $request->input("forklift_ship_{$i}"),
                    'operator_warehouse' => $request->input("operator_warehouse_{$i}"),
                    'forklift_warehouse' => $request->input("forklift_warehouse_{$i}"),
                ]);

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

        // 3. SECTION: MUAT UREA (Loop 1-20)
        for ($i = 1; $i <= 20; $i++) {
            if ($request->filled("ship_name_urea_{$i}")) {
                $bulkActivity = $report->bulkLoadingActivities()->create([
                    'sequence'           => $i,
                    'ship_name'          => $request->input("ship_name_urea_{$i}"),
                    'jetty'              => $request->input("jetty_urea_{$i}"),
                    'destination'        => $request->input("destination_urea_{$i}"),
                    'agent'              => $request->input("agent_urea_{$i}"),
                    'stevedoring'        => $request->input("stevedoring_urea_{$i}"),
                    'commodity'          => $request->input("commodity_urea_{$i}"),
                    'capacity'           => (float) $request->input("capacity_urea_{$i}"),
                    'berthing_time'      => $request->input("berthing_time_urea_{$i}") ?: null,
                    'start_loading_time' => $request->input("start_loading_time_urea_{$i}") ?: null,
                ]);
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

        // 4. BONGKAR MATERIAL
        if ($request->filled('ship_name_material') || $request->has('unloading_materials')) {
            $materialActivity = $report->materialActivity()->create([
                'ship_name'             => $request->ship_name_material ?? $request->ship_name,
                'agent'                 => $request->agent_material ?? $request->agent,
                'capacity'              => (float) ($request->capacity_material ?? $request->Capacity),
                'ship_tally_names'        => $request->material_ship_tally_names,
                'forklift_operator_names' => $request->material_forklift_operator_names,
                'delivery_tally_names'    => $request->material_delivery_tally_names,
                'driver_names'            => $request->material_driver_names,
                'working_hours'           => $request->material_working_hours,
            ]);
            if ($request->has('unloading_materials')) {
                foreach ($request->unloading_materials as $mat) {
                    if (!empty($mat['raw_material_type'])) {
                        $materialActivity->items()->create([
                            'raw_material_type' => $mat['raw_material_type'],
                            'qty_current'       => (float) ($mat['qty_current'] ?? 0),
                            'qty_prev'          => (float) ($mat['qty_prev'] ?? 0),
                            'qty_total'         => (float) ($mat['qty_total'] ?? 0),
                        ]);
                    }
                }
            }
        }

        // 5. BONGKAR CONTAINER
        if ($request->filled('ship_name_container') || $request->has('unloading_containers')) {
            $containerActivity = $report->containerActivity()->create([
                'ship_name'      => $request->ship_name_container,
                'agent'          => $request->agent_container,
                'capacity'       => (float) $request->capacity_container,
                'ship_tally_names'   => $request->container_ship_tally_names,
                'gudang_tally_names' => $request->container_gudang_tally_names,
                'driver_names'       => $request->container_driver_names,
            ]);
            if ($request->has('unloading_containers')) {
                foreach ($request->unloading_containers as $cont) {
                    if (!empty($cont['time'])) {
                        $containerActivity->items()->create([
                            'time'        => $cont['time'],
                            'status'      => $cont['status'] ?? null,
                            'qty_current' => (float) ($cont['qty_current'] ?? 0),
                            'qty_prev'    => (float) ($cont['qty_prev'] ?? 0),
                            'qty_total'   => (float) ($cont['qty_total'] ?? 0),
                        ]);
                    }
                }
            }
        }

        // 6. TURBA
        $turba = $report->turbaActivity()->create([
            'tally_gudang_names'      => $request->tally_gudang_names,
            'forklift_operator_names' => $request->turba_forklift_operator,
            'driver_names'            => $request->turba_driver_names,
            'working_hours'           => $request->turba_working_hours,
        ]);
        if ($request->has('turba_deliveries')) {
            foreach ($request->turba_deliveries as $truck) {
                if (!empty($truck['truck_name'])) {
                    $turba->deliveries()->create([
                        'truck_name'      => $truck['truck_name'],
                        'do_so_number'    => $truck['do_so_number'] ?? null,
                        'capacity'        => (float) ($truck['capacity'] ?? 0),
                        'marking_type'    => $truck['marking_type'] ?? null,
                        'qty_current'     => (float) ($truck['qty_current'] ?? 0),
                        'qty_prev'        => (float) ($truck['qty_prev'] ?? 0),
                        'qty_accumulated' => (float) ($truck['qty_accumulated'] ?? 0),
                    ]);
                }
            }
        }

        // 7. CEK UNIT (Vehicle, Inventory, Shelter)
        if ($request->has('unit_logs')) {
            foreach ($request->unit_logs as $log) {
                $report->unitCheckLogs()->create([
                    'category'              => 'vehicle',
                    'item_name'             => 'Unit ID: ' . ($log['master_unit_id'] ?? 'Unknown'),
                    'master_id'             => $log['master_unit_id'] ?? null,
                    'fuel_level'            => isset($log['fuel_level']) ? (float) $log['fuel_level'] : null,
                    'condition_received'    => $log['condition_received'] ?? null,
                    'condition_handed_over' => $log['condition_handed_over'] ?? null,
                ]);
            }
        }
        if ($request->has('inventory_logs')) {
            foreach ($request->inventory_logs as $log) {
                $report->unitCheckLogs()->create([
                    'category'              => 'inventory',
                    'item_name'             => 'Item ID: ' . ($log['master_inventory_item_id'] ?? 'Unknown'),
                    'master_id'             => $log['master_inventory_item_id'] ?? null,
                    'quantity'              => (int) ($log['quantity'] ?? 1),
                    'condition_received'    => $log['condition_received'] ?? null,
                    'condition_handed_over' => $log['condition_handed_over'] ?? null,
                ]);
            }
        }
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

        // 8. EMPLOYEES LOGS
        // a. Shift
        for ($s = 1; $s <= 20; $s++) {
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
        // b. Operasi (Lembur & Relief)
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
        // c. Kegiatan Lain
        for ($l = 1; $l <= 5; $l++) {
            if ($request->filled("kegiatan_desc_{$l}")) {
                $report->employeeLogs()->create([
                    'category'       => 'lain',
                    'description'    => $request->input("kegiatan_desc_{$l}"),
                    'name'           => $request->input("kegiatan_personil_{$l}"),
                    'personil_count' => $request->input("kegiatan_personil_{$l}"), // Asumsi nama kolom DB personil_count/name sama
                    'time_in'        => $request->input("kegiatan_jam_{$l}"),
                ]);
            }
        }

        // d. OP.7 (BARU - Support Edit)
        if ($request->has('op7_logs')) {
            foreach ($request->op7_logs as $log) {
                if (!empty($log['name'])) {
                    $report->employeeLogs()->create([
                        'category'    => 'op7',
                        'name'        => $log['name'],
                        'time_in'     => $log['time_in'] ?? null,
                        'time_out'    => $log['time_out'] ?? null,
                        'work_area'   => $log['work_area'] ?? null,
                        'no_forklift_' => $log['no_forklift_'] ?? null,
                        'description' => $log['description'] ?? null,
                    ]);
                }
            }
        }

        // e. PENGGANTI (BARU - Support Edit)
        if ($request->has('replacement_logs')) {
            foreach ($request->replacement_logs as $log) {
                if (!empty($log['name'])) {
                    $report->employeeLogs()->create([
                        'category'    => 'replacement',
                        'name'        => $log['name'],
                        'time_in'     => $log['time_in'] ?? null,
                        'time_out'    => $log['time_out'] ?? null,
                        'work_area'   => $log['work_area'] ?? null,
                        'no_forklift_' => $log['no_forklift_'] ?? null,
                        'description' => $log['description'] ?? null,
                    ]);
                }
            }
        }
    }

    /**
     * Menampilkan Detail Laporan (Show Preview)
     */
    public function show($id)
    {
        $report = DailyReport::with([
            'creator', 'receiver', 'approver',
            'loadingActivities.timesheets',
            'bulkLoadingActivities.logs',
            'materialActivity.items',
            'containerActivity.items',
            'turbaActivity.deliveries',
            'unitCheckLogs',
            'employeeLogs'
        ])->findOrFail($id);

        // Kirim isPdf = false karena ini tampilan HTML browser
        $isPdf = false;

        return view('officer.viewpdf', compact('report', 'isPdf'));
    }

    /**
     * Action Tanda Tangan (Sign)
     */
    public function sign($id)
    {
        $user = Auth::user();
        $report = DailyReport::findOrFail($id);

        try {
            // LOGIKA 1: PENERIMA LAPORAN (Handover Shift)
            if ($report->status === 'submitted') {
                $report->update([
                    'status' => 'acknowledged',
                    'received_by_user_id' => $user->id,
                    'received_at' => Carbon::now(),
                ]);
                return back()->with('success', 'Laporan berhasil diterima dan ditanda tangani (Handover).');
            }

            // LOGIKA 2: MANAJER (Approval Akhir)
            if ($report->status === 'acknowledged') {
                $report->update([
                    'status' => 'approved',
                    'approved_by' => $user->id,
                    'approved_at' => Carbon::now(),
                ]);
                return back()->with('success', 'Laporan berhasil disetujui (Approved).');
            }

            return back()->with('error', 'Status laporan tidak valid untuk ditanda tangani.');

        } catch (Exception $e) {
            return back()->with('error', 'Gagal memproses tanda tangan: ' . $e->getMessage());
        }
    }

    /**
     * Export Laporan ke PDF
     */
    public function exportPdf($id)
    {
        $report = DailyReport::with([
            'creator', 'receiver', 'approver',
            'loadingActivities.timesheets',
            'bulkLoadingActivities.logs',
            'materialActivity.items',
            'containerActivity.items',
            'turbaActivity.deliveries',
            'unitCheckLogs',
            'employeeLogs'
        ])->findOrFail($id);

        // Kirim isPdf = true agar view menggunakan public_path()
        $isPdf = true;

        $pdf = Pdf::loadView('officer.pdf', compact('report', 'isPdf'));

        $custom_paper = array(0, 0, 612.00, 1008.00); // Ukuran Legal Custom
        $pdf->setPaper($custom_paper, 'portrait');

        // Opsi tambahan untuk performa (Opsional)
        $pdf->setOption('isRemoteEnabled', true);

        return $pdf->stream('Laporan-Harian-Shift-' . $report->id . '.pdf');
    }
}
