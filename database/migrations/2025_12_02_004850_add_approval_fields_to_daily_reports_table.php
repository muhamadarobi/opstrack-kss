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
        Schema::table('daily_reports', function (Blueprint $table) {

            // 1. TAHAP PERTAMA: PEMBUAT LAPORAN (Shift Awal)
            // Kolom ini mencatat user ID pembuat laporan
            // Nullable ditambahkan agar tidak error jika ada data lama
            $table->foreignId('created_by')
                  ->nullable()
                  ->after('id') // Ditaruh di atas
                  ->constrained('users')
                  ->onDelete('cascade'); // Jika user dihapus, laporan ikut terhapus (opsional)

            // 2. TAHAP KEDUA: SERAH TERIMA (Shift Selanjutnya)
            // Kolom ini mencatat user ID penerima laporan
            $table->foreignId('received_by_user_id')
                  ->nullable()
                  ->after('received_by_group') // Ditaruh setelah info grup penerima
                  ->constrained('users');

            // Mencatat KAPAN tombol terima ditekan
            $table->timestamp('received_at')->nullable()->after('received_by_user_id');

            // 3. TAHAP KETIGA: MANAJER (Approval)
            // Kolom ini mencatat user ID manajer yang approve
            $table->foreignId('approved_by')
                  ->nullable()
                  ->after('status') // Ditaruh setelah status
                  ->constrained('users');

            // Mencatat KAPAN tombol approve ditekan
            $table->timestamp('approved_at')->nullable()->after('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            // Hapus Foreign Key dan Kolom secara berurutan

            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');

            $table->dropForeign(['received_by_user_id']);
            $table->dropColumn(['received_by_user_id', 'received_at']);

            $table->dropForeign(['approved_by']);
            $table->dropColumn(['approved_by', 'approved_at']);
        });
    }
};
