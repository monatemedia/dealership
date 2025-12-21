<?php // database/seeders/CategoryDrivetrainSeeder.php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\DrivetrainGroup;
use Illuminate\Database\Seeder;

class CategoryDrivetrainSeeder extends Seeder
{
    public function run(): void
    {
        $config = config('categories.drivetrain_by_category');

        foreach ($config as $categoryName => $drivetrainConfig) {
            $category = Category::where('name', $categoryName)->first();
            if (!$category) {
                continue;
            }

            $groups = $drivetrainConfig['groups'] ?? [];
            $default = $drivetrainConfig['default'] ?? null;
            $canEdit = $drivetrainConfig['can_edit'] ?? true;

            foreach ($groups as $groupName) {
                $group = DrivetrainGroup::where('name', $groupName)->first();
                if (!$group) {
                    continue;
                }

                $category->drivetrainGroups()->syncWithoutDetaching([
                    $group->id => [
                        'default_drivetrain' => $default,
                        'can_edit' => $canEdit
                    ]
                ]);
            }
        }
    }
}
