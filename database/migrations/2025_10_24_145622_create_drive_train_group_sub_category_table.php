<?php // database/migrations/2025_10_24_145622_create_drive_train_group_sub_category_table.php

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
        Schema::create('drive_train_group_sub_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('drive_train_group_id')->constrained()->onDelete('cascade');
            $table->string('default_drive_train')->nullable(); // e.g., 'Rear-Wheel Drive (RWD)'
            $table->boolean('can_edit')->default(true);

            $table->unique(['sub_category_id', 'drive_train_group_id'], 'sub_drive_train_group_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drive_train_group_sub_category');
    }
};
