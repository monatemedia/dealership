<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DestroyTypesense extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'typesense:destroy {--force : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Destroy Typesense Docker container and remove all data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if Docker is running
        exec('docker info 2>&1', $output, $returnCode);
        if ($returnCode !== 0) {
            $this->error('⚠ Docker Desktop is not running!');
            return 1;
        }

        // Check if container exists
        exec('docker ps -a --filter "name=typesense-local" --format "{{.Names}}"', $output);

        if (!in_array('typesense-local', $output)) {
            $this->info('Typesense container does not exist. Nothing to destroy.');
            return 0;
        }

        // Confirm destruction unless --force flag is used
        if (!$this->option('force')) {
            if (!$this->confirm('This will permanently delete the Typesense container and all its data. Continue?', false)) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->info('Destroying Typesense container...');

        // Stop the container if it's running
        exec('docker stop typesense-local 2>&1', $stopOutput, $stopCode);

        // Remove the container
        exec('docker rm typesense-local 2>&1', $rmOutput, $rmCode);

        if ($rmCode === 0) {
            $this->info('✓ Typesense container destroyed');

            // Ask if they want to remove the volume too
            if ($this->confirm('Do you also want to remove the data volume? (This will delete all indexed data)', false)) {
                exec('docker volume rm typesense-data 2>&1', $volOutput, $volCode);

                if ($volCode === 0) {
                    $this->info('✓ Typesense data volume removed');
                } else {
                    $this->warn('⚠ Could not remove data volume (it may be in use or not exist)');
                }
            }
        } else {
            $this->error('Failed to destroy Typesense container');
            return 1;
        }

        return 0;
    }
}
