<?php // app/Console/Commands/DemoCommand.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\DemoDataSeeder;

class DemoCommand extends Command
{
    protected $signature = 'db:demo {--count=1 : Number of times to run the seeder}';
    protected $description = 'Runs the DemoDataSeeder to populate the database with development/demo records.';

    /**
     * The handle method to execute the console command.
     */
    public function handle(): int
    {
        // 💡 NEW: Check for the presence of the Faker library (which is a dev dependency)
        if (!class_exists(\Faker\Factory::class)) {
            $this->error("\n❌ Faker Library Missing (Dev Dependencies)");

            // Explain the problem
            $this->warn("It appears the 'Faker\\Factory' class is missing. This usually means your application was built without Composer development dependencies.");
            $this->line("The `db:demo` command requires dev dependencies (including Faker) to populate the database.");

            // Suggest the solution
            $this->newLine();
            $this->info("To fix this, rebuild your Docker image with dev dependencies:");
            $this->comment("docker compose build --build-arg INSTALL_DEV_DEPENDENCIES=true dealership-web");

            // Suggest a command to confirm
            $this->newLine();
            $this->line("You can confirm the status of your current build by running this command inside the container:");
            $this->comment("docker exec dealership-web composer show fakerphp/faker");
            $this->line("If the command fails, the dependency is definitely missing.");

            return Command::FAILURE;
        }

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
