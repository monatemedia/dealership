<?php // database/migrations/2025_11_11_165704_add_unique_constraints_migration.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Clean up duplicates before adding constraints

        // 1. Remove duplicate manufacturers (keep oldest by id)
        DB::statement("
            DELETE FROM manufacturers
            WHERE id NOT IN (
                SELECT MIN(id)
                FROM manufacturers
                GROUP BY name
            )
        ");

        // 2. Remove duplicate models (keep oldest by id)
        DB::statement("
            DELETE FROM models
            WHERE id NOT IN (
                SELECT MIN(id)
                FROM models
                GROUP BY name, manufacturer_id
            )
        ");

        // 3. Remove duplicate provinces (keep oldest by id)
        DB::statement("
            DELETE FROM provinces
            WHERE id NOT IN (
                SELECT MIN(id)
                FROM provinces
                GROUP BY name
            )
        ");

        // 4. Remove duplicate cities (keep oldest by id)
        DB::statement("
            DELETE FROM cities
            WHERE id NOT IN (
                SELECT MIN(id)
                FROM cities
                GROUP BY name, province_id
            )
        ");

        // Now add unique constraints
        Schema::table('manufacturers', function (Blueprint $table) {
            $table->unique('name');
        });

        Schema::table('models', function (Blueprint $table) {
            $table->unique(['name', 'manufacturer_id']);
        });

        Schema::table('provinces', function (Blueprint $table) {
            $table->unique('name');
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->unique(['name', 'province_id']);
        });
    }

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
