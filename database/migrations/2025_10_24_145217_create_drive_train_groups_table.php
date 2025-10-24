<?php // database/migrations/2025_10_24_145217_create_drivetrain_groups_table.php

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
        Schema::create('drivetrain_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45); // e.g., 'Standard', 'Advanced'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivetrain_groups');
    }
};
