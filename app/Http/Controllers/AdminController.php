<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Role;
use App\Models\MasterUnit;
use App\Models\MasterInventoryItem;
use App\Models\MasterTruck;
use App\Models\MasterEmployee;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    /**
     * Dashboard Utama Admin
     * Menampilkan statistik dan dokumen yang perlu persetujuan (Acknowledged)
     */
    public function index()
    {
        // 1. Statistik Dokumen
        $totalDocs = DailyReport::count();
        $todayDocs = DailyReport::whereDate('report_date', Carbon::today())->count();
        $monthlyDocs = DailyReport::whereMonth('report_date', Carbon::now()->month)
                                    ->whereYear('report_date', Carbon::now()->year)
                                    ->count();

        // 2. Statistik Petugas
        $totalPetugas = User::whereHas('role', function($q){
            $q->where('name', 'like', '%petugas%');
        })->count();

        // 3. Daftar Dokumen Pending (Menunggu Tanda Tangan Admin)
        // Status 'acknowledged' berarti sudah diserahkan oleh petugas shift selanjutnya
        $recentReports = DailyReport::with(['user', 'creator', 'receiver'])
                                    ->where('status', 'acknowledged')
                                    ->orderBy('received_at', 'asc') // Urutkan dari yang terlama menunggu
                                    ->get();

        return view('admin.dashboard', compact(
            'totalDocs',
            'todayDocs',
            'monthlyDocs',
            'totalPetugas',
            'recentReports'
        ));
    }

    /**
     * Proses Approval & Tanda Tangan Admin
     * - Mengubah status jadi 'approved'
     * - Mencatat waktu dan admin yang menyetujui
     * - MENYIMPAN FILE PDF FISIK KE STORAGE (Caching) agar loading selanjutnya cepat
     */
    public function approve($id)
    {
        $report = DailyReport::with([
            'loadingActivities.timesheets',
            'bulkLoadingActivities.logs',
            'materialActivity.items',
            'containerActivity.items',
            'turbaActivity.deliveries',
            'unitCheckLogs',
            'employeeLogs',
            'creator', 'receiver'
        ])->findOrFail($id);

        // Validasi Status
        if ($report->status !== 'acknowledged') {
            return redirect()->back()->with('error', 'Status dokumen tidak valid atau sudah disetujui sebelumnya.');
        }

        // 1. Update Database
        $report->update([
            'status'      => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => Carbon::now(),
        ]);

        // 2. Load Relasi Approver (Diri sendiri, karena baru saja update)
        $report->load('approver');

        // 3. Generate PDF & Simpan ke Storage (Caching)
        try {
            // Pastikan folder storage ada
            $storagePath = storage_path('app/public/reports');
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }

            $filename = 'report-' . $report->id . '.pdf';
            $pdf = Pdf::loadView('officer.pdf', compact('report'));
            $pdf->setPaper([0, 0, 612.00, 1008.00], 'portrait');

            // Simpan file fisik
            $pdf->save($storagePath . '/' . $filename);

        } catch (\Exception $e) {
            // Jika gagal save PDF, log error tapi biarkan proses lanjut (user bisa view on-fly nanti)
            Log::error("Gagal menyimpan cache PDF ID {$id}: " . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Dokumen berhasil disetujui, ditanda tangani, dan diarsipkan.');
    }

    /**
     * Menampilkan PDF Laporan
     * Menggunakan strategi Caching: Cek file fisik dulu, jika tidak ada baru render ulang.
     */
    public function viewPdf($id)
    {
        $report = DailyReport::findOrFail($id);
        $filename = 'report-' . $report->id . '.pdf';
        $path = storage_path('app/public/reports/' . $filename);

        // CEK 1: Apakah status Approved DAN file fisik ada?
        if ($report->status == 'approved' && file_exists($path)) {
            // Serve file langsung (Sangat Cepat, < 100ms)
            return response()->file($path, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"'
            ]);
        }

        // CEK 2: Jika belum approved atau file cache hilang, Render On-The-Fly (Beban Server)
        // Load semua relasi yang diperlukan untuk view PDF
        $report->load([
            'loadingActivities.timesheets',
            'bulkLoadingActivities.logs',
            'materialActivity.items',
            'containerActivity.items',
            'turbaActivity.deliveries',
            'unitCheckLogs',
            'employeeLogs',
            'creator', 'receiver', 'approver'
        ]);

        $pdf = Pdf::loadView('officer.pdf', compact('report'));
        $pdf->setPaper([0, 0, 612.00, 1008.00], 'portrait');

        return $pdf->stream($filename);
    }

    /**
     * Halaman Manajemen Dokumen (Arsip)
     * Hanya menampilkan dokumen yang sudah 'approved'
     */
    public function dokumen(Request $request)
    {
        $query = DailyReport::with(['creator', 'approver'])
                            ->where('status', 'approved');

        // Filter Pencarian
        if ($request->filled('cari')) {
            $search = $request->cari;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('creator', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('report_date', $request->tanggal);
        }

        $reports = $query->latest('report_date')->paginate(10)->withQueryString();

        return view('admin.dokumen', compact('reports'));
    }

    /**
     * Hapus Dokumen Laporan
     */
    public function dokumenDestroy($id)
    {
        $report = DailyReport::findOrFail($id);

        // Hapus file fisik jika ada
        $filename = 'report-' . $report->id . '.pdf';
        $path = storage_path('app/public/reports/' . $filename);
        if (file_exists($path)) {
            unlink($path);
        }

        $report->delete();

        return redirect()->back()->with('success', 'Dokumen laporan berhasil dihapus.');
    }

    // ==========================================
    // MANAJEMEN PENGGUNA (USERS)
    // ==========================================

    public function pengguna(Request $request)
    {
        $query = User::with('role');

        if ($request->filled('cari')) {
            $query->where('name', 'like', '%' . $request->cari . '%')
                  ->orWhere('username', 'like', '%' . $request->cari . '%');
        }

        $users = $query->orderBy('role_id')->paginate(10)->withQueryString();
        $roles = Role::all();

        return view('admin.pengguna', compact('users', 'roles'));
    }

    public function penggunaStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username|max:255',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'status' => 'aktif',
        ]);

        return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function penggunaUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'role_id' => $request->role_id,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function penggunaDestroy($id)
    {
        $user = User::findOrFail($id);
        if (auth()->id() == $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }
        $user->delete();
        return redirect()->back()->with('success', 'Pengguna berhasil dihapus.');
    }

    // ==========================================
    // MASTER DATA (UNITS, TRUCKS, INVENTORY, EMPLOYEES)
    // ==========================================

    public function masterdata()
    {
        $units = MasterUnit::orderBy('id', 'asc')->paginate(10, ['*'], 'units_page');
        $trucks = MasterTruck::orderBy('id', 'asc')->paginate(10, ['*'], 'trucks_page');
        $inventories = MasterInventoryItem::orderBy('id', 'asc')->paginate(10, ['*'], 'inv_page');
        $employees = MasterEmployee::orderBy('id', 'asc')->paginate(10, ['*'], 'emps_page');

        return view('admin.masterdata', compact('units', 'trucks', 'inventories', 'employees'));
    }

    // --- Master Units ---
    public function unitsStore(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255', 'type' => 'required|string|max:255']);
        MasterUnit::create(['name' => $request->name, 'type' => $request->type, 'status' => 'active']);
        return redirect()->back()->with('success', 'Unit berhasil ditambahkan.');
    }
    public function unitsUpdate(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255', 'type' => 'required|string|max:255']);
        $unit = MasterUnit::findOrFail($id);
        $unit->update($request->all());
        return redirect()->back()->with('success', 'Unit berhasil diperbarui.');
    }
    public function unitsDestroy($id)
    {
        $unit = MasterUnit::findOrFail($id);
        $unit->delete();
        return redirect()->back()->with('success', 'Unit berhasil dihapus.');
    }

    // --- Master Trucks ---
    public function trucksStore(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255', 'plate_number' => 'nullable|string|max:20', 'description' => 'nullable|string|max:255']);
        MasterTruck::create(['name' => $request->name, 'plate_number' => $request->plate_number, 'description' => $request->description]);
        return redirect()->back()->with('success', 'Truck berhasil ditambahkan.');
    }
    public function trucksUpdate(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255', 'plate_number' => 'nullable|string|max:20', 'description' => 'nullable|string|max:255']);
        $truck = MasterTruck::findOrFail($id);
        $truck->update(['name' => $request->name, 'plate_number' => $request->plate_number, 'description' => $request->description]);
        return redirect()->back()->with('success', 'Truck berhasil diperbarui.');
    }
    public function trucksDestroy($id)
    {
        $truck = MasterTruck::findOrFail($id);
        $truck->delete();
        return redirect()->back()->with('success', 'Truck berhasil dihapus.');
    }

    // --- Master Inventories ---
    public function inventoriesStore(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255', 'category' => 'required|string|max:255']);
        MasterInventoryItem::create(['name' => $request->name, 'category' => $request->category, 'stock' => 0, 'status' => 'active']);
        return redirect()->back()->with('success', 'Item inventory berhasil ditambahkan.');
    }
    public function inventoriesUpdate(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255', 'category' => 'required|string|max:255']);
        $item = MasterInventoryItem::findOrFail($id);
        $item->update($request->all());
        return redirect()->back()->with('success', 'Item inventory berhasil diperbarui.');
    }
    public function inventoriesDestroy($id)
    {
        $item = MasterInventoryItem::findOrFail($id);
        $item->delete();
        return redirect()->back()->with('success', 'Item inventory berhasil dihapus.');
    }

    // --- Master Employees ---
    public function employeesStore(Request $request)
    {
        $request->validate([
            'npk' => 'required|unique:master_employees,npk',
            'name' => 'required|string|max:255',
            'group_name' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255'
        ]);
        MasterEmployee::create([
            'npk' => $request->npk,
            'name' => $request->name,
            'group_name' => $request->group_name,
            'position' => $request->position,
            'status' => 'active'
        ]);
        return redirect()->back()->with('success', 'Karyawan berhasil ditambahkan.');
    }
    public function employeesUpdate(Request $request, $id)
    {
        $request->validate([
            'npk' => 'required|unique:master_employees,npk,' . $id,
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255'
        ]);
        $emp = MasterEmployee::findOrFail($id);
        $emp->update($request->all());
        return redirect()->back()->with('success', 'Data karyawan berhasil diperbarui.');
    }
    public function employeesDestroy($id)
    {
        $emp = MasterEmployee::findOrFail($id);
        $emp->delete();
        return redirect()->back()->with('success', 'Data karyawan berhasil dihapus.');
    }
}
