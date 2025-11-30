<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeLog extends Model
{
    // Mengizinkan mass assignment untuk semua kolom kecuali id
    protected $guarded = ['id'];

    /**
     * Relasi ke Parent: DailyReport
     * Log ini adalah bagian dari laporan harian tertentu.
     */
    public function dailyReport()
    {
        return $this->belongsTo(DailyReport::class);
    }

    /**
     * Relasi ke Master Data: MasterEmployee
     * Log ini merujuk pada satu orang karyawan.
     * * PENTING: Pastikan tabel 'employee_logs' memiliki kolom 'master_employee_id'
     */
    public function employee()
    {
        return $this->belongsTo(MasterEmployee::class, 'master_employee_id');
    }
}
