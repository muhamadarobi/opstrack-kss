<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_units', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: Trailler KSS-01
            $table->string('type'); // Contoh: Trailer, Forklift, dll
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_units');
    }
};
