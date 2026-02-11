<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update Manufacturers
        Schema::table('manufacturers', function (Blueprint $table) {
            $table->string('source')->default('original')->after('name');
            $table->timestamp('last_ai_check_at')->nullable();
            $table->integer('ai_retry_count')->default(0);
            // REMOVED: unique('name') - handled in 2025_11_11 migration
        });

        // Update Models
        Schema::table('models', function (Blueprint $table) {
            $table->string('source')->default('original')->after('name');
            $table->timestamp('last_ai_check_at')->nullable();
            $table->integer('ai_retry_count')->default(0);
            // REMOVED: unique(['name', 'manufacturer_id']) - handled in 2025_11_11 migration
        });

        // Create Alias Tables (These are NEW, so they need constraints)
        Schema::create('manufacturer_aliases', function (Blueprint $table) {
            $table->id();
            $table->string('alias')->unique();
            $table->foreignId('manufacturer_id')->constrained()->onDelete('cascade');
        });

        Schema::create('model_aliases', function (Blueprint $table) {
            $table->id();
            $table->string('alias');
            $table->foreignId('model_id')->constrained()->onDelete('cascade');
            $table->unique(['alias', 'model_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('model_aliases');
        Schema::dropIfExists('manufacturer_aliases');

        Schema::table('models', function (Blueprint $table) {
            $table->dropColumn(['source', 'last_ai_check_at', 'ai_retry_count']);
        });

        Schema::table('manufacturers', function (Blueprint $table) {
            $table->dropColumn(['source', 'last_ai_check_at', 'ai_retry_count']);
        });
    }
};
