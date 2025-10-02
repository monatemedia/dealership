<?php // database/migrations/2024_12_23_234000_create_cities_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45);
            $table->foreignId('province_id')->constrained('provinces');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            // $table->timestamps();

            // Optional: Add index for faster distance queries
            $table->index(['latitude', 'longitude']);
        });
    }
};
