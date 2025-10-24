<?php // database/migrations/2025_10_24_145341_add_drive_train_group_id_to_drive_trains_table.php

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
        Schema::table('drive_trains', function (Blueprint $table) {
            $table->foreignId('drive_train_group_id')
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
        Schema::table('drive_trains', function (Blueprint $table) {
            $table->dropForeign(['drive_train_group_id']);
            $table->dropColumn('drive_train_group_id');
        });
    }
};
