<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicle_types', function (Blueprint $table) {
            $table->string('long_name')->after('name');
            $table->string('description')->after('long_name');
            $table->string('image_path')->after('description');
            $table->string('slug')->unique()->after('image_path');
        });
    }

    public function down(): void
    {
        Schema::table('vehicle_types', function (Blueprint $table) {
            $table->dropColumn(['long_name', 'description', 'image_path', 'slug']);
        });
    }
};
