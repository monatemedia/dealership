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
        $createdFeatures = []; // Track created features

        foreach ($features as $groupName => $featureList) {
            if ($groupName === 'None') continue;

            // Create or get the feature group
            $group = FeatureGroup::firstOrCreate(['name' => $groupName]);

            // Create features within this group
            foreach ($featureList as $featureName) {
                // Check if we already created this feature
                if (isset($createdFeatures[$featureName])) {
                    // Feature exists, just log a warning
                    $this->command->warn("Feature '{$featureName}' already exists in group '{$createdFeatures[$featureName]}', skipping in group '{$groupName}'");
                    continue;
                }

                Feature::firstOrCreate([
                    'name' => $featureName,
                    'feature_group_id' => $group->id
                ]);

                // Track that we created this feature
                $createdFeatures[$featureName] = $groupName;
            }
        }
    }
}
