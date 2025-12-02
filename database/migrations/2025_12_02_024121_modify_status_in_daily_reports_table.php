<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // LANGKAH 1: Amankan data lama (Opsional tapi Disarankan)
        // Jika ada status lama yang tidak ada di daftar baru (misal: 'rejected'),
        // kita ubah dulu menjadi 'draft' agar tidak error saat alter table.
        DB::table('daily_reports')
            ->whereNotIn('status', ['draft', 'submitted', 'acknowledged', 'approved'])
            ->update(['status' => 'draft']);

        // LANGKAH 2: Ubah kolom menjadi ENUM dengan Raw SQL
        // Menggunakan MODIFY COLUMN agar presisi
        DB::statement("ALTER TABLE daily_reports MODIFY COLUMN status ENUM('draft', 'submitted', 'acknowledged', 'approved') NOT NULL DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke tipe String (Varchar) seperti schema awal Anda
        // Agar aman jika di-rollback
        Schema::table('daily_reports', function (Blueprint $table) {
            $table->string('status')->default('draft')->change();
        });
    }
};
