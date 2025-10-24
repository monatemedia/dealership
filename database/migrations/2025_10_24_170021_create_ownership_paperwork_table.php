<?php // database/migrations/2025_10_24_170021_create_ownership_paperwork_table.php

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
        Schema::create('ownership_paperwork', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('category'); // 'Ownership Checklist', 'Supporting Documents', etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ownership_paperwork');
    }
};
