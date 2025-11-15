<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StopTypesense extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'typesense:stop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stop Typesense Docker container';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Stopping Typesense...');
        exec('docker stop typesense-local', $output, $code);

        if ($code === 0) {
            $this->info('✓ Typesense stopped');
        } else {
            $this->error('Failed to stop Typesense (may not be running)');
        }

        return 0;
    }
}
