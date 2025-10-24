<?php // database/migrations/2025_10_24_145622_create_drivetrain_group_sub_category_table.php

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
        Schema::create('drivetrain_group_sub_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('drivetrain_group_id')->constrained()->onDelete('cascade');
            $table->string('default_drivetrain')->nullable(); // e.g., 'Rear-Wheel Drive (RWD)'
            $table->boolean('can_edit')->default(true);

            $table->unique(['sub_category_id', 'drivetrain_group_id'], 'sub_drivetrain_group_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivetrain_group_sub_category');
    }
};
