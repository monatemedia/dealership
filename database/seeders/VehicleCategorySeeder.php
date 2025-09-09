<?php

// database/seeders/VehicleCategorySeeder.php
namespace Database\Seeders;

use App\Models\VehicleCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VehicleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = config('vehicles.categories');

        foreach ($categories as $name => $data) {
            VehicleCategory::updateOrCreate(
                ['name' => $name],
                [
                    'slug' => Str::slug($name),
                    // [
                    //     'vehicle_types' => $data['vehicle_types'],
                    //     'fuel_types' => $data['fuel_types'],
                    // ]
                ]
            );
        }
    }
}
