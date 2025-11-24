<?php
// app/Console/Commands/StartTypesense.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Typesense\Client as TypesenseClient;

class StartTypesense extends Command
{
    protected $signature = 'typesense:start';
    protected $description = 'Start Typesense Docker container';

    public function handle()
    {
        $this->info('🚀 Starting Typesense...');
        $this->newLine();

        // Check if Docker is running
        if (!$this->isDockerRunning()) {
            $this->warn('⚠️  Docker Desktop is not running!');
            $this->info('Please start Docker Desktop and try again...');
            return 1;
        }

        $apiKey = config('scout.typesense.client-settings.api_key') ?? env('TYPESENSE_API_KEY');
        if (!$apiKey) {
            $this->error('❌ TYPESENSE_API_KEY not found in .env file');
            return 1;
        }

        $containerName = env('TYPESENSE_HOST', 'dealership-typesense');
        $port = env('TYPESENSE_PORT', '8108');

        // Check if container already exists
        exec("docker ps -a --filter \"name={$containerName}\" --format \"{{.Names}}\"", $output);

        if (in_array($containerName, $output)) {
            // Container exists, just start it
            $this->info('📦 Container exists, starting...');
            exec("docker start {$containerName}", $startOutput, $startCode);

            if ($startCode !== 0) {
                $this->error('❌ Failed to start Typesense container');
                return 1;
            }
        } else {
            // Create new container
            $this->info('📦 Creating new Typesense container...');
            $command = 'docker run -d ' .
                "--name {$containerName} " .
                "-p {$port}:8108 " .
                '-v typesense-data:/data ' .
                'typesense/typesense:29.0 ' .
                '--data-dir /data ' .
                '--api-key=' . escapeshellarg($apiKey) . ' ' .
                '--enable-cors';

            exec($command, $createOutput, $createCode);

            if ($createCode !== 0) {
                $this->error('❌ Failed to create Typesense container');
                $this->error(implode("\n", $createOutput));
                return 1;
            }
        }

        // Wait for Typesense API to be ready
        $this->info('⏳ Waiting for Typesense API...');
        if (!$this->waitForTypesense()) {
            $this->error('❌ Typesense failed to start within 30 seconds');
            return 1;
        }

        $this->newLine();
        $this->info('✅ Typesense is running!');
        $this->line("   📍 API Endpoint: http://localhost:{$port}");
        $this->newLine();
        $this->info('💡 Next steps:');
        $this->line('   php artisan typesense:create-collections --force --import');
        $this->line('   php artisan typesense:status');

        return 0;
    }

    protected function isDockerRunning(): bool
    {
        exec('docker info 2>&1', $output, $returnCode);
        return $returnCode === 0;
    }

    protected function waitForTypesense(): bool
    {
        $config = config('scout.typesense.client-settings');
        $maxAttempts = 30;
        $attempt = 0;

        while ($attempt < $maxAttempts) {
            try {
                $client = new TypesenseClient($config);
                $health = $client->health->retrieve();

                if ($health['ok'] === true) {
                    $this->info('   ✓ API is healthy');
                    return true;
                }
            } catch (\Exception $e) {
                // Ignore errors while waiting
            }

            sleep(1);
            $attempt++;

            if ($attempt % 5 === 0) {
                $this->line("   Still waiting... ({$attempt}s)");
            }
        }

        return false;
    }
}
