<?php // database/seeders/DevelopmentSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Summary of DevelopmentSeeder
 * This seeder is responsible for populating the development/testing data
 */
class DevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Run the essential production seeders first
        $this->call(ProductionSeeder::class);

        // 2. Run seeders with fake/demo data
        $this->call(DemoDataSeeder::class);
    }
}
