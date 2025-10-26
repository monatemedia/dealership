<?php
// database/seeders/InteriorSeeder.php

namespace Database\Seeders;

use App\Models\Interior;
use App\Models\InteriorGroup;
use Illuminate\Database\Seeder;

class InteriorSeeder extends Seeder
{
    public function run(): void
    {
        $interiorsConfig = config('lookups.interior');

        foreach ($interiorsConfig as $groupName => $interiorData) {
            // Create the interior group
            $group = InteriorGroup::updateOrCreate(
                ['name' => $groupName]
            );

            // Handle the nested 'Colours' array structure
            if (isset($interiorData['Colours']) && is_array($interiorData['Colours'])) {
                $interiorOptions = $interiorData['Colours'];
            } else {
                // Fallback for simple arrays like 'None'
                $interiorOptions = is_array($interiorData) ? $interiorData : [$interiorData];
            }

            // Create interiors for this group
            foreach ($interiorOptions as $interiorName) {
                Interior::updateOrCreate(
                    [
                        'name' => $interiorName,
                        'interior_group_id' => $group->id
                    ]
                );
            }
        }
    }
}
