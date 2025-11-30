<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: Mesin Jahit Portable
            $table->integer('stock')->default(0); // Jumlah standar sesuai daftar (dikembalikan)
            $table->string('status')->default('active'); // active/inactive
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_inventory_items');
    }
};
