<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTruck extends Model
{
    use HasFactory;

    protected $table = 'master_trucks';

    protected $fillable = [
        'name',        // e.g., 'Buffer Stock'
        'plate_number',
        'description', // e.g., 'Truck Hino 500'
        'status',
    ];
}
