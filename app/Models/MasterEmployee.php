<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterEmployee extends Model
{
    use HasFactory;

    protected $table = 'master_employees';

    protected $fillable = [
        'npk',
        'name',
        'group_name', // e.g., 'Group A', 'Foreman'
        'position',   // Added position field as requested in the view form
        'status',
    ];
}
