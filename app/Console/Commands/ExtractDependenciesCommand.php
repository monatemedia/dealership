<?php
// app/Console/Commands/ExtractDependenciesCommand.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Output\OutputInterface;

class ExtractDependenciesCommand extends Command
{
    protected $signature = 'blade:extract-dependencies
        {view_path}
        {--out= : Write output directly to a file}';

    protected $description = 'Extracts all relevant CSS and JS/Alpine logic for a given Blade view.';

    protected $jsPath  = 'resources/js/app.js';
    protected $cssPath = 'resources/css/app.css';

    public function handle()
    {
        // 🔑 CRITICAL: disable decoration (Termwind / TTY)
        $this->output->setDecorated(false);

        $viewPathInput = $this->argument('view_path');
        $viewPathInput = preg_replace('/^resources\/views\//', '', $viewPathInput);
        $viewPathInput = preg_replace('/^views\//', '', $viewPathInput);

        $bladePath = base_path("resources/views/{$viewPathInput}");

        if (!File::exists($bladePath)) {
            $this->write("ERROR: Blade file not found: {$bladePath}");
            return Command::FAILURE;
        }

        $bladeContent = File::get($bladePath);

        $classes   = $this->extractCssClasses($bladeContent);
        $functions = $this->extractJsFunctions($bladeContent);

        $this->write("\n### Extracted CSS Rules from {$this->cssPath} ###\n");
        $this->write($this->extractCssRules($classes));

        $this->write("\n\n### Extracted JavaScript Logic from {$this->jsPath} ###\n");
        $this->write($this->extractJsLogic($functions));

        $this->write("\n\nDONE");

        return Command::SUCCESS;
    }

    private function write(string $text): void
    {
        // RAW stdout — always redirectable
        fwrite(STDOUT, $text . PHP_EOL);
    }

    /* ---------- extraction helpers (unchanged logic) ---------- */

    public function extractCssClasses(string $content): array
    {
        $classes = [];
        preg_match_all('/(?:class|x-bind:class)=["\']([^"\']+)["\']/', $content, $matches);

        foreach ($matches[1] as $match) {
            foreach (preg_split('/\s+/', $match) as $part) {
                $clean = trim(preg_replace('/[^a-zA-Z0-9\-_:\[\]]/', '', $part));
                if ($clean !== '') {
                    $classes[] = $clean;
                }
            }
        }

        return array_unique($classes);
    }

    public function extractJsFunctions(string $content): array
    {
        $functions = [];
        preg_match_all('/(?:x-data|x-on|@)\s*=\s*["\']([^"\']+)["\']/', $content, $matches);

        foreach ($matches[1] as $match) {
            if (preg_match_all('/([a-zA-Z_$][a-zA-Z0-9_$]*)\s*\(/', $match, $m)) {
                $functions = array_merge($functions, $m[1]);
            }

            if (preg_match('/^\s*([a-zA-Z_$][a-zA-Z0-9_$]*)/', $match, $m)) {
                $functions[] = $m[1];
            }
        }

        if (str_contains($content, 'alpine:init')) {
            $functions[] = 'alpine:init';
        }

        return array_unique($functions);
    }

    public function extractCssRules(array $classes): string
    {
        if (!File::exists($this->cssPath)) {
            return "/* CSS file not found */";
        }

        $css = File::get($this->cssPath);
        $rules = [];

        foreach ($classes as $class) {
            $safe = preg_quote($class, '/');
            if (preg_match_all("/\\.{$safe}[^\\{]*\\{[^}]*\\}/i", $css, $m)) {
                $rules = array_merge($rules, $m[0]);
            }
        }

        return $rules ? implode("\n\n", array_unique($rules)) : "/* No CSS rules found */";
    }

    public function extractJsLogic(array $functions): string
    {
        if (!File::exists($this->jsPath)) {
            return "// JS file not found";
        }

        $js = File::get($this->jsPath);
        $out = [];

        foreach ($functions as $name) {
            $safe = preg_quote($name, '/');

            if (preg_match("/function\s+{$safe}\s*\([^)]*\)\s*\{.*?\}/is", $js, $m)) {
                $out[] = $m[0];
            }

            if (preg_match("/Alpine\.data\s*\(\s*['\"]{$safe}['\"].*?\);/is", $js, $m)) {
                $out[] = $m[0];
            }
        }

        return $out ? implode("\n\n", array_unique($out)) : "// No JS logic found";
    }
}
