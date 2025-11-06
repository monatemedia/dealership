<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class StorageLinkSeeder extends Seeder
{
    /**
     * Run the seeder to ensure storage symlink exists.
     */
    public function run(): void
    {
        $link = public_path('storage');
        $target = storage_path('app/public');

        // Check if symlink already exists
        if (File::exists($link)) {
            $this->command->info('✓ Storage symlink already exists.');
            return;
        }

        // Create the symlink
        try {
            $this->command->info('Creating storage symlink...');
            Artisan::call('storage:link');
            $this->command->info('✓ Storage symlink created successfully!');
        } catch (\Exception $e) {
            $this->command->error('✗ Failed to create storage symlink: ' . $e->getMessage());
            $this->command->warn('Please run: php artisan storage:link manually');
        }
    }
}
