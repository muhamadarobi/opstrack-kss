<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi ke Material Activity (Parent)
    public function materialActivity()
    {
        return $this->belongsTo(MaterialActivity::class);
    }
}
