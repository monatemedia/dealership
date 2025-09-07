<?php

// database/migrations/2024_12_24_001408_create_car_features_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('car_features', function (Blueprint $table) {
            // Car_features table has 1 to 1 relationship with cars table, and uses the car's id
            $table->unsignedBigInteger('car_id')->primary();
            $table->boolean('abs')->default(0);
            $table->boolean('air_conditioning')->default(0);
            $table->boolean('power_windows')->default(0);
            $table->boolean('power_door_locks')->default(0);
            $table->boolean('cruise_control')->default(0);
            $table->boolean('bluetooth_connectivity')->default(0);
            $table->boolean('remote_start')->default(0);
            $table->boolean('gps_navigation')->default(0);
            $table->boolean('heated_seats')->default(0);
            $table->boolean('climate_control')->default(0);
            $table->boolean('rear_parking_sensors')->default(0);
            $table->boolean('leather_seats')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_features');
    }
};
