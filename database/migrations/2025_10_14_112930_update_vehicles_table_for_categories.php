<?php // database/migrations/2025_10_14_112930_update_vehicles_table_for_categories.php

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
            // Drop old vehicle_category_id if it exists
            if (Schema::hasColumn('vehicles', 'vehicle_category_id')) {
                $table->dropForeign(['vehicle_category_id']);
                $table->dropColumn('vehicle_category_id');
            }
        });

        Schema::table('vehicles', function (Blueprint $table) {
            // Add main_category_id column if missing
            if (!Schema::hasColumn('vehicles', 'main_category_id')) {
                $table->unsignedBigInteger('main_category_id')->nullable()->after('id');
            }

            // Add sub_category_id column if missing
            if (!Schema::hasColumn('vehicles', 'sub_category_id')) {
                $table->unsignedBigInteger('sub_category_id')->nullable()->after('main_category_id');
            }
        });

        Schema::table('vehicles', function (Blueprint $table) {
            // Add foreign key constraints
            $table->foreign('main_category_id')
                  ->references('id')
                  ->on('main_categories')
                  ->nullOnDelete();

            $table->foreign('sub_category_id')
                  ->references('id')
                  ->on('sub_categories')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['main_category_id']);
            $table->dropForeign(['sub_category_id']);
            $table->dropColumn(['main_category_id', 'sub_category_id']);

            // Restore old vehicle_category_id field
            $table->unsignedBigInteger('vehicle_category_id')->nullable()->after('id');
        });
    }
};
