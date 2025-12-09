<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employee_logs', function (Blueprint $table) {
            // Menambahkan kolom work_time bertipe string
            // nullable() digunakan agar tidak error pada data lama yang sudah ada
            // after('time_out') agar posisi kolomnya rapi setelah time_out
            $table->string('work_time')->nullable()->after('time_out');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_logs', function (Blueprint $table) {
            $table->dropColumn('work_time');
        });
    }
};
