<?php
// app/Console/Commands/ContextCommand.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Console\Commands\ExtractDependenciesCommand;

class ContextCommand extends Command
{
    protected $signature = 'blade:context {view_path} {--type=all}';
    protected $description = 'Extract CSS, JS, or both for a Blade view.';

    public function handle()
    {
        $this->output->setDecorated(false);

        $type = strtolower($this->option('type'));
        $viewPathInput = preg_replace('/^resources\/views\//', '', $this->argument('view_path'));

        $bladePath = base_path("resources/views/{$viewPathInput}");
        if (!File::exists($bladePath)) {
            fwrite(STDOUT, "ERROR: Blade file not found\n");
            return Command::FAILURE;
        }

        $blade = File::get($bladePath);
        $extractor = new ExtractDependenciesCommand();

        if ($type === 'css' || $type === 'all') {
            fwrite(STDOUT, "\n### CSS ###\n");
            fwrite(STDOUT, $extractor->extractCssRules(
                $extractor->extractCssClasses($blade)
            ) . "\n");
        }

        if ($type === 'js' || $type === 'all') {
            fwrite(STDOUT, "\n### JS ###\n");
            fwrite(STDOUT, $extractor->extractJsLogic(
                $extractor->extractJsFunctions($blade)
            ) . "\n");
        }

        return Command::SUCCESS;
    }
}
