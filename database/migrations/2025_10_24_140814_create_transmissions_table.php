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
        Schema::create('transmissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100); // Increased length for long names like 'Continuously Variable Transmission (CVT)'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transmissions');
    }
};
