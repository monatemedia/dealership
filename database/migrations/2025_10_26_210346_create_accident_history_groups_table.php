<?php

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
        Schema::create('accident_history_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45); // e.g., 'None', 'Minor', 'Moderate', 'Severe'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accident_history_groups');
    }
};
