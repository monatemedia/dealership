<?php
// database/seeders/VehicleCategorySeeder.php
namespace Database\Seeders;

use App\Models\Section;
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
        // Step 1: Seed Sections
        $sections = config('categories.sections');

        foreach ($sections as $sectionName => $sectionData) {
            $section = Section::updateOrCreate(
                ['name' => $sectionName],
                [
                    'singular' => $sectionData['singular'],
                    'long_name' => $sectionData['long_name'],
                    'description' => $sectionData['description'],
                    'image_path' => $sectionData['image_path'],
                    'slug' => Str::slug($sectionName),
                ]
            );

            // Step 2: Seed Sub-Categories for this Section
            if (isset($sectionData['subcategories'])) {
                foreach ($sectionData['subcategories'] as $subName) {
                    $subData = config('categories.subcategories.' . $subName);

                    if ($subData) {
                        $sub = Subcategory::updateOrCreate(
                            ['name' => $subName, 'section_id' => $section->id],
                            [
                                'singular' => $subData['singular'],
                                'long_name' => $subData['long_name'],
                                'description' => $subData['description'],
                                'image_path' => $subData['image_path'],
                                'slug' => Str::slug($subName),
                                'section_id' => $section->id,
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
