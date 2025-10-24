<?php // database/migrations/2025_10_24_170141_create_ownership_paperwork_vehicle_table.php

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
        Schema::create('ownership_paperwork_vehicle', function (Blueprint $table) {
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ownership_paperwork_id')->constrained('ownership_paperwork')->cascadeOnDelete();
            $table->primary(['vehicle_id', 'ownership_paperwork_id'], 'opv_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ownership_paperwork_vehicle');
    }
};
