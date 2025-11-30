<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnloadingActivity extends Model
{
    protected $guarded = ['id'];

    public function dailyReport()
    {
        return $this->belongsTo(DailyReport::class);
    }

    public function materials()
    {
        return $this->hasMany(UnloadingMaterial::class);
    }

    public function containers()
    {
        return $this->hasMany(UnloadingContainer::class);
    }
}
