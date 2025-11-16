<?php
// app/Console/Commands/TypesenseStatus.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Typesense\Client as TypesenseClient;

class TypesenseStatus extends Command
{
    protected $signature = 'typesense:status';
    protected $description = 'Check Typesense server status and collection information';

    public function handle()
    {
        $this->info('🔍 Checking Typesense status...');
        $this->newLine();

        // Check Docker container
        $containerName = env('TYPESENSE_HOST', 'dealership-typesense');
        exec("docker ps --filter \"name={$containerName}\" --format \"{{.Names}}\"", $output);

        if (in_array($containerName, $output)) {
            $this->info("✅ Docker container '{$containerName}' is running");
        } else {
            $this->error("❌ Docker container '{$containerName}' is not running");
            $this->warn("Run: php artisan typesense:start");
            return 1;
        }

        // Check Typesense API connectivity
        try {
            $client = app(TypesenseClient::class);
            $health = $client->health->retrieve();

            if ($health['ok'] === true) {
                $this->info('✅ Typesense API is healthy');
            }
        } catch (\Exception $e) {
            $this->error('❌ Cannot connect to Typesense API');
            $this->error($e->getMessage());
            return 1;
        }

        $this->newLine();
        $this->info('📚 Collections:');
        $this->newLine();

        // List all collections
        try {
            $collections = $client->collections->retrieve();

            if (empty($collections)) {
                $this->warn('No collections found. Run: php artisan typesense:import');
                return 0;
            }

            foreach ($collections as $collection) {
                $name = $collection['name'];
                $numDocuments = $collection['num_documents'] ?? 0;

                $this->line("  📦 <fg=cyan>{$name}</>: {$numDocuments} documents");

                // Show schema field count
                if (isset($collection['fields'])) {
                    $fieldCount = count($collection['fields']);
                    $this->line("     Fields: {$fieldCount}");
                }
            }

            $this->newLine();
            $this->info('✨ All systems operational!');

        } catch (\Exception $e) {
            $this->error('Failed to retrieve collections');
            $this->error($e->getMessage());
            return 1;
        }

        return 0;
    }
}
