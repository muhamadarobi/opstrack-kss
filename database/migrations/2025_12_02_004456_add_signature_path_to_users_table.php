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
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom path untuk gambar tanda tangan (PNG)
            // Disimpan setelah password agar rapi
            $table->string('signature_path')->nullable()->after('password');
            // Menambahkan kolom group untuk petugas (A, B, C, D)
            // Nullable karena admin/manajer mungkin tidak punya grup shift
            $table->enum('group', ['a', 'b', 'c', 'd'])->nullable()->after('signature_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kedua kolom jika rollback
            $table->dropColumn(['signature_path', 'group']);
        });
    }
};
