<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StartTypesense extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'typesense:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start Typesense Docker container for local development';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Typesense...');

        $apiKey = config('scout.typesense.api_key') ?? env('TYPESENSE_API_KEY');

        if (!$apiKey) {
            $this->error('TYPESENSE_API_KEY not found in .env file');
            return 1;
        }

        // Check if Docker is running
        if (!$this->isDockerRunning()) {
            $this->warn('⚠ Docker Desktop is not running!');
            $this->info('Please start Docker Desktop and this command will automatically continue...');

            // Wait for Docker to be available
            $this->waitForDocker();
        }

        // Check if container already exists
        exec('docker ps -a --filter "name=typesense-local" --format "{{.Names}}"', $output);

        if (in_array('typesense-local', $output)) {
            $this->info('Typesense container exists. Starting...');
            exec('docker start typesense-local', $startOutput, $startCode);

            if ($startCode === 0) {
                $this->info('✓ Typesense is running on http://localhost:8108');
            } else {
                $this->error('Failed to start Typesense container');
            }
        } else {
            $this->info('Creating new Typesense container...');

            $command = 'docker run -d ' .
                '--name typesense-local ' .
                '-p 8108:8108 ' .
                '-v typesense-data:/data ' .
                'typesense/typesense:27.1 ' .
                '--data-dir /data ' .
                '--api-key=' . escapeshellarg($apiKey) . ' ' .
                '--enable-cors';

            exec($command, $createOutput, $createCode);

            if ($createCode === 0) {
                $this->info('✓ Typesense container created and running on http://localhost:8108');
            } else {
                $this->error('Failed to create Typesense container');
            }
        }

        return 0;
    }

    protected function isDockerRunning(): bool
    {
        exec('docker info 2>&1', $output, $returnCode);
        return $returnCode === 0;
    }

    protected function waitForDocker(): void
    {
        $maxAttempts = 60; // Wait up to 60 seconds
        $attempt = 0;

        while ($attempt < $maxAttempts) {
            if ($this->isDockerRunning()) {
                $this->info('✓ Docker Desktop is now running!');
                sleep(2); // Give Docker a moment to fully initialize
                return;
            }

            sleep(1);
            $attempt++;

            // Show a progress indicator every 5 seconds
            if ($attempt % 5 === 0) {
                $this->line("Still waiting... ({$attempt}s)");
            }
        }

        $this->error('Timeout: Docker Desktop did not start within 60 seconds');
        exit(1);
    }
}
