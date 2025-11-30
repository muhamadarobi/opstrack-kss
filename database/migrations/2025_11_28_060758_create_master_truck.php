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
        Schema::create('master_trucks', function (Blueprint $table) {
            $table->id();
            $table->string('name');        // Nama Truk (Buffer Stock, Buffer Stufing)
            $table->string('plate_number')->nullable(); // Plat Nomor (Boleh Kosong)
            $table->string('description')->nullable();  // Deskripsi/Jenis Mobil (Boleh Kosong)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_trucks');
    }
};
