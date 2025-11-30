<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
    // ... (Index method unchanged) ...
    public function index()
    {
        $totalDocs = DailyReport::count();
        $todayDocs = DailyReport::whereDate('report_date', Carbon::today())->count();
        $monthlyDocs = DailyReport::whereMonth('report_date', Carbon::now()->month)
                                    ->whereYear('report_date', Carbon::now()->year)
                                    ->count();

        $totalPetugas = User::whereHas('role', function($q){
            $q->where('name', 'like', '%petugas%');
        })->count();

        $recentReports = DailyReport::with('user')
                                    ->latest('created_at')
                                    ->take(5)
                                    ->get();

        return view('admin.dashboard', compact(
            'totalDocs',
            'todayDocs',
            'monthlyDocs',
            'totalPetugas',
            'recentReports'
        ));
    }

    // ... (View PDF method unchanged) ...
    public function viewPdf($id)
    {
        $report = DailyReport::with([
            'loadingActivities.timesheets',
            'bulkLoadingActivities.logs',
            'materialActivity.items',
            'containerActivity.items',
            'turbaActivity.deliveries',
            'unitCheckLogs',
            'employeeLogs',
            'user'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('officer.pdf', compact('report'));

        $custom_paper = array(0, 0, 612.00, 1008.00);
        $pdf->setPaper($custom_paper, 'portrait');

        $tanggal = \Carbon\Carbon::parse($report->report_date)->translatedFormat('d F Y');
        $shift = $report->shift;

        $filename = "Laporan Shift Harian-{$tanggal}-{$shift}.pdf";

        return $pdf->stream($filename);
    }

    // --- MANAJEMEN DOKUMEN ---

    public function dokumen(Request $request)
    {
        $query = DailyReport::with('user');

        // Filter Pencarian
        if ($request->filled('cari')) {
            $search = $request->cari;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('report_date', $request->tanggal);
        }

        $reports = $query->latest('created_at')->paginate(10)->withQueryString();

        return view('admin.dokumen', compact('reports'));
    }

    // [BARU] Method untuk Menghapus Dokumen
    public function dokumenDestroy($id)
    {
        $report = DailyReport::findOrFail($id);

        // Opsional: Hapus relasi terkait jika tidak menggunakan onDelete('cascade') di database
        // $report->loadingActivities()->delete();
        // dll...

        $report->delete();

        return redirect()->back()->with('success', 'Dokumen laporan berhasil dihapus.');
    }

    // ... (Pengguna & Master Data methods unchanged below) ...

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

    public function masterdata()
    {
        $units = MasterUnit::orderBy('id', 'asc')->paginate(10, ['*'], 'units_page');
        $trucks = MasterTruck::orderBy('id', 'asc')->paginate(10, ['*'], 'trucks_page');
        $inventories = MasterInventoryItem::orderBy('id', 'asc')->paginate(10, ['*'], 'inv_page');
        $employees = MasterEmployee::orderBy('id', 'asc')->paginate(10, ['*'], 'emps_page');

        return view('admin.masterdata', compact('units', 'trucks', 'inventories', 'employees'));
    }

    // CRUD Master Data Methods...
    public function unitsStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
        ]);
        MasterUnit::create([
            'name' => $request->name,
            'type' => $request->type,
            'status' => 'active'
        ]);
        return redirect()->back()->with('success', 'Unit berhasil ditambahkan.');
    }

    public function unitsUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
        ]);
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

    public function trucksStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'plate_number' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:255',
        ]);
        MasterTruck::create([
            'name' => $request->name,
            'plate_number' => $request->plate_number,
            'description' => $request->description
        ]);
        return redirect()->back()->with('success', 'Truck berhasil ditambahkan.');
    }

    public function trucksUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'plate_number' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:255',
        ]);
        $truck = MasterTruck::findOrFail($id);
        $truck->update([
            'name' => $request->name,
            'plate_number' => $request->plate_number,
            'description' => $request->description,
        ]);
        return redirect()->back()->with('success', 'Truck berhasil diperbarui.');
    }

    public function trucksDestroy($id)
    {
        $truck = MasterTruck::findOrFail($id);
        $truck->delete();
        return redirect()->back()->with('success', 'Truck berhasil dihapus.');
    }

    public function inventoriesStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
        ]);
        MasterInventoryItem::create([
            'name' => $request->name,
            'category' => $request->category,
            'stock' => 0,
            'status' => 'active'
        ]);
        return redirect()->back()->with('success', 'Item inventory berhasil ditambahkan.');
    }

    public function inventoriesUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
        ]);
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

    public function employeesStore(Request $request)
    {
        $request->validate([
            'npk' => 'required|unique:master_employees,npk',
            'name' => 'required|string|max:255',
            'group_name' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
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
            'position' => 'nullable|string|max:255',
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
