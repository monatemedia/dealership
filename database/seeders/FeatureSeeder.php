<?php // database/seeders/FeatureSeeder.php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\FeatureGroup;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{

    public function run(): void
    {
        $features = config('features.features');

        foreach ($features as $groupName => $featureList) {
            if ($groupName === 'None') continue;

            // Create or get the feature group
            $group = FeatureGroup::firstOrCreate(['name' => $groupName]);

            // Create features and attach them to this group
            foreach ($featureList as $featureName) {
                // Create feature if it doesn't exist (globally unique by name)
                $feature = Feature::firstOrCreate(['name' => $featureName]);

                // Attach feature to this group (if not already attached)
                $group->features()->syncWithoutDetaching($feature->id);
            }

            $this->command->info("Processed feature group: {$groupName}");
        }

        $this->command->info("✓ All features seeded successfully");
    }
}
