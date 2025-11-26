<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\DemoDataSeeder;

/**
 * Artisan command to run the specific DemoDataSeeder.
 * Signature: db:demo
 */
class DemoCommand extends Command
{
    /**
     * The name and signature of the console command.
     * This registers the command as `php artisan db:demo`.
     * @var string
     */
    protected $signature = 'db:demo';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Runs the DemoDataSeeder to populate the database with development/demo records.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting demo database seeding...');

        // Call the built-in 'db:seed' command, specifying the DemoDataSeeder class
        Artisan::call('db:seed', [
            '--class' => DemoDataSeeder::class,
            // The following flag prevents prompting for confirmation in production,
            // though custom commands are usually safe for dev environments.
            '--force' => true
        ], $this->output);

        $this->info('Demo seeding finished.');
        return Command::SUCCESS;
    }
}
