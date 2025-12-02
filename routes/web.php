<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\CheckRole;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// GROUP GUEST
Route::group([], function () {
    // Halaman Login Utama
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
});

// GROUP AUTH
Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // --- AREA ADMIN ---
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // Dashboard & Menu Utama
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

        Route::get('/dokumen', [AdminController::class, 'dokumen'])->name('admin.dokumen');
        Route::delete('/admin/dokumen/{id}', [AdminController::class, 'dokumenDestroy'])->name('admin.dokumen.destroy');

        Route::get('/users', [AdminController::class, 'pengguna'])->name('admin.pengguna');
        Route::post('/users', [AdminController::class, 'penggunaStore'])->name('admin.users.store');
        Route::put('/users/{id}', [AdminController::class, 'penggunaUpdate'])->name('admin.users.update');
        Route::delete('/users/{id}', [AdminController::class, 'penggunaDestroy'])->name('admin.users.destroy');

        // Master Data Page
        Route::get('/masterdata', [AdminController::class, 'masterdata'])->name('admin.masterdata');

        // CRUD Master Unit
        Route::post('/units', [AdminController::class, 'unitsStore'])->name('admin.units.store');
        Route::put('/units/{id}', [AdminController::class, 'unitsUpdate'])->name('admin.units.update');
        Route::delete('/units/{id}', [AdminController::class, 'unitsDestroy'])->name('admin.units.destroy');

        // CRUD Master Trucks
        Route::post('/trucks', [AdminController::class, 'trucksStore'])->name('admin.trucks.store');
        Route::put('/trucks/{id}', [AdminController::class, 'trucksUpdate'])->name('admin.trucks.update');
        Route::delete('/trucks/{id}', [AdminController::class, 'trucksDestroy'])->name('admin.trucks.destroy');

        // CRUD Master Inventory
        Route::post('/inventories', [AdminController::class, 'inventoriesStore'])->name('admin.inventories.store');
        Route::put('/inventories/{id}', [AdminController::class, 'inventoriesUpdate'])->name('admin.inventories.update');
        Route::delete('/inventories/{id}', [AdminController::class, 'inventoriesDestroy'])->name('admin.inventories.destroy');

        // CRUD Master Employee
        Route::post('/employees', [AdminController::class, 'employeesStore'])->name('admin.employees.store');
        Route::put('/employees/{id}', [AdminController::class, 'employeesUpdate'])->name('admin.employees.update');
        Route::delete('/employees/{id}', [AdminController::class, 'employeesDestroy'])->name('admin.employees.destroy');

        // View/Download PDF Laporan (Khusus Admin)
        Route::get('/report/{id}/view', [AdminController::class, 'viewPdf'])->name('admin.report.view');

        // [BARU] Route Approve Dokumen (Tanda Tangan Admin)
        Route::post('/report/{id}/approve', [AdminController::class, 'approve'])->name('admin.report.approve');
    });

    // --- AREA REPORTS (Petugas) ---
    // Prefix URL akan menjadi /reports/...
    Route::middleware('role:petugas')->prefix('reports')->group(function () {

        // 1. Halaman History (Utama)
        // Saya ubah namanya jadi 'reports.history' agar sinkron dengan redirect di ReportController
        Route::get('/officer', [ReportController::class, 'history'])->name('reports.index');

        // 2. Halaman Form Create
        Route::get('/officer/create', [ReportController::class, 'create'])->name('reports.create');

        // 3. Proses Simpan Data (POST)
        Route::post('/officer/store', [ReportController::class, 'store'])->name('reports.store');

        // 4. Export PDF
        Route::get('/officer/{id}/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export_pdf');

        // --- TAMBAHAN ROUTE ---
        // Route untuk Halaman Tanda Tangan (Sesuai Blade: reports.sign)
        Route::get('/officer/{id}/sign', [ReportController::class, 'sign'])->name('reports.sign');

        // Route Proses Simpan Tanda Tangan (Opsional, jika nanti butuh POST)
        // Route::post('/{id}/sign', [ReportController::class, 'processSign'])->name('reports.sign.process');

        // Route Detail Laporan (Sesuai Blade: reports.show)
        // Ditaruh paling bawah agar tidak bentrok dengan URL lain seperti /create
        Route::get('/officer/{id}', [ReportController::class, 'show'])->name('reports.show');
    });

});
