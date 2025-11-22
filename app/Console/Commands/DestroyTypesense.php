<?php
// app/Console/Commands/DestroyTypesense.php
namespace App\Console\Commands;

use Illuminate\Console\Command;

class DestroyTypesense extends Command
{
    protected $signature = 'typesense:destroy
        {--force : Skip confirmation}
        {--keep-volume : Keep the data volume}';

    protected $description = 'Destroy Typesense container and optionally remove data volumes';

    public function handle()
    {
        $containerName = env('TYPESENSE_HOST', 'dealership-typesense');

        // Check if container exists
        exec("docker ps -a --filter \"name={$containerName}\" --format \"{{.Names}}\"", $output);

        if (!in_array($containerName, $output)) {
            $this->info('ℹ️  Typesense container does not exist');
            return 0;
        }

        // Confirm destruction unless --force
        if (!$this->option('force')) {
            if (!$this->confirm('⚠️  This will permanently delete the Typesense container. Continue?', false)) {
                $this->info('Operation cancelled');
                return 0;
            }
        }

        $this->info('🗑️  Destroying Typesense container...');

        // Stop container
        exec("docker stop {$containerName} 2>&1");

        // Remove container
        exec("docker rm {$containerName} 2>&1", $rmOutput, $rmCode);

        if ($rmCode === 0) {
            $this->info('✅ Container destroyed');

            // Handle volume removal
            if (!$this->option('keep-volume')) {
                if ($this->option('force') || $this->confirm('Also remove the data volume? (deletes all indexed data)', false)) {
                    exec('docker volume rm typesense-data 2>&1', $volOutput, $volCode);

                    if ($volCode === 0) {
                        $this->info('✅ Data volume removed');
                    } else {
                        $this->warn('⚠️  Could not remove volume (may be in use)');
                    }
                }
            } else {
                $this->info('ℹ️  Data volume kept');
            }
        } else {
            $this->error('❌ Failed to destroy container');
            return 1;
        }

        return 0;
    }
}
