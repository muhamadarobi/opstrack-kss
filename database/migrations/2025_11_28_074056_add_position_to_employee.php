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
        Schema::table('master_employees', function (Blueprint $table) {
            // Cek untuk memastikan kolom belum ada agar tidak error duplicate
            if (!Schema::hasColumn('master_employees', 'position')) {
                // Menambahkan kolom position setelah kolom group_name
                $table->string('position')->nullable()->after('group_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_employees', function (Blueprint $table) {
            if (Schema::hasColumn('master_employees', 'position')) {
                $table->dropColumn('position');
            }
        });
    }
};
