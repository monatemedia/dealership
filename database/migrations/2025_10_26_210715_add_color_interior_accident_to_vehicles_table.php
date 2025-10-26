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
            $table->foreignId('color_id')->nullable()->after('fuel_type_id')->constrained();
            $table->foreignId('interior_id')->nullable()->after('color_id')->constrained();
            $table->foreignId('accident_history_id')->nullable()->after('interior_id')->constrained('accident_histories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['color_id']);
            $table->dropColumn('color_id');
            $table->dropForeign(['interior_id']);
            $table->dropColumn('interior_id');
            $table->dropForeign(['accident_history_id']);
            $table->dropColumn('accident_history_id');
        });
    }
};
