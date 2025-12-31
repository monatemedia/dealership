<?php // database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Summary of DatabaseSeeder
 * This is the main database seeder that calls other
 * seeders to populate the database with initial data.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * By default, this runs the production seeder.
     */
    public function run(): void
    {
        // The production seeder is responsible for populating the core application data
        $this->call(ProductionSeeder::class);
    }
}
