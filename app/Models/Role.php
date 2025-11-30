<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // Melindungi field id, sisanya boleh diisi (mass assignment)
    protected $guarded = ['id'];

    // Relasi: Satu Role memiliki banyak User
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
