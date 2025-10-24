<?php // database/migrations/2025_10_24_145112_create_drive_trains_table.php

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
        Schema::create('drive_trains', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100); // Increased length for names like 'Front-Wheel Drive (FWD)'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drive_trains');
    }
};
