<?php // app/Console/Commands/StartTypesense.php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StartTypesense extends Command
{
    protected $signature = 'typesense:start
        {--skip-import : Skip data import after starting}
        {--fresh : Flush existing data before importing}
        {--validate-schema : Validate collection schemas match config}';

    protected $description = 'Start Typesense Docker container for local development';

    // Updated to include Vehicle model and proper import order
    protected $modelsToImport = [
        'App\Models\Manufacturer',  // Import first (no dependencies)
        'App\Models\Model',         // Depends on Manufacturer
        'App\Models\Province',      // Import first (no dependencies)
        'App\Models\City',          // Depends on Province
        'App\Models\Vehicle',       // Import last (depends on all above)
    ];

    public function handle()
    {
        $this->info('Starting Typesense...');

        $apiKey = config('scout.typesense.client-settings.api_key')
            ?? env('TYPESENSE_API_KEY');

        if (!$apiKey) {
            $this->error('TYPESENSE_API_KEY not found in .env file');
            return 1;
        }

        // Check if Docker is running
        if (!$this->isDockerRunning()) {
            $this->warn('⚠ Docker Desktop is not running!');
            $this->info('Please start Docker Desktop and this command will automatically continue...');
            $this->waitForDocker();
        }

        // Use container name from env (dealership-typesense)
        $containerName = env('TYPESENSE_HOST', 'dealership-typesense');
        $port = env('TYPESENSE_PORT', '8108');

        // Check if container already exists
        exec("docker ps -a --filter \"name={$containerName}\" --format \"{{.Names}}\"", $output);

        if (in_array($containerName, $output)) {
            $this->info('Typesense container exists. Starting...');
            exec("docker start {$containerName}", $startOutput, $startCode);

            if ($startCode === 0) {
                $this->info("✓ Typesense is running on http://localhost:{$port}");
            } else {
                $this->error('Failed to start Typesense container');
                return 1;
            }
        } else {
            $this->info('Creating new Typesense container...');

            // Updated to version 29.0 as per requirements
            $command = 'docker run -d ' .
                "--name {$containerName} " .
                "-p {$port}:8108 " .
                '-v typesense-data:/data ' .
                'typesense/typesense:29.0 ' .
                '--data-dir /data ' .
                '--api-key=' . escapeshellarg($apiKey) . ' ' .
                '--enable-cors';

            exec($command, $createOutput, $createCode);

            if ($createCode === 0) {
                $this->info("✓ Typesense container created and running on http://localhost:{$port}");

                // Wait for Typesense to be ready
                $this->info('Waiting for Typesense to be ready...');
                sleep(3);
            } else {
                $this->error('Failed to create Typesense container');
                $this->error(implode("\n", $createOutput));
                return 1;
            }
        }

        // Validate schemas if flag is set
        if ($this->option('validate-schema')) {
            $this->newLine();
            $this->info('Validating collection schemas...');
            $this->validateSchemas();
        }

        // Import data unless --skip-import flag is used
        if (!$this->option('skip-import')) {
            $this->newLine();

            // Flush if --fresh flag is used
            if ($this->option('fresh')) {
                $this->info('Flushing existing data from Typesense...');
                $this->flushData();
                $this->newLine();
            }

            $this->info('Importing data to Typesense...');
            $this->importData();
        }

        $this->newLine();
        $this->info('✨ Typesense is ready for InstantSearch!');
        $this->info("📍 API Endpoint: http://localhost:{$port}");
        $this->info("🔍 Test search at: /vehicles/search");

        return 0;
    }

    protected function flushData(): void
    {
        foreach ($this->modelsToImport as $model) {
            $modelName = class_basename($model);
            $this->info("Flushing {$modelName}...");

            $exitCode = $this->call('scout:flush', ['model' => $model]);

            if ($exitCode === 0) {
                $this->info("✓ {$modelName} flushed successfully");
            } else {
                $this->warn("⚠ Failed to flush {$modelName}");
            }
        }
    }

    protected function importData(): void
    {
        // Use our custom import command
        $exitCode = $this->call('typesense:import');

        if ($exitCode === 0) {
            $this->newLine();
            $this->info('✓ All data imported successfully!');
        } else {
            $this->warn('⚠ Import failed');
        }
    }

    protected function validateSchemas(): void
    {
        try {
            $client = app(\Typesense\Client::class);

            foreach ($this->modelsToImport as $modelClass) {
                $model = new $modelClass;
                $collectionName = $model->searchableAs();

                try {
                    $collection = $client->collections[$collectionName]->retrieve();
                    $this->info("✓ Collection '{$collectionName}' exists");

                    // Check if schema matches config
                    $configSchema = config("scout.typesense.model-settings.{$modelClass}.collection-schema");
                    if ($configSchema) {
                        $expectedFields = count($configSchema['fields']);
                        $actualFields = count($collection['fields']);

                        if ($expectedFields === $actualFields) {
                            $this->info("  ✓ Schema matches config ({$actualFields} fields)");
                        } else {
                            $this->warn("  ⚠ Schema mismatch: Expected {$expectedFields} fields, got {$actualFields}");
                            $this->warn("  Run with --fresh to recreate collections");
                        }
                    }
                } catch (\Typesense\Exceptions\ObjectNotFound $e) {
                    $this->warn("⚠ Collection '{$collectionName}' does not exist - will be created on import");
                }
            }
        } catch (\Exception $e) {
            $this->error('Failed to validate schemas: ' . $e->getMessage());
        }
    }

    protected function isDockerRunning(): bool
    {
        exec('docker info 2>&1', $output, $returnCode);
        return $returnCode === 0;
    }

    protected function waitForDocker(): void
    {
        $maxAttempts = 60;
        $attempt = 0;

        while ($attempt < $maxAttempts) {
            if ($this->isDockerRunning()) {
                $this->info('✓ Docker Desktop is now running!');
                sleep(2);
                return;
            }

            sleep(1);
            $attempt++;

            if ($attempt % 5 === 0) {
                $this->line("Still waiting... ({$attempt}s)");
            }
        }

        $this->error('Timeout: Docker Desktop did not start within 60 seconds');
        exit(1);
    }
}
