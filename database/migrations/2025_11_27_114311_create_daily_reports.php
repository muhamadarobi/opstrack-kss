<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Utama: Laporan Harian
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->date('report_date');
            $table->string('shift'); // Pagi, Sore, Malam
            $table->string('group_name'); // A, B, C, D
            $table->string('received_by_group'); // A, B, C, D
            $table->string('time_range'); // 07-15, etc
            $table->string('status')->default('draft'); // draft, submitted
            $table->timestamps();
        });

        // 2. Section: Muat Kantong (Loading Activities) - Disimpan per Sequence (1-4)
        Schema::create('loading_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_report_id')->constrained()->onDelete('cascade');
            $table->integer('sequence'); // 1, 2, 3, 4

            // Info Kapal
            $table->string('ship_name')->nullable();
            $table->string('agent')->nullable();
            $table->string('jetty')->nullable();
            $table->string('destination')->nullable();
            $table->decimal('capacity', 15, 2)->default(0)->nullable();
            $table->string('wo_number')->nullable();
            $table->string('cargo_type')->nullable();
            $table->string('marking')->nullable();
            $table->dateTime('arrival_time')->nullable();
            $table->string('operating_gang')->nullable();
            $table->integer('tkbm_count')->default(0);
            $table->string('foreman')->nullable();

            // Quantities (Current, Previous, Accumulated)
            // Delivery
            $table->decimal('qty_delivery_current', 15, 2)->default(0);
            $table->decimal('qty_delivery_prev', 15, 2)->default(0);
            // Loading
            $table->decimal('qty_loading_current', 15, 2)->default(0);
            $table->decimal('qty_loading_prev', 15, 2)->default(0);
            // Damage
            $table->decimal('qty_damage_current', 15, 2)->default(0);
            $table->decimal('qty_damage_prev', 15, 2)->default(0);

            // Petugas di Tab ini
            $table->string('tally_warehouse')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('truck_number')->nullable();
            $table->string('tally_ship')->nullable();
            $table->string('operator_ship')->nullable();
            $table->string('forklift_ship')->nullable();
            $table->string('operator_warehouse')->nullable();
            $table->string('forklift_warehouse')->nullable();

            $table->timestamps();
        });

        // 2.1 Timesheets untuk Muat Kantong
        Schema::create('loading_timesheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loading_activity_id')->constrained()->onDelete('cascade');
            $table->string('category'); // 'delivery' atau 'loading'
            $table->time('time');
            $table->string('activity');
            $table->timestamps();
        });

        // 3. Section: Muat Urea (Bulk Loading) - Sequence 1-2
        Schema::create('bulk_loading_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_report_id')->constrained()->onDelete('cascade');
            $table->integer('sequence'); // 1, 2

            $table->string('ship_name')->nullable();
            $table->string('jetty')->nullable();
            $table->string('destination')->nullable();
            $table->string('agent')->nullable();
            $table->string('stevedoring')->nullable(); // PBM
            $table->string('commodity')->nullable();
            $table->decimal('capacity', 15, 2)->default(0)->nullable();
            $table->dateTime('berthing_time')->nullable();
            $table->dateTime('start_loading_time')->nullable();
            $table->timestamps();
        });

        // 3.1 Logs untuk Muat Urea (Timeline)
        Schema::create('bulk_loading_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bulk_loading_activity_id')->constrained()->onDelete('cascade');
            $table->dateTime('datetime');
            $table->string('activity');
            $table->integer('cob')->nullable(); // COB number
            $table->timestamps();
        });

        // 4. Section: Bongkar (Unloading)
        // 1. Tabel Parent: Bongkar Bahan Baku
        Schema::create('material_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_report_id')->constrained()->onDelete('cascade');
            $table->string('ship_name')->nullable();
            $table->string('agent')->nullable();
            $table->decimal('capacity', 15, 2)->default(0)->nullable();

            // Petugas khusus Bahan Baku
            $table->string('ship_tally_names')->nullable();
            $table->string('forklift_operator_names')->nullable();
            $table->string('delivery_tally_names')->nullable();
            $table->string('driver_names')->nullable();
            $table->string('working_hours')->nullable(); // Tetap ada untuk Bahan Baku
            $table->timestamps();
        });

        // 2. Tabel Child: Item Bahan Baku
        Schema::create('material_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_activity_id')->constrained('material_activities')->onDelete('cascade');
            $table->string('raw_material_type');
            $table->decimal('qty_current', 15, 2)->default(0);
            $table->decimal('qty_prev', 15, 2)->default(0);
            $table->decimal('qty_total', 15, 2)->default(0);
            $table->timestamps();
        });

        // 3. Tabel Parent: Bongkar Container
        Schema::create('container_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_report_id')->constrained()->onDelete('cascade');
            $table->string('ship_name')->nullable();
            $table->string('agent')->nullable();
            $table->string('capacity')->nullable();

            // Petugas khusus Container
            $table->string('ship_tally_names')->nullable(); // Tally Muat
            $table->string('gudang_tally_names')->nullable(); // Tally Gudang
            $table->string('driver_names')->nullable();
            // REMOVED: working_hours dihapus dari sini
            $table->timestamps();
        });

        // 4. Tabel Child: Item Container
        Schema::create('container_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('container_activity_id')->constrained('container_activities')->onDelete('cascade');
            $table->time('time')->nullable();
            $table->decimal('qty_current', 15, 2)->default(0);
            $table->decimal('qty_prev', 15, 2)->default(0);
            $table->decimal('qty_total', 15, 2)->default(0);
            $table->string('status')->nullable();
            $table->timestamps();
        });


        // 5. Section: Gudang Turba
        Schema::create('turba_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_report_id')->constrained()->onDelete('cascade');
            // Petugas
            $table->string('tally_gudang_names')->nullable();
            $table->string('forklift_operator_names')->nullable();
            $table->string('driver_names')->nullable();
            $table->string('working_hours')->nullable();
            $table->timestamps();
        });

        // 5.1 Kartu Truck Turba
        Schema::create('turba_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('turba_activity_id')->constrained()->onDelete('cascade');
            $table->string('truck_name')->nullable();
            $table->string('do_so_number')->nullable();
            $table->decimal('capacity', 15, 2)->default(0);
            $table->string('marking_type')->nullable();
            $table->decimal('qty_current', 15, 2)->default(0);
            $table->decimal('qty_prev', 15, 2)->default(0);
            $table->decimal('qty_accumulated', 15, 2)->default(0);
            $table->timestamps();
        });

        // 6. Section: Cek Unit & Inventaris (Digabung dalam satu tabel log dengan tipe beda)
        Schema::create('unit_check_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_report_id')->constrained()->onDelete('cascade');
            $table->string('category'); // 'vehicle', 'inventory', 'shelter'
            $table->string('item_name'); // Nama Unit / Barang
            $table->string('master_id')->nullable(); // ID referensi jika ada master data
            $table->string('fuel_level')->nullable(); // Khusus vehicle
            $table->string('condition_received')->nullable(); // Baik/Rusak
            $table->string('condition_handed_over')->nullable(); // Baik/Rusak
            $table->integer('quantity')->default(1); // Khusus Inventory
            $table->timestamps();
        });

        // 7. Section: Karyawan
        Schema::create('employee_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_report_id')->constrained()->onDelete('cascade');
            $table->string('category'); // 'shift', 'operasi', 'lain'
            $table->string('name')->nullable(); // Nama Karyawan / Deskripsi Kegiatan Lain
            $table->string('personil_count')->nullable(); // Untuk kegiatan lain
            $table->time('time_in')->nullable(); // Masuk / Lembur
            $table->time('time_out')->nullable(); // Pulang
            $table->string('description')->nullable(); // Keterangan / Relief Malam
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_logs');
        Schema::dropIfExists('unit_check_logs');
        Schema::dropIfExists('turba_deliveries');
        Schema::dropIfExists('turba_activities');
        Schema::dropIfExists('container_items');
        Schema::dropIfExists('container_activities');
        Schema::dropIfExists('material_items');
        Schema::dropIfExists('material_activities');
        Schema::dropIfExists('bulk_loading_logs');
        Schema::dropIfExists('bulk_loading_activities');
        Schema::dropIfExists('loading_timesheets');
        Schema::dropIfExists('loading_activities');
        Schema::dropIfExists('daily_reports');
    }
};
