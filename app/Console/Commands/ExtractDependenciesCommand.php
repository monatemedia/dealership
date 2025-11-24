<?php // app/Console/Commands/ExtractDependenciesCommand.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExtractDependenciesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * The argument takes the path relative to the resources/views directory.
     * E.g., php artisan blade:extract-dependencies posts/show.blade.php
     * @var string
     */
    protected $signature = 'blade:extract-dependencies {view_path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extracts all relevant CSS and JS/Alpine logic for a given Blade view.';

    // Default asset paths (relative to base_path)
    protected $jsPath = 'resources/js/app.js';
    protected $cssPath = 'resources/css/app.css';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $viewPathInput = $this->argument('view_path');

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

        // --- 1. Extract CSS and JS/Alpine Dependencies from Blade ---

        $extractedClasses = $this->extractCssClasses($bladeContent);
        $extractedFunctions = $this->extractJsFunctions($bladeContent);

        // --- 2. Extract CSS Rules from app.css ---

        $this->info("\n### Extracted CSS Rules from {$this->cssPath} ###\n");
        $extractedCss = $this->extractCssRules($extractedClasses);
        $this->line($extractedCss);

        // --- 3. Extract JS Functions/Alpine Logic from app.js ---

        $this->info("\n### Extracted JavaScript Logic from {$this->jsPath} ###\n");
        $extractedJs = $this->extractJsLogic($extractedFunctions);
        $this->line($extractedJs);

        $this->info("\nSuccessfully extracted dependencies for LLM context.");

        return Command::SUCCESS;
    }

    /**
     * Extracts unique CSS class names used in the Blade content.
     * Includes static 'class' and dynamic 'x-bind:class' directives.
     *
     * @param string $content
     * @return array
     */
    public function extractCssClasses(string $content): array // Made public for use in ContextCommand
    {
        $classes = [];
        // Regex to find class="..." or x-bind:class="..."
        preg_match_all('/(?:class|x-bind:class)=["\']([^"\']+)["\']/', $content, $matches);

        foreach ($matches[1] as $match) {
            // Split by space, handle potential dynamic expressions (e.g., 'isActive ? 'text-blue' : 'text-gray'')
            $parts = preg_split('/\s+/', $match);
            foreach ($parts as $part) {
                // Clean up any extra characters from dynamic class logic or Tailwind prefixes
                $cleaned = trim(preg_replace('/[^a-zA-Z0-9\-_:\[\]]/', '', $part));
                if (!empty($cleaned)) {
                    $classes[] = $cleaned;
                }
            }
        }
        return array_unique($classes);
    }

    /**
     * Extracts potential JS function calls and Alpine component names.
     * Focuses on x-data, x-on, and simple function calls.
     *
     * @param string $content
     * @return array
     */
    public function extractJsFunctions(string $content): array // Made public for use in ContextCommand
    {
        $functions = [];
        // Regex to capture potential JS names in directives like x-data, x-on, @click, etc.
        // It's heuristic but attempts to find names that might be defined in app.js
        preg_match_all('/(?:x-data|x-on|@)\s*=\s*["\']([^"\']+)["\']/', $content, $matches);

        foreach ($matches[1] as $match) {
            // Check for explicit function calls, e.g., 'submitForm(id)'
            if (preg_match_all('/([a-zA-Z_$][a-zA-Z0-9_$]*)\s*\(/', $match, $funcMatches)) {
                $functions = array_merge($functions, $funcMatches[1]);
            }

            // Check for Alpine.data component names, e.g., 'postModal' in x-data="postModal"
            // We'll treat all first words inside x-data as potential component names
            if (preg_match('/^\s*([a-zA-Z_$][a-zA-Z0-9_$]*)/', $match, $dataMatch)) {
                $functions[] = $dataMatch[1];
            }
        }

        // Add standard Alpine event initializer
        if (str_contains($content, 'alpine:init')) {
             $functions[] = 'alpine:init';
        }

        return array_unique(array_filter($functions));
    }

    /**
     * Searches app.css and extracts the full rule blocks for the provided classes.
     *
     * @param array $classes
     * @return string
     */
    public function extractCssRules(array $classes): string // Made public for use in ContextCommand
    {
        $cssContent = File::exists($this->cssPath) ? File::get($this->cssPath) : '';
        $extractedRules = [];

        if (empty($cssContent)) {
            return "/* CSS file not found or is empty. Path: {$this->cssPath} */";
        }

        // Escape class names for use in regex
        $classRegex = array_map(function ($class) {
            // Escapes dots, brackets, and other special characters that might be in CSS selectors
            $safeClass = preg_quote($class, '/');
            // Look for the class name either standalone or with a prefix like [class.name] or a state like .class-name:hover
            // The pattern ensures it looks for the class as a whole word/selector
            return '(?<=[^\w\-\:])' . $safeClass . '(?=[^\w\-\:])';
        }, $classes);

        // Combined regex to find the rule block for the class
        $pattern = '/(' . implode('|', $classRegex) . ')\s*\{(.+?)\}/is';

        if (preg_match_all($pattern, $cssContent, $matches)) {
            foreach ($matches[0] as $rule) {
                // Ensure unique rules
                if (!in_array($rule, $extractedRules)) {
                    $extractedRules[] = trim($rule);
                }
            }
        }

        return empty($extractedRules) ? "/* No relevant CSS rules found in {$this->cssPath} */" : implode("\n\n", $extractedRules);
    }

    /**
     * Searches app.js and extracts full function/Alpine data definitions.
     *
     * @param array $functions
     * @return string
     */
    public function extractJsLogic(array $functions): string // Made public for use in ContextCommand
    {
        $jsContent = File::exists($this->jsPath) ? File::get($this->jsPath) : '';
        $extractedCode = [];

        if (empty($jsContent)) {
            return "// JS file not found or is empty. Path: {$this->jsPath}";
        }

        $jsNames = array_map(fn($f) => preg_quote($f, '/'), $functions);

        foreach ($jsNames as $name) {
            // 1. Match standard function definition: function name(...) { ... }
            $funcPattern = "/(function\s+{$name}\s*\([^{]*\)\s*\{.*?\})/is";
            if (preg_match($funcPattern, $jsContent, $match)) {
                $extractedCode[] = trim($match[1]);
                continue;
            }

            // 2. Match Alpine.data() definition: Alpine.data('name', (...) => { ... })
            $alpinePattern = "/(Alpine\.data\s*\(\s*['\"]{$name}['\"]\s*,\s*\([^{]*\)\s*=>\s*\{.*?\});?/is";
            if (preg_match($alpinePattern, $jsContent, $match)) {
                $extractedCode[] = trim($match[1]);
                continue;
            }

            // 3. Match the alpine:init block if the flag was set
            if ($name === 'alpine:init') {
                $initPattern = "/(document\.addEventListener\s*\(\s*['\"]alpine:init['\"]\s*,\s*\(\s*\)\s*=>\s*\{.*?\});?/is";
                if (preg_match($initPattern, $jsContent, $match)) {
                    $extractedCode[] = trim($match[1]);
                    continue;
                }
            }
        }

        $uniqueCode = array_unique($extractedCode);

        return empty($uniqueCode) ? "// No relevant JS or Alpine logic found in {$this->jsPath}" : implode("\n\n", $uniqueCode);
    }
}
