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
            if ($view->getName() === 'vehicle.create') {
                return; // prevent overriding on create page
            }

            $mainCategory = request()->route('mainCategory');
            $subCategory = request()->route('subCategory');

            // If route parameter is a string, fetch from database
            if ($mainCategory && is_string($mainCategory)) {
                $mainCategory = \App\Models\MainCategory::where('slug', $mainCategory)->first();
            }

            if ($subCategory && is_string($subCategory)) {
                $subCategory = \App\Models\SubCategory::where('slug', $subCategory)->first();
            }

            $view->with('mainCategory', $mainCategory)
                 ->with('subCategory', $subCategory);
        });
    }
}
