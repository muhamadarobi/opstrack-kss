<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // --- TAMBAHAN PENTING: RELASI KE USER ---
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Section 1: Muat Kantong
    public function loadingActivities()
    {
        return $this->hasMany(LoadingActivity::class);
    }

    // Relasi ke Section 2: Muat Urea
    public function bulkLoadingActivities()
    {
        return $this->hasMany(BulkLoadingActivity::class);
    }

    // Relasi ke Section 3: Bongkar
    public function materialActivity()
    {
        return $this->hasOne(MaterialActivity::class);
    }

    public function containerActivity()
    {
        return $this->hasOne(ContainerActivity::class);
    }

    // Relasi ke Section 4: Gudang Turba
    public function turbaActivity()
    {
        return $this->hasOne(TurbaActivity::class);
    }

    // Relasi ke Section 5: Log Unit & Inventaris
    public function unitCheckLogs()
    {
        return $this->hasMany(UnitCheckLog::class);
    }

    // Relasi ke Section 6: Log Karyawan
    public function employeeLogs()
    {
        return $this->hasMany(EmployeeLog::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi 2: Petugas Penerima (Shift Berikutnya)
    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by_user_id');
    }

    // Relasi 3: Manajer
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
