<?php // app/Providers/AppServiceProvider.php

namespace App\Providers;

use App\Services\TaxonomyRouteService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register TaxonomyRouteService as singleton
        $this->app->singleton(TaxonomyRouteService::class, function ($app) {
            return new TaxonomyRouteService();
        });
    }

    public function boot(): void
    {
        // Check if the application is running behind a proxy or in a
        // production-like environment (like staging or production)
        // This forces Laravel to generate ALL URLs using the 'https' scheme,
        // resolving the "insecure connection" warning from the browser.
        if ($this->app->environment(['staging', 'production'])) {
            URL::forceScheme('https');
        }

        // Set default pagination view
        Paginator::defaultView('pagination');

        // Share the year with all views
        View::share('year', date('Y'));

        // Share section and categories with all views
        View::composer('*', function ($view) {
            // Skip these views
            if (in_array($view->getName(), [
                'vehicle.create',
                'vehicle.edit'])) {
                return; // prevent overriding on create page
            }

            $section = request()->route('section');
            $category = request()->route('category');

            // If route parameter is a string, fetch from database
            if ($section && is_string($section)) {
                $section = \App\Models\Section::where('slug', $section)->first();
            }

            if ($category && is_string($category)) {
                $category = \App\Models\Category::where('slug', $category)->first();
            }

            $view->with('section', $section)
                 ->with('category', $category);
        });
    }
}
