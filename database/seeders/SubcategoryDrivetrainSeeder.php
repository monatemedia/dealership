<?php // database/seeders/SubcategoryDrivetrainSeeder.php

namespace Database\Seeders;

use App\Models\Subcategory;
use App\Models\DrivetrainGroup;
use Illuminate\Database\Seeder;

class SubcategoryDrivetrainSeeder extends Seeder
{
    public function run(): void
    {
        $config = config('categories.drivetrain_by_category');

        foreach ($config as $subcategoryName => $drivetrainConfig) {
            $subcategory = Subcategory::where('name', $subcategoryName)->first();
            if (!$subcategory) {
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

                $subcategory->drivetrainGroups()->syncWithoutDetaching([
                    $group->id => [
                        'default_drivetrain' => $default,
                        'can_edit' => $canEdit
                    ]
                ]);
            }
        }
    }
}
