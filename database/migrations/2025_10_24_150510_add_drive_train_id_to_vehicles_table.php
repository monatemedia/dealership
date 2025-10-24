<?php // database/migrations/2025_10_24_150326_add_transmission_id_to_vehicles_table.php

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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreignId('drivetrain_id')
                ->nullable()
                ->constrained('drivetrains')
                ->nullOnDelete()
                ->after('transmission_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['drivetrain_id']);
            $table->dropColumn('drivetrain_id');
        });
    }
};
