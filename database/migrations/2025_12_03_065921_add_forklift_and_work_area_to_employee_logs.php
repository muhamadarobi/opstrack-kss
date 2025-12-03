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
            // Menambahkan kolom no_forklift dan work_area
            // Menggunakan nullable() karena tidak semua kategori log (misal 'shift' biasa) memerlukannya
            $table->string('no_forklift')->nullable()->after('description');
            $table->string('work_area')->nullable()->after('no_forklift');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_logs', function (Blueprint $table) {
            $table->dropColumn(['no_forklift', 'work_area']);
        });
    }
};
