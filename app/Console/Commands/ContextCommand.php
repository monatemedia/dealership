<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

// We must import the comprehensive command to reuse its core logic functions
use App\Console\Commands\ExtractDependenciesCommand;

class ContextCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blade:context {view_path} {--type= : Specify the context type: css, js, or all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extracts only the specified context (css/js) for a Blade view.';

    // Default asset paths (relative to base_path)
    protected $jsPath = 'resources/js/app.js';
    protected $cssPath = 'resources/css/app.css';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $viewPathInput = $this->argument('view_path');
        $type = strtolower($this->option('type') ?? 'all');

        // --- FIX START: Normalize the input path by stripping known prefixes ---
        $viewPathInput = preg_replace('/^resources\/views\//', '', $viewPathInput);
        $viewPathInput = preg_replace('/^views\//', '', $viewPathInput);
        // --- FIX END ---

        $bladePath = base_path("resources/views/{$viewPathInput}");

        if (!File::exists($bladePath)) {
            $this->error("Blade file not found at: {$bladePath}");
            return Command::FAILURE;
        }

        $bladeContent = File::get($bladePath);

        // --- 1. Extract Dependencies (Using logic from the other command) ---
        $extractor = new ExtractDependenciesCommand();
        $extractedClasses = $extractor->extractCssClasses($bladeContent);
        $extractedFunctions = $extractor->extractJsFunctions($bladeContent);

        // --- 2. Filter Output Based on Type ---
        $output = "";

        if ($type === 'css' || $type === 'all') {
            $this->info("\n### Extracted CSS Rules from {$this->cssPath} ###\n");
            $cssRules = $extractor->extractCssRules($extractedClasses);
            $output .= $cssRules . "\n\n";
        }

        if ($type === 'js' || $type === 'all') {
            $this->info("\n### Extracted JavaScript Logic from {$this->jsPath} ###\n");
            $jsLogic = $extractor->extractJsLogic($extractedFunctions);
            $output .= $jsLogic;
        }

        if (empty($output)) {
             $this->error("Invalid type specified. Use 'css', 'js', or omit the --type option for both.");
             return Command::FAILURE;
        }

        $this->line($output);

        $this->info("\nContext extraction complete for type: {$type}.");

        return Command::SUCCESS;
    }
}
