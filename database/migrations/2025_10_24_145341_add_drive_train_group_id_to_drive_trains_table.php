<?php // database/migrations/2025_10_24_145341_add_drivetrain_group_id_to_drivetrains_table.php

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
        Schema::table('drivetrains', function (Blueprint $table) {
            $table->foreignId('drivetrain_group_id')
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
        Schema::table('drivetrains', function (Blueprint $table) {
            $table->dropForeign(['drivetrain_group_id']);
            $table->dropColumn('drivetrain_group_id');
        });
    }
};
