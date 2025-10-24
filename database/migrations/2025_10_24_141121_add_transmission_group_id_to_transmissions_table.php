<?php // database/migrations/2025_10_24_140814_create_transmissions_table.php

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
        Schema::table('transmissions', function (Blueprint $table) {
            $table->foreignId('transmission_group_id')
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
        Schema::table('transmissions', function (Blueprint $table) {
            $table->dropForeign(['transmission_group_id']);
            $table->dropColumn('transmission_group_id');
        });
    }
};
