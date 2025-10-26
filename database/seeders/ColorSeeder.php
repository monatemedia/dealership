<?php
// database/seeders/ColorSeeder.php

namespace Database\Seeders;

use App\Models\Color;
use App\Models\ColorGroup;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colorsConfig = config('lookups.color');

        foreach ($colorsConfig as $groupName => $colors) {
            // Create the color group
            $group = ColorGroup::updateOrCreate(
                ['name' => $groupName]
            );

            // Create colors for this group
            foreach ($colors as $colorName) {
                Color::updateOrCreate(
                    [
                        'name' => $colorName,
                        'color_group_id' => $group->id
                    ]
                );
            }
        }
    }
}
