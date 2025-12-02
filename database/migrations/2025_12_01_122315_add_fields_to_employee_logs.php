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
            // Menambahkan kolom no_forklift_ setelah kolom name
            // Menggunakan nullable() agar data lama tidak error dan opsional
            $table->string('no_forklift_')->nullable()->after('name');

            // Menambahkan kolom work_area setelah no_forklift_
            $table->string('work_area')->nullable()->after('no_forklift_');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_logs', function (Blueprint $table) {
            // Menghapus kolom jika dilakukan rollback
            $table->dropColumn(['no_forklift_', 'work_area']);
        });
    }
};
