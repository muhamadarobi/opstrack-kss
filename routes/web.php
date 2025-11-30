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

        Route::get('/users', [AdminController::class, 'pengguna'])->name('admin.pengguna'); // Sudah ada
        Route::post('/users', [AdminController::class, 'penggunaStore'])->name('admin.users.store');
        Route::put('/users/{id}', [AdminController::class, 'penggunaUpdate'])->name('admin.users.update');
        Route::delete('/users/{id}', [AdminController::class, 'penggunaDestroy'])->name('admin.users.destroy');

        // Master Data Page
        Route::get('/masterdata', [AdminController::class, 'masterdata'])->name('admin.masterdata');

        // CRUD Master Unit
        Route::post('/units', [AdminController::class, 'unitsStore'])->name('admin.units.store');
        Route::put('/units/{id}', [AdminController::class, 'unitsUpdate'])->name('admin.units.update');
        Route::delete('/units/{id}', [AdminController::class, 'unitsDestroy'])->name('admin.units.destroy');

        // CRUD Master Trucks (NEW)
        Route::post('/trucks', [AdminController::class, 'trucksStore'])->name('admin.trucks.store');
        Route::put('/trucks/{id}', [AdminController::class, 'trucksUpdate'])->name('admin.trucks.update');
        Route::delete('/trucks/{id}', [AdminController::class, 'trucksDestroy'])->name('admin.trucks.destroy');

        // CRUD Master Inventory
        Route::post('/inventories', [AdminController::class, 'inventoriesStore'])->name('admin.inventories.store');
        Route::put('/inventories/{id}', [AdminController::class, 'inventoriesUpdate'])->name('admin.inventories.update');
        Route::delete('/inventories/{id}', [AdminController::class, 'inventoriesDestroy'])->name('admin.inventories.destroy');

        // CRUD Master Employee (NEW)
        Route::post('/employees', [AdminController::class, 'employeesStore'])->name('admin.employees.store');
        Route::put('/employees/{id}', [AdminController::class, 'employeesUpdate'])->name('admin.employees.update');
        Route::delete('/employees/{id}', [AdminController::class, 'employeesDestroy'])->name('admin.employees.destroy');

        // View/Download PDF Laporan (Khusus Admin)
        Route::get('/report/{id}/view', [AdminController::class, 'viewPdf'])->name('admin.report.view');
    });

    // --- AREA REPORTS (Petugas) ---
    // Prefix URL akan menjadi /reports/...
    Route::middleware('role:petugas')->prefix('reports')->group(function () {

        // 1. Halaman History (Kita jadikan ini halaman utama /reports)
        Route::get('/', [ReportController::class, 'history'])->name('reports.history');

        // 2. Halaman Form Create
        Route::get('/create', [ReportController::class, 'create'])->name('reports.create');

        // 3. Proses Simpan Data (POST)
        Route::post('/store', [ReportController::class, 'store'])->name('reports.store');

        // Route PDF Baru (Untuk Petugas, jika diizinkan melihat PDF sendiri)
        Route::get('/{id}/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export_pdf');
    });

});
