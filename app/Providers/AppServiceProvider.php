<?php

namespace App\Providers;

use App\Services\TaxonomyRouteService;
use Illuminate\Pagination\Paginator;
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
        // Set default pagination view
        Paginator::defaultView('pagination');

        // Share the year with all views
        View::share('year', date('Y'));

        // Share main and sub categories with all views
        View::composer('*', function ($view) {
            // Skip these views
            if (in_array($view->getName(), [
                'vehicle.create',
                'vehicle.edit'])) {
                return; // prevent overriding on create page
            }

            $mainCategory = request()->route('mainCategory');
            $subcategory = request()->route('subcategory');

            // If route parameter is a string, fetch from database
            if ($mainCategory && is_string($mainCategory)) {
                $mainCategory = \App\Models\MainCategory::where('slug', $mainCategory)->first();
            }

            if ($subcategory && is_string($subcategory)) {
                $subcategory = \App\Models\Subcategory::where('slug', $subcategory)->first();
            }

            $view->with('mainCategory', $mainCategory)
                 ->with('subcategory', $subcategory);
        });
    }
}
