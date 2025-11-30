<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_employees', function (Blueprint $table) {
            $table->id();
            $table->string('npk')->unique(); // Nomor Pokok Karyawan, unik
            $table->string('name');
            $table->string('group_name')->nullable(); // Group A, B, C, D atau Foreman/Manager
            $table->string('status')->default('active'); // active/inactive
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_employees');
    }
};
