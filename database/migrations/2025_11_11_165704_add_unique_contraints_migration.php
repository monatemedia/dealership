<?php // database/migrations/2025_11_11_165704_add_unique_constraints_migration.php

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
        // Add unique constraint to manufacturers table
        Schema::table('manufacturers', function (Blueprint $table) {
            $table->unique('name');
        });

        // Add composite unique constraint to models table
        Schema::table('models', function (Blueprint $table) {
            $table->unique(['name', 'manufacturer_id']);
        });

        // Add unique constraint to provinces table
        Schema::table('provinces', function (Blueprint $table) {
            $table->unique('name');
        });

        // Add composite unique constraint to cities table
        Schema::table('cities', function (Blueprint $table) {
            $table->unique(['name', 'province_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('manufacturers', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });

        Schema::table('models', function (Blueprint $table) {
            $table->dropUnique(['name', 'manufacturer_id']);
        });

        Schema::table('provinces', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->dropUnique(['name', 'province_id']);
        });
    }
};
