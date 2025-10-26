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
        Schema::create('color_group_subcategory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subcategory_id')->constrained()->onDelete('cascade');
            $table->foreignId('color_group_id')->constrained()->onDelete('cascade');
            $table->string('default_color')->nullable();
            $table->boolean('can_edit')->default(true);

            $table->unique(['subcategory_id', 'color_group_id'], 'sub_color_group_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('color_group_subcategory');
    }
};
