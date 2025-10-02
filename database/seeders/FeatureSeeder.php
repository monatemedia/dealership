<?php // database/seeders/FeatureSeeder.php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        $features = config('features');

        foreach ($features as $name) {
            Feature::firstOrCreate(['name' => $name]);
        }
    }
}
