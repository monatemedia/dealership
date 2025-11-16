<?php
// app/Console/Commands/TypesenseReset.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Typesense\Client as TypesenseClient;

class TypesenseReset extends Command
{
    protected $signature = 'typesense:reset
        {--force : Skip confirmation prompt}';

    protected $description = 'Delete all Typesense collections and reimport data';

    protected $modelsToImport = [
        'App\Models\Manufacturer',
        'App\Models\Model',
        'App\Models\Province',
        'App\Models\City',
        'App\Models\Vehicle',
    ];

    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('This will DELETE all Typesense collections and reimport data. Continue?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->warn('🗑️  Deleting all collections...');

        try {
            $client = app(TypesenseClient::class);
            $collections = $client->collections->retrieve();

            foreach ($collections as $collection) {
                $name = $collection['name'];
                $this->info("  Deleting {$name}...");

                try {
                    $client->collections[$name]->delete();
                    $this->info("  ✓ {$name} deleted");
                } catch (\Exception $e) {
                    $this->warn("  ⚠ Failed to delete {$name}: " . $e->getMessage());
                }
            }

        } catch (\Exception $e) {
            $this->error('Failed to delete collections: ' . $e->getMessage());
            return 1;
        }

        $this->newLine();
        $this->info('📥 Reimporting all data...');

        $exitCode = $this->call('typesense:import');

        if ($exitCode === 0) {
            $this->newLine();
            $this->info('✨ Reset complete! All data has been reimported.');
        } else {
            $this->error('Import failed. Check the errors above.');
            return 1;
        }

        return 0;
    }
}
