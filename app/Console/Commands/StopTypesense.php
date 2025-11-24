<?php
// app/Console/Commands/StopTypesense.php
namespace App\Console\Commands;

use Illuminate\Console\Command;

class StopTypesense extends Command
{
    protected $signature = 'typesense:stop';
    protected $description = 'Stop Typesense Docker container';

    public function handle()
    {
        $containerName = env('TYPESENSE_HOST', 'dealership-typesense');

        $this->info('🛑 Stopping Typesense...');
        exec("docker stop {$containerName} 2>&1", $output, $code);

        if ($code === 0) {
            $this->info('✅ Typesense stopped');
        } else {
            $this->warn('⚠️  Container not running or already stopped');
        }

        return 0;
    }
}
