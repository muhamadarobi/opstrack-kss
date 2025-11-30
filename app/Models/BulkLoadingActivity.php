<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulkLoadingActivity extends Model
{
    protected $guarded = ['id'];

    public function dailyReport()
    {
        return $this->belongsTo(DailyReport::class);
    }

    public function logs()
    {
        return $this->hasMany(BulkLoadingLog::class);
    }
}
