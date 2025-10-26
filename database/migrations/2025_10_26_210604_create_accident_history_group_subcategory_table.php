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
        Schema::create('accident_history_group_subcategory', function (Blueprint $table) {
            $table->id();

            // Short, explicit foreign key names to avoid MySQL's 64-char limit
            $table->foreignId('subcategory_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('accident_history_group_id')
                ->constrained(table: 'accident_history_groups', indexName: 'ahg_subcat_fk')
                ->onDelete('cascade');

            $table->string('default_accident_history')->nullable();
            $table->boolean('can_edit')->default(true);

            $table->unique(['subcategory_id', 'accident_history_group_id'], 'sub_accident_group_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accident_history_group_subcategory');
    }
};
