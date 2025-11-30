<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterUnit extends Model
{
    use HasFactory;

    protected $table = 'master_units';

    protected $fillable = [
        'name',
        'type',
        'status',
    ];

    /**
     * Relasi ke Log Pengecekan Unit (History pengecekan unit ini)
     * Mengambil data dari tabel unit_check_logs dimana category = 'vehicle'
     */
    public function checkLogs()
    {
        return $this->hasMany(UnitCheckLog::class, 'master_id', 'id')
                    ->where('category', 'vehicle');
    }
}
