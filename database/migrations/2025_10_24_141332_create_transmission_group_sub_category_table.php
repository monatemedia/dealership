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
        Schema::create('transmission_group_sub_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('transmission_group_id')->constrained()->onDelete('cascade');
            $table->string('default_transmission')->nullable(); // e.g., '5-Speed Manual'
            $table->boolean('can_edit')->default(true);

            $table->unique(['sub_category_id', 'transmission_group_id'], 'sub_transmission_group_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transmission_group_sub_category');
    }
};
