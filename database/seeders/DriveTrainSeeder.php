<?php // database/seeders/DrivetrainSeeder.php

namespace Database\Seeders;

use App\Models\Drivetrain;
use App\Models\DrivetrainGroup;
use Illuminate\Database\Seeder;

class DrivetrainSeeder extends Seeder
{
    public function run(): void
    {
        // Note: config key is 'drivetrain' not 'drivetrains'
        $config = config('lookups.drivetrain');

        foreach ($config as $groupName => $drivetrains) {
            $group = DrivetrainGroup::updateOrCreate(
                ['name' => $groupName]
            );

            foreach ($drivetrains as $drivetrainName) {
                Drivetrain::updateOrCreate(
                    [
                        'name' => $drivetrainName,
                        'drivetrain_group_id' => $group->id
                    ]
                );
            }
        }
    }
}
