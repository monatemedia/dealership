<?php // database/migrations/2025_10_14_112837_create_sections_table.php

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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45)->unique();
            $table->string('singular', 30);
            $table->string('long_name');
            $table->string('description');
            $table->string('image_path');
            $table->string('slug')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    // public function down(): void
    // {
    //     Schema::dropIfExists('sections');
    // }
};
