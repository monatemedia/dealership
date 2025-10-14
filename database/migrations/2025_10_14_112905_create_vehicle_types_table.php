<?php // database/migrations/2025_10_14_112905_create_vehicle_types_table.php

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
        // Check if table already exists (from old implementation)
        if (Schema::hasTable('vehicle_types')) {
            // If it exists, we need to add the sub_category_id column if missing
            if (!Schema::hasColumn('vehicle_types', 'sub_category_id')) {
                Schema::table('vehicle_types', function (Blueprint $table) {
                    $table->foreignId('sub_category_id')
                        ->after('name') // 'name' exists; 'slug' does not
                        ->nullable()
                        ->constrained('sub_categories')
                        ->cascadeOnDelete();
                });
            }
        } else {
            // Create the table if it doesn't exist
            Schema::create('vehicle_types', function (Blueprint $table) {
                $table->id();
                $table->string('name', 45);
                $table->string('long_name');
                $table->string('description');
                $table->string('image_path');
                $table->string('slug')->unique();
                $table->foreignId('sub_category_id')
                    ->constrained('sub_categories')
                    ->cascadeOnDelete();

                $table->unique(['name', 'sub_category_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    // public function down(): void
    // {
    //     Schema::dropIfExists('vehicle_types');
    // }
};
