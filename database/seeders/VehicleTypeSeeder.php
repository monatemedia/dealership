<?php // database/seeders/VehicleTypeSeeder.php

namespace Database\Seeders;

use App\Models\VehicleType;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VehicleTypeSeeder extends Seeder
{
    public function run(): void
    {
        $vehicleTypesConfig = config('categories.vehicle_types_by_category');

        foreach ($vehicleTypesConfig as $categoryName => $types) {
            $category = Category::where('name', $categoryName)->first();

            if (!$category) continue;

            foreach ($types as $typeData) {
                $slug = Str::slug($typeData['name'] . '-' . $category->name);

                VehicleType::updateOrCreate(
                    [
                        'name' => $typeData['name'],
                        'category_id' => $category->id,
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
