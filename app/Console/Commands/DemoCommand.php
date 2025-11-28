<?php // app/Console/Commands/DemoCommand.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\DemoDataSeeder;

class DemoCommand extends Command
{
    protected $signature = 'db:demo {--count=1 : Number of times to run the seeder}';

    protected $description = 'Runs the DemoDataSeeder to populate the database with development/demo records.';

    public function handle(): int
    {
        $count = (int) $this->option('count');

        $this->info("Running demo seeder {$count} time(s)...");

        // Progress bar
        $bar = $this->output->createProgressBar($count);
        $bar->start();

        for ($i = 0; $i < $count; $i++) {
            Artisan::call('db:seed', [
                '--class' => DemoDataSeeder::class,
                '--force' => true,
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info('All demo runs completed.');

        return Command::SUCCESS;
    }
}
