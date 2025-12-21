<?php // database/seeders/CategoryFeatureSeeder.php

namespace Database\Seeders;

use App\Models\FeatureGroup;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryFeatureSeeder extends Seeder
{
    public function run(): void
    {
        $config = config('categories.features_by_category');

        foreach ($config as $categoryName => $settings) {
            $category = Category::where('name', $categoryName)->first();

            if (!$category) {
                $this->command->warn("Category not found: {$categoryName}");
                continue;
            }

            foreach ($settings['groups'] as $groupName) {
                if ($groupName === 'None') continue;

                $group = FeatureGroup::where('name', $groupName)->first();

                if (!$group) {
                    $this->command->warn("Feature group not found: {$groupName}");
                    continue;
                }

                $category->featureGroups()->syncWithoutDetaching([
                    $group->id => [
                        'can_edit' => $settings['can_edit']
                    ]
                ]);
            }

            $this->command->info("Linked features for: {$categoryName}");
        }
    }
}
