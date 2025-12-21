<?php // 2025_10_23_025416_create_fuel_type_group_category_table.php

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
        Schema::create('fuel_type_group_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('fuel_type_group_id')->constrained()->onDelete('cascade');
            $table->string('default_fuel_type')->nullable(); // e.g., 'Petrol', 'Diesel', 'None'
            $table->boolean('can_edit')->default(true);

            $table->unique(['category_id', 'fuel_type_group_id'], 'category_fuel_group_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuel_type_group_category');
    }
};
