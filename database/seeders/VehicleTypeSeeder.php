<?php // database/seeders/VehicleTypeSeeder.php

namespace Database\Seeders;

use App\Models\VehicleType;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VehicleTypeSeeder extends Seeder
{
    public function run(): void
    {
        $vehicleTypesConfig = config('categories.vehicle_types_by_subcategory');

        foreach ($vehicleTypesConfig as $subCategoryName => $types) {
            $subCategory = Subcategory::where('name', $subCategoryName)->first();

            if (!$subCategory) continue;

            foreach ($types as $typeData) {
                $slug = Str::slug($typeData['name'] . '-' . $subCategory->name);

                VehicleType::updateOrCreate(
                    [
                        'name' => $typeData['name'],
                        'subcategory_id' => $subCategory->id,
                    ],
                    [
                        'long_name' => $typeData['long_name'] ?? $typeData['name'],
                        'description' => $typeData['description'] ?? null,
                        'image_path' => $typeData['image_path'] ?? null,
                        'slug' => $slug,
                    ]
                );
            }
        }
    }
}
