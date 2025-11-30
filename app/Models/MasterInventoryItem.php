<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterInventoryItem extends Model
{
    use HasFactory;

    protected $table = 'master_inventory_items';

    protected $fillable = [
        'name',
        'stock',
        'status',
    ];

    /**
     * Relasi ke Log Pengecekan Inventaris (History pengecekan barang ini)
     * Mengambil data dari tabel unit_check_logs dimana category = 'inventory'
     */
    public function checkLogs()
    {
        return $this->hasMany(UnitCheckLog::class, 'master_id', 'id')
                    ->where('category', 'inventory');
    }
}
