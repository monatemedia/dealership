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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreignId('exterior_condition_id')
                ->nullable()
                ->after('accident_history_id')
                ->constrained('conditions')
                ->nullOnDelete();

            $table->foreignId('interior_condition_id')
                ->nullable()
                ->after('exterior_condition_id')
                ->constrained('conditions')
                ->nullOnDelete();

            $table->foreignId('mechanical_condition_id')
                ->nullable()
                ->after('interior_condition_id')
                ->constrained('conditions')
                ->nullOnDelete();

            $table->foreignId('service_history_id')
                ->nullable()
                ->after('mechanical_condition_id')
                ->constrained('service_histories')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['exterior_condition_id']);
            $table->dropForeign(['interior_condition_id']);
            $table->dropForeign(['mechanical_condition_id']);
            $table->dropForeign(['service_history_id']);

            $table->dropColumn([
                'exterior_condition_id',
                'interior_condition_id',
                'mechanical_condition_id',
                'service_history_id'
            ]);
        });
    }
};
