<?php // database/migrations/2025_10_02_141018_drop_vehicle_features_table.php

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
        // Drop the vehicle_features table
        Schema::dropIfExists('vehicle_features');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
