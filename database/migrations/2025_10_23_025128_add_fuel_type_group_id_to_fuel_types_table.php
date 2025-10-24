<?php // 2025_10_23_025128_add_fuel_type_group_id_to_fuel_types_table.php

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
        Schema::table('fuel_types', function (Blueprint $table) {
            $table->foreignId('fuel_type_group_id')
                ->constrained()
                ->cascadeOnDelete()
                ->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fuel_types', function (Blueprint $table) {
            $table->dropForeign(['fuel_type_group_id']);
            $table->dropColumn('fuel_type_group_id');
        });
    }
};
