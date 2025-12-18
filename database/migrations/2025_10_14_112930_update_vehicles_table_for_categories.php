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
            // Add section_id column if missing
            if (!Schema::hasColumn('vehicles', 'section_id')) {
                $table->unsignedBigInteger('section_id')->nullable()->after('id');
            }

            // Add subcategory_id column if missing
            if (!Schema::hasColumn('vehicles', 'subcategory_id')) {
                $table->unsignedBigInteger('subcategory_id')->nullable()->after('section_id');
            }
        });

        Schema::table('vehicles', function (Blueprint $table) {
            // Add foreign key constraints
            $table->foreign('section_id')
                  ->references('id')
                  ->on('sections')
                  ->nullOnDelete();

            $table->foreign('subcategory_id')
                  ->references('id')
                  ->on('subcategories')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
            $table->dropForeign(['subcategory_id']);
            $table->dropColumn(['section_id', 'subcategory_id']);

            // Restore old vehicle_category_id field
            $table->unsignedBigInteger('vehicle_category_id')->nullable()->after('id');
        });
    }
};
