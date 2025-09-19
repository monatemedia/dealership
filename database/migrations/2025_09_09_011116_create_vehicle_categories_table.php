<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicle_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45)->unique();
            $table->string('singular', 30);
            $table->string('long_name');
            $table->string('description');
            $table->string('image_path');
            $table->string('slug')->unique();
        });

        // Add category_id to vehicles table
        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreignId('vehicle_category_id')
                ->after('id')
                ->constrained('vehicle_categories');
        });
    }
};
