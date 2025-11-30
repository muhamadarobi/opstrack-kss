<?php

namespace App\Models;

// Pastikan menggunakan trait Authenticatable
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = ['id'];

    // Sembunyikan password dan token saat model di-convert ke array/json
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casting tipe data (opsional tapi disarankan)
    protected $casts = [
        'password' => 'hashed',
    ];

    // Relasi: User memiliki satu Role
    // Ini penting agar kita bisa memanggil $user->role->name
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
