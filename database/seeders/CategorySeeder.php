<?php
// database/seeders/VehicleCategorySeeder.php
namespace Database\Seeders;

use App\Models\MainCategory;
use App\Models\Subcategory;
use App\Models\VehicleType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Step 1: Seed Main Categories
        $mainCategories = config('categories.main_categories');

        foreach ($mainCategories as $mainName => $mainData) {
            $main = MainCategory::updateOrCreate(
                ['name' => $mainName],
                [
                    'singular' => $mainData['singular'],
                    'long_name' => $mainData['long_name'],
                    'description' => $mainData['description'],
                    'image_path' => $mainData['image_path'],
                    'slug' => Str::slug($mainName),
                ]
            );

            // Step 2: Seed Sub-Categories for this Main Category
            if (isset($mainData['subcategories'])) {
                foreach ($mainData['subcategories'] as $subName) {
                    $subData = config('categories.subcategories.' . $subName);

                    if ($subData) {
                        $sub = Subcategory::updateOrCreate(
                            ['name' => $subName, 'main_category_id' => $main->id],
                            [
                                'singular' => $subData['singular'],
                                'long_name' => $subData['long_name'],
                                'description' => $subData['description'],
                                'image_path' => $subData['image_path'],
                                'slug' => Str::slug($subName),
                                'main_category_id' => $main->id,
                            ]
                        );

                        // Step 3: Seed Vehicle Types for this Subcategory
                        $vehicleTypes = config('categories.vehicle_types.' . $subName, []);

                        foreach ($vehicleTypes as $typeData) {
                            VehicleType::updateOrCreate(
                                ['name' => $typeData['name'], 'subcategory_id' => $sub->id],
                                [
                                    'name' => $typeData['name'],
                                    'long_name' => $typeData['long_name'],
                                    'description' => $typeData['description'],
                                    'image_path' => $typeData['image_path'],
                                    'slug' => Str::slug($typeData['name']),
                                    'subcategory_id' => $sub->id,
                                ]
                            );
                        }
                    }
                }
            }
        }
    }
}
