<?php // 2025_10_23_024758_create_fuel_type_groups_table.php

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
        Schema::create('fuel_type_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45); // e.g., 'Powersport', 'Internal Combustion', etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuel_type_groups');
    }
};
